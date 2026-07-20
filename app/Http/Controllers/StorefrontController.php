<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\StorefrontProductService;
use App\Services\CartService;
use App\Services\CheckoutOrderService;
use App\Http\Requests\StoreCheckoutRequest;

class StorefrontController extends Controller
{
    protected $productService;
    protected $cartService;
    protected $checkoutOrderService;

    public function __construct(
        StorefrontProductService $productService,
        CartService $cartService,
        CheckoutOrderService $checkoutOrderService
    ) {
        $this->productService = $productService;
        $this->cartService = $cartService;
        $this->checkoutOrderService = $checkoutOrderService;
    }

    public function home()
    {
        $featured = $this->productService->getFeaturedProducts();
        $categories = $this->productService->getAllCategories();
        $stats = $this->productService->getHomeStats();

        return view('frontend.home', array_merge(
            compact('featured', 'categories'),
            $stats
        ));
    }

    public function shop()
    {
        $products = $this->productService->getShopProducts();
        $categories = $this->productService->getAllCategories();

        return view('frontend.shop', compact('products', 'categories'));
    }

    public function product($slug)
    {
        $product = $this->productService->getProductBySlug($slug);
        $related = $this->productService->getRelatedProducts($product);

        return view('frontend.product', compact('product', 'related'));
    }

    public function cart()
    {
        $cart = $this->cartService->getCart();
        $totals = $this->cartService->calculateTotals($cart);

        return view('frontend.cart', array_merge(compact('cart'), $totals));
    }

    public function addToCart(Request $request)
    {
        $variantId = $request->input('variant_id');
        $quantity = $request->has('quantity') ? intval($request->quantity) : 1;

        $cart = $this->cartService->addItem(
            $request->product_id,
            $variantId ? intval($variantId) : null,
            $quantity
        );

        if ($cart === null) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        return response()->json([
            'success'    => 'Product added to your crate successfully!',
            'cart_count' => $this->cartService->getTotalItemCount($cart),
        ]);
    }

    public function updateCart(Request $request)
    {
        if (!$request->id || !$request->quantity) {
            return response()->json(['error' => 'Invalid Request data'], 400);
        }

        $cart = $this->cartService->updateItemQuantity($request->id, $request->quantity);
        $totals = $this->cartService->calculateTotals($cart);

        return response()->json([
            'success'        => true,
            'subtotal'       => 'Rs. ' . number_format($totals['subtotal'], 0),
            'delivery'       => 'Rs. ' . number_format($totals['deliveryCharge'], 0),
            'total'          => 'Rs. ' . number_format($totals['totalAmount'], 0),
            'item_subtotal'  => 'Rs. ' . number_format($cart[$request->id]['price'] * $cart[$request->id]['quantity'], 0),
        ]);
    }

    public function removeFromCart(Request $request)
    {
        if (!$request->id) {
            return response()->json(['error' => 'Invalid Request ID'], 400);
        }

        $cart = $this->cartService->removeItem($request->id);
        $totals = $this->cartService->calculateTotals($cart);

        return response()->json([
            'success'    => true,
            'cart_count' => count($cart),
            'subtotal'   => 'Rs. ' . number_format($totals['subtotal'], 0),
            'delivery'   => 'Rs. ' . number_format($totals['deliveryCharge'], 0),
            'total'      => 'Rs. ' . number_format($totals['totalAmount'], 0),
        ]);
    }

    public function checkout()
    {
        $cart = $this->cartService->getCart();

        if (empty($cart)) {
            return redirect()->route('shop')->with('error', 'Your crate is empty!');
        }

        $totals = $this->cartService->calculateTotals($cart);

        return view('frontend.checkout', array_merge(compact('cart'), $totals));
    }

    public function storeCheckout(StoreCheckoutRequest $request)
    {
        try {
            $order = $this->checkoutOrderService->placeOrder($request->validated());

            return redirect()->route('order.success', $order->order_number)
                ->with('success', 'Order placed successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }
}
