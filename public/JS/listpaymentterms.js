import { Requests } from "./Requests.js";

const tabela = $('#tabela').DataTable({
    paging: true,
    lengthChange: true,
    searching: true,
    ordering: true,
    info: true,
    autoWidth: false,
    responsive: true,
    stateSave: true,
    processing: true,
    serverSide: true,
    select: true,

    language: {
        url: 'https://cdn.datatables.net/plug-ins/1.13.4/i18n/pt-BR.json',
        searchPlaceholder: 'Digite sua pesquisa...'
    },

    ajax: {
        url: '/paymentterms/lista-json',
        type: 'POST'
    },

    columns: [
        {
            data: 'id',
            name: 'id',
            width: '60px'
        },
        {
            data: 'valor',
            name: 'valor',
            className: 'text-end',
            render: data => `R$ ${Number(data).toFixed(2)}`
        },
        {
            data: 'parcelas',
            name: 'parcelas',
            className: 'text-center'
        },
        {
            data: 'data',
            name: 'data',
            render: data =>
                new Date(data).toLocaleDateString('pt-BR')
        },
        {
            data: 'status',
            name: 'status',
            className: 'text-center',
            render: data => `
                <span class="badge ${data ? 'bg-success' : 'bg-danger'}">
                    ${data ? 'Ativo' : 'Inativo'}
                </span>
            `
        },
        {
            data: 'id',
            orderable: false,
            searchable: false,
            className: 'text-center',
            render: id => `
                <button class="btn btn-sm btn-primary me-1" onclick="Editar(${id})">
                    <i class="fas fa-edit"></i>
                </button>
                <button class="btn btn-sm btn-danger" onclick="Delete(${id})">
                    <i class="fas fa-trash"></i>
                </button>
            `
        }
    ]
});


// ================= DELETE =================
async function Delete(id) {
    document.getElementById('id').value = id;

    const response = await Requests
        .SetForm('form')
        .Post('/paymentterms/delete');

    if (!response.status) {
        Swal.fire({
            title: "Erro ao remover!",
            icon: "error",
            html: response.msg,
            timer: 1200,
            timerProgressBar: true
        });
        return;
    }

    Swal.fire({
        title: "Removido com sucesso!",
        icon: "success",
        html: response.msg,
        timer: 1000,
        timerProgressBar: true
    });

    tabela.ajax.reload(null, false);
}

// ================= EDITAR =================
function Editar(id) {
    window.location.href = `/paymentterms/alterar/${id}`;
}

// expõe para o HTML
window.Delete = Delete;
window.Editar = Editar;
