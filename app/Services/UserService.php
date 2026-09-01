<?php

namespace App\Services;

use App\Mail\ForgotPasswordLink;
use App\Repositories\AddressRepository;
use App\Repositories\OrganisationRepository;
use App\Repositories\UserAddressRepository;
use App\Repositories\UserRepository;
use App\Repositories\UserReviewRepository;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Stripe\Account;
use Stripe\AccountLink;
use Stripe\Customer;

class UserService
{
    protected UserRepository $userRepository;

    protected OrganisationRepository $organisationRepository;

    protected UserAddressRepository $userAddressRepository;

    protected AddressRepository $addressRepository;

    protected UserReviewRepository $userReviewRepository;

    protected PaymentService $paymentService;

    public function __construct(UserRepository $userRepository, OrganisationRepository $organisationRepository, UserAddressRepository $userAddressRepository, AddressRepository $addressRepository, UserReviewRepository $userReviewRepository, PaymentService $paymentService)
    {
        $this->userRepository = $userRepository;
        $this->organisationRepository = $organisationRepository;
        $this->userAddressRepository = $userAddressRepository;
        $this->addressRepository = $addressRepository;
        $this->userReviewRepository = $userReviewRepository;
        $this->paymentService = $paymentService;
    }

    public function createAddress(array $data)
    {
        return $this->addressRepository->create($data);
    }

    public function login(array $data)
    {
        $user = $this->userRepository->findByEmail($data['email']);

        if ($user) {
            if ($user->status == 'blocked') {
                throw new Exception('Your account is blocked.', 403);
            }

            if (! Auth::attempt($data)) {
                throw new Exception('Wrong Credentials.', 404);
            }

            $token = $user->createToken(env('APP_KEY'));
            if ($user->type == 'organisation') {
                $user->load('organisation');
            }

            return ['user' => $user, 'token' => $token->plainTextToken];
        } else {
            throw new Exception('Invalid Credentials.', 404);
        }
    }

    public function checkEmail(string $email)
    {
        $user = $this->userRepository->findByEmail($email);

        return $user ? true : false;
    }

    public function create(array $data)
    {
        try {
            DB::beginTransaction();

            $data['password'] = Hash::make($data['password']);

            $user = $this->userRepository->create($data);

            $address = $this->addressRepository->create($data['address']);

            $user->address()->attach($address->id, ['type' => 'shipping']);

            if ($data['type'] == 'organisation') {
                $data['organisation']['user_id'] = $user->id;
                $this->organisationRepository->create($data['organisation']);
            }

            if (isset($data['profile_image']) && $data['profile_image']) {

                // if (!in_array(strtolower($imageType), ['jpg', 'jpeg', 'png'])) {
                //     throw new Exception('Invalid image type. Only JPG, JPEG and PNG are allowed.', 422);
                // }

                $user->addMediaFromBase64($data['profile_image'])
                    ->toMediaCollection('profile');
            }

            DB::commit();

            $customer = Customer::create([
                'email' => $data['email'],
                'name' => $data['name'],
            ]);

            $user->stripe_customer_id = $customer->id;
            $user->save();

            $token = $user->createToken(env('APP_KEY'));
            if ($user->type == 'organisation') {
                $user->load('organisation');
            }

            return ['user' => $user, 'token' => $token->plainTextToken];
        } catch (Exception $ex) {
            DB::rollBack();
            throw $ex;
        }
    }

