document.querySelector(`#cards`).addEventListener(`change`, () => {
	// Получаем текущий номер карты
	let cardNumber = document.querySelector(`#cards`).value;

	// Проверяем начало номера карты и устанавливаем соответствующий тип и статус
	if (cardNumber.startsWith(`1111`)) {
		document.querySelector(`#card_type`).innerText = `Visa`; // Устанавливаем тип карты Visa
		document.querySelector(`#card_status`).innerText = `Активна`; // Устанавливаем статус "Активна"
	} else if (cardNumber.startsWith(`2222`)) {
		document.querySelector(`#card_type`).innerText = `MasterCard`; // Устанавливаем тип карты MasterCard
		document.querySelector(`#card_status`).innerText = `Активна`; // Устанавливаем статус "Активна"
	} else if (cardNumber.startsWith(`3333`)) {
		document.querySelector(`#card_type`).innerText = `Мир`; // Устанавливаем тип карты "Мир"
		document.querySelector(`#card_status`).innerText = `Активна`; // Устанавливаем статус "Активна"
	} else {
		document.querySelector(`#card_type`).innerText = `-`; // Если номер карты не соответствует известным префиксам, устанавливаем тип "-"
		document.querySelector(`#card_status`).innerText = `-`; // Устанавливаем статус "-"
	}
});
