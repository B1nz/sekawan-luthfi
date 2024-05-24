@extends('layouts.app')

@section('title', 'Order')

@section('content')
    <!-- Page Heading -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <div class="row">
                <div class="col">
                    <h1 class="h3 mb-2 text-gray-800">Order Request Data</h1>
                </div>
                <div class="col text-right">
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
                                <td>{{ \Carbon\Carbon::parse($order->start)->format('Y-m-d') }}</td>
                                <td>{{ \Carbon\Carbon::parse($order->end)->format('Y-m-d') }}</td>
                                <td>{{ $order->created_at }}</td>
                                <td class="text-right">
                                    <a class="btn btn-danger btn-icon-split reject-btn" data-id="{{ $order->id }}">
                                        <span class="icon text-white-50">
                                            <i class="fas fa-xmark"></i>
                                        </span>
                                        <span class="text">Reject Order</span>
                                    </a>

                                    <a class="btn btn-success btn-icon-split approve-btn" data-id="{{ $order->id }}">
                                        <span class="icon text-white-50">
                                            <i class="fas fa-thumbs-up"></i>
                                        </span>
                                        <span class="text">Approve Order</span>
                                    </a>
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

    {{-- Approve and Reject Modals --}}
    <script>
        // Reject data modal
        document.querySelectorAll('.reject-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const dataId = this.getAttribute('data-id');
                Swal.fire({
                    title: `Are you sure want to reject?`,
                    text: "You won't be able to revert this!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Yes, reject it!"
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch(`/orreq/reject/${dataId}`, {
                                method: "POST",
                                headers: {
                                    "Content-Type": "application/json",
                                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                                },
                            })
                            .then(response => response.json())
                            .then(data => {
                                Swal.fire({
                                    title: "Rejected!",
                                    text: "Order has been rejected.",
                                    icon: "success"
                                }).then(() => {
                                    window.location.reload();
                                });
                            })
                            .catch(error => {
                                Swal.fire('Error', 'An error occurred while rejecting order data.', 'error');
                            });
                    }
                });
            });
        });

        // Approve button
        document.querySelectorAll('.approve-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const orderId = this.getAttribute('data-id');
                const approverOptions = @json($approvers);
                const currentUserRoleId = {{ Auth::user()->role_id }};

                const approverSelect = document.createElement('select');
                approverSelect.id = 'approver';
                approverSelect.className = 'swal2-input custom-select';
                approverSelect.innerHTML = `<option value="" hidden>Select Approver</option>`;
                approverOptions.forEach(approver => {
                    approverSelect.innerHTML += `<option value="${approver.id}">${approver.name}</option>`;
                });

                if (currentUserRoleId === 1) {
                    approverSelect.innerHTML += `<option value="0">Final Approve</option>`;
                }

                Swal.fire({
                    title: 'Order Details',
                    html: `<div style="text-align: left;"><b>Approver:</b> ${approverSelect.outerHTML}<br></div>`,
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Approve',
                    cancelButtonText: 'Reject',
                    reverseButtons: true,
                    showCloseButton: true,
                    preConfirm: () => {
                        const approverId = document.getElementById('approver').value;
                        return { approverId };
                    },
                    allowOutsideClick: () => !Swal.isLoading()
                }).then((result) => {
                    if (result.isConfirmed) {
                        const { approverId } = result.value;
                        fetch(`/orreq/approve/${orderId}`, {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "X-CSRF-TOKEN": "{{ csrf_token() }}"
                            },
                            body: JSON.stringify({ approver_id: approverId })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.error) {
                                Swal.fire('Error', data.error, 'error');
                            } else {
                                Swal.fire({
                                    title: 'Approved!',
                                    text: 'Order has been approved.',
                                    icon: 'success'
                                }).then(() => {
                                    window.location.reload();
                                });
                            }
                        })
                        .catch(error => {
                            Swal.fire('Error', 'An error occurred while approving the order.', 'error');
                        });
                    }
                });
            });
        });
    </script>
@endsection
