@extends('layouts.app')

@section('content')
<h4>Import Excel File</h4>

<form action="{{ route('businesses.import') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="mb-3">
        <label class="form-label">Choose Excel File</label>
        <input type="file" name="file" class="form-control" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Import Mode</label>
        <select name="import_mode" class="form-select" required>
            <option value="latest">Work on Only Latest File</option>
            <option value="combined">Work on All Files (Combined)</option>
        </select>
    </div>

    <button type="submit" class="btn btn-success">Upload</button>
</form>
@endsection