<table>
    <thead>
        <tr>
            <th>id</th>
            <th>before</th>
            <th>after</th>
            <th>route</th>
            <th>ip_address</th>
            <th>user_agent</th>
            <th>loggable_id</th>
            <th>loggable_type</th>
            <th>user_id</th>
            <th>action_id</th>
            <th>created_at</th>
            <th>updated_at</th>
        </tr>
    </thead>
    <tbody>
        @foreach($audits as $audit)
            <tr>
                <td>{{ $audit->id }}</td>
                <td>{{ $audit->before }}</td>
                <td>{{ $audit->after }}</td>
                <td>{{ $audit->route }}</td>
                <td>{{ $audit->ip_address }}</td>
                <td>{{ $audit->user_agent }}</td>
                <td>{{ $audit->loggable_id }}</td>
                <td>{{ $audit->loggable_type }}</td>
                <td>{{ $audit->user_id }}</td>
                <td>{{ $audit->action_id }}</td>
                <td>{{ $audit->created_at }}</td>
                <td>{{ $audit->updated_at }}</td>
            </tr>
        @endforeach
    </tbody>
</table>