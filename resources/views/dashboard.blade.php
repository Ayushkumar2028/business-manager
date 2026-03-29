@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <h4 class="mb-0">Dashboard</h4>

    <form action="{{ route('businesses.clearAll') }}" method="POST" onsubmit="return confirm('Are you sure you want to delete all records? This action cannot be undone.')">
        @csrf
        <button type="submit" class="btn btn-danger">Clear All Records</button>
    </form>
</div>

<div class="row">
    <div class="col-md-4 mb-3">
        <div class="card p-3">
            <h5>Total Records</h5>
            <h3>{{ $total }}</h3>
        </div>
    </div>

    <div class="col-md-4 mb-3">
        <div class="card p-3">
            <h5>Duplicate Records</h5>
            <h3>{{ $duplicates }}</h3>
        </div>
    </div>

    <div class="col-md-4 mb-3">
        <div class="card p-3">
            <h5>Incomplete Records</h5>
            <h3>{{ $incomplete }}</h3>
        </div>
    </div>
</div>
@endsection