 // Função para mostrar ou ocultar o campo de valor em dinheiro dependendo da forma de pagamento selecionada, caso o usuario clica em dinheiro 
 
document.getElementById('formaPagamento').addEventListener('change', function() {
    var formaPagamento = this.value;
    if (formaPagamento === 'dinheiro') {
        document.getElementById('campoDinheiro').style.display = 'block';
    } else {
        document.getElementById('campoDinheiro').style.display = 'none';
    }
});
