<?php

namespace App\Services;

use App\Mail\ForgotPasswordLink;
use App\Repositories\AddressRepository;
use App\Repositories\UserAddressRepository;
use App\Repositories\UserRepository;
use App\Repositories\UserReviewRepository;
use App\Repositories\UserSchoolRepository;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Stripe\Account;
use Stripe\AccountLink;
use Stripe\Customer;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;

class UserService
{
    protected UserRepository $userRepository;
    protected UserSchoolRepository $userSchoolRepository;
    protected UserAddressRepository $userAddressRepository;
    protected AddressRepository $addressRepository;
    protected UserReviewRepository $userReviewRepository;
    protected PaymentService $paymentService;

    public function __construct(UserRepository $userRepository, UserSchoolRepository $userSchoolRepository, UserAddressRepository $userAddressRepository, AddressRepository $addressRepository, UserReviewRepository $userReviewRepository, PaymentService $paymentService)
    {
        $this->userRepository = $userRepository;
        $this->userSchoolRepository = $userSchoolRepository;
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

            if (!Auth::attempt($data)) {
                throw new Exception('Wrong Credentials.', 404);
            }

            $token = $user->createToken(env('APP_KEY'));
            if ($user->type == 'school') {
                $user->school = $user->school;
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

            if ($data['type'] == 'school') {
                $data['school']['user_id'] = $user->id;
                $this->userSchoolRepository->create($data['school']);
            }

            if (isset($data['profile_image']) && $data['profile_image']) {
                $imageType = explode(';', $data['profile_image'])[0];
                $imageType = explode('/', $imageType)[1];

                if (!in_array(strtolower($imageType), ['jpg', 'jpeg', 'png'])) {
                    throw new Exception('Invalid image type. Only JPG, JPEG and PNG are allowed.', 422);
                }

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
            if ($user->type == 'school') {
                $user->school = $user->school;
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
            $account = Account::create([
                'type' => 'standard',
            ]);

            $user = $this->userRepository->findById($user->id);

            if (!$user) {
                throw new Exception('User not found', 404);
            }

            $user->stripe_seller_id = $account->id;
            $user->save();

            $accountLink = AccountLink::create([
                'account' => $account->id,
                'refresh_url' => env('REFRESH_URL'),
                'return_url' => env("RETURN_URL"),
                'type' => 'account_onboarding',
            ]);

            return $accountLink->url;
        } catch (Exception $ex) {
            throw new Exception($ex->getMessage(), 422);
        }
    }

    public function changePassword(array $data)
    {
        $user = $this->userRepository->findById(Auth::user()->id);

        if (!Hash::check($data['old_password'], $user->password)) {
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
        if (!$user) {
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
        if (!$user) {
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
        if (!$user) {
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

            if (!in_array(strtolower($imageType), ['jpg', 'jpeg', 'png'])) {
                throw new Exception('Invalid image type. Only JPG, JPEG and PNG are allowed.', 422);
            }

            $user->addMediaFromBase64($data['profile_image'])
                ->toMediaCollection('profile');
        }

        if (isset($data['address']) && is_array($data['address'])) {
            foreach ($data['address'] as $address) {
                $this->addressRepository->findById($address['id'])->fill($address)->save();
            }
        }

        if ($data['type'] == 'school') {
            $user->school->fill($data['school'])->save();
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
            'average_rating' => $averageRating
        ];
    }

    public function getProfile()
    {
        $user = Auth::user();

        $profile = $this->userRepository->findById($user->id);

        $profile->load('school', 'media', 'address');

        return $profile;
    }

    public function findById(int $id)
    {
        return $this->userRepository->findById($id);
    }
}
