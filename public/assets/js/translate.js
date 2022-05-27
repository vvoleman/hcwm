const BTN_TRANSLATE = 'translate-text'

const buttons = document.querySelectorAll(`.${BTN_TRANSLATE}`)

async function translate(item_id, language, element) {
	// ADD AJAX

	setLoading(element, true)
	element.attributes.disabled = true;
	const p = element.parentNode.parentNode.querySelector('.abstract')

	const URL = `${AJAX_URL}?item=${item_id}&language=${language}&_csrf=${_CSRF}`
	const response = await fetch(URL)
	const data = await response.json()

	if (response.ok) {
		p.textContent = data.data.translation
		element.attributes.disabled = false
		setLoading(element, true)
	}

}

function setLoading(element, display){

}

for (const button of buttons) {
	button.addEventListener('click', async (e)=>{
		const element = e.target;
		const select = e.target.parentNode.querySelector('select[data-id]')
		const item_id = select.dataset.id
		const language = select.value

		await translate(item_id, language, element)
	})
}
