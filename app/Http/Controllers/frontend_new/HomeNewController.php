<?php

namespace App\Http\Controllers\frontend_new;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use App\Models\Page;
use App\Models\Product;
use App\Models\User;
use App\Mail\contactEmail;
use App\Models\BiddingProduct;
use App\Models\Newsletter;
use App\Models\Wishlist;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\Blog;
use App\Models\ProductRating;
use App\Models\Category;


class HomeNewController extends Controller
{
    public function index()
    {
         $products = Product::latest()
            ->where('status', 1)
            ->with('images')
            ->with(['variations' => function ($query) {
                $query->where('quantity', '>', 0);
            }])
            ->get()
            ->shuffle()
            ->take(8);

        $featured =  Product::latest()
            ->where('is_featured', 1)
            ->where('status', 1)
            ->where('is_bidding', false)
            ->with('images')
            ->get()
            ->shuffle()
            ->take(10);

        $blogs = Blog::inRandomOrder()->take(6)->get();
        $data['blogs'] = $blogs;
        $data['featured'] = $featured;
        $data['products'] = $products;

        $data['categories'] = Category::where('status', 1)->get();
        return view('frontend_new.index-1', $data);
    }
   public function login()
    {
        return view('frontend_new.login');
    }

    public function myAccount()
    {
        return view('frontend_new.my-account');
    }

    public function myAccount2()
    {
        return view('frontend_new.my-account-2');
    }

    public function portfolio()
    {
        return view('frontend_new.portfolio');
    }

    public function printDesign()
    {
        return view('frontend_new.print_design');
    }

    public function privacyPolicy()
    {
        return view('frontend_new.privacy-policy');
    }

    public function productDetails()
    {
        return view('frontend_new.product-details');
    }

    public function productGallery()
    {
        return view('frontend_new.product-gallery');
    }

    public function productLeftSidebar()
    {
        return view('frontend_new.product-left-sidebar');
    }

    public function productVideo()
    {
        return view('frontend_new.product-video');
    }

    public function shopGridList()
    {
        return view('frontend_new.shop-grid-list');
    }

    public function shopGrid()
    {
        return view('frontend_new.shop-grid');
    }

    public function shopList()
    {
        return view('frontend_new.shop-list');
    }

    public function shopRightSidebar()
    {
        return view('frontend_new.shop-right-sidebar');
    }

    public function shop()
    {
        // call products based on catgory id if passed in query string
        $category_id = request()->query('category');
        if ($category_id) {
            $products = Product::where('category_id', $category_id)
                ->where('status', 1)
                ->with('images')
                ->with(['variations' => function ($query) {
                    $query->where('quantity', '>', 0);
                }])
                ->get();
        } else {
            $products = Product::latest()
                ->where('status', 1)
                ->with('images')
                ->with(['variations' => function ($query) {
                    $query->where('quantity', '>', 0);
                }])
                ->get();
        }   

        return view('frontend_new.shop',compact('products'));
    }

    public function wishlist()
    {
        return view('frontend_new.wishlist');
    }

    public function compare()
    {
        return view('frontend_new.compare');
    }

    public function contact()
    {
        return view('frontend_new.contact');
    }

    public function faq()
    {
        return view('frontend_new.faq');
    }
     public function errorPage()
    {
        return view('frontend_new.404');
    }
     public function about()
    {
        return view('frontend_new.about');
    }
   public function blogDetails($id)
    {
        
        $blog = Blog::findOrFail($id);

        
        $recentBlogs = Blog::latest()->inRandomOrder()->take(6)->get();

        
        return view('frontend_new.blog-details', compact('blog',  'recentBlogs'));
    }


     public function blogLeftSidebar()
    {
        return view('frontend_new.blog-left-sidebar');
    }
     public function blogRightSidebar()
    {
        return view('frontend_new.blog-right-sidebar');
    }
     public function blog()
    {
        $blogs = Blog::inRandomOrder()->take(6)->get();
        $data['blogs'] = $blogs;
      return view('frontend_new.blog', $data);
    }
     public function checkout()
    {
      return view('frontend_new.checkout');
    }
     public function checkout2()
    {
      return view('frontend_new.checkout-2');
    }
     public function checkout3()
    {
       return view('frontend_new.checkout-3');
    }
     public function checkout4()
    {
      return view('frontend_new.checkout-4');
    }
     public function cart()
    {

      return view('frontend_new.cart');

    }
    

   
}
