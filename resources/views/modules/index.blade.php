@extends('layouts.app')

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
                    <tr
                        class="
                    @if ($module->status == 'Active') table-success
                    @elseif($module->status == 'Inactive')
                        table-warning
                    @elseif($module->status == 'Malfunction')
                        table-danger @endif
                ">
                        <td>{{ $module->id }}</td>
                        <td>{{ $module->name }}</td>
                        <td>{{ $module->type }}</td>
                        <td>{{ $module->measured_value }}</td>
                        <td>{{ $module->status }}</td>
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

        <!-- Module History Section -->
        <h2 class="display-6 mt-5 mb-4 text-center">Module History</h2>
        <table id="moduleHistoryTable" class="table table-hover table-bordered table-striped">
            <thead class="table-secondary">
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

        <canvas id="moduleChart" width="400" height="200"></canvas>

    </div>

    <!-- DataTables Initialization Script -->
    <script>
        $(document).ready(function() {
            // Initialize DataTables for the two tables
            let moduleTable = $('#moduleStatusTable').DataTable({
                "paging": true,
                "searching": true,
                "pageLength": 10
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
                        console.log('Table updated with new data.');
                    },
                    error: function(error) {
                        console.error('Error fetching module data:', error);
                    }
                });
            }

            setInterval(refreshModuleData, 3000);

            $('#moduleHistoryTable').DataTable({
                "paging": true,
                "searching": true,
                "pageLength": 10
            });
        });
    </script>

    {{-- <script>
    document.addEventListener('DOMContentLoaded', function () {
        // Event listener for history buttons
        document.querySelectorAll('.fetch-history').forEach(button => {
            button.addEventListener('click', function () {
                const moduleId = this.getAttribute('data-id');
                // Make AJAX request to fetch history
                fetch(`/modules/${moduleId}/history`)
                    .then(response => response.json())
                    .then(data => {
                        // Populate the history modal with data
                        let historyTable = document.getElementById('history-table');
                        historyTable.innerHTML = ''; // Clear previous data

                        data.forEach(item => {
                            let row = `<tr>
                                <td>${item.measured_value}</td>
                                <td>${item.status}</td>
                                <td>${item.operating_time}</td>
                                <td>${item.data_sent_count}</td>
                                <td>${item.recorded_at}</td>
                            </tr>`;
                            historyTable.innerHTML += row;
                        });

                        // Show the modal
                        document.getElementById('history-modal').style.display = 'block';
                    })
                    .catch(error => console.error('Error:', error));
            });
        });
    });
    </script> --}}

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
