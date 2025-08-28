<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function manageJoiningPackage(Request $request)
    {
        // Paginate the results, 25 per page
        $products = Product::where('joining', 1)
            ->orderBy('pro_id')
            ->paginate(25);

        return view('admin.products.manage', compact('products'));
    }
}
