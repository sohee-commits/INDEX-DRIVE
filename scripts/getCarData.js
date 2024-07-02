// Функция для форматирования числа с разделением разрядов
function numberFormat(number, decimals, decPoint, thousandsSep) {
	decimals = decimals || 0;
	decPoint = decPoint || `.`;
	thousandsSep = thousandsSep || `,`;

	let parts = number.toFixed(decimals).split(decPoint);
	parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, thousandsSep);

	return parts.join(decPoint);
}

// Получение элементов из DOM
let markSelect = document.querySelector(`#mark`);
let modelSelect = document.querySelector(`#model`);
let carIdElement = document.querySelector(`#car_id`);
let carNumberElement = document.querySelector(`#car_number`);
let carPriceElement = document.querySelector(`#car_price`);
let branchSelect = document.querySelector(`#branch`);
let dateStartInput = document.querySelector(`#date_start`);
let dateEndInput = document.querySelector(`#date_end`);
let nullOption = document.createElement(`option`);
nullOption.innerHTML = `Выбрать`;

// Обработчик события изменения выбора марки автомобиля
markSelect.addEventListener(`change`, function () {
	let selectedMark = markSelect.value;

	// Запрос на сервер для получения моделей автомобилей
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

			// Добавление вариантов моделей в выпадающий список
			data.forEach(function (model) {
				let option = document.createElement(`option`);
				option.value = model;
				option.textContent = model;
				modelSelect.appendChild(option);
			});

			modelSelect.appendChild(nullOption);
			nullOption.selected = true;

			// Обновление данных о выбранном автомобиле
			carIdElement.textContent = `-`;
			carNumberElement.textContent = `-`;
			carPriceElement.value = `-`;

			// Очистка списка филиалов
			branchSelect.innerHTML = ``;
		})
		.catch(function (error) {
			console.error(`Fetch error:`, error.message);
		});
});

// Обработчик события изменения выбора модели автомобиля
modelSelect.addEventListener(`change`, function () {
	let selectedMark = markSelect.value;
	let selectedModel = modelSelect.value;

	// Запрос на сервер для получения данных об автомобиле по выбранной марке и модели
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

			// Обновление данных об автомобиле
			carIdElement.value = data._car_id;
			carNumberElement.textContent = data.number;
			carPriceElement.value = numberFormat(data.price, 0, ``, ` `) + ` ₽`;

			// Запрос на сервер для получения списка филиалов, где доступен выбранный автомобиль
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

			// Обновление списка филиалов в выпадающем списке
			branchSelect.innerHTML = ``;

			branches.forEach(function (branch) {
				let option = document.createElement(`option`);
				option.value = branch._branch_id;
				option.textContent = branch.name;
				branchSelect.appendChild(option);
			});
		})
		.catch(function (error) {
			console.error(`Fetch error:`, error.message);
		});
});

// Функция для проверки доступности автомобиля на выбранные даты
function checkAvailability() {
	let carId = carIdElement.textContent;
	let branchId = branchSelect.value;
	let dateStart = dateStartInput.value;
	let dateEnd = dateEndInput.value;

	// Проверка наличия всех необходимых данных
	if (carId === `-` || branchId === `` || dateStart === `` || dateEnd === ``) {
		return;
	}

	// Запрос на сервер для проверки доступности автомобиля
	fetch(
		`./scripts/checkAvailability.php?car_id=` +
			encodeURIComponent(carId) +
			`&branch_id=` +
			encodeURIComponent(branchId) +
			`&date_start=` +
			encodeURIComponent(dateStart) +
			`&date_end=` +
			encodeURIComponent(dateEnd)
	)
		.then(function (response) {
			if (!response.ok) {
				throw new Error(`Network response was not ok`);
			}
			return response.json();
		})
		.then(function (data) {
			if (data.error) {
				console.log(data.error);
			} else {
				console.log(`Car is available for the selected dates.`);
			}
		})
		.catch(function (error) {
			console.error(`Fetch error:`, error.message);
		});
}

// Добавление обработчиков события изменения даты начала и окончания аренды
dateStartInput.addEventListener(`change`, checkAvailability);
dateEndInput.addEventListener(`change`, checkAvailability);
