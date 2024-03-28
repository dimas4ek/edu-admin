 // Добавляем обработчик события клика на кнопку
    document.querySelectorAll('.details-btn').forEach(button => {
    button.addEventListener('click', function() {
        const details = this.parentElement.nextElementSibling; // Находим следующий элемент после кнопки (блок с подробностями)
        details.style.display = details.style.display === 'none' ? 'block' : 'none'; // Переключаем отображение блока
    });
});
