<?php

use App\Models\Product;
use Illuminate\Support\Facades\Route;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

use App\Http\Controllers\Admin\AdminLoginController;
use App\Http\Controllers\Admin\AdminPermissionController;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\CarouselController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DiscountCodeController;
use App\Http\Controllers\Admin\EnvController;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\ImportController;
use App\Http\Controllers\Admin\LeafCategoryController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\SubCategoryController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\VariationController;
use App\Http\Controllers\Admin\GeminiController;
use App\Http\Controllers\Admin\TagController;
use App\Http\Controllers\frontend_new\HomeNewController;


use App\Http\Controllers\Front\FrontHomeController;
use App\Http\Controllers\Front\AuthController;
use App\Http\Controllers\Front\CartController;
use App\Http\Controllers\Front\ShopController;


use App\Http\Controllers\giftController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\DatabaseController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/sitemap.xml', function () {
    $sitemap = Sitemap::create();

    // Add static pages
    $sitemap->add(Url::create('/')
        ->setPriority(1.0)
        ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
        ->setLastModificationDate(now()));

    $sitemap->add(Url::create('/cart')
        ->setPriority(0.8)
        ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
        ->setLastModificationDate(now()));

    $sitemap->add(Url::create('/checkout')
        ->setPriority(0.8)
        ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
        ->setLastModificationDate(now()));

    // Add dynamic categories
    $categories = \App\Models\Category::all(); // Ensure you have a Category model
    foreach ($categories as $category) {
        $sitemap->add(Url::create("/shop/{$category->slug}")
            ->setPriority(0.9)
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
            ->setLastModificationDate($category->updated_at));
        foreach ($category->subCategories as $subCategory) {
            $sitemap->add(
                Url::create("/shop/{$category->slug}/{$subCategory->slug}")
                    ->setPriority(0.8)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                    ->setLastModificationDate($subCategory->updated_at)
            );

            // Add Leaf-Categories under this Sub-Category
            foreach ($subCategory->leafCategories as $leafCategory) {
                $sitemap->add(
                    Url::create("/shop/{$category->slug}/{$subCategory->slug}/{$leafCategory->slug}")
                        ->setPriority(0.7)
                        ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                        ->setLastModificationDate($leafCategory->updated_at)
                );
            }
        }
    }

    $products = Product::with('variations')->get();

    foreach ($products as $product) {
        if ($product->has_variations && $product->variations->count()) {
            // Products that have variations
            foreach ($product->variations as $variation) {
                $sitemap->add(
                    Url::create("/product/{$product->slug}/{$variation->sku}")
                        ->setPriority(0.7)
                        ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
                        ->setLastModificationDate($variation->updated_at ?? $product->updated_at)
                );
            }
        } else {
            // Products that don't have variations (single product)
            $sitemap->add(
                Url::create("/product/{$product->slug}")
                    ->setPriority(0.7)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
                    ->setLastModificationDate($product->updated_at)
            );
        }
    }

    // Add dynamic pages
    $pages = \App\Models\Page::all(); // Ensure you have a Page model
    foreach ($pages as $page) {
        $sitemap->add(Url::create("/page/{$page->slug}")
            ->setPriority(0.6)
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
            ->setLastModificationDate($page->updated_at));
    }

    return $sitemap->toResponse(request());
});


