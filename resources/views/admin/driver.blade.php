@extends('layouts.app')

@section('title', 'Drivers')

@section('content')
    <!-- Page Heading -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <div class="row">
                <div class="col">
                    <h1 class="h3 mb-2 text-gray-800">Drivers Data</h1>
                </div>
                <div class="col text-right">
                    <a href="#" target="" class="btn btn-success btn-icon-split" id="add-new-data-btn">
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
                            <th>Name</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($drivers as $index => $driver)
                            <tr>
                                <td>{{ $index+1 }}</td>
                                <td>{{ $driver->name }}</td>
                                <td class="text-right">
                                    <a class="btn btn-info btn-icon-split edit-btn" data-id="{{ $driver->id }}" data-name="{{ $driver->name }}">
                                        <span class="icon text-white-50">
                                            <i class="fas fa-pen-to-square"></i>
                                        </span>
                                        <span class="text">Edit</span>
                                    </a>
                                    <a class="btn btn-danger btn-icon-split delete-btn" data-id="{{ $driver->id }}" data-name="{{ $driver->name }}">
                                        <span class="icon text-white-50">
                                            <i class="fas fa-trash"></i>
                                        </span>
                                        <span class="text">Delete</span>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center">No Data</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Add data modal --}}
    <script>
        // Add data modal
        document.getElementById('add-new-data-btn').addEventListener('click', async function(event) {
            event.preventDefault(); // Prevent default link behavior

            const { value: inputText } = await Swal.fire({
                title: "Enter new data",
                input: "text",
                inputPlaceholder: "Enter data here...",
                showCancelButton: true,
                inputValidator: (value) => {
                    if (!value) {
                        return "Input can not be empty!";
                    }
                }
            });

            if (inputText) {
                // Submit the data to the specified route
                fetch("{{ url('/driver/add') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({ name: inputText })
                })
                .then(response => response.json())
                .then(data => {
                    Swal.fire({
                        title: "Success!",
                        text: "Driver name added successfully!",
                        icon: "success"
                    }).then(() => {
                        window.location.reload();
                    });
                })
                .catch(error => {
                    Swal.fire('Error', 'An error occurred while adding data.', 'error');
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
                        fetch(`/driver/delete/${dataId}`, {
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
                                text: "Driver data has been deleted.",
                                icon: "success"
                            }).then(() => {
                                window.location.reload();
                            });
                        })
                        .catch(error => {
                            Swal.fire('Error', 'An error occurred while deleting data.', 'error');
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
                const inputOptions = {
                    title: "Enter new name",
                    input: "text",
                    inputValue: dataName,
                    showCancelButton: true,
                    inputValidator: (value) => {
                        if (!value) {
                            return "Name cannot be empty!";
                        }
                    }
                };

                Swal.fire(inputOptions).then((result) => {
                    if (result.isConfirmed) {
                        const newName = result.value;
                        fetch(`/driver/edit/${dataId}`, {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "X-CSRF-TOKEN": "{{ csrf_token() }}"
                            },
                            body: JSON.stringify({ name: newName })
                        })
                        .then(response => response.json())
                        .then(data => {
                            Swal.fire({
                                title: "Success!",
                                text: "Driver name updated successfully!",
                                icon: "success"
                            }).then(() => {
                                window.location.reload();
                            });
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
