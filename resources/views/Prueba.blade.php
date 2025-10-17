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
  <textarea id="inputText" rows="4" cols="50" placeholder="Escribe el texto a encriptar..."></textarea><br>
  <button id="encryptBtn">Encriptar</button>
  <div>
    <p><strong>Texto Encriptado:</strong></p>
    <textarea id="encryptedText" rows="4" cols="50" readonly></textarea>
    <p><strong>Llave generada:</strong> <span id="generatedKey"></span></p>
  </div>

  <hr>

  <h2>🔓 Desencriptar</h2>
  <textarea id="inputEncrypted" rows="4" cols="50" placeholder="Pega el texto encriptado aquí..."></textarea><br>
  <input type="text" id="inputKey" placeholder="Introduce la llave"><br>
  <button id="decryptBtn">Desencriptar</button>
  <div>
    <p><strong>Texto Desencriptado:</strong></p>
    <textarea id="decryptedText" rows="4" cols="50" readonly></textarea>
  </div>

  <script src="{{ asset('js/Prueba.js') }}"></script>
</body>
</html>