function fetchCardDetails() {
	var cardNumber = document.getElementById('cards').value;

	if (cardNumber.startsWith('1111')) {
		document.getElementById('card_type').innerText = 'Visa';
	} else if (cardNumber.startsWith('2222')) {
		document.getElementById('card_type').innerText = 'MasterCard';
	} else if (cardNumber.startsWith('3333')) {
		document.getElementById('card_type').innerText = 'Мир';
	} else {
		document.getElementById('card_type').innerText = '-';
	}

	var xhr = new XMLHttpRequest();
	xhr.open('POST', 'fetch_card_status.php', true);
	xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	xhr.onreadystatechange = function () {
		if (xhr.readyState === 4 && xhr.status === 200) {
			document.getElementById('card_status').innerText = xhr.responseText;
		}
	};
	xhr.send('cardNumber=' + cardNumber);
}
