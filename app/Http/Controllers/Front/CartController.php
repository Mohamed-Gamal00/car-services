<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Color;
use App\Models\Product;
use App\Repositories\Cart\CartModelRepository;
use App\Repositories\Cart\CartRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    protected $cart;

    public function __construct(CartRepository $cart)
    {
        $this->cart = $cart;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('front.cart.index', [
            'cart' => $this->cart,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => ['required', 'int', 'exists:products,id'],
            'quantity' => ['nullable', 'int', 'min:1'],
            'color_id' => 'nullable|exists:colors,id'
        ]);

        $product = Product::findOrfail($request->post('product_id'));

        if ($product->quantity < $request->quantity) {
            return redirect()->back()->with('warning', 'عفوا هذه الكميه ليست متاحه');
        }


        $action = $request->input('action');

//        dd($action);

        if ($action === 'add') {
            $this->cart->add($product, $request->input('quantity'), $request->color_id);
            return redirect()->back()->with('info', 'تم اضافة المنتج في السلة');
        } elseif ($action === 'addAndGoToCart') {
            $this->cart->add($product, $request->input('quantity'), $request->color_id);
            return redirect()->route('order.index');
        }

        $this->cart->add($product, $request->post('quantity'));
        return redirect()->back(); //new line
    }

    public function storeAndGoToCart(Request $request)
    {
        $request->validate([
            'product_id' => ['required', 'int', 'exists:products,id'],
            'quantity' => ['nullable', 'int', 'min:1'],
        ]);

        $product = Product::findOrFail($request->input('product_id'));
        $this->cart->add($product, $request->input('quantity'));

        return redirect()->route('cart.show');
    }


    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => ['required', 'int', 'min:1'],
        ]);
        $this->cart->update($id, $request->post('quantity'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->cart->delete($id);

//        return [
//            'message' => 'Item Deleted!'
//        ];
    }

    public function getCartCount(CartRepository $cart)
    {
        $count = $cart->get()->count();
        return response()->json(['count' => $count]);
    }

    public function getTotalPrice()
    {
        $totalPrice = resolve('App\currency\Currency')->getCurrency($this->cart->total());

        return response()->json(['totalPrice' => $totalPrice]);
    }

    public function getTotalQuantity(CartRepository $cart)
    {
        // Get the total quantity of all items in the cart
        $totalQuantity = $cart->get()->sum('quantity');

        return response()->json(['totalQuantity' => $totalQuantity]);
    }
}
