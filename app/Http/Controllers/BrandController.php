<?php

namespace App\Http\Controllers;

use App\Services\BrandService;
use Exception;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    protected BrandService $brandService;

    public function __construct(BrandService $brandService)
    {
        $this->brandService = $brandService;
    }

    /**
     * @OA\Get(
     *     path="/api/brands",
     *     summary="Get a list of brands",
     *     tags={"Brands"},
     *     security={{"sanctum":{}}},
     *     @OA\Response(response=200, description="Successful operation"),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function index()
    {
        try {
            $brands = $this->brandService->getAll();
            return apiResponse(true, $brands);
        } catch (Exception $ex) {
            return apiResponse(false, $ex->getMessage(), 'Something went wrong', 1, 500);
        }
    }
}
