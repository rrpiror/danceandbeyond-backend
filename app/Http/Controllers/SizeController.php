<?php

namespace App\Http\Controllers;

use App\Services\SizeService;
use Exception;
use Illuminate\Http\Request;

class SizeController extends Controller
{
    protected SizeService $sizeService;

    public function __construct(SizeService $sizeService)
    {
        $this->sizeService = $sizeService;
    }

    public function index()
    {
        try {
            $sizes = $this->sizeService->getAll();
            return apiResponse(true, $sizes);
        } catch (Exception $ex) {
            return apiResponse(false, $ex->getMessage(), 'Something went wrong', 1, 500);
        }
    }
}
