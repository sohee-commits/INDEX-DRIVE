function numberFormat(number, decimals, decPoint, thousandsSep) {
	decimals = decimals || 0;
	decPoint = decPoint || `.`;
	thousandsSep = thousandsSep || `,`;

	let parts = number.toFixed(decimals).split(decPoint);
	parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, thousandsSep);

	return parts.join(decPoint);
}

let markSelect = document.getElementById(`mark`);
let modelSelect = document.getElementById(`model`);
let carIdElement = document.getElementById(`car_id`);
let carNumberElement = document.getElementById(`car_number`);
let carPriceElement = document.getElementById(`car_price`);
let branchSelect = document.getElementById(`branch`);
let nullOption = document.createElement(`option`);
nullOption.innerHTML = `Выбрать`;

markSelect.addEventListener(`change`, function () {
	let selectedMark = markSelect.value;

	fetch(`./scripts/getCarData.php?mark=` + encodeURIComponent(selectedMark))
		.then(function (response) {
			if (!response.ok) {
				throw new Error(`Network response was not ok`);
			}
			return response.json();
		})
		.then(function (data) {
			console.log(`Models received:`, data);
			modelSelect.innerHTML = ``;

			data.forEach(function (model) {
				let option = document.createElement(`option`);
				option.value = model;
				option.textContent = model;
				modelSelect.appendChild(option);
			});

			modelSelect.appendChild(nullOption);
			nullOption.selected = true;

			carIdElement.textContent = `-`;
			carNumberElement.textContent = `-`;
			carPriceElement.textContent = `-`;

			branchSelect.innerHTML = ``;
		})
		.catch(function (error) {
			console.error(`Fetch error:`, error.message);
		});
});

modelSelect.addEventListener(`change`, function () {
	let selectedMark = markSelect.value;
	let selectedModel = modelSelect.value;

	fetch(
		`./scripts/getCarData.php?mark=` +
			encodeURIComponent(selectedMark) +
			`&model=` +
			encodeURIComponent(selectedModel)
	)
		.then(function (response) {
			if (!response.ok) {
				throw new Error(`Network response was not ok`);
			}
			return response.json();
		})
		.then(function (data) {
			console.log(`Car details received:`, data);

			carIdElement.textContent = data._car_id;
			carNumberElement.textContent = data.number;
			carPriceElement.textContent = numberFormat(data.price, 0, ``, ` `) + ` ₽`;

			return fetch(
				`./scripts/getBranches.php?car_id=` + encodeURIComponent(data._car_id)
			);
		})
		.then(function (response) {
			if (!response.ok) {
				throw new Error(`Network response was not ok`);
			}
			return response.json();
		})
		.then(function (branches) {
			console.log(`Branches received:`, branches);

			branchSelect.innerHTML = ``;

			branches.forEach(function (branchName) {
				let option = document.createElement(`option`);
				option.value = branchName;
				option.textContent = branchName;
				branchSelect.appendChild(option);
			});
		})
		.catch(function (error) {
			console.error(`Fetch error:`, error.message);
		});
});
