document.getElementById("encryptBtn").addEventListener("click", () => {
    let text = document.getElementById("inputText").value;
    if (!text) return alert("Escribe un texto para encriptar");

    fetch('/encriptar', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ texto: text })
    })
    .then(res => res.json())
    .then(data => {
        document.getElementById("encryptedText").value = data.resultados;
        document.getElementById("generatedKey").innerText = data.key.join('');
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
