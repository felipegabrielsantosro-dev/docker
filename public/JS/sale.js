// Atualizar relógio em tempo real
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
// Atualizar a cada segundo
setInterval(updateClock, 1000);
updateClock();
// Event Listeners para botões de adicionar
document.addEventListener('DOMContentLoaded', function () {
    // Botões de adicionar produto
    const addButtons = document.querySelectorAll('.btn-add');
    addButtons.forEach(button => {
        button.addEventListener('click', function () {
            const row = this.closest('tr');
            const code = row.cells[0].textContent;
            const description = row.cells[1].textContent;
            const priceText = row.cells[2].textContent;
            const price = parseFloat(priceText.replace('R$', '').replace(',', '.').trim());

            addToCart(code, description, price);

            // Feedback visual
            this.style.transform = 'scale(0.95)';
            setTimeout(() => {
                this.style.transform = '';
            }, 100);
        });
    });

    // Botões de método de pagamento
    const paymentButtons = document.querySelectorAll('.payment-btn');
    paymentButtons.forEach(button => {
        button.addEventListener('click', function () {
            paymentButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');

            const method = this.querySelector('span').textContent.toLowerCase();
            paymentMethod = method;
        });
    });

    // Campo de desconto em valor
    const discountInputRs = document.querySelector('.discount-input-rs');
    if (discountInputRs) {
        discountInputRs.addEventListener('click', function () {
            discount.type = 'valor';
            updateInputStyles();
        });
    }

    // Campo de desconto em porcentagem
    const discountInputPercent = document.querySelector('.discount-input-percent');
    if (discountInputPercent) {
        discountInputPercent.addEventListener('click', function () {
            discount.type = 'percentual';
            updateInputStyles();
        });
    }

    // Valor do desconto
    const discountValue = document.querySelector('.discount-value');
    if (discountValue) {
        discountValue.addEventListener('input', function () {
            discount.amount = parseFloat(this.value) || 0;
            updateTotals();
        });
    }

    // Botão de buscar
    const searchButton = document.querySelector('.btn-search');
    const searchInput = document.querySelector('.search-input');

    if (searchButton) {
        searchButton.addEventListener('click', function () {
            const searchTerm = searchInput.value.toLowerCase();
            filterProducts(searchTerm);
        });
    }

    if (searchInput) {
        searchInput.addEventListener('keypress', function (e) {
            if (e.key === 'Enter') {
                const searchTerm = this.value.toLowerCase();
                filterProducts(searchTerm);
            }
        });
    }

    // Botão finalizar venda
    const finalizeButton = document.querySelector('.btn-finalize');
    if (finalizeButton) {
        finalizeButton.addEventListener('click', function () {
            if (cart.length === 0) {
                alert('Carrinho vazio! Adicione produtos antes de finalizar.');
                return;
            }

            const total = document.querySelector('.total-amount').textContent;
            const confirmation = confirm(`Finalizar venda no valor de ${total}?`);

            if (confirmation) {
                alert('Venda finalizada com sucesso!');
                cart = [];
                discount = { type: 'valor', amount: 0 };
                document.querySelector('.discount-value').value = '0';
                updateCart();
            }
        });
    }

    // Botão cancelar venda
    const cancelButton = document.querySelector('.btn-cancel');
    if (cancelButton) {
        cancelButton.addEventListener('click', function () {
            if (cart.length === 0) {
                return;
            }

            const confirmation = confirm('Deseja cancelar a venda atual?');

            if (confirmation) {
                cart = [];
                discount = { type: 'valor', amount: 0 };
                document.querySelector('.discount-value').value = '0';
                updateCart();
                alert('Venda cancelada!');
            }
        });
    }
});
// Atalhos de teclado
document.addEventListener('keydown', function (e) {
    // F2 - Focar no campo de busca
    if (e.key === 'F2') {
        e.preventDefault();
        document.querySelector('.search-input')?.focus();
    }
    // F9 - Finalizar venda
    if (e.key === 'F9') {
        e.preventDefault();
        document.querySelector('.btn-finalize')?.click();
    }
    // Esc - Cancelar venda
    if (e.key === 'Escape') {
        e.preventDefault();
        document.querySelector('.btn-cancel')?.click();
    }
});
// Feedback visual para cliques
document.addEventListener('click', function (e) {
    if (e.target.matches('button')) {
        e.target.style.transition = 'transform 0.1s';
    }
});

document.addEventListener('keydown', (e) => {
    //Fechamos o modal com a tecla F3
    if (e.key === 'F8') {
        const myModalEl = document.getElementById('pesquisaProdutoModal');
        const modal = bootstrap.Modal.getInstance(myModalEl);
        modal.hide();
    }
});

$("#pesquisa").select2({
    theme: "bootstrap-5",
    placeholder: "Selecione um produto",
    ajax: {
        url: "/produto/listproductdata",
        type: "POST",
        delay: 250
    }
    
});
// Array para armazenar produtos do carrinho
let cart = [];

// Função para adicionar produto ao carrinho
function addToCart(button) {
    const row = button.closest('tr');
    const product = {
        id: row.dataset.id,
        name: row.dataset.name,
        price: parseFloat(row.dataset.price),
        quantity: 1
    };

    // Verifica se o produto já está no carrinho
    const existing = cart.find(p => p.id === product.id);
    if (existing) {
        existing.quantity += 1;
    } else {
        cart.push(product);
    }

    updateCart();
}

// Atualiza o carrinho na interface
function updateCart() {
    const cartSection = document.querySelector('.cart-section');
    const cartEmpty = cartSection.querySelector('.cart-empty');
    const cartItems = cartSection.querySelector('.cart-items');

    // Remove itens antigos se houver
    if (cartItems) cartItems.remove();

    if (cart.length === 0) {
        cartEmpty.style.display = 'flex';
    } else {
        cartEmpty.style.display = 'none';

        const table = document.createElement('table');
        table.classList.add('cart-items');
        table.innerHTML = `
            <thead>
                <tr>
                    <th>Produto</th>
                    <th>Qtd</th>
                    <th>Preço</th>
                    <th>Total</th>
                    <th>Ação</th>
                </tr>
            </thead>
            <tbody>
                ${cart.map(item => `
                    <tr data-id="${item.id}">
                        <td>${item.name}</td>
                        <td>${item.quantity}</td>
                        <td>R$ ${item.price.toFixed(2)}</td>
                        <td>R$ ${(item.price * item.quantity).toFixed(2)}</td>
                        <td>
                            <button class="btn btn-danger btn-sm" onclick="removeFromCart('${item.id}')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                `).join('')}
            </tbody>
        `;
        cartSection.insertBefore(table, cartSection.querySelector('.payment-section'));
    }

    updateTotals();
}

// Remove produto do carrinho
function removeFromCart(id) {
    cart = cart.filter(item => item.id !== id);
    updateCart();
}

// Atualiza subtotal e total
function updateTotals() {
    let subtotal = cart.reduce((acc, item) => acc + item.price * item.quantity, 0);
    const subtotalElem = document.querySelector('.subtotal .amount');
    const totalElem = document.querySelector('.total .total-amount');

    subtotalElem.textContent = `R$ ${subtotal.toFixed(2)}`;
    totalElem.textContent = `R$ ${subtotal.toFixed(2)}`;
}

// Opcional: Botão Finalizar Venda
document.querySelector('.btn-finalize').addEventListener('click', () => {
    if(cart.length === 0){
        alert("Carrinho vazio!");
        return;
    }
    console.log("Venda finalizada:", cart);
    alert("Venda finalizada com sucesso!");
    cart = [];
    updateCart();
});
