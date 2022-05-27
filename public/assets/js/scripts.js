$(document).ready(function() {
	$('.select2-multiple').select2();

	$('.select2-multiple-flags').select2({
		templateResult: (state)=>{
			let element = state.element
			let img = "";
			if(element){
				let flag = element.dataset.flag
				let name = element.dataset.name

				if(flag && name){
					img = `<img width="20px" src="${element.dataset.flag}" alt="${element.dataset.name}" />`;
				}
			}
			console.log(state);

			return $(`<span>${img}${state.text}</span>`);
		}
	});
});