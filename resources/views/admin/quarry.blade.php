@extends('layouts.app')

@section('title', 'Quarry')

@section('content')
    <!-- Page Heading -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <div class="row">
                <div class="col">
                    <h1 class="h3 mb-2 text-gray-800">Quarry Data</h1>
                </div>
                <div class="col text-right">
                    <a href="#" class="btn btn-success btn-icon-split" id="add-new-data-btn">
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
                            <th>Office</th>
                            <th>Name</th>
                            <th>Location</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($quarries as $index => $quarry)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $quarry->office->name }}</td>
                                <td>{{ $quarry->name }}</td>
                                <td>{{ $quarry->location }}</td>
                                <td class="text-right">
                                    <a href="#" class="btn btn-info btn-icon-split edit-btn" id="edit-btn"
                                        data-id="{{ $quarry->id }}" data-name="{{ $quarry->name }}"
                                        data-location="{{ $quarry->location }}" data-office-id="{{ $quarry->office_id }}">
                                        <span class="icon text-white-50">
                                            <i class="fas fa-pen-to-square"></i>
                                        </span>
                                        <span class="text">Edit</span>
                                    </a>
                                    <a href="#" class="btn btn-danger btn-icon-split delete-btn"
                                        data-id="{{ $quarry->id }}" data-name="{{ $quarry->name }}">
                                        <span class="icon text-white-50">
                                            <i class="fas fa-trash"></i>
                                        </span>
                                        <span class="text">Delete</span>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">No Data</td>
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
                title: "Enter new quarry data",
                html:
                    '<select id="office" class="swal2-input custom-select">' +
                    '@foreach($offices as $office)' +
                    '<option value="{{ $office->id }}">{{ $office->name }}</option>' +
                    '@endforeach' +
                    '</select>' +
                    '<input id="name" class="swal2-input" placeholder="Enter name">' +
                    '<input id="location" class="swal2-input" placeholder="Enter location">',
                showCancelButton: true,
                focusConfirm: false,
                preConfirm: () => {
                    return [
                        document.getElementById('office').value,
                        document.getElementById('name').value,
                        document.getElementById('location').value
                    ];
                }
            });

            if (formValues) {
                const [office, name, location] = formValues;
                fetch("{{ url('/quarry/add') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({ office_id: office, name: name, location: location })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.message === 'Quarry added successfully!') {
                        Swal.fire({
                            title: "Success!",
                            text: "Quarry added successfully!",
                            icon: "success"
                        }).then(() => {
                            window.location.reload();
                        });
                    } else {
                        Swal.fire('Error', data.message, 'error');
                    }
                })
                .catch(error => {
                    Swal.fire('Error', 'An error occurred while adding quarry data.', 'error');
                });
            }

        });

        // Delete data modal
        document.querySelectorAll('.delete-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const dataId = this.getAttribute('data-id');
                const dataName = this.getAttribute('data-name');
                Swal.fire({
                    title: `Are you sure want to delete ${dataName}?`,
                    text: "You won't be able to revert this!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Yes, delete it!"
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch(`/quarry/delete/${dataId}`, {
                                method: "POST",
                                headers: {
                                    "Content-Type": "application/json",
                                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                                },
                            })
                            .then(response => response.json())
                            .then(data => {
                                Swal.fire({
                                    title: "Deleted!",
                                    text: "Quarry data has been deleted.",
                                    icon: "success"
                                }).then(() => {
                                    window.location.reload();
                                });
                            })
                            .catch(error => {
                                Swal.fire('Error',
                                    'An error occurred while deleting quarry data.', 'error'
                                    );
                            });
                    }
                });
            });
        });

        // Edit data modal
        document.querySelectorAll('.edit-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const dataId = this.getAttribute('data-id');
                const dataName = this.getAttribute('data-name');
                const dataLocation = this.getAttribute('data-location');
                const currentOfficeId = this.getAttribute('data-office-id');

                const inputOptions = {
                    title: "Enter new data",
                    html:
                        '<select id="office" class="swal2-input custom-select">' +
                        '@foreach($offices as $office)' +
                        '<option value="{{ $office->id }}" ' + ({{ $office->id }} == currentOfficeId ? "selected" : "") + '>{{ $office->name }}</option>' +
                        '@endforeach' +
                        '</select>' +
                        '<input id="name" class="swal2-input" placeholder="Enter name" value="' + dataName + '">' +
                        '<input id="location" class="swal2-input" placeholder="Enter location" value="' + dataLocation + '">',
                    inputValue: dataName,
                    showCancelButton: true,
                    preConfirm: () => {
                        return {
                            office: document.getElementById('office').value,
                            name: document.getElementById('name').value,
                            location: document.getElementById('location').value
                        };
                    },
                    inputValidator: (value) => {
                        if (!value.name || !value.location) {
                            return "Name and location cannot be empty!";
                        }
                    }
                };

                Swal.fire(inputOptions).then((result) => {
                    if (result.isConfirmed) {
                        const { office, name, location } = result.value;
                        fetch(`/quarry/edit/${dataId}`, {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "X-CSRF-TOKEN": "{{ csrf_token() }}"
                            },
                            body: JSON.stringify({ office_id: office, name: name, location: location })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.message === 'Quarry updated successfully') {
                                Swal.fire({
                                    title: "Success!",
                                    text: "Quarry updated successfully!",
                                    icon: "success"
                                }).then(() => {
                                    window.location.reload();
                                });
                            } else {
                                Swal.fire('Error', data.message, 'error');
                            }
                        })
                        .catch(error => {
                            Swal.fire('Error', 'An error occurred while updating data.', 'error');
                        });
                    }
                });
            });
        });

    </script>
@endsection
