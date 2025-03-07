<?php

namespace App\Http\Controllers;

use App\Services\FulfillmentOptionService;
use Exception;
use Illuminate\Http\Request;

class FulfillmentOptionController extends Controller
{
    protected FulfillmentOptionService $fulfillmentOptionService;

    public function __construct(FulfillmentOptionService $fulfillmentOptionService)
    {
        $this->fulfillmentOptionService = $fulfillmentOptionService;
    }

    public function index()
    {
        try {
            $options = $this->fulfillmentOptionService->getAll();
            return apiResponse(true, $options);
        } catch (Exception $ex) {
            return apiResponse(false, $ex->getMessage(), 'Something went wrong', 1, 500);
        }
    }
}