Route::group(['prefix' => 'admin'], function () {

    Route::group(['middleware' => 'admin.guest'], function () {
        Route::match(['get', 'post'], '/', [AdminLoginController::class, 'index'])->name('admin.login');
        Route::match(['get', 'post'], '/forgetPassword', [AdminLoginController::class, 'forgotPassword'])->name('admin.forgotPassword');
        Route::match(['get', 'post'], '/reset/{token}', [AdminLoginController::class, 'reset'])->name('admin.reset');
    });

    Route::group(['middleware' => 'admin.auth'], function () {

        Route::match(['get', 'post'], '/changepassword', [AdminLoginController::class, 'changePassword'])->name('admin.changePassword');
        Route::get('logout', [AdminLoginController::class, 'logout'])->name('admin.logout');

        Route::controller(DatabaseController::class)->group(function () {
            Route::post('/truncate-tables', 'truncateTables')->name('truncate.tables');
        });


        Route::controller(HomeController::class)->group(function () {
            Route::get('/dashboard', 'home')->name('admin.home');
            Route::middleware('check.permission:manage_settings')->group(function () {
                Route::match(['get', 'post'], 'settings', 'settings')->name('application.settings');
                Route::post('/settings/group', 'fetchGroup')->name('settings.group');
            });

            Route::post('slug', 'slug')->name('categories.slug');
        });

        Route::controller(TagController::class)->middleware('check.permission:manage_seo')->group(function () {
            Route::get('edit-codes', 'index')->name('index.tag.codes');
            Route::get('edit-tag-codes/{type}', 'edit')->name('edit.tag.codes');
            Route::post('update-tag-codes', 'update')->name('update.tag.codes');
        });


        Route::controller(EnvController::class)->middleware('check.permission:manage_config')->group(function () {
            Route::get('/env', 'index')->name('env.index');
            Route::post('/env', 'update')->name('env.update');
        });

        Route::controller(CategoryController::class)->middleware('check.permission:manage_grouping')->group(function () {
            Route::get('categories', 'index')->name('categories.view');
            Route::match(['get', 'post'], 'category/create', 'create')->name('categories.create');
            Route::match(['get', 'post'], 'category/{category}/edit', 'edit')->name('categories.edit');
            Route::post('category/delete', 'destroy')->name('categories.delete');
            Route::post('slug', 'slug')->name('categories.slug');
        });

        Route::controller(SubCategoryController::class)->middleware('check.permission:manage_grouping')->group(function () {
            Route::get('sub-category', 'index')->name('subCategories.view');
            Route::match(['get', 'post'], 'sub-category/create', 'create')->name('subCategories.create');
            Route::match(['get', 'post'], 'sub-category/{subcategory}/edit', 'edit')->name('subCategories.edit');
            Route::post('sub-category/delete', 'destroy')->name('subCategories.delete');
        });

        Route::controller(LeafCategoryController::class)->middleware('check.permission:manage_grouping')->group(function () {
            Route::get('leaf-category', 'index')->name('leafCategories.view');
            Route::match(['get', 'post'], 'leaf-category/create', 'create')->name('leafCategories.create');
            Route::match(['get', 'post'], 'leaf-category/{leafcategory}/edit', 'edit')->name('leafCategories.edit');
            Route::post('leaf-category/delete', 'destroy')->name('leafCategories.delete');
            Route::post('leaf-category/specs', 'specs')->name('leafCategories.specs');
        });

        Route::controller(BrandController::class)->middleware('check.permission:manage_grouping')->group(function () {
            Route::get('brands', 'index')->name('brands.view');
            Route::match(['get', 'post'], 'brand/create', 'create')->name('brands.create');
            Route::match(['get', 'post'], 'brand/{brand}/edit', 'edit')->name('brands.edit');
            Route::post('brand/delete', 'destroy')->name('brands.delete');
        });


        Route::controller(ProductController::class)->middleware('check.permission:manage_inventory')->group(function () {

            Route::get('/products', 'index')->name('products.index');
            Route::get('/products/create',  'create')->name('products.create');
            Route::post('/generate-description', 'generateDescription')->name('generate.description');
            Route::post('/products/store', 'store')->name('products.store');

            Route::get('/search-product', 'searchBySKU')->name('product.search.sku');

            Route::get('/products/edit/{id}',  'edit')->name('products.edit');
            Route::post('/products/edit/{id}/store', 'store_edit')->name('products.edit.store');

            Route::post('tempImages/create/{productId?}/{variationId?}', 'create_temp_img')->name('tempImages.create');
            Route::post('tempImages/delete', 'tempImagesDelete')->name('tempImages.delete');

            Route::get('/get-sub-categories/{category_id}', 'getSubCategories')->name('get-sub-category');
            Route::get('/get-leaf-categories/{sub_category_id}', 'getLeafCategories')->name('get-leaf-category');
            Route::post('product/delete', 'destroy')->name('products.delete');

            Route::match(['get', 'post'], 'product/reviews', 'reviews')->name('products.reviews');
            Route::post('product/reviews/delete', 'reviewDestroy')->name('reviews.delete');
        });

        Route::controller(BlogController::class)->group(function () {
            Route::get('blogs', 'index')->name('blogs.index');
            Route::get('blogs/create', 'create')->name('blogs.create');
            Route::post('blogs/store', 'store')->name('blogs.store');
            Route::get('blogs/{blog}/edit', 'edit')->name('blogs.edit');
            Route::post('blogs/{blog}/edit/store', 'update')->name('blogs.update');
            Route::post('blogs/destroy', 'destroy')->name('blogs.destroy');
        });

        Route::controller(ImportController::class)->middleware('check.permission:manage_inventory')->group(function () {
            Route::post('import-products', 'importProducts')->name('products.import');
            Route::post('export-example', 'exportExample')->name('product.example.export');
        });

        Route::controller(VariationController::class)->middleware('check.permission:manage_inventory')->group(function () {
            Route::get('/product/{id}/variations', 'index')->name('products.variations');
            Route::get('/products/{id}/variation/create',  'create')->name('variations.create');
            Route::post('/products/{id}/variation/store', 'store')->name('variations.store');

            Route::get('/products/{id}/variation/edit/',  'edit')->name('variations.edit');

            Route::post('/products/{id}/variation/edit/store', 'store_edit')->name('variations.edit.store');

            Route::post('product/variations/delete', 'deleteVariation')->name('products.variations.delete');
        });

        Route::controller(UserController::class)->middleware('check.permission:manage_users')->group(function () {
            Route::get('users/type/{type?}', 'index')->name('users.view');
            Route::post('users/create', 'create')->name('users.create');
            Route::match(['get', 'post'], 'category/edit', 'edit')->name('users.edit');
            Route::post('user/delete', 'destroy')->name('users.delete');
        });

        Route::resource('carousel', CarouselController::class)->middleware('check.permission:manage_carousel');

        Route::controller(PageController::class)->middleware('check.permission:manage_static_pages')->group(function () {
            Route::get('pages', 'index')->name('pages.view');
            Route::match(['get', 'post'], 'page/create', 'create')->name('pages.create');
            Route::match(['get', 'post'], 'page/{page}/edit', 'edit')->name('pages.edit');
            Route::post('pages/delete', 'destroy')->name('pages.delete');
        });

        Route::controller(DiscountCodeController::class)->middleware('check.permission:manage_discount_codes')->group(function () {
            Route::get('discount', 'index')->name('discount.view');
            Route::match(['get', 'post'], 'discount/create', 'create')->name('discount.create');
            Route::match(['get', 'post'], 'discount/{discount}/edit', 'edit')->name('discount.edit');
            Route::post('discount/delete', 'destroy')->name('discount.delete');
        });

        Route::controller(OrderController::class)->middleware('check.permission:manage_orders')->group(function () {
            Route::get('orders', 'index')->name('orders.view');
            Route::get('order/{orderId}', 'detail')->name('order.detail');
            Route::post('order/update/{Id}', 'updateStatus')->name('order.update');
            Route::post('order/sendInvoice/{Id}', 'sendInvoiceEmail')->name('order.sendInvoice');
        });

        Route::get('/admin/users/{user}/permissions', [AdminPermissionController::class, 'edit'])->name('admin.permissions.edit');
        Route::put('/admin/users/{user}/permissions', [AdminPermissionController::class, 'update'])->name('admin.permissions.update');

        Route::controller(GeminiController::class)->group(function () {
            Route::post('generate-gemini', 'generate')->name('generate.gemini');
        });
    });
});

