<?php

namespace App\Repositories;

use App\Models\UserReview;

class UserReviewRepository
{
    protected UserReview $userReview;

    public function __construct(UserReview $userReview)
    {
        $this->userReview = $userReview;
    }

    public function create(array $data)
    {
        return $this->userReview->create($data);
    }

    public function findBySellerId($sellerId)
    {
        return $this->userReview->where('seller_id', $sellerId)->with('user')->latest()->get();
    }
}
