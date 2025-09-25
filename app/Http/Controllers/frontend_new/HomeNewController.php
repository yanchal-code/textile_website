<?php

namespace App\Http\Controllers\frontend_new;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str; 
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

            return view('frontend_new.product-gallery', compact('products'));
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
            $page = Page::where('slug', 'contact-us')->first();
            return view('frontend_new.contact', compact('page'));
        }
        public function sendContactEmail(Request $request)
        {
            if ($request->isMethod('post')) {
                $validator = Validator::make($request->all(), [
                    'name'    => 'required|string|max:100',
                    'email'   => 'required|email|max:100',
                    'subject' => 'required|string|max:150',
                    'message' => 'required|string|max:1000',
                ]);

                if ($validator->fails()) {
                    return response()->json([
                        'status' => false,
                        'errors' => $validator->errors()
                    ]);
                }

                $mailData = [
                    'name'    => $request->name,
                    'email'   => $request->email,
                    'subject' => $request->subject,
                    'message' => $request->message,
                    'mail_subject' => 'A contact query from ' . $request->name
                ];

                $admin = User::find(1); // admin email
                if ($admin) {
                    Mail::to($admin->email)->send(new ContactEmail($mailData));
                }

                return response()->json([
                    'status' => true,
                    'message' => "Message sent successfully, thanks for contacting."
                ]);
            }
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
            $sections = Page::where('slug', 'about-us')->first();
            $limitedContent = Str::limit($sections->content, 200);
            return view('frontend_new.about', compact('limitedContent', 'sections'));
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

