/*
 * File name : app.js
 * Version 0.3
 * Date (mm/dd/yyyy) : 10/14/2014
 * Author : Tony VINCENT
 */

// Définition de la banque d'image pour OL
OpenLayers.ImgPath = 'http://js.mapbox.com/theme/dark/';

// Déclaration de l'étendu
var extent = new OpenLayers.Bounds(-1.61913,44.96020,1.25998,47.34050);

// Déclaration des projections utilisées 
var epsg4326 = new OpenLayers.Projection("EPSG:4326");
var epsg900913 = new OpenLayers.Projection("EPSG:900913");
var epsg3857 = new OpenLayers.Projection("EPSG:3857");

// On transforme l'étendu dans la nouvelle projection
extent.transform(epsg4326,epsg3857);

//
var resolutions = OpenLayers.Layer.Bing.prototype.serverResolutions.slice(8, 22);

// Déclaration des options de la carte
var mapOptions = {
    maxExtent: extent,
    restrictedExtent: extent,
    maxResolution: "auto",
    projection: epsg3857,
    units: "m",
    displayProjection: epsg4326,
    numZoomLevels: 10,
    minZoomLevel: 8,
    maxZoomLevel: 18,
    controls: [
        new OpenLayers.Control.Navigation(),
        new OpenLayers.Control.PanZoomBar(),
        new OpenLayers.Control.Attribution(),
        //new OpenLayers.Control.MousePosition(),
        //new OpenLayers.Control.LayerSwitcher(),
        new OpenLayers.Control.ScaleLine({bottomOutUnits: ''})
    ],
    allOverlays: false
};

// On créer la carte
//var map = new OpenLayers.Map("map", mapOptions); (PRODUCTION)
map = new OpenLayers.Map("map", mapOptions);

// Couche Google Maps
var l_gmap = new OpenLayers.Layer.Google(
    'Google - Plan', // the default
    {numZoomLevels: 20, visibility: false, attribution: 'Données cartographiques ©2014 Google'}
);
map.addLayer(l_gmap);
var l_ghyb = new OpenLayers.Layer.Google(
    'Google - Photo aérienne', // the default
    {type: google.maps.MapTypeId.HYBRID, numZoomLevels: 20, visibility: false, attribution: 'Données cartographiques ©2014 Google'}
);
map.addLayer(l_ghyb);   

// Couche OSM
var l_osm = new OpenLayers.Layer.OSM('OSM', null, {
    isBaseLayer:true,
    displayInLayerSwitcher:true,
    zoomOffset: 8,
    resolutions: resolutions
});
map.addLayer(l_osm);

// Limites administratives

// Limites des régions de France
var l_regions_autres = new OpenLayers.Layer.Vector("Régions", {
    displayInLayerSwitcher:false,
    projection: map.displayProjection,
    strategies: [new OpenLayers.Strategy.Fixed()],
    protocol: new OpenLayers.Protocol.HTTP({
        url: "xml/l_regions_limitrophes_pc.kml",
        format: new OpenLayers.Format.KML({
            extractStyles: true,
            extractAttributes: true
        }),
        visibility: true
    }),
    attribution: " - &copy;IGN-Paris 2010 ROUTE 500"
});

// Limites des départements du Poitou-Charentes
var l_departement_pc = new OpenLayers.Layer.Vector("Départements", {
    displayInLayerSwitcher:false,
    projection: map.displayProjection,
    strategies: [new OpenLayers.Strategy.Fixed()],
    protocol: new OpenLayers.Protocol.HTTP({
        url: "xml/l_departements_pc.kml",
        format: new OpenLayers.Format.KML({
            extractStyles: true,
            extractAttributes: true
        }),
        visibility: true
    })
});
// Ajout des couches à la carte
map.addLayers([l_regions_autres,l_departement_pc]);  

// Define three colors that will be used to style the cluster features
// depending on the number of features they contain.
var colors = {
    low: "rgb(181, 226, 140)", 
    middle: "rgb(241, 211, 87)", 
    high: "rgb(253, 156, 115)"
};
        
