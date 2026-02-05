import Swal from "https://cdn.jsdelivr.net/npm/sweetalert2@11/+esm";

document.getElementById('salvar').addEventListener('click', () => {

    const form = document.getElementById('form');
    const formData = new FormData(form);

    const acao = formData.get('acao');
    let url = '/produto/insert';

    if (acao === 'e') {
        url = '/produto/update';
    }

    fetch(url, {
        method: 'POST',
        body: formData
    })
    .then(res => res.json ? res.json() : res.text())
    .then(data => {

        if (data.status === false) {
            Swal.fire('Erro', data.msg, 'error');
            return;
        }

        Swal.fire({
            icon: 'success',
            title: 'Sucesso',
            text: data.msg ?? 'Salvo com sucesso!',
            confirmButtonText: 'OK'
        }).then(() => {
            window.location.href = '/produto/lista';
        });

    })
    .catch(() => {
        Swal.fire('Erro', 'Erro ao salvar produto', 'error');
    });

});
