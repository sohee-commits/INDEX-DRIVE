let dialog = document.querySelector(`dialog`);
let openDialogBtn = document.querySelector(`#add-card`);

openDialogBtn.addEventListener(`click`, () => {
	dialog.showModal();
});
