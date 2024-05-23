@extends('layouts.app')

@section('title', 'Vehicle')

@section('content')
    <!-- Page Heading -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <div class="row">
                <div class="col">
                    <h1 class="h3 mb-2 text-gray-800">Vehicle Data</h1>
                </div>
                <div class="col text-right">
                    <a class="btn btn-success btn-icon-split" id="add-new-data-btn">
                        <span class="icon text-white-50">
                            <i class="fas fa-plus"></i>
                        </span>
                        <span class="text">Add New Data</span>
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
                            <th>Registration Number</th>
                            <th>Type</th>
                            <th>Owner</th>
                            <th>Renter</th>
                            <th>Next Service</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($vehicles as $index => $vehicle)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $vehicle->registration_number }}</td>
                                <td>{{ $vehicle->type }}</td>
                                <td>{{ $vehicle->owner }}</td>
                                <td>{{ $vehicle->renter }}</td>
                                <td>{{ $vehicle->service }}</td>
                                <td class="text-right">
                                    <a class="btn btn-info btn-icon-split edit-btn" id="edit-btn"
                                        data-id="{{ $vehicle->id }}" data-registration="{{ $vehicle->registration_number }}"
                                        data-type="{{ $vehicle->type }}" data-owner="{{ $vehicle->owner }}"
                                        data-renter="{{ $vehicle->renter }}" data-service="{{ $vehicle->service }}">
                                        <span class="icon text-white-50">
                                            <i class="fas fa-pen-to-square"></i>
                                        </span>
                                        <span class="text">Edit</span>
                                    </a>
                                    <a class="btn btn-danger btn-icon-split delete-btn"
                                        data-id="{{ $vehicle->id }}" data-registration="{{ $vehicle->registration_number }}">
                                        <span class="icon text-white-50">
                                            <i class="fas fa-trash"></i>
                                        </span>
                                        <span class="text">Delete</span>
                                    </a>
                                    <a class="btn btn-warning btn-icon-split history-btn" data-id="{{ $vehicle->id }}">
                                        <span class="icon text-white-50">
                                            <i class="fas fa-list"></i>
                                        </span>
                                        <span class="text">History</span>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">No Data</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Add, Edit, and Delete Modals --}}
    <script>
        // Add data modal
        document.getElementById('add-new-data-btn').addEventListener('click', async function(event) {
            console.log("add modal")
            event.preventDefault();

            const { value: formValues } = await Swal.fire({
                title: "Enter new vehicle data",
                html:
                    '<input id="registration_number" class="swal2-input" placeholder="Enter registration number">' +
                    '<select id="type" class="swal2-input custom-select">' +
                        '<option value="" hidden>Tipe Kendaraan</option>' +
                        '<option value="barang">Barang</option>' +
                        '<option value="penumpang">Penumpang</option>' +
                    '</select>' +
                    '<select id="owner" class="swal2-input custom-select">' +
                        '<option value="" hidden>Kepemilikan Kendaraan</option>' +
                        '<option value="perusahaan">Perusahaan</option>' +
                        '<option value="sewa">Sewa</option>' +
                    '</select>' +
                    '<input id="renter" class="swal2-input" placeholder="Enter renter">' +
                    '<input id="service" class="swal2-input" placeholder="Enter next service date">',
                showCancelButton: true,
                focusConfirm: false,
                preConfirm: () => {
                    return {
                        registration_number: document.getElementById('registration_number').value,
                        type: document.getElementById('type').value,
                        owner: document.getElementById('owner').value,
                        renter: document.getElementById('renter').value,
                        service: document.getElementById('service').value
                    };
                }
            });

            if (formValues) {
                const { registration_number, type, owner, renter, service } = formValues;
                fetch("{{ url('/vehicle/add') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({ registration_number, type, owner, renter, service })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.message === 'Vehicle added successfully!') {
                        Swal.fire({
                            title: "Success!",
                            text: "Vehicle added successfully!",
                            icon: "success"
                        }).then(() => {
                            window.location.reload();
                        });
                    } else {
                        Swal.fire('Error', data.error, 'error');
                    }
                })
                .catch(error => {
                    Swal.fire('Error', 'An error occurred while adding vehicle data.', 'error');
                });
            }
        });

        // Edit data modal
        document.querySelectorAll('.edit-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const dataId = this.getAttribute('data-id');
                const registrationNumber = this.getAttribute('data-registration');
                const type = this.getAttribute('data-type');
                const owner = this.getAttribute('data-owner');
                const renter = this.getAttribute('data-renter');
                const service = this.getAttribute('data-service');
                // console.log(dataId);

                Swal.fire({
                    title: "Edit vehicle data",
                    html:
                        '<input id="registration_number" class="swal2-input" placeholder="Enter registration number" value="' + registrationNumber + '">' +
                        '<select id="type" class="swal2-input custom-select">' +
                            '<option value="barang"' + (type == "barang" ? " selected" : "") + '>Barang</option>' +
                            '<option value="penumpang"' + (type == "penumpang" ? " selected" : "") + '>Penumpang</option>' +
                        '</select>' +
                        '<select id="owner" class="swal2-input custom-select">' +
                            '<option value="perusahaan"' + (owner == "perusahaan" ? " selected" : "") + '>Perusahaan</option>' +
                            '<option value="sewa"' + (owner == "sewa" ? " selected" : "") + '>Sewa</option>' +
                        '</select>' +
                        '<input id="renter" class="swal2-input" placeholder="Enter renter" value="' + renter + '">' +
                        '<input id="service" class="swal2-input" placeholder="Enter next service date" value="' + service + '">',
                    showCancelButton: true,
                    focusConfirm: false,
                    preConfirm: () => {
                        return {
                            registration_number: document.getElementById('registration_number').value,
                            type: document.getElementById('type').value,
                            owner: document.getElementById('owner').value,
                            renter: document.getElementById('renter').value,
                            service: document.getElementById('service').value
                        };
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        const { registration_number, type, owner, renter, service } = result.value;
                        fetch(`/vehicle/edit/${dataId}`, {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "X-CSRF-TOKEN": "{{ csrf_token() }}"
                            },
                            body: JSON.stringify({ registration_number, type, owner, renter, service })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.message === 'Vehicle updated successfully!') {
                                Swal.fire({
                                    title: "Success!",
                                    text: "Vehicle updated successfully!",
                                    icon: "success"
                                }).then(() => {
                                    window.location.reload();
                                });
                            } else {
                                Swal.fire('Error', data.error, 'error');
                            }
                        })
                        .catch(error => {
                            Swal.fire('Error', 'An error occurred while updating vehicle data.', 'error');
                        });
                    }
                });
            });
        });

        // Delete data modal
        document.querySelectorAll('.delete-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const dataId = this.getAttribute('data-id');
                const registrationNumber = this.getAttribute('data-registration');
                Swal.fire({
                    title: `Are you sure you want to delete ${registrationNumber}?`,
                    text: "You won't be able to revert this!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Yes, delete it!"
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch(`/vehicle/delete/${dataId}`, {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "X-CSRF-TOKEN": "{{ csrf_token() }}"
                            },
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.message === 'Vehicle deleted successfully!') {
                                Swal.fire({
                                    title: "Deleted!",
                                    text: "Vehicle data has been deleted.",
                                    icon: "success"
                                }).then(() => {
                                    window.location.reload();
                                });
                            } else {
                                Swal.fire('Error', data.error, 'error');
                            }
                        })
                        .catch(error => {
                            Swal.fire('Error', 'An error occurred while deleting vehicle data.', 'error');
                        });
                    }
                });
            });
        });

        //Vehicle History
        document.querySelectorAll('.history-btn').forEach(btn => {
        btn.addEventListener('click', async function() {
            const vehicleId = this.getAttribute('data-id');

            try {
                const response = await fetch(`/vehicle/history/${vehicleId}`);
                const historyData = await response.json();

                if (historyData.length > 0) {
                    let historyTable = '<table class="table table-bordered"><thead><tr><th>Date</th><th>Description</th></tr></thead><tbody>';
                    historyData.forEach(history => {
                        historyTable += `<tr><td>${history.date}</td><td>${history.description}</td></tr>`;
                    });
                    historyTable += '</tbody></table>';

                    Swal.fire({
                        title: 'Vehicle History',
                        html: historyTable,
                        width: '600px',
                        showCloseButton: true,
                        showCancelButton: true,
                        focusConfirm: false,
                    });
                } else {
                    Swal.fire({
                        title: 'Vehicle History',
                        text: 'No history available for this vehicle.',
                        icon: 'info',
                        showCloseButton: true,
                        showCancelButton: true,
                        focusConfirm: false,
                    });
                }
            } catch (error) {
                console.error('Error fetching vehicle history:', error);
                Swal.fire('Error', 'An error occurred while fetching vehicle history.', 'error');
            }
        });
    });
    </script>
@endsection
