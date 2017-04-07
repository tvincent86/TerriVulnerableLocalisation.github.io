<!DOCTYPE html>
<html lang="fr">
  <head>
    <title>Carte avec Leaflet</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	
	<style>
	    body {
		    padding: 0;
		    margin: 0;
		    //background-color :#435a6b;
		    background-color :#fff;
	    }
	    html, body {
		    height: 100%;
	    }		    
	    #map {
		    width:500px; 
		    height:580px; 
	    }
	</style>
	
	<!--Leaflet
		-->
	<link rel="stylesheet" href="https://unpkg.com/leaflet@1.0.3/dist/leaflet.css" />	
	<script src="https://unpkg.com/leaflet@1.0.3/dist/leaflet.js"></script>

	<!--Leaflet
	<link rel="stylesheet" type="text/css" href="./lib/leaflet-0.7.3/leaflet.css" />
	<!--JS Leaflet
	<script src="./lib/leaflet-0.7.3/leaflet.js"></script>
	-->
	<!--Leaflet-Plugin-->
	
	<!--Leaflet.NavBar : https://github.com/davidchouse/Leaflet.NavBar-->
	<link rel="stylesheet" type="text/css" href="./lib/leaflet-plugin/NavBar/Leaflet.NavBar.css" />
	<script src="./lib/leaflet-plugin/NavBar/Leaflet.NavBar.js" type="text/javascript"></script>
	<!--Leaflet.MousePosition : https://github.com/ardhi/Leaflet.MousePosition-->
	<link rel="stylesheet" type="text/css" href="./lib/leaflet-plugin/MousePosition/L.Control.MousePosition.css" />
	<script src="./lib/leaflet-plugin/MousePosition/L.Control.MousePosition.js" type="text/javascript"></script>
	<!--Leaflet.MiniMap : https://github.com/Norkart/Leaflet-MiniMap-->
	<link rel="stylesheet" type="text/css" href="./lib/leaflet-plugin/MiniMap/Control.MiniMap.css" />
	<script src="./lib/leaflet-plugin/MiniMap/Control.MiniMap.js" type="text/javascript"></script>
	<!--Leaflet.GroupedLayerControl : https://github.com/ismyrnow/leaflet-groupedlayercontrol-->
	<link rel="stylesheet" type="text/css" href="./lib/leaflet-plugin/GroupedLayerControl/leaflet.groupedlayercontrol.min.css" />
	<script src="./lib/leaflet-plugin/GroupedLayerControl/leaflet.groupedlayercontrol.min.js"></script>
	<!--Leaflet.Ajax : https://github.com/calvinmetcalf/leaflet-ajax-->
	<script src="./lib/leaflet-plugin/LeafletAjax/leaflet.ajax.js"></script>
	<!--Leaflet.Ajax : https://github.com/calvinmetcalf/leaflet-ajax-->
	<link rel="stylesheet" href="./lib/leaflet-plugin/Geocoder/Control.Geocoder.css" />
	<script src="./lib/leaflet-plugin/Geocoder/Control.Geocoder.js"></script>
	
	<script>
	    // Fonction qui contient l'aplicatino
	    function init(){
		    
		var map = new L.Map('map', {
			center: [45.00, 0.35],
			zoom: 7
		});
			
		//MapQuest
		var MapQuestOpen_OSM = L.tileLayer('http://otile{s}.mqcdn.com/tiles/1.0.0/map/{z}/{x}/{y}.jpeg', {
		    //attribution: 'Tiles Courtesy of <a href="http://www.mapquest.com/">MapQuest</a> &mdash; Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>',
		    subdomains: '1234'
		}).addTo(map);
		
		// MapBox
		var mapbox   = L.tileLayer('https://b.tiles.mapbox.com/v3/examples.c7d2024a/{z}/{x}/{y}.png', {
		    //attribution: 'Map tiles by <a href="http://www.mapbox.com/">MapBox</a> &mdash; Map data &copy; <a href="https://osm.org/copyright">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>'
		}).addTo(map);
		
		var stamenLayer = L.tileLayer('https://stamen-tiles-{s}.a.ssl.fastly.net/toner/{z}/{x}/{y}.png', {
		    //attribution: 'Map tiles by <a href="http://stamen.com">Stamen Design</a>, under <a href="http://creativecommons.org/licenses/by/3.0">CC BY 3.0</a>. Data by <a href="http://openstreetmap.org">OpenStreetMap</a>, under <a href="http://creativecommons.org/licenses/by-sa/3.0">CC BY SA</a>.'
		}).addTo(map);
	
		// Création de la couche OSM
		var OpenStreetMap = L.tileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
			//attribution: '&copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>'
		});
		// Ajout de la couche OSM à la carte
		OpenStreetMap.addTo(map);
				
			
		// Couche Quartiers prioritaire
		var customQuartiersPrio = {
		    style: function(feature) {
			return {
			    fillColor: "red",
			    color: "red",
			    weight: 1,
			    opacity: 1,
			    fillOpacity: 0.4
			};			        
		    }
		};		
		var layerQuartiersPrio = new L.GeoJSON.AJAX('./data/quatrier_prio_epsg4326.geojson', customQuartiersPrio);       
		layerQuartiersPrio.addTo(map);
		
		// Couche Territoire vulnérable
		var vulnerabilite = null;
		var layerTerriVulnerable = new L.GeoJSON.AJAX('./data/terri_vulnerable_epsg4326.geojson', {
		    style: function (feature) {
			//
			synthese = feature.properties.sc2_nb_frag;
			//
			switch (synthese) {
			    // Peu fragile (0 et 1)
			    case "0": return {
				fillColor: "#fad7a0",
				color: "#000",
				weight: 1,
				opacity: 1,
				fillOpacity: 0.4
			    };
			    case "1": return {
				fillColor: "#fad7a0",
				color: "#000",
				weight: 1,
				opacity: 1,
				fillOpacity: 0.4
			    };
			    // Fragilité partielle (2 et 3)
			    case "2": return {
				fillColor: "#aed6f1",
				color: "#000",
				weight: 1,
				opacity: 1,
				fillOpacity: 0.4
			    };
			    case "3": return {
				fillColor: "#aed6f1",
				color: "#000",
				weight: 1,
				opacity: 1,
				fillOpacity: 0.4
			    };
			    // Forte fragilité
			    case "4": return {
				fillColor: "#1b4f72",
				color: "#000",
				weight: 1,
				opacity: 1,
				fillOpacity: 0.4
			    };
			    // 
			    case null: return {
				fillColor: "white",
				color: "#000",
				weight: 1,
				opacity: 1,
				fillOpacity: 0.4
			    };
			}
		    },
		    // Appel de la fonction au clic sur un élément
		    onEachFeature: function (feature, layer) {
			    var popupContent = "<p><u>Vulnérabilité</u></p><br><b>" + feature.properties.degre_fragilite + "</b>";

			    if (feature.properties && feature.properties.popupContent) {
				    popupContent += feature.properties.popupContent;
			    }
    
			    layer.bindPopup(popupContent);
		     }
		});       
		layerTerriVulnerable.addTo(map);	
			
		//----------------------------
		// Control

		// Echelle
		//L.control.scale({imperial: false}).addTo(map);
		
		// NavBar
		//L.control.navbar({homeTitle : "Vue initiale"}).addTo(map);
		
		// Position de la souris
		//L.control.mousePosition({position: 'bottomright', prefix: 'XY : ', separator: ' , ', emptyString: 'Position de la souris'}).addTo(map);
		
		// MiniMap
		var OpenStreetMap2 = new L.TileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
			minZoom: 6
		});
		var miniMap = new L.Control.MiniMap(OpenStreetMap2, { toggleDisplay: true }).addTo(map);
		
		var baseLayers = {
		    //'MapQuestOSM': MapQuestOpen_OSM,
		    //'MapBox': mapbox,
		    //'Stamen': stamenLayer,
		    'OpenStreetMap': OpenStreetMap
		};
		var groupedOverlays = {
		  "Landmarks": {
			'Quartiers prioritaire': layerQuartiersPrio,
			'Territoires vulnérable': layerTerriVulnerable
		  }
		};
		//L.control.groupedLayers(baseLayers, groupedOverlays).addTo(map);
		
		L.Control.geocoder({
		    geocoder: L.Control.Geocoder.nominatim({
			geocodingQueryParams: {countrycodes: 'fr', state: 'Nouvelle-Aquitaine'}
		    })
		}).addTo(map);
		
	    }
	</script>
  </head>
  <body onload="init()">
      	<div id="legend">
	    <div>Vulnérabilité des territoires</div><div><img src="./images/legende.png"/><div>
	</div>
	<br>
	<div id="map"></div>

  </body>
</html>
