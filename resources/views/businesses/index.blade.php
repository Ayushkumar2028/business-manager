@extends('layouts.app')

@section('content')
<h4>Business List</h4>

<div class="table-responsive">
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Business Name</th>
                <th>Area</th>
                <th>City</th>
                <th>Mobile</th>
                <th>Category</th>
                <th>Sub Category</th>
                <th>Address</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($businesses as $business)
                <tr>
                    <td>{{ $business->id }}</td>
                    <td>{{ $business->business_name }}</td>
                    <td>{{ $business->area }}</td>
                    <td>{{ $business->city }}</td>
                    <td>{{ $business->mobile_no }}</td>
                    <td>{{ $business->category }}</td>
                    <td>{{ $business->sub_category }}</td>
                    <td>{{ $business->address }}</td>
                    <td>
                        @if($business->is_duplicate)
                            <span class="badge bg-danger">Duplicate</span>
                        @else
                            <span class="badge bg-success">Unique</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="text-center">No records found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="d-flex justify-content-center mt-4">
    {{ $businesses->links() }}
</div>
@endsection