    public function userOnboarding()
    {
        try {
            $user = Auth::user();
            $user = $this->userRepository->findById($user->id);

            if (! $user) {
                throw new Exception('User not found', 404);
            }

            $businessProfile = [
                'name' => $user->type === 'organisation' ? $user->name : 'Vestu seller',
                'product_description' => 'I sell new and pre-owned dancewear through the Vestu marketplace. Vestu processes customer payments and releases payouts to sellers after delivery or approval.',
                'url' => env('STRIPE_BUSINESS_PROFILE_URL', 'https://vestu.co.uk'),
                'support_url' => env('STRIPE_SUPPORT_URL', 'https://vestu.co.uk'),
                'support_email' => env('STRIPE_SUPPORT_EMAIL', 'support@vestu.co.uk'),
            ];

            if ($user->stripe_seller_id) {
                $account = Account::retrieve($user->stripe_seller_id);
                Account::update($account->id, [
                    'business_profile' => $businessProfile,
                ]);
            } else {
                $businessType = $user->type === 'organisation' ? 'company' : 'individual';

                $account = Account::create([
                    'type' => 'standard',
                    'country' => 'GB',
                    'email' => $user->email,
                    'business_type' => $businessType,
                    'business_profile' => $businessProfile,
                    'capabilities' => [
                        'card_payments' => ['requested' => true],
                        'transfers' => ['requested' => true],
                    ],
                ]);

                $user->stripe_seller_id = $account->id;
                $user->save();
            }

            $accountLink = AccountLink::create([
                'account' => $account->id,
                'refresh_url' => env('REFRESH_URL'),
                'return_url' => env('RETURN_URL'),
                'type' => 'account_onboarding',
                'collection_options' => [
                    'fields' => 'currently_due',
                ],
            ]);

            return $accountLink->url;
        } catch (Exception $ex) {
            throw new Exception($ex->getMessage(), 422);
        }
    }

    public function stripeConnectStatus()
    {
        $user = Auth::user();
        $user = $this->userRepository->findById($user->id);

        if (! $user || ! $user->stripe_seller_id) {
            return [
                'connected' => false,
                'account_id' => null,
                'details_submitted' => false,
                'charges_enabled' => false,
                'payouts_enabled' => false,
                'transfers_enabled' => false,
                'ready_for_payouts' => false,
            ];
        }

        $account = Account::retrieve($user->stripe_seller_id);
        $transfers = $account->capabilities->transfers ?? null;
        $transfersEnabled = $transfers === 'active';

        return [
            'connected' => true,
            'account_id' => $account->id,
            'details_submitted' => (bool) $account->details_submitted,
            'charges_enabled' => (bool) $account->charges_enabled,
            'payouts_enabled' => (bool) $account->payouts_enabled,
            'transfers_enabled' => $transfersEnabled,
            'ready_for_payouts' => (bool) $account->payouts_enabled && $transfersEnabled,
        ];
    }

    public function changePassword(array $data)
    {
        $user = $this->userRepository->findById(Auth::user()->id);

        if (! Hash::check($data['old_password'], $user->password)) {
            throw new Exception('Old password is incorrect.', 400);
        }

        $user->password = Hash::make($data['password']);
        $user->save();

        return $user;
    }

    public function deleteAccount()
    {
        $user = $this->userRepository->findById(Auth::user()->id);
        $user->delete();

        return true;
    }

    public function sendForgotPasswordLink(string $email)
    {
        $user = $this->userRepository->findByEmail($email);
        if (! $user) {
            throw new Exception('User not found.', 404);
        }

        $token = Str::random(60);
        $user->reset_password_token = $token;
        $user->reset_password_token_at = now()->addDay(1);
        $user->save();

        Mail::to($user->email)->send(new ForgotPasswordLink($token));

        return $user;
    }

    public function validateResetPasswordToken(string $token)
    {
        $user = $this->userRepository->findByResetPasswordToken($token);
        if (! $user) {
            throw new Exception('Invalid token.', 400);
        }

        if ($user->reset_password_token_at < now()) {
            throw new Exception('Token expired.', 400);
        }

        return $user;
    }

    public function resetPassword(string $token, string $password)
    {

        $user = $this->userRepository->findByResetPasswordToken($token);
        if (! $user) {
            throw new Exception('Invalid token.', 400);
        }

        $user->password = Hash::make($password);
        $user->reset_password_token = null;
        $user->reset_password_token_at = null;
        $user->save();

        return $user;
    }

