@extends('Components.admin')

@section('title', 'Manage Memberships | GameSlot')

@section('content')

<div class="card text-dark">
    <div class="card-body">

        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="card-title mb-0">Manage Memberships</h3>
            <a href="{{ route('admin.membership.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg"></i> Add Membership
            </a>
        </div>

        {{-- Success Messages --}}
        @if ($message = Session::get('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ $message }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if ($message = Session::get('success2'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ $message }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- No Memberships Alert --}}
        @if (count($plans) < 1)
            <div class="alert alert-info mt-3">
                Sorry, no membership plans are available at the moment.
            </div>
        @else
            {{-- Memberships Table --}}
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th scope="col" style="width: 30%;">Membership Name</th>
                            <th scope="col">Price ($)</th>
                            <th scope="col">Description</th>
                            <th scope="col" class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($plans as $plan)
                            <tr>
                                <td>
                                    <h5>{{ $plan->name }}</h5>
                                </td>
                                <td>${{ number_format($plan->price, 2) }}</td>
                                <td>{{ $plan->description ?? '-' }}</td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.membership.edit', ['id' => $plan->id]) }}" class="btn btn-warning">
                                            <i class="bi bi-pencil"></i> Edit
                                        </a>
                                        <form action="{{ route('admin.membership.delete', ['id' => $plan->id]) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this membership?');">
                                            @method('delete')
                                            @csrf
                                            <button type="submit" class="btn btn-outline-danger">
                                                <i class="bi bi-trash3"></i> Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        {{-- Pagination --}}
        @if($plans->hasPages())
            <div class="mt-3">
                {{ $plans->links() }}
            </div>
        @endif

    </div>
</div>

@endsection
