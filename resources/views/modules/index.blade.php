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

        td {
            transition: background-color 0.5s ease;
        }

        .dataTables_filter {
            margin-bottom: 1rem;
        }
    </style>
@endsection

@section('content')
    <div class="container my-5">

        <!-- Headline and add button -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="h3 mb-0">Module Status</h2>
            <a href="modules/add" class="btn btn-primary btn-sm">ADD MODULE</a>
        </div>

        <!-- Module Status Table -->
        <table id="moduleStatusTable" class="table table-hover table-bordered table-striped ">
            <thead class="table-dark mt-5">
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
                            {{ $module->status }}</td>
                        <td>{{ $module->operating_time }}</td>
                        <td>{{ $module->data_sent_count }}</td>
                        <td>
                            <a href="#" class="btn btn-info btn-sm">Details</a>
                            <button class="btn btn-danger btn-sm btn-delete">Delete</button>
                            <button class="btn btn-success btn-sm dynamic-btn">Start</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <canvas id="moduleChart" width="400" height="200"></canvas>

    </div>

    <!-- DataTables Initialization -->
    <script>
        $(document).ready(function() {
            let moduleTable = $('#moduleStatusTable').DataTable({
                "paging": true,
                "searching": true,
                "pageLength": 10,

            });

            let previousData = {};

            // fetch updated data and update ui
            const refreshModuleData = () => {
                $.ajax({
                    url: '/api/modules',
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        moduleTable.clear();

                        data.forEach(function(module) {
                            const statusClass = module.status.toLowerCase();
                            const rowId = `module-${module.id}`;
                            // Compare current and previous values
                            const prevMeasuredValue = previousData[rowId]?.measured_value;
                            const prevOperatingTime = previousData[rowId]?.operating_time;
                            const prevDataSentCount = previousData[rowId]?.data_sent_count;
                            moduleTable.row.add([
                                module.id,
                                module.name,
                                module.type,
                                `<span class="measured-value" data-prev="${prevMeasuredValue || ''}">${module.measured_value}</span>`,
                                `<td class="${statusClass}">${module.status}</td>`,
                                `<span class="operating-time" data-prev="${prevOperatingTime || ''}">${module.operating_time}</span>`,
                                `<span class="data-sent-count" data-prev="${prevDataSentCount || ''}">${module.data_sent_count}</span>`,
                                `<button class="btn btn-primary fetch-history" data-id="${module.id}">Show History</button>`
                            ]);
                            previousData[rowId] = {
                                measured_value: module.measured_value,
                                operating_time: module.operating_time,
                                data_sent_count: module.data_sent_count,
                            };
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

                        $('#moduleStatusTable tbody tr').each(function() {
                            const $row = $(this);

                            // Highlight changed values
                            highlightChange($row.find('.measured-value'));
                            highlightChange($row.find('.operating-time'));
                            highlightChange($row.find('.data-sent-count'));
                        });

                        console.log('Table updated with new data and status classes applied.');

                    },
                    error: function(error) {
                        console.error('Error fetching module data:', error);
                    }
                });
            }

            // setInterval(refreshModuleData, 3000);
        });

        // Helper function to highlight changes
        const highlightChange = ($cell) => {
            const newValue = $cell.text();
            const prevValue = $cell.attr('data-prev');

            if (newValue !== prevValue) {
                // Temporarily change the background color
                $cell.css('background-color', '#ffeb3b');

                // Smoothl transition to the original color
                setTimeout(() => {
                    $cell.css('transition', 'background-color 1s');
                    $cell.css('background-color', '');
                }, 500);

                // Update the previous value attribute
                $cell.attr('data-prev', newValue);
            }
        };
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
    <script>
        // delete request handler
        $(document).on('click', '.btn-delete', function(e) {
            e.preventDefault();
            const moduleId = $(this).data('id');

            if (confirm('Are you sure you want to delete this module?')) {
                $.ajax({
                    url: `/modules/delete/${moduleId}`,
                    type: 'DELETE',
                    success: function(response) {
                        alert(response.success);
                        $('#moduleStatusTable').DataTable().ajax.reload(); // Reload the table
                    },
                    error: function(error) {
                        alert('Error deleting module');
                    }
                });
            }
        });
    </script>
@endsection
