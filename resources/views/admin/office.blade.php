@extends('layouts.app')

@section('title', 'Offices')

@section('content')
    <!-- Page Heading -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <div class="row">
                <div class="col">
                    <h1 class="h3 mb-2 text-gray-800">Offices Data</h1>
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
                            <th>Location</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($offices as $index => $office)
                            <tr>
                                <td>{{ $index+1 }}</td>
                                <td>{{ $office->name }}</td>
                                <td>{{ $office->location }}</td>
                                <td class="text-right">
                                    @if (!$office->is_hq)
                                        <a class="btn btn-info btn-icon-split edit-btn" data-id="{{ $office->id }}" data-name="{{ $office->name }}"
                                            data-location="{{ $office->location }}">
                                            <span class="icon text-white-50">
                                                <i class="fas fa-pen-to-square"></i>
                                            </span>
                                            <span class="text">Edit</span>
                                        </a>
                                        <a class="btn btn-danger btn-icon-split delete-btn" data-id="{{ $office->id }}" data-name="{{ $office->name }}">
                                            <span class="icon text-white-50">
                                                <i class="fas fa-trash"></i>
                                            </span>
                                            <span class="text">Delete</span>
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">No Data</td>
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

            const { value: formValues } = await Swal.fire({
                title: "Enter new data",
                html:
                    '<input id="name" class="swal2-input" placeholder="Enter name">' +
                    '<input id="location" class="swal2-input" placeholder="Enter location">',
                showCancelButton: true,
                preConfirm: () => {
                    return [
                        document.getElementById('name').value,
                        document.getElementById('location').value
                    ];
                }
            });

            if (formValues) {
                const [name, location] = formValues;
                // Submit the data to the specified route
                fetch("{{ url('/office/add') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({ name: name, location: location }) // Mengirim nama dan lokasi ke backend
                })
                .then(response => response.json())
                .then(data => {
                    Swal.fire({
                        title: "Success!",
                        text: "Office data added successfully!",
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
                        fetch(`/office/delete/${dataId}`, {
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
                                text: "Office data has been deleted.",
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
                const dataLocation = this.getAttribute('data-location'); // Mengambil data lokasi

                const inputOptions = {
                    title: "Edit data",
                    html:
                        '<input id="name" class="swal2-input" placeholder="Enter name" value="'+dataName+'">' +
                        '<input id="location" class="swal2-input" placeholder="Enter location" value="'+dataLocation+'">',
                    showCancelButton: true,
                    preConfirm: () => {
                        return [
                            document.getElementById('name').value,
                            document.getElementById('location').value
                        ];
                    }
                };

                Swal.fire(inputOptions).then((result) => {
                    if (result.isConfirmed) {
                        const [name, location] = result.value;
                        fetch(`/office/edit/${dataId}`, {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "X-CSRF-TOKEN": "{{ csrf_token() }}"
                            },
                            body: JSON.stringify({ name: name, location: location })
                        })
                        .then(response => response.json())
                        .then(data => {
                            Swal.fire({
                                title: "Success!",
                                text: "Office data updated successfully!",
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
