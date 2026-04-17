<?php

namespace App\Http\Controllers\AbandonedCart;

use App\Http\Controllers\Controller;
use App\Models\AbandonedCart\AbandonedCart;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index()
    {
        $carts = AbandonedCart::with([
            'cart' => [
                'items' => [
                    'product'
                ],
            ],
        ])->latest()->paginate(10);

        return view('backend.abandoned_cart.carts.index', compact('carts'));
    }
}
