<!DOCTYPE html>
<html>
<head>
    <title>Business Manager</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h2 class="mb-4">Business Management App</h2>

    <nav class="mb-4 d-flex gap-2 flex-wrap">
        <a href="{{ route('dashboard') }}" class="btn btn-primary btn-sm">Dashboard</a>
        <a href="{{ route('businesses.index') }}" class="btn btn-secondary btn-sm">Businesses</a>
        <a href="{{ route('businesses.import.form') }}" class="btn btn-success btn-sm">Import</a>
        <a href="{{ route('businesses.duplicates') }}" class="btn btn-warning btn-sm">Duplicates</a>
        <a href="{{ route('businesses.reports') }}" class="btn btn-info btn-sm">Reports</a>
    </nav>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @yield('content')
</div>
</body>
</html>