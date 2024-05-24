@extends('layouts.app')

@section('title', 'Order')

@section('content')
    <!-- Page Heading -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <div class="row">
                <div class="col">
                    <h1 class="h3 mb-2 text-gray-800">Order Data</h1>
                </div>
                <div class="col text-right">
                    <a class="btn btn-info btn-icon-split" id="excel-export-btn">
                        <span class="icon text-white-50">
                            <i class="fas fa-file-excel"></i>
                        </span>
                        <span class="text">Export to Excel</span>
                    </a>
                    <a class="btn btn-success btn-icon-split" id="add-new-data-btn">
                        <span class="icon text-white-50">
                            <i class="fas fa-plus"></i>
                        </span>
                        <span class="text">Add New Order</span>
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>User</th>
                            <th>Quarry</th>
                            <th>Driver</th>
                            <th>Vehicle</th>
                            <th>Status</th>
                            <th>Start</th>
                            <th>End</th>
                            <th>Ordered At</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($orders as $index => $order)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $order->user->name }}</td>
                                <td>{{ $order->quarry->name }}</td>
                                <td>{{ $order->driver->name }}</td>
                                <td>{{ $order->vehicle->registration_number }}</td>
                                @if ($order->status == 'Cancelled')
                                    <td>
                                        <a class="btn btn-secondary btn-icon-split">
                                            <span class="text">Cancelled</span>
                                        </a>
                                    </td>
                                @endif
                                @if ($order->status == 'Requested')
                                    <td>
                                        <a class="btn btn-warning btn-icon-split">
                                            <span class="text">Requested</span>
                                        </a>
                                    </td>
                                @endif
                                @if ($order->status == 'On Process')
                                    <td>
                                        <a class="btn btn-info btn-icon-split">
                                            <span class="text">On Process</span>
                                        </a>
                                    </td>
                                @endif
                                @if ($order->status == 'Approved')
                                    <td>
                                        <a class="btn btn-success btn-icon-split">
                                            <span class="text">Approved</span>
                                        </a>
                                    </td>
                                @endif
                                @if ($order->status == 'Rejected')
                                    <td>
                                        <a class="btn btn-danger btn-icon-split">
                                            <span class="text">Rejected</span>
                                        </a>
                                    </td>
                                @endif
                                @if ($order->status == 'Done')
                                    <td>
                                        <a class="btn btn-primary btn-icon-split">
                                            <span class="text">Done</span>
                                        </a>
                                    </td>
                                @endif
                                <td>{{ \Carbon\Carbon::parse($order->start)->format('Y-m-d') }}</td>
                                <td>{{ \Carbon\Carbon::parse($order->end)->format('Y-m-d') }}</td>
                                <td>{{ $order->created_at }}</td>
                                <td class="text-right">
                                    @if (($order->status == 'Requested' || $order->status == 'On Process') && Auth::user()->role_id == '3')
                                        <a class="btn btn-danger btn-icon-split delete-btn" data-id="{{ $order->id }}">
                                            <span class="icon text-white-50">
                                                <i class="fas fa-xmark"></i>
                                            </span>
                                            <span class="text">Cancel Order</span>
                                        </a>
                                    @endif

                                    @if (Auth::user()->role_id == '2' && $order->status == 'Approved')
                                        <a class="btn btn-info btn-icon-split info-btn" data-id="{{ $order->id }}">
                                            <span class="icon text-white-50">
                                                <i class="fas fa-check-double"></i>
                                            </span>
                                            <span class="text">Mark as Done</span>
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center">No Data</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Add, and Cancel Modals --}}
    <script>
        // Add data modal
        document.getElementById('add-new-data-btn').addEventListener('click', async function(event) {
            event.preventDefault();

            const { value: formValues } = await Swal.fire({
                title: "Enter new order data",
                html:
                    `<select id="approver" class="swal2-input custom-select">
                        <option value="" hidden>Select Approver</option>
                        @foreach($approvers as $approver)
                        <option value="{{ $approver->id }}">{{ $approver->name }}</option>
                        @endforeach
                    </select>
                    <select id="quarry" class="swal2-input custom-select">
                        <option value="" hidden>Select Quarry</option>
                        @foreach($quarries as $quarry)
                        <option value="{{ $quarry->id }}">{{ $quarry->name }}</option>
                        @endforeach
                    </select>
                    <select id="vehicle" class="swal2-input custom-select">
                        <option value="" hidden>Select Vehicle</option>
                        @foreach($vehicles as $vehicle)
                        <option value="{{ $vehicle->id }}">{{ $vehicle->registration_number }}</option>
                        @endforeach
                    </select>
                    <select id="driver" class="swal2-input custom-select">
                        <option value="" hidden>Select Driver</option>
                        @foreach($drivers as $driver)
                        <option value="{{ $driver->id }}">{{ $driver->name }}</option>
                        @endforeach
                    </select>
                    <input id="start" type="date" class="swal2-input" placeholder="Enter start date">
                    <input id="end" type="date" class="swal2-input" placeholder="Enter end date">`,
                showCancelButton: true,
                focusConfirm: false,
                preConfirm: () => {
                    return [
                        document.getElementById('approver').value,
                        document.getElementById('quarry').value,
                        document.getElementById('vehicle').value,
                        document.getElementById('driver').value,
                        document.getElementById('start').value,
                        document.getElementById('end').value
                    ];
                }
            });

            if (formValues) {
                const [approver, quarry, vehicle, driver, start, end] = formValues;
                fetch("{{ url('/order/add') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({ approver_id: approver, quarry_id: quarry, vehicle_id: vehicle, driver_id: driver, start: start, end: end })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        Swal.fire('Error', data.error, 'error');
                    } else {
                        if (data.message === 'Order added successfully!') {
                            Swal.fire({
                                title: "Success!",
                                text: "Order created successfully!",
                                icon: "success"
                            }).then(() => {
                                window.location.reload();
                            });
                        } else {
                            Swal.fire('Error', 'An unexpected error occurred while adding order data.', 'error');
                        }
                    }
                })
                .catch(error => {
                    console.error("Error:", error);
                    Swal.fire('Error', 'An unexpected error occurred while adding order data.', 'error');
                });
            }
        });

        // Cancel data modal
        document.querySelectorAll('.delete-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const dataId = this.getAttribute('data-id');
                Swal.fire({
                    title: `Are you sure want to cancel?`,
                    text: "You won't be able to revert this!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Yes, cancel it!"
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch(`/order/delete/${dataId}`, {
                                method: "POST",
                                headers: {
                                    "Content-Type": "application/json",
                                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                                },
                            })
                            .then(response => response.json())
                            .then(data => {
                                Swal.fire({
                                    title: "Cancelled!",
                                    text: "Order has been cancelled.",
                                    icon: "success"
                                }).then(() => {
                                    window.location.reload();
                                });
                            })
                            .catch(error => {
                                Swal.fire('Error',
                                    'An error occurred while cancelling order data.', 'error'
                                    );
                            });
                    }
                });
            });
        });

        // Done data modal
        document.querySelectorAll('.info-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const orderId = this.getAttribute('data-id');

                Swal.fire({
                    title: 'Mark Order as Done',
                    html:
                        `<input type="text" id="fuel_usage" class="swal2-input" placeholder="Fuel Usage (L)" min="0" inputmode="numeric" pattern="[0-9]*"><br>` +
                        `<input type="text" id="distance" class="swal2-input" placeholder="Distance (km)" min="0" inputmode="numeric" pattern="[0-9]*">`,
                    showCancelButton: true,
                    confirmButtonText: 'Mark as Done',
                    cancelButtonText: 'Cancel',
                    reverseButtons: true,
                    icon: 'info'
                }).then((result) => {
                    if (result.isConfirmed) {
                        const fuelUsage = document.getElementById('fuel_usage').value;
                        const distance = document.getElementById('distance').value;

                        console.log(orderId);
                        fetch(`/order/done/${orderId}`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({ fuel_usage: fuelUsage, distance: distance })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.error) {
                                Swal.fire('Error', data.error, 'error');
                            } else {
                                Swal.fire({
                                    title: "Success!",
                                    text: "Order marked as done successfully",
                                    icon: "success"
                                }).then(() => {
                                    window.location.reload();
                                });
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            Swal.fire('Error', 'An unexpected error occurred.', 'error');
                        });
                    }
                });
            });
        });

        // Excel export modal
        document.getElementById('excel-export-btn').addEventListener('click', function() {
            Swal.fire({
                title: 'Export Data to Excel',
                html:
                    `<p>Kosongkan input jika ingin export seluruh data</p>` +
                    `<input type="date" id="start-date" class="swal2-input" placeholder="Start Date">` +
                    `<input type="date" id="end-date" class="swal2-input" placeholder="End Date">`,
                showCancelButton: true,
                confirmButtonText: 'Export',
                cancelButtonText: 'Cancel',
                reverseButtons: true,
                icon: 'info'
            }).then((result) => {
                if (result.isConfirmed) {
                    const startDate = document.getElementById('start-date').value || null;
                    const endDate = document.getElementById('end-date').value || null;

                    fetch("/export/excel", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
                        },
                        body: JSON.stringify({ start_date: startDate, end_date: endDate })
                    })
                    .then(response => {
                        if (response.ok) {
                            return response.blob(); // Convert response to blob
                        } else {
                            throw new Error('Failed to export data');
                        }
                    })
                    .then(blob => {
                        // Create a URL for the blob
                        const url = window.URL.createObjectURL(blob);
                        // Create a temporary link element
                        const a = document.createElement('a');
                        // Set the href attribute of the link to the URL of the blob
                        a.href = url;
                        // Set the download attribute to specify the filename
                        a.download = 'orders.xlsx';
                        // Append the link to the document body
                        document.body.appendChild(a);
                        // Click the link programmatically to trigger the download
                        a.click();
                        // Remove the link from the document body
                        document.body.removeChild(a);
                    })
                    .catch(error => {
                        console.error("Error:", error);
                        Swal.fire('Error', 'An unexpected error occurred while exporting data.', 'error');
                    });
                }
            });
        });

    </script>
@endsection
