<!DOCTYPE html>
<html>
<head>
    <title>LIST OF ALL PLANT SCAN RESULTS FROM THE GPT-AI SYSTEM</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #333; padding: 4px; font-size: 12px; }
        th { background: #eee; }
    </style>
</head>
<body>
    <h2>LIST OF ALL PLANT SCAN RESULTS FROM THE GPT-AI SYSTEM</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>User</th>
                <th>Plant Name</th>
                <th>Disease Name</th>
                <th>Disease Details</th>
                <th>Suggested Solution</th>
                <th>Prevention Tip</th>
                <th>Detected On</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($pgtAiResults as $result)
                <tr>
                    <td>{{ $result->id }}</td>
                    <td>{{ $result->user->name ?? 'N/A' }}</td>
                    <td>{{ $result->plant_name }}</td>
                    <td>{{ $result->disease_name ?? 'N/A' }}</td>
                    <td>{{ $result->disease_details ?? 'N/A' }}</td>
                    <td>{{ $result->suggested_solution ?? 'N/A' }}</td>
                    <td>{{ $result->prevention_tips ?? 'N/A' }}</td>
                    <td>{{ $result->created_at->format('d M, Y H:i A') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html> 