<?php

namespace App\Services;

use App\Repositories\HiringDetailRepository;
use App\Repositories\UnavailabilityDurationRepository;
use App\Repositories\ProductFulfillmentOptionRepository;
use App\Repositories\ProductRepository;
use App\Repositories\ProductVariantRepository;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Repositories\BrandRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\ConditionRepository;
use App\Repositories\ProductSizeRepository;
use App\Repositories\FulfillmentOptionRepository;
use App\Repositories\ServiceProviderRepository;
use App\Repositories\ProductColourRepository;
use App\Services\PaymentService;

class ProductService
{
    protected ProductRepository $productRepository;
    protected ProductFulfillmentOptionRepository $productFulfillmentOptionRepository;
    protected ProductVariantRepository $productVariantRepository;
    protected HiringDetailRepository $hiringDetailRepository;
    protected UnavailabilityDurationRepository $unavailabilityDurationRepository;
    protected BrandRepository $brandRepository;
    protected CategoryRepository $categoryRepository;
    protected ConditionRepository $conditionRepository;
    protected ProductSizeRepository $productSizeRepository;
    protected FulfillmentOptionRepository $fulfillmentOptionRepository;
    protected ServiceProviderRepository $serviceProviderRepository;
    protected ProductColourRepository $productColourRepository;
    protected PaymentService $paymentService;

    public function __construct(
        ProductRepository $productRepository,
        ProductFulfillmentOptionRepository $productFulfillmentOptionRepository,
        ProductVariantRepository $productVariantRepository,
        HiringDetailRepository $hiringDetailRepository,
        UnavailabilityDurationRepository $unavailabilityDurationRepository,
        BrandRepository $brandRepository,
        CategoryRepository $categoryRepository,
        ConditionRepository $conditionRepository,
        ProductSizeRepository $productSizeRepository,
        FulfillmentOptionRepository $fulfillmentOptionRepository,
        ServiceProviderRepository $serviceProviderRepository,
        ProductColourRepository $productColourRepository,
        PaymentService $paymentService
    ) {
        $this->productRepository = $productRepository;
        $this->productFulfillmentOptionRepository = $productFulfillmentOptionRepository;
        $this->productVariantRepository = $productVariantRepository;
        $this->hiringDetailRepository = $hiringDetailRepository;
        $this->unavailabilityDurationRepository = $unavailabilityDurationRepository;
        $this->brandRepository = $brandRepository;
        $this->categoryRepository = $categoryRepository;
        $this->conditionRepository = $conditionRepository;
        $this->productSizeRepository = $productSizeRepository;
        $this->fulfillmentOptionRepository = $fulfillmentOptionRepository;
        $this->serviceProviderRepository = $serviceProviderRepository;
        $this->productColourRepository = $productColourRepository;
        $this->paymentService = $paymentService;
    }

    public function getAllBrands()
    {
        return $this->brandRepository->getAll();
    }

    public function getAllCategories()
    {
        return $this->categoryRepository->getAll();
    }

    public function getAllConditions()
    {
        return $this->conditionRepository->getAll();
    }

    public function getAllSizes()
    {
        return $this->productSizeRepository->getAll();
    }

    public function getAllFulfillmentOptions()
    {
        return $this->fulfillmentOptionRepository->getAll();
    }

    public function getAllShippingServiceProviders()
    {
        return $this->serviceProviderRepository->getAll();
    }

    public function getAllColours()
    {
        return $this->productColourRepository->getAll();
    }

    public function getAll(array $data)
    {
        return $this->productRepository->findByFilters($data);

    }

    public function findById($id)
    {
        $product = $this->productRepository->findById($id);

        if (!$product) {
            throw new Exception('Product not found.', 404);
        }

        return $product;
    }

