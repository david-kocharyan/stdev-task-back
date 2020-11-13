<?php

namespace App\Http\Controllers\Api;

use App\Helper\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $data = Category::all();
        if (null == $data) {
            return ResponseHelper::fail("Categories Not Found", 422);
        }
        $resp = array(
            "category" => $data
        );
        return ResponseHelper::success($resp);
    }
}
