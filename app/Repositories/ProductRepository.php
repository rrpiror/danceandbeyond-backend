<?php

namespace App\Repositories;

use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;

class ProductRepository
{
    protected Product $product;
    public array $productRelationAttributes = ['brand', 'category', 'condition', 'productSizes.size', 'colours', 'fulfillmentOptions', 'hiringDetail', 'unavailabilityDurations', 'user:id,name', 'reviews.user:id,name', 'media'];

    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    public function query()
    {
        return $this->product->query();
    }

    public function findByFilters(array $data)
    {
        $query = $this->product->query();
        if (isset($data['name'])) {
            $query->where(function ($q) use ($data) {
                $q->where('name', 'like', '%' . $data['name'] . '%')
                    ->orWhere('description', 'like', '%' . $data['name'] . '%');
            });
        }

        if (isset($data['user_id'])) {
            $query->where('user_id', $data['user_id']);
        }

        if (isset($data['price_min'])) {
            $query = $query->where('price', '>=', $data['price_min']);
        }

        if (isset($data['price_max'])) {
            $query = $query->where('price', '<=', $data['price_max']);
        }

        // Handle size_quantities - map of size_id => minimum_quantity
        if (isset($data['size_quantities']) && is_array($data['size_quantities'])) {
            $sizeQuantities = $data['size_quantities'];
            
            // Filter products that have ALL specified sizes with the required quantities
            $query = $query->where(function ($q) use ($sizeQuantities) {
                foreach ($sizeQuantities as $sizeId => $minQuantity) {
                    $q->whereHas('productSizes', function ($query) use ($sizeId, $minQuantity) {
                        $query->where('size_id', $sizeId)
                              ->where('quantity', '>=', $minQuantity);
                    });
                }
            });
        }
        
        // Legacy support: Keep old size_id filtering for backward compatibility
        if (isset($data['size_id']) && !isset($data['size_quantities'])) {
            if (is_array($data['size_id'])) {
                $query = $query->whereHas('productSizes', function ($query) use ($data) {
                    $query->whereIn('size_id', $data['size_id']);
                });
            } else {
                $query = $query->whereHas('productSizes', function ($query) use ($data) {
                    $query->where('size_id', $data['size_id']);
                });
            }
        }

        // Legacy support: Keep old quantity filtering for backward compatibility
        if (isset($data['quantity']) && !isset($data['size_quantities'])) {
            $query = $query->whereHas('productSizes', function ($query) use ($data) {
                $query->where('quantity', '>=', $data['quantity']);
            });
        }

        if (isset($data['brand_id'])) {
            if (is_array($data['brand_id'])) {
                $query = $query->whereIn('brand_id', $data['brand_id']);
            } else {
                $query = $query->where('brand_id', $data['brand_id']);
            }
        }

        if (isset($data['condition_id'])) {
            if (is_array($data['condition_id'])) {
                $query = $query->whereIn('condition_id', $data['condition_id']);
            } else {
                $query = $query->where('condition_id', $data['condition_id']);
            }
        }

        if (isset($data['colour_id'])) {
            if (is_array($data['colour_id'])) {
                $query = $query->whereHas('colours', function ($query) use ($data) {
                    $query->whereIn('colour_id', $data['colour_id']);
                });
            } else {
                $query = $query->whereHas('colours', function ($query) use ($data) {
                    $query->where('colour_id', $data['colour_id']);
                });
            }
        }

        // Pagination parameters
        $perPage = isset($data['per_page']) ? (int)$data['per_page'] : 10;
        $page = isset($data['page']) ? (int)$data['page'] : 1;

        $query = $query->withCount('reviews')
            ->withAvg('reviews', 'rating')
            ->with('media')
            ->latest();

        // Get paginated results
        $paginatedProducts = $query->paginate($perPage, ['*'], 'page', $page);

        // Process the products to add favourite attribute
        $products = $this->addFavouriteAttributeIfAuthenticated($paginatedProducts->getCollection());
        
        // Replace the collection in the paginated result
        $paginatedProducts->setCollection($products);

        return $paginatedProducts;
    }

    public function findById($id)
    {
        $product = $this->product->with($this->productRelationAttributes)->find($id);
        $product = $this->addFavouriteAttributeIfAuthenticated($product);
        return $product;
    }

    private function addFavouriteAttributeIfAuthenticated(Collection|Product $data): Collection|Product
    {
        if (Auth::check()) {
            $user = Auth::user();
            $favouriteProductIds = $user ? $user->favouriteProducts()->pluck('product_id')->toArray() : [];
            if ($data instanceof Collection) {
                $data->each(function ($product) use ($favouriteProductIds) {
                    $product->is_favourite = in_array($product->id, $favouriteProductIds);
                });
            } else {
                $data->is_favourite = in_array($data->id, $favouriteProductIds);
            }
        }
        return $data;
    }

    public function create(array $data)
    {
        return $this->product->create($data);
    }

    public function findByIds(array $ids)
    {
        return $this->product->with('hiringDetail')->whereIn('id', $ids)->get();
    }

    public function findProductsByUserId($userId)
    {
        return $this->product->where('user_id', $userId)->with('media', 'brand')->latest()->get();
    }
}
