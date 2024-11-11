<!-- resources/views/modules/history.blade.php -->
@extends('layouts.app')

@section('head')
    <style>
        .dataTables_filter {
            margin-bottom: 1rem;
        }
    </style>
@endsection

@section('content')
    <div class="container my-4">
        <h1 class="display-5 mb-4 text-center">History for Module: {{ $module->name }}</h1>

        <!-- Module History Table -->
        <table id="moduleHistoryTable" class="table table-hover table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th scope="col">Module Name</th>
                    <th scope="col">Status</th>
                    <th scope="col">Operatng Time</th>
                    <th scope="col">Measured Value</th>
                    <th scope="col">Timestamp</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($history as $entry)
                    <tr>
                        <td>{{ $entry->module->name }}</td>
                        <td>{{ $entry->module->status }}</td>
                        <td>{{ $entry->module->operating_time }}</td>
                        <td>{{ $entry->measured_value }}</td>
                        <td>{{ $entry->created_at }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{-- history chart  --}}
        <canvas id="moduleChart" width="400" height="200"></canvas>

        <a href="{{ route('modules.index') }}" class="btn btn-secondary mt-5">Back to Modules</a>
    </div>
    <!-- Initialize DataTables -->
    <script>
        $(document).ready(function() {
            const moduleHistoryTable = $('#moduleHistoryTable').DataTable({
                "paging": true,
                "searching": true,
                "pageLength": 10,
                "order": [
                    [3, "desc"]
                ]
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            // Prepare data for the chart
            const labels = @json($history->pluck('created_at')->map(fn($date) => $date->format('Y-m-d H:i:s')));
            const dataValues = @json($history->pluck('measured_value'));

            const ctx = document.getElementById('moduleChart').getContext('2d');
            const moduleChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Measured Value Over Time',
                        data: dataValues,
                        borderColor: 'rgba(75, 192, 192, 1)',
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    },
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top'
                        }
                    }
                }
            });


            // Function to fetch and update history data
            const moduleId = {{ $module->id }};
            const refreshHistoryData = () => {
                $.ajax({
                    url: `/api/modules/${moduleId}/history`,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        const labels = [];
                        const dataValues = [];

                        // Populate new data for the chart
                        data.forEach(entry => {
                            labels.push(entry.created_at);
                            dataValues.push(entry.measured_value);
                        });
                        // Update Chart
                        moduleChart.data.labels = labels;
                        moduleChart.data.datasets[0].data = dataValues;
                        moduleChart.update();
                    },
                    error: function(error) {
                        console.error('Error fetching module history:', error);
                    }
                });
            };

            // Fetch data every 5 seconds
            setInterval(refreshHistoryData, 2000);

            // Initial data fetch
            refreshHistoryData();
        });
    </script>
@endsection
