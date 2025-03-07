<?php

namespace App\Services;

use App\Repositories\HiringDetailRepository;
use App\Repositories\HiringUnavailabilityDayRepository;
use App\Repositories\ProductFulfillmentOptionRepository;
use App\Repositories\ProductRepository;
use App\Repositories\ProductSizeRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProductService
{
    protected ProductRepository $productRepository;
    protected ProductFulfillmentOptionRepository $productFulfillmentOptionRepository;
    protected ProductSizeRepository $productSizeRepository;
    protected HiringDetailRepository $hiringDetailRepository;
    protected HiringUnavailabilityDayRepository $hiringUnavailbilityDayRepository;

    public function __construct(ProductRepository $productRepository, ProductFulfillmentOptionRepository $productFulfillmentOptionRepository, ProductSizeRepository $productSizeRepository, HiringDetailRepository $hiringDetailRepository, HiringUnavailabilityDayRepository $hiringUnavailbilityDayRepository)
    {
        $this->productRepository = $productRepository;
        $this->productFulfillmentOptionRepository = $productFulfillmentOptionRepository;
        $this->productSizeRepository = $productSizeRepository;
        $this->hiringDetailRepository = $hiringDetailRepository;
        $this->hiringUnavailbilityDayRepository = $hiringUnavailbilityDayRepository;
    }

    public function getAll()
    {
        return $this->productRepository->getAll();
    }

    public function findById($id)
    {
        return $this->productRepository->findById($id);
    }

    public function create(array $data)
    {
        $data['user_id'] = Auth::id();
        try {
            DB::beginTransaction();

            $product = $this->productRepository->create($data);

            if (isset($data['fulfillment_options'])) {
                $product->fulfillmentOptions()->sync($data['fulfillment_options']);
            }

            if (isset($data['sizes'])) {
                $product->sizes()->sync($data['sizes']);
            }

            if ($data['type'] == 'hire') {
                if (isset($data['hiring_details'])) {
                    $data['hiring_details']['product_id'] = $product->id;

                    $hiringDetails = $this->hiringDetailRepository->create($data['hiring_details']);

                    if (isset($data['unavailable_dates'])) {
                        $hiringDetails->unavailabilityDays()->createMany(
                            collect($data['unavailable_dates'])->map(fn($date) => ['date' => $date])->toArray()
                        );
                    }
                }
            }

            DB::commit();
            return $product;
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

            $product->fill($data)->save();

            if (isset($data['fulfillment_options'])) {
                $product->fulfillmentOptions()->sync($data['fulfillment_options']);
            }

            if (isset($data['sizes'])) {
                $product->sizes()->sync($data['sizes']);
            }

            if ($data['type'] == 'hire') {
                if (isset($data['hiring_details'])) {
                    $data['hiring_details']['product_id'] = $product->id;

                    $hiringDetails = $this->hiringDetailRepository->updateOrCreate($data['hiring_details']);

                    if (isset($data['unavailable_dates'])) {
                        $hiringDetails->unavailabilityDays()->delete();
                        $hiringDetails->unavailabilityDays()->createMany(
                            collect($data['unavailable_dates'])->map(fn($date) => ['date' => $date])->toArray()
                        );
                    }
                }
            }

            DB::commit();
            return $product;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function addFavourite($productId)
    {
        $user = Auth::user();
        $user->favouriteProducts()->sync($productId);
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
        return $user->favouriteProducts;
    }

    public function delete($id)
    {
        $product = $this->productRepository->findById($id);
        $hiringDetail = $this->hiringDetailRepository->findByProductId($id);

        $this->productFulfillmentOptionRepository->deleteByProductId($id);
        $this->productSizeRepository->deleteByProductId($id);
        $this->hiringUnavailbilityDayRepository->deleteByHiringDetailId($hiringDetail->id);
        $this->hiringDetailRepository->deleteByProductId($id);

        $product->delete();

        return true;
    }
}