// Define three rules to style the cluster features.
var oneMarkerRule = new OpenLayers.Rule({
        filter: new OpenLayers.Filter.Comparison({
            type: OpenLayers.Filter.Comparison.EQUAL_TO,
            property: "count",
            value: 1
        }),
        symbolizer: {
            //cursor: 'pointer', // DOESN'T HAVE ANY IMPACT
            //fillOpacity: 0.9, 
            graphicYOffset: -30,
            pointRadius: 14,
            //externalGraphic: './icons/img_vert.png' 
            externalGraphic: '${radius}'
        }
});
var middleRule = new OpenLayers.Rule({
    filter: new OpenLayers.Filter.Comparison({
        type: OpenLayers.Filter.Comparison.BETWEEN,
        property: "count",
        lowerBoundary: 2,
        upperBoundary: 10
    }),
    symbolizer: {
        fillColor: colors.middle,
        fillOpacity: 0.9, 
        strokeColor: colors.middle,
        strokeOpacity: 0.5,
        strokeWidth: 6,
        pointRadius: 10,
        label: "${count}",
        labelOutlineWidth: 0,
        fontColor: "#000",
        //fontOpacity: 0.8,
        fontSize: "12px"
    }
});
var highRule = new OpenLayers.Rule({
    filter: new OpenLayers.Filter.Comparison({
        type: OpenLayers.Filter.Comparison.GREATER_THAN,
        property: "count",
        value: 10
    }),
    symbolizer: {
        fillColor: colors.high,
        fillOpacity: 0.9, 
        strokeColor: colors.high,
        strokeOpacity: 0.5,
        strokeWidth: 8,
        pointRadius: 12,
        label: "${count}",
        labelOutlineWidth: 0,
        fontColor: "#000",
        //fontOpacity: 0.8,
        fontSize: "12px"
    }
});

// Create a Style that uses the three previous rules
vectorStyleMap = new OpenLayers.Style({
    'temporary': {
        cursor: 'pointer'
    }
},{
    context: {
        radius: function(feature) {    
            //console.log(feature);
            var count = feature.attributes.count || feature.cluster[0].attributes.tetat_code;
            if(count == 1) {
                var type = feature.attributes.tetat_code || feature.cluster[0].attributes.tetat_code;
                
                switch (type)
                {
                    case '1':
                        return './icons/img_rouge1.png';
                        //return './icons/picto_borne_elec_hs.png';
                        break;
                    
                   case '2':
                        return './icons/img_vert2.png';
                        //return './icons/picto_borne_elec.png';
                        break;
                }
            }
        }
    },
    rules: [
        oneMarkerRule,
        middleRule,
        highRule
    ]
});  
    
// Couche point Départ
var styleMapDepart = new OpenLayers.StyleMap({
    'default':new OpenLayers.Style({
        pointRadius: 16,
        graphicYOffset: -30,
        externalGraphic: './icons/marker-start.png'
    })
});
var l_depart = new OpenLayers.Layer.Vector('Départ', {
    displayInLayerSwitcher:false,
    styleMap:styleMapDepart
});
map.addLayer(l_depart);

// Couche point Arrivée
var styleMapArrivee = new OpenLayers.StyleMap({
    'default':new OpenLayers.Style({
        pointRadius: 16,
        graphicYOffset: -30,
        externalGraphic: './icons/marker-end.png'
    })
});
var l_arrivee = new OpenLayers.Layer.Vector('Arrivée', {
    displayInLayerSwitcher:false,
    styleMap:styleMapArrivee
});
map.addLayer(l_arrivee);

// Couche "Itinéraires"
var styleMapItineraire = new OpenLayers.StyleMap({
    'default':new OpenLayers.Style({ 
        strokeColor: 'red',
        strokeWidth: 3
    })
});
var l_itineraires = new OpenLayers.Layer.Vector('Itinéraires', {
    displayInLayerSwitcher:false,
    styleMap:styleMapItineraire
});
map.addLayer(l_itineraires);
 
var l_lieu_recharge = new OpenLayers.Layer.Vector("Lieu de recharge",{
    strategies: [
        new OpenLayers.Strategy.Fixed(),
        new OpenLayers.Strategy.AnimatedCluster({
            distance: 45,
            animationMethod: OpenLayers.Easing.Expo.easeOut,
            animationDuration: 10
        })
    ],
    protocol: new OpenLayers.Protocol.HTTP({
        url: "./xml/getdata.php",
        format: new OpenLayers.Format.GeoJSON({
            'internalProjection': map.projection,
            'externalProjection': map.displayProjection
        }),
        formatOptions: {
            extractAttributes: true
        }
    }),
    styleMap: vectorStyleMap,
    visibility: true
});
// Ajout des couches à la carte
map.addLayer(l_lieu_recharge);

//
var saveStrategy = new OpenLayers.Strategy.Save({
    onCommit: function() {
        saveStrategy.layer.refresh();
    }
});

// Création du layer vector (l_etiquette) qui contient tous les layers
var vectors = new OpenLayers.Layer.Vector.RootContainer("vectors",
{
    layers: [l_lieu_recharge]
});

// Ajout des couches à la carte
map.addLayer(vectors);
