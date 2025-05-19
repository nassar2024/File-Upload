<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ProductMasterList;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $perPage = $request->query('per_page', 5);

        $query = ProductMasterList::query();

        if ($search) {
            $query->where('product_id', 'like', "%{$search}%");
        }

        $products = $query->paginate($perPage);

        return response()->json($products);
    }
}