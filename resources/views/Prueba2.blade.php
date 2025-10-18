<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Encriptador simple</title>
</head>
<body>
  <h2>🔐 Encriptar</h2>
  <button id="encryptBtn">Encriptar</button>
  <div>
    <input type="file" id="texto" required>
    <p><strong>Llave generada:</strong> <a id="generatedKey" href="" download></a></p>
  </div>

  <a id="download" href="" download></a>
  <hr>

  <h2>🔓 Desencriptar</h2>
  <input type="file" id="textoEncriptado" required><p>Sube el archivo con el texto encriptado</p>
  <input type="file" id="inputKey" required><p>Sube la llave del texto encriptado</p>
  <button id="decryptBtn">Desencriptar</button>
  <div>
    <p><strong>Texto Desencriptado:</strong></p>
    <a id="downloadDecrypted" href="" download></a>
  </div>

  <script src="{{ asset('js/Prueba2.js') }}"></script>
</body>
</html>