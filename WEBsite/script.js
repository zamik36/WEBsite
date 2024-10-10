document.addEventListener('DOMContentLoaded', () => {
    // Функция для получения товаров из локального хранилища
    const getCartItems = () => JSON.parse(localStorage.getItem('cartItems')) || [];

    // Функция для сохранения товаров в локальное хранилище
    const saveCartItems = (items) => localStorage.setItem('cartItems', JSON.stringify(items));

    // Функция для обновления счетчика корзины
    const updateCartCounter = () => {
        const cartItems = getCartItems();
        const cartCounter = document.querySelector('.cart-counter');
        if (cartCounter) {
            cartCounter.textContent = cartItems.length;
        }
    };

    // Функция для добавления товара в корзину
    const addToCart = (item) => {
        const cartItems = getCartItems();
        if (!cartItems.some(cartItem => cartItem.title === item.title)) {
            cartItems.push(item);
            saveCartItems(cartItems);
            updateCartCounter();
            updateCartDisplay(); // Обновление отображения корзины после добавления товара
            updateTotalPrice(); // Обновление итоговой суммы после добавления товара
        }
    };

    // Функция для обновления отображения корзины
    const updateCartDisplay = () => {
        const cartItems = getCartItems();
        const cartList = document.querySelector('.cart-list');
        cartList.innerHTML = ''; // Очищаем список перед добавлением новых элементов
        cartItems.forEach(item => {
            const listItem = document.createElement('li');
            listItem.textContent = `${item.title} - ${item.price} ₽`;
            cartList.appendChild(listItem);
        });
    };

    // Функция для обновления итоговой суммы
    const updateTotalPrice = () => {
        const cartItems = getCartItems();
        let totalPrice = 0;
        cartItems.forEach(item => {
            totalPrice += parseFloat(item.price.replace(/\D/g, '')) || 0; // Преобразование цены в число и добавление к итоговой сумме
        });

        const totalPriceElement = document.getElementById('total-price');
        totalPriceElement.textContent = `Итоговая сумма: ${totalPrice} ₽`;
    };

    // Функция для очистки корзины
    const clearCart = () => {
        localStorage.removeItem('cartItems');
        updateCartCounter();
        updateCartDisplay();
        updateTotalPrice(); // Обновление итоговой суммы после очистки корзины
    };

    // Привязываем обработчики событий к кнопкам "Добавить в корзину"
    const addButtons = document.querySelectorAll('button[data-card]');
    addButtons.forEach(button => {
        button.addEventListener('click', (event) => {
            const card = event.target.closest('.card');
            const title = card.querySelector('.card-title').textContent.trim();
            const price = card.querySelector('.card-price-discount').textContent.trim();
            addToCart({ title, price });
        });
    });

    // Обновляем счетчик корзины и отображение при загрузке страницы
    updateCartCounter();
    updateCartDisplay();
    updateTotalPrice(); // Обновление итоговой суммы при загрузке страницы

    // Переключение отображения корзины при клике на кнопку "Корзина"
    const cartButton = document.querySelector('.cart-hover');
    cartButton.addEventListener('click', (event) => {
        event.preventDefault();
        const cartContainer = document.querySelector('.cart-container');
        cartContainer.classList.toggle('visible');
    });

    // Привязка обработчика события к кнопке "Очистить корзину"
    const clearCartButton = document.querySelector('.clear-cart-button');
    clearCartButton.addEventListener('click', () => {
        clearCart();
    });
});