// Route::controller(FrontHomeController::class)->group(function () {
//     Route::get('/',  'home')->name('front.home');
//     Route::get('/blogs',  'blogs')->name('front.blogs');
//     Route::get('/blogs/blog/{slug?}',  'blog')->name('front.blog.show');

//     Route::get('page/{slug}', 'page')->name('front.page');

//     Route::post('add-to-wishList', 'addToWishlist')->name('front.addToWishlist');
//     Route::post('delete/wishList/item', 'removeFromWishlist')->name('wishlist.deleteItem');

//     Route::post('newsletter', 'newsletter')->name('front.newsletter');
//     Route::post('sendContactEmail', 'sendContactEmail')->name('front.sendContactEmail');

//     Route::post('fetch-testimonials',  'fetchTestimonials')->name('front.testimonials');
// });

// Route::controller(ShopController::class)->group(function () {
//     Route::get('/shop/{categorySlug?}/{subCategorySlug?}/{leafCategorySlug?}',  'index')->name('front.shop');

//     Route::post('shop/product/quick/view', 'productQuickView')->name('product.quickView');

//     Route::get('product/{sku}', 'product')->name('front.product');

//     Route::post('product/saveReview/{productId}', 'saveReview')->name('front.saveReview');
//     Route::post('product/saveReview/order/item', 'saveReviewOrder')->name('front.saveReviewOrder');

//     Route::post('product/partial', 'partial')->name('front.product.partial');
// });

// Route::controller(CartController::class)->group(function () {
//     Route::get('cart', 'cart')->name('front.cart');
//     Route::get('checkout', 'checkout')->name('front.checkout');
//     // Route::get('thanks', 'thanks')->name('front.thanks');
//     Route::get('cart/content', 'miniCartContent')->name('cart.mini');
//     Route::post('add-to-cart', 'addToCart')->name('front.addTocart');
//     Route::post('update-cart', 'updateCart')->name('cart.update');
//     Route::post('reArrangeCart', 'reArrangeCart')->name('front.reArrangeCart');
//     Route::post('delete-item', 'deleteItem')->name('cart.deleteItem');
//     Route::post('apply-coupon', 'applyCoupon')->name('front.applyCoupon');
//     // Route::post('getShipping', 'shipping')->name('front.getShipping');
// });


