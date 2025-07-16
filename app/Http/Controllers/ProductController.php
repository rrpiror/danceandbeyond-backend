<?php

namespace App\Http\Controllers;

use App\Services\ProductService;
use App\Services\ValidationService;
use Exception;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    protected ProductService $productService;
    protected ValidationService $validationService;
    private const CREATE_RULES = [
        'category_id' => 'required',
        'condition_id' => 'required',
        'brand_id' => 'required',
        'name' => 'required',
        'description' => 'required',
        'price' => 'required',
        'type' => 'required',
        'fulfillment_options' => 'required',
        'sizes' => 'required'
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
            return apiResponse(true, $paginatedProducts->toArray());
        } catch (Exception $ex) {
            return apiResponse(false, $ex->getMessage(), 'Something went wrong', 1, 500);
        }
    }

    public function getProductsByUserId()
    {
        try {
            $products = $this->productService->getProductsByUserId();
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
            }

            $validation = $this->validationService->validate($request, $rules);

            if ($validation) {
                return apiResponse(false, $validation, 'Invalid data', 5, 422);
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
