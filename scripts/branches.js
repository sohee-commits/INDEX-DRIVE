let mapsNodelist = document.querySelectorAll('.map.open-cars');

mapsNodelist.forEach((mapNode) => {
	mapNode.addEventListener('click', () => {
		const carsSection = mapNode.querySelector('.cars');
		carsSection.classList.toggle('hidden');
	});
});
