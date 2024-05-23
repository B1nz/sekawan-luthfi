@extends('layouts.app')

@section('title', 'Users')

@section('content')
    <!-- Page Heading -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <div class="row">
                <div class="col">
                    <h1 class="h3 mb-2 text-gray-800">Users Data</h1>
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
                            <th>Username</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $index => $user)
                            <tr>
                                <td>{{ $index+1 }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->username }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->role->name ?? 'N/A' }}</td>
                                <td class="text-right">
                                    @if ($user->role_id != 1)
                                        <a class="btn btn-info btn-icon-split edit-btn" data-id="{{ $user->id }}" data-role="{{ $user->role_id }}">
                                            <span class="icon text-white-50">
                                                <i class="fas fa-pen-to-square"></i>
                                            </span>
                                            <span class="text">Edit Role</span>
                                        </a>
                                        <a class="btn btn-danger btn-icon-split delete-btn" data-id="{{ $user->id }}" data-name="{{ $user->role_id }}">
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
                                <td colspan="6" class="text-center">No Data</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Add data modal --}}
    <script>

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
                        fetch(`/user/delete/${dataId}`, {
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
                                text: "User data has been deleted.",
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
            btn.addEventListener('click', async function() {
                const userId = this.getAttribute('data-id');
                const currentRoleId = this.getAttribute('data-role');

                try {
                    const response = await fetch(`/user/roles`);
                    const rolesData = await response.json();

                    const roleOptions = {};
                    rolesData.forEach(role => {
                        roleOptions[role.id] = role.name;
                    });

                    const { value: selectedRoleId } = await Swal.fire({
                        title: 'Select Role',
                        input: 'select',
                        inputOptions: roleOptions,
                        inputPlaceholder: 'Select a role',
                        inputValue: currentRoleId,
                        showCancelButton: true,
                    });

                    if (selectedRoleId) {
                        fetch(`/user/edit/${userId}`, {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "X-CSRF-TOKEN": "{{ csrf_token() }}"
                            },
                            body: JSON.stringify({ role_id: selectedRoleId })
                        })
                        .then(response => response.json())
                        .then(data => {
                            Swal.fire({
                                title: "Success!",
                                text: "User role updated successfully!",
                                icon: "success"
                            }).then(() => {
                                window.location.reload();
                            });
                        })
                        .catch(error => {
                            Swal.fire('Error', 'An error occurred while updating user role.', 'error');
                        });
                    }
                } catch (error) {
                    console.error('Error fetching roles:', error);
                    Swal.fire('Error', 'An error occurred while fetching roles.', 'error');
                }
            });
        });
    </script>
@endsection
