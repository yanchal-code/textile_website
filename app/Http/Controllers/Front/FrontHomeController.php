<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\contactEmail;
use App\Models\BiddingProduct;
use App\Models\Newsletter;
use App\Models\Wishlist;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Services\MailChimpClass;
use App\Models\Blog;
use App\Models\ProductRating;

class FrontHomeController extends Controller
{
    public function home()
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

        return view('front.index', $data);
    }

    public function blogs()
    {
        $blogs = Blog::latest()->paginate(12);
        $data['blogs'] = $blogs;

        return view('front.blogs', $data);
    }

    public function blog($slug = null)
    {
        $blog = Blog::where('slug', $slug)->first();
        if ($slug == null) {
            abort(404);
        }
        $data['blog'] = $blog;

        $relatedBlogs = null;
        $data['relatedBlogs'] = $relatedBlogs;
        $recentBlogs = Blog::latest()->inRandomOrder()->take(6)->get();
        $data['recentBlogs'] = $recentBlogs;
        return view('front.blog', $data);
    }

    public function page($slug)
    {
        $page = Page::where('slug', $slug)->first();
        if ($page == null) {
            abort(404);
        }
        $data['page'] = $page;
        return view('front.page', $data);
    }
    public function addToWishlist(Request $request)
    {
        if (Auth::check() == false) {
            session(['urlintended' => url()->previous()]);
            session()->flash('message', 'You need to login to your account first.');
            return response()->json(
                [
                    'status' => false,
                    'redirect_url' => route('account.login')
                ]
            );
        }

        $wishlistFind = wishlist::where('user_id', Auth::user()->id)
            ->where('product_id', $request->id)
            ->first();

        if ($wishlistFind == null) {
            $wishlist = new Wishlist();
            $wishlist->user_id = Auth::user()->id;
            $wishlist->product_id = $request->id;
            $wishlist->save();
            return response()->json(
                [
                    'status' => true,
                    'message' => ' &#128516; Added to your wishlist.',
                    'totalItemsInWishlist' => wishlistCount(),
                ]
            );
        } else {
            $wishlistFind->delete();
            return response()->json(
                [
                    'status' => 'removed',
                    // 'message' => 'Item removed from your wishlist.'
                ]
            );
        }
    }
    public function removeFromWishlist(Request $request)
    {

        $id = $request->id;

        $itemInfo = Wishlist::where('user_id', Auth::user()->id)->where('product_id', $id)->first();

        if ($itemInfo == null) {
            return response()->json([
                'status' => false,
                'message' => 'Item not found in wishlist.'
            ]);
        } else {

            $itemInfo->delete();

            return response()->json([
                'status' => true,
                'message' => 'Item removed successfully.',
                'totalItemsInWishlist' => wishlistCount()
            ]);
        }
    }

    public function sendContactEmail(Request $request)
    {
        if ($request->isMethod('post')) {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'email' => 'required',
                'subject' => 'required',
                'message' => 'required',
            ]);
            if ($validator->passes()) {

                $mailData = [
                    'name' => $request->name,
                    'email' => $request->email,
                    'subject' => $request->subject,
                    'message' => $request->message,
                    'mail_subject' => 'A contact query from ' . $request->name
                ];

                $admin = User::where('id', 1)->first();
                Mail::to($admin->email)->send(new contactEmail($mailData));

                return  response()->json(
                    [
                        'status' => true,
                        'message' => "Message sent successfully, thanks for contacting."
                    ]
                );
            } else {
                return  response()->json(
                    [
                        'status' => false,
                        'errors' => $validator->errors()
                    ]
                );
            }
        }
    }
    public function newsletter(Request $request)
    {

        $validator = Validator::make($request->all(), [

            'email' => 'required|email',

        ]);
        if ($validator->passes()) {


            // 602d7e47e9
            $mailchimp = new MailChimpClass();
            // $newsletterExists = newsletter::where('email', $request->email)->first();
            // if ($newsletterExists != null) {
            //     $mailchimp->add_or_update_list_member('602d7e47e9', $newsletterExists->email);
            //     return response()->json(
            //         [
            //             'status' => true,
            //             'message' => 'You are already our subscriber.'
            //         ]
            //     );
            // }
            // $newsletter = new Newsletter();
            // $newsletter->email = $request->email;
            // $newsletter->name = $request->name;
            // $newsletter->save();

            $mailchimp->add_or_update_list_member($request->email);

            return response()->json(
                [
                    'status' => true,
                    'message' => 'Thankyou for subscribing us.'
                ]
            );
        } else {
            return  response()->json(
                [
                    'status' => false,
                    'errors' => $validator->errors()
                ]
            );
        }
    }
}
