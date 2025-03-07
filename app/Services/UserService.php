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

class UserService
{
    protected UserRepository $userRepository;
    protected UserSchoolRepository $userSchoolRepository;
    protected UserAddressRepository $userAddressRepository;
    protected AddressRepository $addressRepository;
    protected UserReviewRepository $userReviewRepository;

    public function __construct(UserRepository $userRepository, UserSchoolRepository $userSchoolRepository, UserAddressRepository $userAddressRepository, AddressRepository $addressRepository, UserReviewRepository $userReviewRepository)
    {
        $this->userRepository = $userRepository;
        $this->userSchoolRepository = $userSchoolRepository;
        $this->userAddressRepository = $userAddressRepository;
        $this->addressRepository = $addressRepository;
        $this->userReviewRepository = $userReviewRepository;
    }

    public function login(array $data)
    {
        $user = $this->userRepository->findByEmail($data['email']);

        if ($user) {
            if ($user->status == 'blocked') {
                return apiResponse(false, null, 'Your account is blocked.');
            }
            if (Hash::check($data['password'], $user->password)) {
                $token = $user->createToken(env('APP_KEY'));
                if ($user->type == 'school') {
                    $user->school = $user->school;
                }
                return apiResponse(true, ['user' => $user, 'token' => $token->plainTextToken]);
            } else {
                return apiResponse(false, ['password' => 'Incorrect password.'], 'Wrong Credentials.', 3, 401);
            }
        } else {
            return apiResponse(false, ['user' => 'No user registered with this email'], 'Wrong Credentials.', 2, 401);
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
