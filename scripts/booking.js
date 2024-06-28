document.querySelector(`#cards`).addEventListener(`change`, () => {
	let cardNumber = document.querySelector(`#cards`).value;

	if (cardNumber.startsWith('1111')) {
		document.querySelector('#card_type').innerText = 'Visa';
		document.querySelector('#card_status').innerText = 'Активна';
	} else if (cardNumber.startsWith('2222')) {
		document.querySelector('#card_type').innerText = 'MasterCard';
		document.querySelector('#card_status').innerText = 'Активна';
	} else if (cardNumber.startsWith('3333')) {
		document.querySelector('#card_type').innerText = 'Мир';
		document.querySelector('#card_status').innerText = 'Активна';
	} else {
		document.querySelector('#card_type').innerText = '-';
		document.querySelector('#card_status').innerText = '-';
	}
});
