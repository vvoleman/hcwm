// map options
var options = {
	center: [38, -95],
	zoom: 4
}

// create a Leaflet map in our division container with id of 'map'
var map = L.map('symbolMap', options);

// Leaflet providers base map URL
var basemap_source =
	'https://cartodb-basemaps-{s}.global.ssl.fastly.net/rastertiles/dark_all/{z}/{x}/{y}.png'

// Leaflet providers attributes
var basemap_options = {
	attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a> &copy; <a href="http://cartodb.com/attributions">CartoDB</a>',
	subdomains: 'abcd',
	maxZoom: 19
};

// request some basemap tiles and add to the map
var tiles = L.tileLayer(basemap_source, basemap_options).addTo(map);

const points = await fetch()

plants.features.sort(function (a, b) {
	return b.properties.capacity_mw - a.properties.capacity_mw;
});



// var popup = '<h3>' + hotspots[i].name + '</h3>'
// Load power plants
L.geoJson(plants, {
	filter: function (feature) {
		if (feature.properties.fuel_source.Hydro) { // This test to see if the key exits
			return feature;
		}
	},
	pointToLayer: function (feature, latlng) {
		return L.circleMarker(latlng, {
			color: 'darkblue',
			weight: 1,
			fillColor: 'blue',
			fillOpacity: 0.3,
			radius: getRadius(feature.properties.capacity_mw)
		});
	},
	onEachFeature: function (feature, layer) {
		var popup =
			'<p><b>' + layer.feature.properties.plant_name + '</b></p>' +
			'<p>Max capacity: ' + layer.feature.properties.capacity_mw + ' MW</p>' +
			'<p>Hydro: ' + layer.feature.properties.fuel_source.Hydro + ' MW</p>'

		layer.on('mouseover', function () {
			layer.bindPopup(popup).openPopup();
			layer.setStyle({
				fillColor: 'yellow',
				fillOpacity: 1
			});
		});

		layer.on('mouseout', function () {
			layer.setStyle({
				fillColor: 'blue',
				fillOpacity: 0.3
			});
			layer.bindPopup(popup).closePopup();
		})

	}
}).addTo(map);

L.geoJson(plants, {
	filter: function (feature) {
		if (feature.properties.fuel_source.Biomass) { // This test to see if the key exits
			return feature;
		}
	},
	pointToLayer: function (feature, latlng) {
		return L.circleMarker(latlng, {
			color: 'darkbrown',
			weight: 1,
			fillColor: 'brown',
			fillOpacity: 0.3,
			radius: getRadius(feature.properties.capacity_mw)
		});
	},
	onEachFeature: function (feature, layer) {
		var popup =
			'<p><b>' + layer.feature.properties.plant_name + '</b></p>' +
			'<p>Max capacity: ' + layer.feature.properties.capacity_mw + ' MW</p>' +
			'<p>Biomass: ' + layer.feature.properties.fuel_source.Biomass + ' MW</p>'

		layer.on('mouseover', function () {
			layer.bindPopup(popup).openPopup();
			layer.setStyle({
				fillColor: 'yellow',
				fillOpacity: 1
			});
		});

		layer.on('mouseout', function () {
			layer.setStyle({
				fillColor: 'brown',
				fillOpacity: 0.3
			});
			layer.bindPopup(popup).closePopup();
		})

	}
}).addTo(map);

function getRadius(area) {
	var radius = Math.sqrt(area / Math.PI);
	return radius * 0.6;
};