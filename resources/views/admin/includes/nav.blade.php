@can('manage_seo')
    <div class="nav-item d-none d-lg-block">

       <button onclick="window.location.href='{{ route('index.tag.codes') }}'" class="btn nav-link">
            <i class="bi bi-building-fill-gear"></i> <span class="d-none d-lg-inline">Tags & SEO</span>
        </button>

    </div>
@endcan

@can('manage_config')
    <div class="nav-item">

        <button onclick="loadModalContent('{{ route('env.index') }}', '')" class="btn nav-link">
            <i class="bi bi-building-fill-gear"></i> <span class="d-none d-lg-inline">Configs</span>
        </button>

    </div>
@endcan

@can('manage_setting')
    <div class="nav-item">
        <a href="{{ route('application.settings') }}" class="nav-link">
            <i class="bi bi-gear-fill"></i>
            <span class="d-none d-lg-inline">Settings</span>
        </a>
    </div>
@endcan

<div class="nav-item dropdown">
    <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
       {{-- <i class="bi bi-person-fill"></i> --}}
        <img class="rounded-circle profile_icon me-lg-2" src="https://cdn-icons-png.flaticon.com/512/10337/10337609.png" alt="">
        <span class="d-none d-lg-inline-flex">{{ Auth::user()->name }}</span>
    </a>
    <div class="dropdown-menu dropdown-menu-end shadow text-white text-center border-0 rounded-0 rounded-bottom m-0">
        <a href="{{ route('admin.changePassword') }}" class="dropdown-item">My Profile</a>

        <span class="dropdown-item">
            <a href="{{ route('admin.logout') }}" class="btn-sm m-auto btn btn-danger">Log
                Out</a>
        </span>
    </div>
</div>
