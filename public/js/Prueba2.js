document.getElementById("encryptBtn").addEventListener("click", () => {
    const fileInput = document.getElementById("texto");
    const archivo = fileInput.files[0];

    if (!archivo) return alert("Selecciona un archivo para encriptar");

    const formData = new FormData();
    formData.append("user_file", archivo);

    fetch('/encriptar2', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        const linkDiv = document.getElementById('download');

        const downloadUrl = `/downloads/${encodeURIComponent(data.filename)}`;
        const downloadUrlKey = `/downloads/${encodeURIComponent(data.key)}`;

        linkDiv.innerHTML = `<a href="${downloadUrl}" download>Descargar archivo</a>`;
        document.getElementById("generatedKey").innerHTML = `<a href="${downloadUrlKey}" download>Descargar llave</a>`;

    })
    .catch(err => console.error('Error:', err));
});

document.getElementById("decryptBtn").addEventListener("click", () => {
    const fileInput = document.getElementById("textoEncriptado");
    const archivo = fileInput.files[0];

    const llave = document.getElementById("inputKey");
    const archivoLlave = llave.files[0];

    if (!archivo) return alert("Ingresa un archivo para desencriptar");
    if (!archivoLlave) return alert("Ingresa la llave del archivo a desencriptar");

    const formData = new FormData();
    formData.append("user_file", archivo);
    formData.append("user_key", archivoLlave);

    fetch('/desencriptar2', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: formData
    })
    .then(res => res.json())
    .then(data => {

        if (!data || !data.filename) {
            console.error("No filename in response:", data);
            return;
        }

        const linkDiv = document.getElementById('downloadDecrypted');
        const downloadUrl = `/downloads/${encodeURIComponent(data.filename)}`;

        linkDiv.innerHTML = `<a href="${downloadUrl}" download>Descargar archivo desencriptado</a>`;

    })
    .catch(err => console.error('Error:', err));
});
