   <a id="nav-item_dashboard" href="{{ route('admin.home') }}" class="nav-item nav-link"><i class="bi bi-speedometer2"></i>
       Dashboard</a>

   @can('manage_grouping')
       <div class="nav-item dropdown">
           <a id="nav-item_stone_stock" href="#" class="dropdown-toggle nav-link" data-bs-toggle="dropdown"><i
                   class="bi bi-inboxes"></i> Grouping</a>

           <div class="dropdown-menu bg-transparent border-0 py-0 text-center">
               <a href="{{ route('categories.view') }}" class="dropdown-item">Category</a>
               <a href="{{ route('subCategories.view') }}" class="dropdown-item">Sub Category</a>
               <a href="{{ route('leafCategories.view') }}" class="dropdown-item">Leaf Category</a>
               <a href="{{ route('brands.view') }}" class="dropdown-item">Brands</a>
           </div>
       </div>
   @endcan

   @can('manage_inventory')
       <div class="nav-item dropdown">
           <a id="nav-item_inventory" href="#" class="dropdown-toggle nav-link" data-bs-toggle="dropdown"><i
                   class="bi bi-inboxes"></i> Inventory</a>
           <div class="dropdown-menu bg-transparent border-0 py-0 text-center">
               <a href="{{ route('products.index') }}" class="dropdown-item">Products</a>
               <a href="{{ route('products.reviews') }}" class="dropdown-item">Product Reviews</a>
           </div>
       </div>
   @endcan

   @can('manage_orders')
       <div class="nav-item">
           <a id="nav-iteml_orders" href="{{ route('orders.view') }}" class="nav-link">
               <i class="nav-icon fas fa-shopping-bag"></i>
               Orders
           </a>
       </div>
   @endcan

   @can('manage_users')
       <div class="nav-item dropdown">
           <a id="nav-item_users" href="{{ route('users.view') }}" class="nav-link"><i class="bi bi-people"></i> Users</a>

       </div>
   @endcan
   @can('manage_carousel')
       <a id="nav-item_carousel" href="{{ route('carousel.index') }}" class="nav-item nav-link"><i
               class="bi bi-filetype-html"></i> Carousel Items</a>
   @endcan

   @can('manage_static_pages')
       <a id="nav-item_static_pages" href="{{ route('pages.view') }}" class="nav-item nav-link"><i
               class="bi bi-filetype-html"></i> Static Pages</a>
   @endcan

      @can('manage_blogs')
       <a id="nav-item_blogs" href="{{ route('blogs.index') }}" class="nav-item nav-link"><i
               class="bi bi-filetype-html"></i> Blogs</a>
   @endcan

   @can('manage_discount_codes')
       <a id="nav-item_discount_code" href="{{ route('discount.view') }}" class="nav-item nav-link"><i
               class="bi bi-percent"></i> Discount Codes</a>
   @endcan