// Route::group(['prefix' => 'account'], function () {
//     Route::group(['middleware' => 'guest'], function () {
//         Route::controller(AuthController::class)->group(function () {
//             Route::match(['get', 'post'], 'register', 'register')->name('account.register');
//             Route::match(['get', 'post'], 'login', 'login')->name('account.login');
//             Route::match(['get', 'post'], 'account/login', 'login')->name('login');

//             Route::match(['get', 'post'], 'forgot', 'forgot')->name('account.forgot');
//         });
//     });

//     Route::group(['middleware' => 'auth'], function () {
//         Route::controller(AuthController::class)->group(function () {
//             Route::get('profile', 'profile')->name('account.profile');
//             Route::post('updateAddress', 'updateAddress')->name('account.updateAddress');
//             Route::post('updateUserInfo', 'updateUserInfo')->name('account.updateUserInfo');
//             Route::get('logout', 'logout')->name('account.logout');
//             Route::get('my-orders', 'orders')->name('account.orders');
//             Route::get('order-detail/{orderId}', 'orderDetail')->name('account.orderDetail');
//             Route::get('my-wishlist', 'wishlist')->name('account.wishlist');

//             Route::get('updateRegion/{country?}', 'updateRegion')->name('user.updateRegion');
//         });


//         Route::controller(giftController::class)->group(function () {

//             Route::post('getGift', 'gift')->name('account.gift_info');
//             Route::get('user/gift', 'show')->name('user.gift');
//             Route::get('/gift-card/show', 'showGiftCard')->name('gift.show');
//         });
//     });
// });


// // CheckOut Routes

// Route::group(['middleware' => 'auth'], function () {

//     Route::controller(CheckoutController::class)->group(function () {
//         Route::post('/ProcessCheckout', 'processCheckout')->name('checkout.process');
//         Route::get('/paypal-success', 'paypalSuccess')->name('paypal.success');
//         Route::get('/paypal-cancel', 'paypalCancel')->name('paypal.cancel');
//     });
// });

Route::any('phonepe-response', [CheckoutController::class, 'phonePeCallback'])->name('phonepe.payment.callback');
Route::controller(HomeNewController::class)->group(function () {
    Route::get('/', 'index')->name('welcome');
    Route::get('/login',  'login')->name('login');
    Route::get('/my-account',  'myAccount')->name('myAccount');
    Route::get('/my-account-2',  'myAccount2')->name('myAccount2');
    Route::get('/portfolio',  'portfolio')->name('portfolio');
    Route::get('/print-design',  'printDesign')->name('printDesign');
    Route::get('/privacy-policy',  'privacyPolicy')->name('privacyPolicy');
    Route::get('/product-details',  'productDetails')->name('productDetails');
    Route::get('/product-gallery',  'productGallery')->name('productGallery');
    Route::get('/product-left-sidebar', 'productLeftSidebar')->name('productLeftSidebar');
    Route::get('/product-video',  'productVideo')->name('productVideo');
    Route::get('/shop-grid-list',  'shopGridList')->name('shopGridList');
    Route::get('/shop-grid',  'shopGrid')->name('shopGrid');
    Route::get('/shop-list',  'shopList')->name('shopList');
    Route::get('/shop-right-sidebar',  'shopRightSidebar')->name('shopRightSidebar');
    Route::get('/shop',  'shop')->name('shop');
    Route::get('/wishlist',  'wishlist')->name('wishlist');
    Route::get('/compare',  'compare')->name('compare');
    Route::get('/contact',  'contact')->name('contact');
    Route::post('/sendContactEmail', 'sendContactEmail')->name('contact.sendContactEmail');

    Route::get('/faq',  'faq')->name('faq');
    Route::get('/404', 'errorPage')->name('errorPage');
    Route::get('/about', 'about')->name('about');
    Route::get('/blog-details/{id}', 'blogDetails')->name('blogDetails');
    Route::get('/blog-left-sidebar', 'blogLeftSidebar')->name('blogLeftSidebar');
    Route::get('/blog-right-sidebar', 'blogRightSidebar')->name('blogRightSidebar');
    Route::get('/blog', 'blog')->name('blog');
    Route::get('/checkout', 'checkout')->name('checkout');
    Route::get('/checkout-2', 'checkout2')->name('checkout2');
    Route::get('/checkout-3', 'checkout3')->name('checkout3');
    Route::get('/checkout-4', 'checkout4')->name('checkout4');
    Route::get('/cart', 'cart')->name('cart');
    Route::get('/view/design/{id}', 'viewDesign')->name('view_design');
});
