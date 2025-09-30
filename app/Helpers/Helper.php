<?php

use App\Mail\orderEmail;
use App\Models\Carousel;
use App\Models\Category;
use App\Models\Gift;
use App\Models\LeafCategory;
use App\Models\Order;
use App\Models\Page;
use App\Models\Product;
use App\Models\Setting;
use App\Models\ProductImage;
use App\Models\ProductRating;
use App\Models\ProductVariation;
use App\Models\Wishlist;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

function featured_products()
{

    $featured =  Product::latest()
        ->where('is_featured', 1)
        ->where('status', 1)
        ->where('is_bidding', false)
        ->with('images')
        ->get()
        ->shuffle()
        ->take(4);


    return $featured;
}


function carousel()
{
    return Carousel::where('status', 1)->inRandomOrder()->get();
}
function categories()
{
    return Category::where('status', 1)->latest()->get();
}

function leafCategories()
{
    return LeafCategory::where('status', 1)->latest()->get();
}
function getProductsBySubCategory()
{
    $subCategories = LeafCategory::where('status', 1)
        ->whereHas('products')
        ->with(['products' => function ($q) {
            $q->latest()->take(8);
        }])
        ->get();

    return $subCategories;
}
function getStaticPages()
{
    $pages = page::orderBy('name', 'ASC')
        ->where('status', 1)
        ->get();
    return $pages;
}

function getImagesBySku(string $sku)
{
    $images = [];

    $variation = ProductVariation::where('sku', $sku)->with(['product', 'images', 'defaultImage'])->first();
    if ($variation) {
        // Add the default image of the variation (if it exists)
        if ($variation->defaultImage) {
            $images[] = asset($variation->defaultImage->image);
        }

        // Add other images of the variation (if any)
        foreach ($variation->images as $image) {
            $images[] = asset($image->image);
        }

        // Add the default image of the product (if it exists)
        if ($variation->product && $variation->product->defaultImage) {
            $images[] = asset($variation->product->defaultImage->image);
        }

        // Add other product images (excluding default)
        if ($variation->product) {
            foreach ($variation->product->images as $image) {
                if (!$image->is_default) {
                    $images[] = asset($image->image);
                }
            }
        }
    } else {

        $product = Product::where('sku', $sku)->with(['images', 'defaultImage'])->first();

        if ($product) {

            if ($product->defaultImage) {
                $images[] = asset($product->defaultImage->image);
            }

            foreach ($product->images as $image) {
                if (!$image->is_default) {
                    $images[] = asset($image->image);
                }
            }
        }
    }
    return array_values(array_unique($images));
}



function maxPrice()
{

    // if (Auth::check() && Auth::user()->region === 'India') {
    //     $maxPrice = Product::max('price') * Setting::latest()->first()->conversion_rate_usd_to_inr;
    // } else {
    $maxPrice = Product::max('price');
    // }


    return  $maxPrice;
}
function getRandomImage()
{
    $image = ProductImage::inRandomOrder()->first();

    if ($image) {
        return $image->image;
    } else {
        return 'default.jpg';
    }
}

function pages()
{
    return Page::latest()->get();
}

function orderEmail($orderid, $user = 'customer')
{
    $order = Order::where('id', $orderid)->with('items')->first();

    if ($user == 'admin') {
        $subject = 'requested order invoice.';
        $email = Auth::guard('admin')->user()->email;
    } else {
        $subject = 'Thanks for shoping with us.';
        $email = $order->email;
    }
    $mailData = [
        'subject' => $subject,
        'order' => $order,
        'userType' => $user

    ];
    Mail::to($email)->send(new orderEmail($mailData));
}

function getProduct($sku)
{


    $product = Product::Where('sku', $sku)->first();

    if ($product == null) {

        $variation = ProductVariation::where('sku', $sku)->first();

        if ($variation == null) {
            return response()->json([
                'status' => false,
                'message' => 'Sorry, product not found.'
            ]);
        }

        $product = $variation->product;
    }

    return $product;
}

function gettesTimonials()
{
    $testimonials = ProductRating::where('status', 1)
        ->inRandomOrder()
        ->take(10)
        ->get();
    return $testimonials;

}
function getProductByid($id)
{


    $product = Product::find($id);

    if ($product == null) {
        return 'Sorry, product not found.';
    }

    return $product;
}

// function calculateShipping()
// {
//     $totalShipping = 0;

//     if (Cart::count() > 0) {
//         foreach (Cart::content() as $item) {
//             $product = Product::where('sku', $item->id)->first();

//             if ($product == null) {
//                 $variation = ProductVariation::where('sku', $item->id)->first();
//                 if ($variation == null) {
//                     return 0;
//                 }
//                 $product = $variation->product;
//             }

//             // if (Auth::user()->region === 'India') {

//             //     $totalShipping += ($product->inrShipping ?? 0) * $item->qty;
//             // } else {
//             $totalShipping += ($product->shipping ?? 0) * $item->qty;
//             // }
//         }
//     }

//     return $totalShipping;
// }

function calculateShipping()
{
    $totalShipping = 0;

    if (Cart::count() > 0) {
        foreach (Cart::content() as $item) {
            $product = Product::where('sku', $item->id)->first();

            if ($product == null) {
                $variation = ProductVariation::where('sku', $item->id)->first();
                if ($variation == null) {
                    return 0;
                }
                $product = $variation->product;
            }

            $qty = $item->qty;

            $shipping = $product->shipping ?? 0;
            $addonShipping = $product->shippingAddons ?? 0;

            if ($qty > 0) {
                $totalShipping += $shipping; // for the first item
            }

            if ($qty > 1) {
                $totalShipping += ($qty - 1) * $addonShipping; // for remaining items
            }
        }
    }

    return $totalShipping;
}


function wishlistCount()
{
    if (Auth::check()) {
        return Wishlist::where('user_id', Auth::user()->id)->count();
    } else {
        return 0;
    }
}

function is_gift($uid)
{
    return Gift::where('user_id', $uid)->exists();
}

function is_gift_used($uid)
{
    $gift = Gift::where('user_id', $uid)->latest()->first();

    if ($gift) {
        if ($gift->is_used == 1) {
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
}


function getColors($product_id)
{
    // get from Product first
    $product = Product::find($product_id);
    $colors = [];
    if ($product && $product->color) {
        $colors[] = ['color' => $product->color, 'sku' => $product->sku, 'is_main'=> true];
    }

    // get from variations
    $variations = ProductVariation::where('product_id', $product_id)->get();
    foreach ($variations as $variation) {
        if ($variation->color) {
            $colors[] = ['color' => $variation->color, 'sku' => $variation->sku, 'is_main'=> false];
        }
    }

    return $colors;
}

function getSizes($product_id)
{
    // get from Product first
    $product = Product::find($product_id);
    $sizes = [];
    if ($product && $product->size) {
        $sizes[] = ['size' => $product->size, 'sku' => $product->sku, 'is_main'=> true];
    }

    // get from variations
    $variations = ProductVariation::where('product_id', $product_id)->get();
    foreach ($variations as $variation) {
        if ($variation->size) {
            $sizes[] = ['size' => $variation->size, 'sku' => $variation->sku, 'is_main'=> false];
        }
    }

    return $sizes;
}