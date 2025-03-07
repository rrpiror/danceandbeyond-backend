<?php

namespace App\Http\Controllers;

use App\Services\ConditionService;
use Exception;
use Illuminate\Http\Request;

class ConditionController extends Controller
{
    protected ConditionService $conditionService;

    public function __construct(ConditionService $conditionService)
    {
        $this->conditionService = $conditionService;
    }

    public function index()
    {
        try {
            $conditions = $this->conditionService->getAll();
            return apiResponse(true, $conditions);
        } catch (Exception $ex) {
            return apiResponse(false, $ex->getMessage(), 'Something went wrong', 1, 500);
        }
    }
}
