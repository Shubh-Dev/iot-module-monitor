<!-- resources/views/modules/index.blade.php -->
@extends('layouts.app')
@section('head')
    {{-- custom styles to style status cell --}}
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
                            <a href="{{ route('modules.history', ['id' => $module->id]) }}"
                                class="btn btn-info btn-sm">Details</a>
                            <button class="ml-2 btn btn-danger btn-sm btn-delete"
                                data-id="{{ $module->id }}">Delete</button>
                            <button
                                class="ml-2 btn btn-sm dynamic-btn {{ $module->status === 'active' ? 'btn-danger' : 'btn-success' }}"
                                data-id="{{ $module->id }}">
                                {{ $module->status === 'active' ? 'Stop' : 'Start' }}
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
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
                                `<a href="/modules/${module.id}/history/" class="btn btn-info btn-sm">Details</a>
                                <button class="ml-2 btn btn-danger btn-sm btn-delete" data-id="${module.id}">Delete</button>
                               <button
                                class="ml-2 btn btn-sm dynamic-btn ${module.status === 'active' ? 'btn-danger' : 'btn-success'}" data-id="${module.id}">
                               ${module.status === 'active' ? 'Stop' : 'Start'}
                               </button>`
                            ]);
                            previousData[rowId] = {
                                measured_value: module.measured_value,
                                operating_time: module.operating_time,
                                data_sent_count: module.data_sent_count,
                            };
                        });
                        moduleTable.draw(false);
                        $('#moduleStatusTable tbody tr').each(function() {
                            const $row = $(this);
                            const statusText = $row.find('td:nth-child(5)').text()
                                .toLowerCase();

                            $row.find('td:nth-child(5)').removeClass(
                                'active inactive malfunction');
                            if (statusText === 'active') {
                                $row.find('td:nth-child(5)').addClass('active');
                            } else if (statusText === 'inactive') {
                                $row.find('td:nth-child(5)').addClass('inactive');
                            } else if (statusText === 'malfunction') {
                                $row.find('td:nth-child(5)').addClass('malfunction');
                            }

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

            setInterval(refreshModuleData, 3000);
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
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        // delete request handler
        $(document).on('click', '.btn-delete', function(e) {
            e.preventDefault();
            const moduleId = $(this).data('id');
            console.log($('meta[name="csrf-token"]').attr('content'));

            if (confirm('Are you sure you want to delete this module?')) {
                $.ajax({
                    url: `/modules/delete/${moduleId}`,
                    type: 'DELETE',
                    success: function(response) {
                        console.log('Success response:', response); // Log the response for inspection
                        if (response.success) {
                            alert(response.success);

                            // Remove the row from DataTable
                            const rowToDelete = $(
                                `#moduleStatusTable tbody tr button.btn-delete[data-id="${moduleId}"]`
                            ).closest('tr');
                            $('#moduleStatusTable').DataTable().row(rowToDelete).remove().draw(
                                false); // Remove and redraw

                        } else {
                            alert('Failed to delete module');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log('Error response:', xhr
                            .responseText); // Log the error response for inspection
                        alert('Error deleting module: ' + error);
                    }
                });

            }
        });

        $(document).on('click', '.dynamic-btn', function() {
            const $button = $(this);
            const moduleId = $button.closest('tr').find('.dynamic-btn').data('id');
            const currentStatus = $button.text().trim(); // 'Start' or 'Stop'

            // Determine new status
            const newStatus = currentStatus === 'Start' ? 'active' : 'inactive';

            // Show confirmation dialog
            if (confirm(`Are you sure you want to ${currentStatus === 'Start' ? 'start' : 'stop'} this module?`)) {
                $.ajax({
                    url: `/modules/update-status/${moduleId}`,
                    type: 'POST',
                    data: {
                        status: newStatus,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            // Update the button text and class based on the new status
                            $button.text(newStatus === 'active' ? 'Stop' : 'Start');
                            $button.removeClass('btn-success btn-danger')
                                .addClass(newStatus === 'active' ? 'btn-danger' : 'btn-success');

                            // Update the status cell color and text
                            $button.closest('tr').find('td:nth-child(5)').removeClass(
                                    'active inactive malfunction')
                                .addClass(newStatus)
                                .text(newStatus);

                            location.reload();

                        } else {
                            alert('Failed to update module status');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error updating status:', error);
                        alert('Error updating module status');
                    }
                });
            }
        });
    </script>
@endsection
