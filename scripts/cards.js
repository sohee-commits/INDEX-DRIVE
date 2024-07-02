// Получение элемента <dialog> из DOM
let dialog = document.querySelector(`dialog`);

// Получение кнопки для открытия диалога по её ID
let openDialogBtn = document.querySelector(`#add-card`);

// Добавление обработчика события 'click' на кнопку открытия диалога
openDialogBtn.addEventListener(`click`, () => {
	// Отображение диалогового окна
	dialog.showModal();
});
