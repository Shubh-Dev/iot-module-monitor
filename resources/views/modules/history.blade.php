<!-- resources/views/modules/history.blade.php -->
@extends('layouts.app')

@section('content')
    <div class="container my-4">
        <h1 class="display-5 mb-4 text-center">History for Module: {{ $module->name }}</h1>

        <!-- Module History Table -->
        <table id="moduleHistoryTable" class="table table-hover table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th scope="col">Module ID</th>
                    <th scope="col">Module Name</th>
                    <th scope="col">Measured Value</th>
                    <th scope="col">Timestamp</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($history as $entry)
                    <tr>
                        <td>{{ $entry->module_id }}</td>
                        <td>{{ $entry->module->name }}</td>
                        <td>{{ $entry->measured_value }}</td>
                        <td>{{ $entry->created_at }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <a href="{{ route('modules.index') }}" class="btn btn-secondary">Back to Modules</a>
    </div>
    <!-- Initialize DataTables -->
    <script>
        $(document).ready(function() {
            $('#moduleHistoryTable').DataTable({
                "paging": true,
                "searching": true,
                "pageLength": 10,
                "order": [
                    [3, "desc"]
                ] // Order by the Timestamp column (4th column) in descending order
            });
        });
    </script>
@endsection
