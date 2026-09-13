<table>
    <thead>
        <tr>
            <th>id</th>
            <th>type</th>
            <th>actionable_id</th>
            <th>actionable_type</th>
            <th>description</th>
            <th>created_at</th>
            <th>updated_at</th>
        </tr>
    </thead>
    <tbody>
        @foreach($actions as $action)
            <tr>
                <td>{{ $action->id }}</td>
                <td>{{ $action->type }}</td>
                <td>{{ $action->actionable_id }}</td>
                <td>{{ $action->actionable_type }}</td>
                <td>{{ $action->description }}</td>
                <td>{{ $action->created_at }}</td>
                <td>{{ $action->updated_at }}</td>
            </tr>
        @endforeach
    </tbody>
</table>