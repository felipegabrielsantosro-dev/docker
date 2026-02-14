import { Validate } from "./Validate.js";
import { Requests } from "./Requests.js";

const Action = document.getElementById('acao');
const Id = document.getElementById('id');
const insertItemButton = document.getElementById('insertItemButton');


// =============================
// RELÓGIO
// =============================
function updateClock() {
    const now = new Date();

    const hours = String(now.getHours()).padStart(2, '0');
    const minutes = String(now.getMinutes()).padStart(2, '0');
    const seconds = String(now.getSeconds()).padStart(2, '0');

    const days = ['Domingo', 'Segunda-Feira', 'Terça-Feira', 'Quarta-Feira',
        'Quinta-Feira', 'Sexta-Feira', 'Sábado'];

    const months = ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho',
        'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'];

    const dayName = days[now.getDay()];
    const day = now.getDate();
    const month = months[now.getMonth()];
    const year = now.getFullYear();

    const timeElement = document.querySelector('.time');
    const dateElement = document.querySelector('.date');

    if (timeElement) {
        timeElement.textContent = `${hours}:${minutes}:${seconds}`;
    }

    if (dateElement) {
        dateElement.textContent = `${dayName}, ${day} De ${month} De ${year}`;
    }
}

setInterval(updateClock, 1000);
updateClock();


// =============================
// INSERIR VENDA (CRIAÇÃO)
// =============================
async function InsertSale() {

    const valid = Validate.SetForm('form').Validate();
    if (!valid) {
        Swal.fire({
            icon: 'error',
            title: 'Erro',
            text: 'Por favor, preencha os campos corretamente.'
        });
        return;
    }  

    try {

        const response = await Requests.SetForm('form').Post('/venda/insert');

        if (!response.status) {
            Swal.fire({
                icon: 'error',
                title: 'Erro',
                text: response.msg || 'Erro ao inserir venda.'
            });
            return;
        }

        // Define modo edição
        Action.value = 'e';
        Id.value = response.id;

        window.history.pushState({}, '', `/venda/alterar/${response.id}`);

        document.querySelector('.btn-finalize')?.classList.remove('d-none');
        document.querySelector('.btn-cancel')?.classList.remove('d-none');

        Swal.fire({
            icon: 'success',
            title: 'Venda iniciada com sucesso!'
        });

    } catch (error) {
        Swal.fire({
            icon: 'error',
            title: 'Erro',
            text: error.message || 'Erro inesperado.'
        });
    }
}


// =============================
// DOM READY
// =============================
document.addEventListener('DOMContentLoaded', () => {

    // =============================
    // BOTÃO INSERIR (F9)
    // =============================
    if (insertItemButton) {
        insertItemButton.addEventListener('click', async () => {
            await InsertSale();
        });
    }

    // =============================
    // BOTÃO FINALIZAR
    // =============================
    const finalizeButton = document.querySelector('.btn-finalize');
    if (finalizeButton) {
        finalizeButton.addEventListener('click', () => {

            if (!Id.value) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Nenhuma venda iniciada'
                });
                return;
            }

            Swal.fire({
                icon: 'success',
                title: 'Venda finalizada!'
            });

        });
    }

    // =============================
    // BOTÃO CANCELAR
    // =============================
    const cancelButton = document.querySelector('.btn-cancel');
    if (cancelButton) {
        cancelButton.addEventListener('click', () => {

            Swal.fire({
                title: 'Cancelar venda?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sim'
            }).then(result => {

                if (result.isConfirmed) {

                    Action.value = 'c';
                    Id.value = '';

                    window.history.pushState({}, '', `/venda`);

                    Swal.fire({
                        icon: 'success',
                        title: 'Venda cancelada!'
                    });
                }

            });

        });
    }

});


// =============================
// ATALHOS DE TECLADO
// =============================
document.addEventListener('keydown', (e) => {

    const modalEl = document.getElementById('pesquisaProdutoModal');
    const modalInstance = bootstrap.Modal.getOrCreateInstance(modalEl);

    if (e.key === 'F4') {
        e.preventDefault();
        modalInstance.show();
    }

    if (e.key === 'F8') {
        e.preventDefault();
        modalInstance.hide();
    }

    if (e.key === 'F9') {
        e.preventDefault();
        InsertSale();
    }

});


// =============================
// SELECT2
// =============================
$('#pesquisa').select2({
    theme: 'bootstrap-5',
    placeholder: "Selecione um produto",
    language: "pt-BR",
    ajax: {
        url: '/produto/listproductdata',
        type: 'POST'
    }
});

$('.form-select').on('select2:open', function () {
    let inputElement = document.querySelector('.select2-search__field');
    if (inputElement) {
        inputElement.placeholder = 'Digite para pesquisar...';
        inputElement.focus();
    }
});
