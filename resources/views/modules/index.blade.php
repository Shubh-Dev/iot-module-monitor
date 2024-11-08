@extends('layouts.app')

@section('content')
<div class="container my-4">
    <!-- Main Heading -->
    <h1 class="display-5 mb-4 text-center">Module Status</h1>

    <!-- Module Status Table -->
    <table class="table table-hover table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th scope="col">Name</th>
                <th scope="col">Type</th>
                <th scope="col">Measured Value</th>
                <th scope="col">Status</th>
                <th scope="col">Operating Time</th>
                <th scope="col">Data Sent Count</th>
            </tr>
        </thead>
        <tbody>
            @foreach($modules as $module)
                <tr class="{{ $module->status == 'Active' ? 'table-success' : 'table-danger' }}">
                    <td>{{ $module->name }}</td>
                    <td>{{ $module->type }}</td>
                    <td>{{ $module->measured_value }}</td>
                    <td>{{ $module->status }}</td>
                    <td>{{ $module->operating_time }}</td>
                    <td>{{ $module->data_sent_count }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Module History Section -->
    <h2 class="display-6 mt-5 mb-4 text-center">Module History</h2>
    <table class="table table-hover table-bordered table-striped">
        <thead class="table-secondary">
            <tr>
                <th scope="col">Module Name</th>
                <th scope="col">Measured Value</th>
                <th scope="col">Timestamp</th>
            </tr>
        </thead>
        <tbody>
            @foreach($history as $entry)
                <tr>
                    <td>{{ $entry->module->name }}</td>
                    <td>{{ $entry->measured_value }}</td>
                    <td>{{ $entry->created_at }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Script for Testing Data Output -->
<script>
    // Passing PHP data to JavaScript for testing purposes
    var modules = @json($modules);
    console.log(modules);
</script>
@endsection
