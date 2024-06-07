<?php

namespace App\Http\Controllers;
use Auth;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Wishlist;
class WishlistController extends Controller
{
    protected $product=null;
    public function __construct(Product $product){
        $this->product=$product;
    }

    public function wishlist(Request $request) {
        if (empty($product = Product::where('slug', $request->slug)->first()) || empty($request->slug)) {
            request()->session()->flash('error','Недопустимые продукты');
            return back();
        }

        $already_wishlist = Wishlist::where('user_id', auth()->user()->id)->where('cart_id',null)->where('product_id', $product->id)->first();

        if($already_wishlist) {
            request()->session()->flash('error','Это уже внесено в список желаний');
            return back();
        }else{

            $wishlist = new Wishlist;
            $wishlist->user_id = auth()->user()->id;
            $wishlist->product_id = $product->id;
            $wishlist->price = ($product->price-($product->price*$product->discount)/100);
            $wishlist->quantity = 1;
            $wishlist->amount=$wishlist->price*$wishlist->quantity;
            if ($wishlist->product->stock < $wishlist->quantity || $wishlist->product->stock <= 0) return back()->with('error','Недостаточно товара на складе!.');
            $wishlist->save();
        }
        request()->session()->flash('success','Product successfully added to wishlist');
        return back();
    }

    public function wishlistDelete(Request $request){
        $wishlist = Wishlist::find($request->id);
        if ($wishlist) {
            $wishlist->delete();
            request()->session()->flash('success','Wishlist successfully removed');
            return back();
        }
        request()->session()->flash('error','Ошибка, пожалуйста, повторите попытку');
        return back();
    }
}
