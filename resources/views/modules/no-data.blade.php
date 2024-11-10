@extends('layouts.app')

@section('content')
    <div class="alert alert-warning">
        <h3>Data Not Available</h3>
        <p>No history data is available for this module.</p>
        <a href="{{ route('modules.history', ['id' => $module->id]) }}" class="btn btn-primary">Back to History</a>
    </div>
@endsection
