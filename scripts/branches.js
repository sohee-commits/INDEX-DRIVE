// Получение всех элементов с классом `map open-cars`
let mapsNodelist = document.querySelectorAll(`.map.open-cars`);

// Проход по каждому элементу в NodeList
mapsNodelist.forEach((mapNode) => {
	// Добавление обработчика события `click` к каждому элементу
	mapNode.addEventListener(`click`, () => {
		// Поиск элемента с классом `cars` внутри текущего `mapNode`
		const carsSection = mapNode.querySelector(`.cars`);
		// Переключение класса `hidden` у найденного элемента `cars`
		carsSection.classList.toggle(`hidden`);
	});
});
