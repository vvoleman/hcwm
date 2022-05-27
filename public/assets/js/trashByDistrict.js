const trashes = {
	'F180101': 'Ostré předměty',
	'F180102': 'Části těla a orgány včetně krevních vaků a krevních konzerv',
	'F180103': 'Odpady, na jejichž sběr a odstraňování jsou kladeny zvláštní požadavky s ohledem na prevenci infekce',
	'F180104': 'Odpady, na jejichž sběr a odstraňování nejsou kladeny zvláštní požadavky s ohledem na prevenci infekce',
	'F180106': 'Chemikálie, které jsou nebo obsahují nebezpečné látky',
	'F180107': 'Chemikálie neuvedené pod číslem 18 01 06',
	'F180108': 'Nepoužitelná cytostatika',
	'F180109': 'Jiná nepoužitelná léčiva neuvedená pod číslem 18 01 08',
	'F180110': 'Odpadní amalgám ze stomatologické péče'
}

function simpleHash (str, seed=0) {
	let h1 = 0xdeadbeef ^ seed, h2 = 0x41c6ce57 ^ seed;
	for (let i = 0, ch; i < str.length; i++) {
		ch = str.charCodeAt(i);
		h1 = Math.imul(h1 ^ ch, 2654435761);
		h2 = Math.imul(h2 ^ ch, 1597334677);
	}
	h1 = Math.imul(h1 ^ (h1>>>16), 2246822507) ^ Math.imul(h2 ^ (h2>>>13), 3266489909);
	h2 = Math.imul(h2 ^ (h2>>>16), 2246822507) ^ Math.imul(h1 ^ (h1>>>13), 3266489909);
	return 4294967296 * (2097151 & h2) + (h1>>>0);
}

function stringToColor(str) {
	const hash = simpleHash(str)

	let color = '#';
	let i;
	for (i = 0; i < 3; i++) {
		const value = (hash >> (i * 8)) & 0xFF;
		color += ('00' + value.toString(16)).substr(-2);
	}
	return color;
}

const data = {
	labels: [],
	datasets:[]
};

const config = {
	type: 'bar',
	data: data,
	options: {
		scales: {
			y: {
				ticks: {
					// Include a dollar sign in the ticks
					callback: function(value, index, ticks) {
						return value + ' t';
					}
				}
			}
		},
		tooltips: {
			callbacks: {
				label: (item) => `${item.formattedValue} t`,
			},
		},
	}
};

const trashByDistrict = new Chart(
	document.getElementById('trashByDistrict'),
	config
);

const selectDistrict = document.getElementById('selectDistrict')
$(selectDistrict).select2();

$(selectDistrict).on('select2:select', async (e) => {
	const value = e.target.value
	if(value === '') return;
	const selectDistrictURL = `${TRASH_URL}?district_id=${value}&_csrf=${CSRF}`
	const response = await fetch(selectDistrictURL)
	const json = await response.json()

	const keys = Object.keys(json.data.records)

	let datasets = []

	for (const key of keys) {
		datasets.push({
			label: key,
			backgroundColor: stringToColor(trashes[key]),
			data: Object.values(json.data.records[key])
		})
	}
	trashByDistrict.data.datasets = datasets
	trashByDistrict.data.labels = Object.keys(json.data.records[keys[0]]);
	trashByDistrict.update()

})