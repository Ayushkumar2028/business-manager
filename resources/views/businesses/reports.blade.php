@extends('layouts.app')

@section('content')
<h4>Reports</h4>

<div class="mb-4">
    <p><strong>Total Names:</strong> {{ $totalNames }}</p>
    <p><strong>Unique Listings:</strong> {{ $uniqueListing }}</p>
    <p><strong>Duplicate Listings:</strong> {{ $duplicateListing }}</p>
    <p><strong>Incomplete Listings:</strong> {{ $incompleteListing }}</p>
</div>

<div class="mb-4 d-flex gap-2 flex-wrap">
    <a href="{{ route('reports.download.summary') }}" class="btn btn-dark btn-sm">Download Summary</a>
    <a href="{{ route('reports.download.citywise') }}" class="btn btn-primary btn-sm">Download City Wise</a>
    <a href="{{ route('reports.download.categorycitywise') }}" class="btn btn-success btn-sm">Download Category + City Wise</a>
    <a href="{{ route('reports.download.categoryareawise') }}" class="btn btn-info btn-sm">Download Category + Area Wise</a>
    <a href="{{ route('reports.download.duplicates') }}" class="btn btn-warning btn-sm">Download Duplicate Listing</a>
    <a href="{{ route('reports.download.incomplete') }}" class="btn btn-danger btn-sm">Download Incomplete Listing</a>
</div>

<h5>City Wise Data</h5>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>City</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
        @forelse($cityWise as $item)
            <tr>
                <td>{{ $item->city ?: 'N/A' }}</td>
                <td>{{ $item->total }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="2" class="text-center">No data found.</td>
            </tr>
        @endforelse
    </tbody>
</table>

<h5>Category + City Wise Data</h5>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>Category</th>
            <th>City</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
        @forelse($categoryCityWise as $item)
            <tr>
                <td>{{ $item->category ?: 'N/A' }}</td>
                <td>{{ $item->city ?: 'N/A' }}</td>
                <td>{{ $item->total }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="3" class="text-center">No data found.</td>
            </tr>
        @endforelse
    </tbody>
</table>

<h5>Category + Area Wise Data</h5>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>Category</th>
            <th>Area</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
        @forelse($categoryAreaWise as $item)
            <tr>
                <td>{{ $item->category ?: 'N/A' }}</td>
                <td>{{ $item->area ?: 'N/A' }}</td>
                <td>{{ $item->total }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="3" class="text-center">No data found.</td>
            </tr>
        @endforelse
    </tbody>
</table>
@endsection