<?php

namespace App\Http\Controllers;

use App\Services\ProductService;
use App\Services\ValidationService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    protected ProductService $productService;
    protected ValidationService $validationService;
    private const CREATE_RULES = [
        'category_id' => 'required|integer|exists:categories,id',
        'condition_id' => 'required|integer|exists:conditions,id',
        'brand_id' => 'required|integer|exists:brands,id',
        'name' => 'required|string',
        'description' => 'required|string',
        'price' => 'required|numeric|min:0',
        'delivery_charge' => 'nullable|numeric|min:0',
        'type' => 'required|in:sale,hire',
        'fulfillment_option_ids' => 'required|array|min:1',
        'fulfillment_option_ids.*' => 'integer|exists:fulfillment_options,id',
        'variants' => 'required|array|min:1',
        'variants.*.colour_id' => 'nullable|integer|exists:product_colours,id',
        'variants.*.size_id' => 'nullable|integer|exists:product_sizes,id',
        'variants.*.quantity' => 'required|integer|min:1',
        'images' => 'nullable|array',
        'images.*' => 'string',
    ];

    public function __construct(ProductService $productService, ValidationService $validationService)
    {
        $this->productService = $productService;
        $this->validationService = $validationService;
    }

    public function index(Request $request)
    {
        try {
            $paginatedProducts = $this->productService->getAll($request->all());
            return apiResponse(true, $paginatedProducts);
        } catch (Exception $ex) {
            return apiResponse(false, $ex->getMessage(), 'Something went wrong', 1, 500);
        }
    }


    public function getProductsByUserId($userId)
    {
        try {
            $products = $this->productService->getProductsByUserId($userId);
            return apiResponse(true, $products);
        } catch (Exception $ex) {
            return apiResponse(false, $ex->getMessage(), 'Something went wrong', 1, 500);
        }
    }
    public function getMyProducts(Request $request)
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return apiResponse(false, null, 'User not found', 5, 404);
            }
            $products = $this->productService->getProductsByUserId($user->id);
            return apiResponse(true, $products);
        } catch (Exception $ex) {
            return apiResponse(false, $ex->getMessage(), 'Something went wrong', 1, 500);
        }
    }
    // Get all favourite products
    public function favouriteProducts()
    {
        try {
            $products = $this->productService->getFavouriteProducts();
            return apiResponse(true, $products);
        } catch (Exception $ex) {
            return apiResponse(false, $ex->getMessage(), 'Something went wrong', 1, 500);
        }
    }

    public function show($id)
    {
        try {
            $product = $this->productService->findById($id);
            return apiResponse(true, $product);
        } catch (Exception $ex) {
            return apiResponse(false, $ex->getMessage(), 'Something went wrong', 1, 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $rules = self::CREATE_RULES;

            if ($request->type == 'hire') {
                $rules['hiring_details'] = 'required';
                $rules['hiring_details.min_hire_days'] = 'required|integer|min:1';
                $rules['hiring_details.additional_fee_per_day'] = 'required|numeric|min:0';
                $rules['hiring_details.deposit_amount'] = 'nullable|numeric|min:0';
                $rules['unavailability_durations'] = 'nullable|array';
                $rules['unavailability_durations.*.start_date'] = 'required|date';
                $rules['unavailability_durations.*.end_date'] = 'required|date';
            }

            $validation = $this->validationService->validate($request, $rules);

            if ($validation) {
                return apiResponse(false, $validation, 'Invalid data', 5, 422);
            }

            $user = Auth::user();
            if (!$user?->stripe_seller_id) {
                return apiResponse(false, [
                    'stripe' => 'Please set up payouts before listing an item.',
                ], 'Please set up payouts before listing an item.', 5, 422);
            }

            $product = $this->productService->create($request->all());
            return apiResponse(true, $product);
        } catch (Exception $ex) {
            return apiResponse(false, $ex->getMessage(), 'Something went wrong', 1, 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $rules = self::CREATE_RULES;

            if ($request->type == 'hire') {
                $rules['hiring_details'] = 'required';
                $rules['hiring_details.min_hire_days'] = 'required|integer|min:1';
                $rules['hiring_details.additional_fee_per_day'] = 'required|numeric|min:0';
                $rules['hiring_details.deposit_amount'] = 'nullable|numeric|min:0';
                $rules['unavailability_durations'] = 'nullable|array';
                $rules['unavailability_durations.*.start_date'] = 'required|date';
                $rules['unavailability_durations.*.end_date'] = 'required|date';
            }

            $validation = $this->validationService->validate($request, $rules);

            if ($validation) {
                return apiResponse(false, $validation, 'Invalid data', 5, 422);
            }

            $product = $this->productService->findById($id);

            if (!$product) {
                return apiResponse(false, null, 'Product not found', 5, 404);
            }

            $product = $this->productService->update($request->all(), $id);
            return apiResponse(true, $product);
        } catch (Exception $ex) {
            return apiResponse(false, $ex->getMessage(), 'Something went wrong', 1, 500);
        }
    }

    public function addFavourite($id)
    {
        try {
            $this->productService->addFavourite($id);
            return apiResponse(true, 'Product added to favourites');
        } catch (Exception $ex) {
            return apiResponse(false, $ex->getMessage(), 'Something went wrong', 1, 500);
        }
    }

    public function removeFavourite($id)
    {
        try {
            $this->productService->removeFavourite($id);
            return apiResponse(true, 'Product removed from favourites');
        } catch (Exception $ex) {
            return apiResponse(false, $ex->getMessage(), 'Something went wrong', 1, 500);
        }
    }

    public function destroy($id)
    {
        try {
            $product = $this->productService->findById($id);

            if (!$product) {
                return apiResponse(false, null, 'Product not found', 5, 404);
            }

            $this->productService->delete($id);
            return apiResponse(true, null);
        } catch (Exception $ex) {
            return apiResponse(false, $ex->getMessage(), 'Something went wrong', 1, 500);
        }
    }

    public function featureThisItem($id)
    {
        try {
            $url = $this->productService->featureThisItem($id);
            return apiResponse(true, $url);
        } catch (Exception $ex) {
            return apiResponse(false, $ex->getMessage(), 'Something went wrong', 1, 500);
        }
    }
}
