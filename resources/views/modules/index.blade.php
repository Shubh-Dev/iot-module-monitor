@extends('layouts.app')
@section('content')
<div class="container">
    <h1>Module Status</h1>
    <table class="table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Type</th>
                <th>Measured Value</th>
                <th>Status</th>
                <th>Operating Time</th>
                <th>Data Sent Count</th>
            </tr>
        </thead>
        <tbody>
            @foreach($modules as $module)
                <tr>
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

    <h2>Module History</h2>
    <table class="table">
        <thead>
            <tr>
                <th>Module Name</th>
                <th>Measured Value</th>
                <th>Timestamp</th>
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
@endsection