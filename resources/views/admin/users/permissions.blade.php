@extends('admin.includes.layout')

@section('content')
    <div class="container">
        <h3 class="text-center mb-4">Admin Permissions</h3>

        <form action="{{ route('admin.permissions.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row">
                @foreach ($groupedPermissions as $group => $permissions)
                    <div class="col-lg-3 co-md-4 col-6">
                        <div class="card mb-3">
                            <div class="card-header">{{ $group }} Permissions</div>
                            <div class="card-body">
                                @foreach ($permissions as $perm)
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" name="permissions[]"
                                            value="{{ $perm->id }}" id="perm_{{ $perm->id }}"
                                            {{ $user->permissions->contains($perm->id) ? 'checked' : '' }}>
                                        <label class="form-check-label"
                                            for="perm_{{ $perm->id }}">{{ ucwords(str_replace('_', ' ', $perm->name)) }}</label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <button type="submit" class="btn btn-primary">Update Permissions</button>
        </form>
    </div>
@endsection
