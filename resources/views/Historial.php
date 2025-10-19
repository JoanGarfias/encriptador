<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Historial de Archivos Encriptados</title>
  <style>
    body {
      font-family: sans-serif;
      padding: 2rem;
      background-color: #f9f9f9;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 1rem;
    }
    th, td {
      padding: 0.75rem;
      border: 1px solid #ccc;
      text-align: left;
      vertical-align: middle;
    }
    th {
      background-color: #eee;
    }
    .descarga {
      margin-left: 0.5rem;
      text-decoration: none;
      font-size: 1.2rem;
    }
    .clave {
      font-family: monospace;
      color: #2b6cb0;
    }
    .pagination {
      margin-top: 1rem;
      display: flex;
      gap: 1rem;
      align-items: center;
    }
    .pagination button {
      padding: 0.5rem 1rem;
    }
  </style>
</head>
<body>

  <h2>📁 Historial de Archivos Encriptados</h2>

  <?php
    // Datos de prueba
    $archivos = [
      ['filename' => 'documento1.txt', 'key' => 'documento1_key.key', 'timestamp' => '2025-10-18 18:45'],
      ['filename' => 'reporte_final.pdf', 'key' => 'reporte_final_key.key', 'timestamp' => '2025-10-17 22:10'],
      ['filename' => 'imagen_secreta.png', 'key' => 'imagen_secreta_key.key', 'timestamp' => '2025-10-16 14:30'],
    ];
  ?>

  <table>
    <thead>
      <tr>
        <th>Archivo</th>
        <th>Clave</th>
        <th>Fecha y Hora</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($archivos as $file): ?>
      <tr>
        <td>
          <?= $file['filename'] ?>
          <a href="/downloads/<?= $file['filename'] ?>" class="descarga" title="Descargar archivo">📄</a>
        </td>
        <td>
          <span class="clave"><?= $file['key'] ?></span>
          <a href="/downloads/<?= $file['key'] ?>" class="descarga" title="Descargar clave">🔑</a>
        </td>
        <td><?= $file['timestamp'] ?></td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>

  <div class="pagination">
    <button disabled>← Anterior</button>
    <span>Página 1 de 1</span>
    <button disabled>Siguiente →</button>
  </div>

</body>
</html>