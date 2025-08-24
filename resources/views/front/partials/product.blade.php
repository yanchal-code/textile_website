
 <div class="col-xl-3 col-lg-4 col-md-6 wow fadeInUp">
     <div class="product-item">
         <div class="position-relative bg-light overflow-hidden">
           <a href="{{ route('front.product', $product->slug) }}"><img class="img-fluid w-100" src="{{ asset($product->defaultImage->image ?? $product->images->first()->image) }}" alt="{{$product->alt_image_text}}"></a>
             @if ($product->created_at->gt(now()->subDays(7)))
                 <div class="bg-secondary rounded text-white position-absolute start-0 top-0 m-4 py-1 px-3">New</div>
             @endif
         </div>
         <div class="text-center p-4">
             <a class="d-block h5 mb-2" href="{{ route('front.product', $product->slug) }}">{{ $product->name }}</a>
             <span class="text-primary me-1">{{config('settings.currency_symbol')}} {{ $product->price }}</span>
             <span class="text-body text-decoration-line-through">{{config('settings.currency_symbol')}} {{$product->compare_price}}</span>
         </div>
         <div class="d-flex border-top">
             <small class="w-50 text-center border-end py-2">
                 <button class="btn text-body" onclick="return loadQuickView('{{$product->sku}}')"><i class="fa fa-eye text-primary me-2"></i>View</button>
             </small>
             <small class="w-50 text-center py-2">
                 <button data-bs-sku="{{$product->sku}}" value="{{$product->sku}}" class="btn text-body quick-add"><i class="fa fa-shopping-bag text-primary me-2"></i>Add</button>
             </small>
         </div>
     </div>
 </div>
