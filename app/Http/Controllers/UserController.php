<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use App\Services\ValidationService;
use Exception;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected ValidationService $validationService;
    protected UserService $userService;

    public function __construct(ValidationService $validationService, UserService $userService)
    {
        $this->validationService = $validationService;
        $this->userService = $userService;
    }

    /**
     * @OA\Post(
     *     path="/api/login",
     *     summary="Login a user",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="email", type="string"),
     *             @OA\Property(property="password", type="string")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Successful operation"),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function login(Request $request)
    {
        try {
            $rules = [
                'email' => 'required|email',
                'password' => 'required',
            ];

            $validation = $this->validationService->validate($request, $rules);

            if ($validation) {
                return apiResponse(false, $validation, 'Invalid data', 5, 422);
            }

            $response = $this->userService->login($request->all());

            return apiResponse(true, $response);
        } catch (Exception $ex) {
            return apiResponse(false, $ex->getMessage(), 'Something went wrong', 1, $ex->getCode());
        }
    }

    public function signup(Request $request)
    {
        try {
            $rules = [
                'name' => 'required',
                'email' => 'required|email',
                'phone_number' => 'required|string|max:20',
                'password' => 'required',
                'type' => 'required|in:school,individual',
                'address' => 'required',
                'school' => 'required_if:type,school',
                'school.name' => 'required_if:type,school',
                'school.website' => 'required_if:type,school|url',
                'address.house_number' => 'required',
                'address.street' => 'required',
                'address.city' => 'required',
                'address.postcode' => ['required', 'regex:/^([Gg][Ii][Rr] 0[Aa]{2})|((([A-Za-z][0-9]{1,2})|(([A-Za-z][A-Ha-hJ-Yj-y][0-9]{1,2})|(([A-Za-z][0-9][A-Za-z])|([A-Za-z][A-Ha-hJ-Yj-y][0-9][A-Za-z]?))))\s?[0-9][A-Za-z]{2})$/'],
            ];

            $validation = $this->validationService->validate($request, $rules);

            if ($validation) {
                return apiResponse(false, $validation, 'Invalid data', 5, 422);
            }

            if ($this->userService->checkEmail($request->email)) {
                return apiResponse(false, ['email' => 'Email already exists'], 'Invalid data', 5, 422);
            }

            $user = $this->userService->create($request->all());

            return apiResponse(true, $user, 'User created successfully');
        } catch (Exception $ex) {
            return apiResponse(false, $ex->getMessage(), 'Something went wrong', 1, 500);
        }
    }

    public function stripeConnect()
    {
        try {
            $accountLink = $this->userService->userOnboarding();

            return apiResponse(true, $accountLink);
        } catch (Exception $ex) {
            return apiResponse(false, $ex->getMessage(), 'Something went wrong', 1, $ex->getCode());
        }
    }

    public function addReview(Request $request)
    {
        try {
            $rules = [
                'rating' => 'required|decimal:0,2',
                'description' => 'required|string',
                'seller_id' => 'required|exists:users,id'
            ];

            $validation = $this->validationService->validate($request, $rules);

            if ($validation) {
                return apiResponse(false, $validation, 'Invalid data', 5, 422);
            }

            $review = $this->userService->addReview($request->all());

            return apiResponse(true, $review, 'Review added');
        } catch (Exception $ex) {
            return apiResponse(false, $ex->getMessage(), 'Something went wrong', 1, 500);
        }
    }

    public function getReviews($sellerId)
    {
        try {
            $reviews = $this->userService->getReviews($sellerId);
            return apiResponse(true, $reviews);
        } catch (Exception $ex) {
            return apiResponse(false, $ex->getMessage(), 'Something went wrong', 1, 500);
        }
    }

    public function getProfile()
    {
        try {
            $profile = $this->userService->getProfile();
            return apiResponse(true, $profile);
        } catch (Exception $ex) {
            return apiResponse(false, $ex->getMessage(), 'Something went wrong', 1, 500);
        }
    }

    public function updateProfile(Request $request)
    {
        try {
            $rules = [
                'name' => 'required',
                'phone_number' => 'required|string|max:20',
                'type' => 'required|in:school,individual',
                'address' => 'required',
                'school' => 'required_if:type,school',
                'school.name' => 'required_if:type,school',
                'school.website' => 'required_if:type,school|url',
                'address.*.house_number' => 'required',
                'address.*.street' => 'required',
                'address.*.city' => 'required',
                'address.*.postcode' => ['required', 'regex:/^([Gg][Ii][Rr] 0[Aa]{2})|((([A-Za-z][0-9]{1,2})|(([A-Za-z][A-Ha-hJ-Yj-y][0-9]{1,2})|(([A-Za-z][0-9][A-Za-z])|([A-Za-z][A-Ha-hJ-Yj-y][0-9][A-Za-z]?))))\s?[0-9][A-Za-z]{2})$/'],
            ];

            $validation = $this->validationService->validate($request, $rules);

            if ($validation) {
                return apiResponse(false, $validation, 'Invalid data', 5, 422);
            }

            $profile = $this->userService->update($request->all());

            return apiResponse(true, $profile);
        } catch (Exception $ex) {
            return apiResponse(false, $ex->getMessage(), 'Something went wrong', 1, 500);
        }
    }

    public function changePassword(Request $request)
    {
        try {
            $rules = [
                'old_password' => 'required',
                'password' => 'required',
            ];

            $validation = $this->validationService->validate($request, $rules);

            if ($validation) {
                return apiResponse(false, $validation, 'Invalid data', 5, 422);
            }

            $password = $this->userService->changePassword($request->all());

            return apiResponse(true, $password, 'Password changed successfully');
        } catch (Exception $ex) {
            return apiResponse(false, $ex->getMessage(), 'Something went wrong', 1, 500);
        }
    }

    public function sendForgotPasswordLink(Request $request)
    {
        try {
            $rules = [
                'email' => 'required|email',
            ];

            $validation = $this->validationService->validate($request, $rules);

            if ($validation) {
                return apiResponse(false, $validation, 'Invalid data', 5, 422);
            }

            $user = $this->userService->sendForgotPasswordLink($request->email);

            return apiResponse(true, $user);
        } catch (Exception $ex) {
            return apiResponse(false, $ex->getMessage(), 'Something went wrong', 1, 500);
        }
    }

    public function validateResetPasswordToken(Request $request)
    {
        try {
            $rules = [
                'token' => 'required',
            ];

            $validation = $this->validationService->validate($request, $rules);

            if ($validation) {
                return apiResponse(false, $validation, 'Invalid data', 5, 422);
            }

            $user = $this->userService->validateResetPasswordToken($request->token);

            return apiResponse(true, $user);
        } catch (Exception $ex) {
            return apiResponse(false, $ex->getMessage(), 'Something went wrong', 1, 500);
        }
    }

    public function resetPassword(Request $request)
    {
        try {
            $rules = [
                'token' => 'required',
                'password' => 'required',
            ];

            $validation = $this->validationService->validate($request, $rules);

            if ($validation) {
                return apiResponse(false, $validation, 'Invalid data', 5, 422);
            }

            $user = $this->userService->resetPassword($request->token, $request->password);

            return apiResponse(true, $user);
        } catch (Exception $ex) {
            return apiResponse(false, $ex->getMessage(), 'Something went wrong', 1, 500);
        }
    }

    public function deleteAccount()
    {
        try {
            $user = $this->userService->deleteAccount();
            return apiResponse(true, 'Account deleted successfully');
        } catch (Exception $ex) {
            return apiResponse(false, $ex->getMessage(), 'Something went wrong', 1, 500);
        }
    }
}
