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

        // Build download URL pointing to the new controller route
        const downloadUrl = `/downloads/${encodeURIComponent(data.filename)}`;
        const downloadUrlKey = `/downloads/${encodeURIComponent(data.key)}`;

        linkDiv.innerHTML = `<a href="${downloadUrl}" download>Descargar archivo</a>`;
        document.getElementById("generatedKey").innerHTML = `<a href="${downloadUrlKey}" download>Descargar llave</a>`;

    })
    .catch(err => console.error('Error:', err));
});
document.getElementById("decryptBtn").addEventListener("click", () => {
    let encrypted = document.getElementById("inputEncrypted").value;
    let keyStr = document.getElementById("inputKey").value;

    if (!encrypted || !keyStr) return alert("Introduce el texto y la llave");

    fetch('/desencriptar', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            texto: encrypted,
            key: keyStr
        })
    })
    .then(res => res.json())
    .then(data => {
        document.getElementById("decryptedText").value = atob(data.resultados);
    })
    .catch(err => console.error('Error:', err));
});
