<!-- resources/views/modules/index.blade.php -->
@extends('layouts.app')
@section('head')
    <style>
        .active {
            background-color: #17B169 !important;
            color: white;
        }

        .malfunction {
            background-color: #FF5F1F !important;
            color: white;
        }

        .inactive {
            background-color: #E32636 !important;
            color: white;
        }
    </style>
@endsection

@section('content')
    <div class="container my-4">
        <!-- Main Heading -->
        <h1 class="display-5 mb-4 text-center">Module Status</h1>

        <!-- Module Status Table -->
        <table id="moduleStatusTable" class="table table-hover table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Name</th>
                    <th scope="col">Type</th>
                    <th scope="col">Measured Value</th>
                    <th scope="col">Status</th>
                    <th scope="col">Operating Time</th>
                    <th scope="col">Data Sent Count</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($modules as $module)
                    <tr>
                        <td>{{ $module->id }}</td>
                        <td>{{ $module->name }}</td>
                        <td>{{ $module->type }}</td>
                        <td>{{ $module->measured_value }}</td>
                        <td class="{{ strtolower($module->status) }}">
                            {{ ucfirst($module->status) }}</td>
                        <td>{{ $module->operating_time }}</td>
                        <td>{{ $module->data_sent_count }}</td>
                        <td>
                            <button class="btn btn-primary fetch-history" data-id="{{ $module->id }}">Show
                                History</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <canvas id="moduleChart" width="400" height="200"></canvas>

    </div>

    <!-- DataTables Initialization Script -->
    <script>
        $(document).ready(function() {
            // Initialize DataTables for the two tables
            let moduleTable = $('#moduleStatusTable').DataTable({
                "paging": true,
                "searching": true,
                "pageLength": 10,

            });

            const refreshModuleData = () => {
                console.log('Starting AJAX request to fetch module data...');
                $.ajax({
                    url: '/api/modules',
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        console.log('Data received from API:', data);
                        moduleTable.clear();

                        data.forEach(function(module) {
                            const statusClass = module.status.toLowerCase();
                            moduleTable.row.add([
                                module.id,
                                module.name,
                                module.type,
                                module.measured_value,
                                module.status,
                                module.operating_time,
                                module.data_sent_count,
                                `<button class="btn btn-primary fetch-history" data-id="${module.id}">Show History</button>`
                            ]);
                        });
                        moduleTable.draw(false);
                        // Apply status classes after the table is redrawn
                        $('#moduleStatusTable tbody tr').each(function() {
                            const statusText = $(this).find('td:nth-child(5)').text()
                                .toLowerCase();
                            $(this).find('td:nth-child(5)').removeClass(
                                'active inactive malfunction');
                            if (statusText === 'active') {
                                $(this).find('td:nth-child(5)').addClass('active');
                            } else if (statusText === 'inactive') {
                                $(this).find('td:nth-child(5)').addClass('inactive');
                            } else if (statusText === 'malfunction') {
                                $(this).find('td:nth-child(5)').addClass('malfunction');
                            }
                        });

                        console.log('Table updated with new data and status classes applied.');

                    },
                    error: function(error) {
                        console.error('Error fetching module data:', error);
                    }
                });
            }

            setInterval(refreshModuleData, 3000);
        });
    </script>
    <script>
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
        });
    </script>
@endsection
