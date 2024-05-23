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
                    <a href="#" class="btn btn-success btn-icon-split" id="add-new-data-btn">
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
                                        <a class="btn btn-light btn-icon-split">
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
                                <td>{{ \Carbon\Carbon::parse($order->start)->format('Y-m-d') }}</td>
                                <td>{{ \Carbon\Carbon::parse($order->end)->format('Y-m-d') }}</td>
                                <td>{{ $order->created_at }}</td>
                                <td class="text-right">
                                    @if ($order->status == 'Requested' || $order->status == 'On Process')
                                        <a class="btn btn-danger btn-icon-split delete-btn"
                                            data-id="{{ $order->id }}">
                                            <span class="icon text-white-50">
                                                <i class="fas fa-xmark"></i>
                                            </span>
                                            <span class="text">Cancel Order</span>
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

    </script>
@endsection
