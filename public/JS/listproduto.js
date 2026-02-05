import Swal from "https://cdn.jsdelivr.net/npm/sweetalert2@11/+esm";

let tabela = $('#tabela').DataTable({
    processing: true,
    serverSide: true,
    ajax: {
        url: '/produto/listproduto',
        type: 'POST'
    },
    language: {
        url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/pt-BR.json'
    },
    columnDefs: [
        { orderable: false, targets: -1 } // ações
    ]
});

/** 🔥 DELETE PRODUTO */
window.Delete = function (id) {
    Swal.fire({
        title: 'Tem certeza?',
        text: 'Este produto será removido permanentemente!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sim, excluir',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch('/produto/delete', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `id=${id}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.status) {
                    Swal.fire(
                        'Excluído!',
                        data.msg,
                        'success'
                    );
                    tabela.ajax.reload(null, false);
                } else {
                    Swal.fire(
                        'Erro!',
                        data.msg,
                        'error'
                    );
                }
            })
            .catch(() => {
                Swal.fire(
                    'Erro!',
                    'Não foi possível excluir o produto.',
                    'error'
                );
            });
        }
    });
};
