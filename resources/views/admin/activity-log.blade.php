@extends('layouts.app')

@section('title', 'Activity Log')

@section('content')
    <!-- Page Heading -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <div class="row">
                <div class="col">
                    <h1 class="h3 mb-2 text-gray-800">Activity Log</h1>
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
                            <th>Action</th>
                            <th>Target</th>
                            <th>Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($activities as $index => $activity)
                            <tr>
                                <td>{{ $index+1 }}</td>
                                <td>{{ $activity->user->name }}</td>
                                <td>{{ $activity->type }}</td>

                                @if ($activity->target == "Driver")
                                    <td>{{ $activity->driver->name ?? "N/A" }}</td>
                                @endif

                                @if ($activity->target == "Office")
                                    <td>{{ $activity->office->name ?? "N/A" }}</td>
                                @endif

                                @if ($activity->target == "Order")
                                    <td>Order id : {{ $activity->related_id }}</td>
                                @endif

                                @if ($activity->target == "Approve" || $activity->target == "Reject")
                                    <td>{{ $activity->orderApproval->name ?? "N/A" }}</td>
                                @endif

                                @if ($activity->target == "Quarry")
                                    <td>{{ $activity->quarry->name }}</td>
                                @endif

                                @if ($activity->target == "User")
                                    <td>{{ $activity->user->name ?? "N/A" }}</td>
                                @endif

                                @if ($activity->target == "Vehicle")
                                    <td>{{ $activity->vehicle->registration_number ?? "N/A" }}</td>
                                @endif

                                <td>{{ $activity->created_at }}</td>
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
@endsection
