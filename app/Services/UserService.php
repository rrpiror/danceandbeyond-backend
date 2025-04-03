<?php

namespace App\Services;

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
            throw new Exception('Wrong Credentials.', 404);
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

    public function update(array $data, $id)
    {
        $user = $this->userRepository->findById($id);

        $user->fill($data)->save();

        if (isset($data['file'])) {
            $user->addMedia($data['file'])
                ->toMediaCollection('profile_pictures');
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
        return $this->userReviewRepository->findBySellerId($sellerId);
    }
}