    public function create(array $data)
    {
        $data['user_id'] = Auth::id();
        try {
            DB::beginTransaction();

            $product = $this->productRepository->create($data);
            
            if (isset($data['fulfillment_option_ids'])) {
                $product->fulfillmentOptions()->sync($data['fulfillment_option_ids']);
            }

            // Handle variants - array of {colour_id, size_id, quantity}
            if (isset($data['variants']) && is_array($data['variants'])) {
                foreach ($data['variants'] as $variant) {
                    $product->variants()->create([
                        'colour_id' => $variant['colour_id'],
                        'size_id' => $variant['size_id'],
                        'quantity' => $variant['quantity'] ?? 0,
                    ]);
                }
            }

            if ($data['type'] == 'hire') {
                if (isset($data['hiring_details'])) {
                    $data['hiring_details']['product_id'] = $product->id;
                    $hiringDetails = $this->hiringDetailRepository->create($data['hiring_details']);
                }
            }

            // Handle unavailability durations for all products (both sale and hire)
            if (isset($data['unavailability_durations']) && is_array($data['unavailability_durations'])) {
                $unavailabilityData = collect($data['unavailability_durations'])->map(function($duration) {
                    return [
                        'start_date' => $duration['start_date'],
                        'end_date' => $duration['end_date']
                    ];
                })->toArray();
                
                $product->unavailabilityDurations()->createMany($unavailabilityData);
            }

            if (isset($data['shipping_service_providers'])) {
                $product->shippingServiceProviders()->sync($data['shipping_service_providers']);
            }

            if (isset($data['images']) && is_array($data['images'])) {
                foreach ($data['images'] as $base64Image) {
                    $imageType = explode(';', $base64Image)[0];
                    $imageType = explode('/', $imageType)[1];

                    if (!in_array(strtolower($imageType), ['jpg', 'jpeg', 'png'])) {
                        throw new Exception('Invalid image type. Only JPG, JPEG and PNG are allowed.', 422);
                    }

                    $product->addMediaFromBase64($base64Image)
                        ->usingFileName(uniqid() . '.' . $imageType)
                        ->toMediaCollection('images');
                }
            }

            DB::commit();
            return $product->load($this->productRepository->productRelationAttributes);
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function update(array $data, $id)
    {
        try {
            DB::beginTransaction();

            $product = $this->productRepository->findById($id);

            if (!$product) {
                throw new Exception('Product not found.', 404);
            }

            $product->fill($data)->save();

            if (isset($data['fulfillment_options'])) {
                $product->fulfillmentOptions()->sync($data['fulfillment_options']);
            }

            // Handle variants - delete existing and create new ones
            if (isset($data['variants'])) {
                $product->variants()->delete();
                
                if (is_array($data['variants']) && !empty($data['variants'])) {
                    foreach ($data['variants'] as $variant) {
                        $product->variants()->create([
                            'colour_id' => $variant['colour_id'],
                            'size_id' => $variant['size_id'],
                            'quantity' => $variant['quantity'] ?? 0,
                        ]);
                    }
                }
            }

            if ($data['type'] == 'hire') {
                if (isset($data['hiring_details'])) {
                    $data['hiring_details']['product_id'] = $product->id;
                    $hiringDetails = $this->hiringDetailRepository->updateOrCreate($data['hiring_details']);
                }
            }

            // Handle unavailability durations for all products (both sale and hire)
            if (isset($data['unavailability_durations'])) {
                // Delete existing unavailability durations
                $product->unavailabilityDurations()->delete();
                
                // Create new ones if provided
                if (is_array($data['unavailability_durations']) && !empty($data['unavailability_durations'])) {
                    $unavailabilityData = collect($data['unavailability_durations'])->map(function($duration) {
                        return [
                            'start_date' => $duration['start_date'],
                            'end_date' => $duration['end_date']
                        ];
                    })->toArray();
                    
                    $product->unavailabilityDurations()->createMany($unavailabilityData);
                }
            }

            if (isset($data['shipping_service_providers'])) {
                $product->shippingServiceProviders()->sync($data['shipping_service_providers']);
            }

            if (isset($data['images']) && is_array($data['images'])) {
                foreach ($data['images'] as $base64Image) {
                    $imageType = explode(';', $base64Image)[0];
                    $imageType = explode('/', $imageType)[1];

                    if (!in_array(strtolower($imageType), ['jpg', 'jpeg', 'png'])) {
                        throw new Exception('Invalid image type. Only JPG, JPEG and PNG are allowed.', 422);
                    }

                    $product->addMediaFromBase64($base64Image)
                        ->usingFileName(uniqid() . '.' . $imageType)
                        ->toMediaCollection('images');
                }
            }

            DB::commit();
            $product = $this->productRepository->findById($id);
            return $product;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function addFavourite($productId)
    {
        $user = Auth::user();
        $user->favouriteProducts()->syncWithoutDetaching($productId);
        return true;
    }

    public function removeFavourite($productId)
    {
        $user = Auth::user();
        $user->favouriteProducts()->detach($productId);
        return true;
    }

    public function getFavouriteProducts()
    {
        $user = Auth::user();
        return $user->favouriteProducts->load('media');
    }

    public function delete($id)
    {
        $product = $this->productRepository->findById($id);

        $this->productFulfillmentOptionRepository->deleteByProductId($id);
        $this->productVariantRepository->deleteByProductId($id);
        $this->unavailabilityDurationRepository->deleteByProductId($id);
        
        // Delete hiring detail if exists
        $hiringDetail = $this->hiringDetailRepository->findByProductId($id);
        if ($hiringDetail) {
            $this->hiringDetailRepository->deleteByProductId($id);
        }

        $product->delete();

        return true;
    }

    public function getProductsByUserId($userId)
    {
        return $this->productRepository->findProductsByUserId($userId);
    }

    public function featureThisItem($id)
    {
        $product = $this->productRepository->findById($id);

        if ($product->is_featured == 0) {
            throw new Exception('Product is not featured.', 400);
        }

        $url = $this->paymentService->createFeatureItemPaymentIntent($product->toArray());

        return $url;
    }
}
