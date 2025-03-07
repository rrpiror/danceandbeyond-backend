<?php

namespace App\Services;

use App\Repositories\PaymentMethodRepository;

class PaymentMethodService
{
    protected PaymentMethodRepository $paymentMethodRepository;

    public function __construct(PaymentMethodRepository $paymentMethodRepository)
    {
        $this->paymentMethodRepository = $paymentMethodRepository;
    }

    public function getAll()
    {
        return $this->paymentMethodRepository->getAll();
    }
}
