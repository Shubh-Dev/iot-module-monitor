@extends('layouts.app')

@section('content')
<div class="container my-4">
    <!-- Main Heading -->
    <h1 class="display-5 mb-4 text-center">Module Status</h1>

    <!-- Module Status Table -->
    <table id="moduleHistoryTable" class="table table-hover table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th scope="col">ID</th>
                <th scope="col">Module Id</th>
                <th scope="col">Measured Value</th>
                <th scope="col">Status</th>
                <th scope="col">Operating Time</th>
                <th scope="col">Data Sent Count</th>
                <th scope="col">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($histories as $history)
                <tr class="
                    @if($history->status == 'Active') 
                        table-success
                    @elseif($history->status == 'Inactive')
                        table-warning
                    @elseif($history->status == 'Malfunction')
                        table-danger
                    @endif
                ">
                    <td>{{ $module->id }}</td>
                    <td>{{ $module->module_id }}</td>
                    <td>{{ $module->measured_value }}</td>
                    <td>{{ $module->status }}</td>
                    <td>{{ $module->operating_time }}</td>
                    <td>{{ $module->data_sent_count }}</td>
                    <td> Delete</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- DataTables Initialization Script -->
<script>
    $(document).ready(function() {
        // Initialize DataTables for the two tables
        $('#moduleHistoryTable').DataTable({
            "paging": true,
            "searching": true,
            "pageLength": 10
        });

        $('#moduleHistoryTable').DataTable({
            "paging": true,
            "searching": true,
            "pageLength": 10
        });
    });
</script>

@endsection



