@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Duplicate Records</h4>

    @if($duplicateGroups->count() > 0)
        <form action="{{ route('businesses.mergeAll') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-danger">Merge All</button>
        </form>
    @endif
</div>

@forelse($duplicateGroups as $groupId => $group)
    <div class="card mb-4">
        <div class="card-header">
            Duplicate Group #{{ $groupId }}
        </div>

        <div class="card-body">
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
                    </tr>
                </thead>
                <tbody>
                    @foreach($group as $item)
                        <tr>
                            <td>{{ $item->id }}</td>
                            <td>{{ $item->business_name }}</td>
                            <td>{{ $item->area }}</td>
                            <td>{{ $item->city }}</td>
                            <td>{{ $item->mobile_no }}</td>
                            <td>{{ $item->category }}</td>
                            <td>{{ $item->sub_category }}</td>
                            <td>{{ $item->address }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <form action="{{ route('businesses.merge', $groupId) }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-warning">Merge This Group</button>
            </form>
        </div>
    </div>
@empty
    <div class="alert alert-info">No duplicate records found.</div>
@endforelse
@endsection