    public function update(array $data)
    {
        $user = $this->userRepository->findById(Auth::user()->id);

        $user->fill($data)->save();

        if (isset($data['profile_image']) && $data['profile_image']) {
            $imageType = explode(';', $data['profile_image'])[0];
            $imageType = explode('/', $imageType)[1];

            // if (!in_array(strtolower($imageType), ['jpg', 'jpeg', 'png'])) {
            //     throw new Exception('Invalid image type. Only JPG, JPEG and PNG are allowed.', 422);
            // }

            $user->addMediaFromBase64($data['profile_image'])
                ->toMediaCollection('profile');
        }

        if (isset($data['address']) && is_array($data['address'])) {
            foreach ($data['address'] as $address) {
                $addressData = [
                    'house_number' => $address['house_number'],
                    'building_name' => $address['building_name'] ?? null,
                    'street' => $address['street'],
                    'town' => $address['town'] ?? null,
                    'city' => $address['city'],
                    'postcode' => $address['postcode'],
                ];

                $type = $address['pivot']['type'] ?? 'shipping';
                $addressId = $address['id'] ?? null;
                $existingAddress = $addressId && $addressId > 0
                    ? $user->address()->where('addresses.id', $addressId)->first()
                    : null;

                if (! $existingAddress) {
                    $existingAddress = $user->address()
                        ->wherePivot('type', $type)
                        ->where('house_number', $addressData['house_number'])
                        ->where('street', $addressData['street'])
                        ->where('city', $addressData['city'])
                        ->where('postcode', $addressData['postcode'])
                        ->where(function ($query) use ($addressData) {
                            if ($addressData['building_name']) {
                                $query->where('building_name', $addressData['building_name']);
                            } else {
                                $query->whereNull('building_name');
                            }
                        })
                        ->where(function ($query) use ($addressData) {
                            if ($addressData['town']) {
                                $query->where('town', $addressData['town']);
                            } else {
                                $query->whereNull('town');
                            }
                        })
                        ->first();
                }

                if ($existingAddress) {
                    $existingAddress->fill($addressData)->save();

                    $user->address()->updateExistingPivot($existingAddress->id, [
                        'type' => $type,
                    ]);
                } else {
                    $newAddress = $this->addressRepository->create($addressData);

                    $user->address()->syncWithoutDetaching([
                        $newAddress->id => [
                            'type' => $type,
                        ],
                    ]);
                }
            }
        }

        if ($data['type'] == 'organisation') {
            $user->organisation->fill($data['organisation'])->save();
        }

        return $user;
    }

    public function addReview(array $data)
    {
        $data['user_id'] = Auth::user()->id;

        return $this->userReviewRepository->create($data);
    }

    public function getReviews($sellerId)
    {
        $reviews = $this->userReviewRepository->findBySellerId($sellerId);
        $totalReviews = $reviews->count();
        $averageRating = $reviews->avg('rating');

        return [
            'reviews' => $reviews,
            'total_reviews' => $totalReviews,
            'average_rating' => $averageRating,
        ];
    }

    public function getProfile()
    {
        $user = Auth::user();

        $profile = $this->userRepository->findById($user->id);

        $profile->load('organisation', 'media', 'address');

        return $profile;
    }

    public function findById(int $id)
    {
        return $this->userRepository->findById($id);
    }

    public function doesEmailExist(string $email): bool
    {
        return $this->userRepository->findByEmail($email) !== null;
    }

    public function doesPhoneNumberExist(string $phoneNumber): bool
    {
        $user = $this->userRepository->findByPhoneNumber($phoneNumber);

        return $user !== null;
    }

    public function doesPhoneNumberChangeExist(string $phoneNumber, int $userId): bool
    {
        $user = $this->userRepository->findByPhoneNumber($phoneNumber);

        return $user !== null && $user->id !== $userId;
    }

    public function doesUsernameExist(string $username): bool
    {
        $user = $this->userRepository->findByUsername($username);

        return $user !== null;
    }

    public function doesUsernameChangeExist(string $username, int $userId): bool
    {
        $user = $this->userRepository->findByUsername($username);

        return $user !== null && $user->id !== $userId;
    }
}
