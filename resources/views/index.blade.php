<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Historial</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="p-4">
<div class="container">
    <h1>Historial</h1>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Contenido</th>
                <th>Creado</th>
                <th>Key</th>
            </tr>
        </thead>
        <tbody>
        @forelse($data as $row)
            <tr>
                <td>{{ $row->id }}</td>
                <td style="max-width:400px;word-break:break-all;">{{ $row->content }}</td>
                <td>{{ $row->created_at }}</td>
                <td>
                    <a href="{{ route('download.key', $row->id) }}" class="btn btn-sm btn-outline-primary">
                        Descargar .key
                    </a>
                </td>
            </tr>
        @empty
            <tr><td colspan="4">No hay registros</td></tr>
        @endforelse
        </tbody>
    </table>

    {{ $data->withQueryString()->links() }}
</div>
</body>
</html>