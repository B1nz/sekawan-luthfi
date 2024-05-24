<table style="border: 1px solid">
    <thead>
        <tr style="font-weight: bold;">
            <th>No</th>
            <th>User</th>
            <th>Quarry</th>
            <th>Driver</th>
            <th>Vehicle</th>
            <th>Status</th>
            <th>Start</th>
            <th>End</th>
            <th>Ordered At</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($orders as $index => $order)
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $order->user->name }}</td>
            <td>{{ $order->quarry->name }}</td>
            <td>{{ $order->driver->name }}</td>
            <td>{{ $order->vehicle->registration_number }}</td>
            <td>{{ $order->status }}</td>
            <td>{{ $order->start }}</td>
            <td>{{ $order->end }}</td>
            <td>{{ $order->created_at }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
