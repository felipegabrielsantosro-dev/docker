const forma = document.getElementById('forma_pagamento');
const grupoParcelas = document.getElementById('grupoParcelas');
const grupoValorParcela = document.getElementById('grupoValorParcela');
const valor = document.getElementById('valor');
const parcelas = document.getElementById('parcelas');
const valorParcela = document.getElementById('valor_parcela');

forma.addEventListener('change', () => {
    if (forma.value === 'credito' || forma.value === 'boleto') {
        grupoParcelas.classList.remove('d-none');
        grupoValorParcela.classList.remove('d-none');
    } else {
        grupoParcelas.classList.add('d-none');
        grupoValorParcela.classList.add('d-none');
        parcelas.value = 1;
        valorParcela.value = '';
    }
});

function calcularParcela() {
    if (valor.value && parcelas.value) {
        valorParcela.value = 
            (parseFloat(valor.value) / parseInt(parcelas.value))
            .toFixed(2)
            .replace('.', ',');
    }
}

valor.addEventListener('input', calcularParcela);
parcelas.addEventListener('change', calcularParcela);
