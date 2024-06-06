 // Adicione aqui o código JavaScript necessário para manipular as quantidades e calcular o preço total
 function increment(productName) {
    var quantityElement = document.getElementById("quantity_" + productName);
    var quantityInput = document.getElementById("quantity_hidden");
    var currentValue = parseInt(quantityElement.innerText);
    quantityElement.innerText = currentValue + 1;
    quantityInput.value = currentValue + 1;
    updateTotalPrice(productName);
  }

  function decrement(productName) {
    var quantityElement = document.getElementById("quantity_" + productName);
    var quantityInput = document.getElementById("quantity_hidden");
    var currentValue = parseInt(quantityElement.innerText);
    if (currentValue > 0) {
      quantityElement.innerText = currentValue - 1;
      quantityInput.value = currentValue - 1;
      updateTotalPrice(productName);
    }
  }

  function updateTotalPrice(productName) {
    var precoUnitario = parseFloat(document.getElementById('value_' + productName).value);
    var quantidade = parseInt(document.getElementById('quantity_' + productName).innerText);
    var total = precoUnitario * quantidade;
    document.getElementById('totalPrice_' + productName).innerText = 'R$ ' + total.toFixed(2);

    // Atualiza o valor total no botão "Adicionar ao Carrinho"
    var addToCartButton = document.getElementById('addToCartButton');
    if (addToCartButton) {
      var buttonText = `Adicionar ao Carrinho Por: R$ ${total.toFixed(2)}`;
      addToCartButton.textContent = buttonText;
    }

    // Atualiza o valor de totalPriceInput quando totalPriceDisplay é alterado
    var totalPriceInput = document.getElementById('totalPrice_hidden');
    totalPriceInput.value = total.toFixed(2);
  }

  function calculateTotal() {
    var totalPrice = 0;
    document.querySelectorAll('[id^="totalPrice_"]').forEach(function(element) {
      totalPrice += parseFloat(element.innerText.replace('R$ ', ''));
    });

    // Atualiza o valor total no botão "Adicionar ao Carrinho"
    var addToCartButton = document.getElementById('addToCartButton');
    if (addToCartButton) {
      var buttonText = `Adicionar ao Carrinho Por: R$ ${totalPrice.toFixed(2)}`;
      addToCartButton.textContent = buttonText;
    }

    // Atualiza o valor de totalPriceInput quando totalPriceDisplay é alterado
    var totalPriceInput = document.getElementById('totalPrice_hidden');
    totalPriceInput.value = totalPrice.toFixed(2);
  }