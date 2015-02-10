/*
 * File name : app.js
 * Version 0.2
 * Date (mm/dd/yyyy) : 10/14/2014
 * Author : Tony VINCENT
 */

/**** Declaration des variables globales ****/
var map, popup, filterPanelItem2, mapPanel;    

//Ext.BLANK_IMAGE_URL = "./lib/ext/resources/images/default/s.gif";
Ext.BLANK_IMAGE_URL = "http://extjs.cachefly.net/ext-3.4.0/resources/images/default/s.gif";

Ext.QuickTips.init();

// Fonction de chargement
Ext.onReady(function() {
    // Image de chargement
    var hideMask = function () {
        Ext.get('loading').remove();
        Ext.fly('loading-mask').fadeOut({
            remove:true
        });
    };
    hideMask.defer(350);
    
    var button = false;

    // Fonction permettant de recentrer la carte sur le lieu
    var recenter_lieu = function(x,y) {
        map.setCenter(new OpenLayers.LonLat(x, y),8);
    };
    
    // Fonction pour créer le Popup
    function createPopup(feature) {
        // Récupération des attributs
        var txt_title = feature.attributes.titre_fiche || feature.cluster[0].attributes.titre_fiche;
        var code_lieu = feature.attributes.tlrecharge_code || feature.cluster[0].attributes.tlrecharge_code;
        
        //var bogusMarkup = "<iframe src='http://www.iaat.org/epn_api_poitou_charentes/back_office/t_lieu_visualiser_fiche_carto1.php?id_lieu="+feature.attributes.tsite_id+"' width='665' height='580' ></iframe>";
        //var bogusMarkup = '<iframe src="http://dev2.iaat.org/region_poitou_charentes/bornes_electriques/fiche_bornes.php?code='+code_lieu+'" width="665" height="580" ></iframe>';
        var bogusMarkup = '<iframe src="./fiche_bornes.php?code='+code_lieu+'" width="665" height="580" ></iframe>';

        popup = new GeoExt.Popup({
            title: ' '+txt_title+' ',
            location: feature,
            map: mapPanel.map,
            width: 680,
            height: 630,
            html: bogusMarkup,
            maximizable: false,
            unpinnable: false,
            anchored: false,
            resizable: false,
            modal: true,
            buttonAlign: 'center',
            buttons: [{
                //titre du bouton!
                text: 'Zoomer sur le lieu',
                //handler = réponse sur événement clic!
                handler: function(){
                    
                    //action a exécuté en cas de clic sur le bouton Quitter
                    //ici on ferme la fenêtre.
                    popup.hide();

                    var x = feature.geometry.x;
                    var y = feature.geometry.y;

                    recenter_lieu(x,y);

                    selectCtrl.unselect(feature);
                }
            },{
                text: 'Fermer la fenêtre',
                // handler = réponse sur événement clic!
                handler: function(){
                    //action a exécuté en cas de clic sur le bouton Quitter
                    //ici on ferme la fenêtre.
                    popup.hide();

                    selectCtrl.unselect(feature);
                }
            }]
        });

        //// On ferme le Popup,quand la couche est déselectionnée 
        popup.on({
            close: function()
            {
                if(OpenLayers.Util.indexOf(l_lieu_recharge.selectedFeatures,
                feature) > -1) {
                    selectCtrl.unselect(feature);
                }
            }
        });
        popup.show();
    }
    


    // Initialisation des variables
    
    // Declaration des variables globales
    //var map, popup, vectorStyleMap;    

    // Calcul de l'itinéraire
    var adresse_depart = '';
    var adresse_arrivee = '';
    
    var point_depart;
    var point_arrivee;
    var point_intermediaire;
    var autoroute;
    var encoded_polyline;
    var directionsDisplay;
    var encoded_polyline;
    var directionsService = new google.maps.DirectionsService();

    /*
     * 
     */
    function calcRoute(pointDepart,pointArrivee, pointIntermediaire, autoroute) {
    //console.log(pointIntermediaire);
    
    if (!pointIntermediaire) {
    pointIntermediaire = '';
    }
    
    var waypts = [];
        if (pointIntermediaire) {
          waypts.push({
                      location: pointIntermediaire+' poitou-charentes',
                      stopover:true
                });  
        }
    
        request = {
            origin: pointDepart+' poitou-charentes',
            destination: pointArrivee+' poitou-charentes',
            waypoints: waypts,
            optimizeWaypoints: true,
            avoidHighways:autoroute,
            travelMode:google.maps.TravelMode.DRIVING
        };    
    
        directionsService.route(request,function(result, status){    
        //directionsService.route({origin:pointDepart, destination:pointArrivee, waypoints:waypts, optimizeWaypoints: true, avoidHighways:autoroute, travelMode:google.maps.TravelMode.DRIVING},function(result, status){
            if (status == google.maps.DirectionsStatus.OK) {
                // Chaîne de LatLng encodée
                encoded_polyline = result['routes'][0]['overview_polyline'];
                // Décodage de la chaîne de caractères
                var decoded_polyline = new google.maps.geometry.encoding.decodePath(encoded_polyline);
                // On ajoute ce point à notre tableau de points
                var points = new Array();
                /**
                 * On parcourir le tableau de points
                 * pour en extraire les latitudes et longitudes.
                 */
                for ( var i=0, I=decoded_polyline.length; i < I; i++ ) {
                    /** On récupère les deux coordonnées en WGS84 pour en faire un objet
                     * "Point" correctement reprojeté 
                     */
                    var lonlat = new OpenLayers.LonLat(decoded_polyline[i].lng(), decoded_polyline[i].lat()).transform(epsg4326, epsg3857);
                    var point = new OpenLayers.Geometry.Point(lonlat.lon, lonlat.lat);
                    points.push(point);
                    
                    // On récupére le point de départ
                    if (i == 0) {point_depart = point};
                    // On récupére le point d'arrivée
                    if (i == I-1) {point_arrivee = point};
                }
        
                /** On ajoute, à nos couches départ et arrivée, un objet "Point" construit 
                 * à partir du tableau de "Points"
                 */
                l_depart.addFeatures([new OpenLayers.Feature.Vector(point_depart)]);
                l_arrivee.addFeatures([new OpenLayers.Feature.Vector(point_arrivee)]);
                /** On ajoute, à notre couche d'itinéraires, un objet "Ligne" construit 
                 * à partir du tableau de "Points"
                 */
                //l_itinerairesAutour.addFeatures([new OpenLayers.Feature.Vector(new OpenLayers.Geometry.LineString(points))]);
                l_itineraires.addFeatures([new OpenLayers.Feature.Vector(new OpenLayers.Geometry.LineString(points))]);
                map.zoomToExtent(l_itineraires.getDataExtent());
            }
        });
    };
    
    // Création du control de selection d'un feature
    var selectCtrl = new OpenLayers.Control.SelectFeature([l_lieu_recharge]);

    // Création du popup, en fonction de la ou les couches sélectionnées 
    l_lieu_recharge.events.on({
        featureselected: function(e) {
            //console.log(e);
            
            var count_feature = e.feature.attributes.count;
            // Afiche un popup si un seul feature,
            // sinon, on zoom sur le cluster
            if(count_feature == 1) {
                createPopup(e.feature);
            }else{  
                // On récupére le niveau de zoom
                var getZoom = map.getZoom();
                // On récupére les cordonnées du cluster
                var x = e.feature.geometry.x;
                var y = e.feature.geometry.y;
                // On zoom sur le cluster
                map.setCenter(new OpenLayers.LonLat(x, y), getZoom+1);
            }
        }
    });

    // Toolbar
    var ctrl, toolbarItems = [], action, actions = {};

    // Bouton de contrôle pour, zoomer sur l'étendu maximum
    action = new GeoExt.Action({
        control: new OpenLayers.Control.ZoomToMaxExtent(),
        map: map,
        iconCls: "zoomfull",
        toggleGroup: "map",
        tooltip: "Retour à la carte initiale"
    });

    actions["max_extent"] = action;
    toolbarItems.push(action);

    // Ajout de la barre de séparation
    toolbarItems.push('-');

    // Liste déroulante, pour les zooms prédéfinit
    action = new GeoExt.ux.ShortcutCombo({
        map: map,
        id: 'shortcutCombo',
        width: 150,
        store: GeoExt.ux.ShortcutCombo.countryStore,
        bboxField: 'bbox',
        zoomField: 'zoom',
        bboxSrs: 'EPSG:900913'
    });
    actions['shortcut'] = action;
    toolbarItems.push(action);

    // Ajout de la barre de séparation
    toolbarItems.push("-");

    action = new GeoExt.Action({
        text: 'Fonds de carte',
        menu: new Ext.menu.Menu({
            items: [{
                text:'<img src="./icons/img_l_gmap.png" alt=""/> Google Plan',
                xtype: 'menucheckitem',
                handler: function() {
                    map.setBaseLayer(l_gmap);
                },
                checked:true,
                group:'rp-group',
                scope:this,
                iconCls:'preview-bottom'
            },{
                text:'<img src="./icons/img_l_ghyb.png" alt=""/> Google Satellite',
                xtype: 'menucheckitem',
                handler: function() {
                    map.setBaseLayer(l_ghyb);
                },
                group:'rp-group',
                scope:this,
                iconCls:'preview-bottom'
            },{
                text:'<img src="./icons/img_l_osm.png" alt=""/> OpenStreetMap',
                xtype: 'menucheckitem',
                handler: function() {
                    map.setBaseLayer(l_osm);
                },
                group:'rp-group',
                scope:this,
                iconCls:'preview-bottom'
            }]
        })
    });
    actions['baselayers'] = action;
    toolbarItems.push(action);
    
    // Ajout de la barre de séparation
    toolbarItems.push('-');
    
    // On position les éléments de la barre à droite
    toolbarItems.push('->');

    // Ajout de la barre de séparation
    toolbarItems.push('-');

    // Afficher le bouton impression
    action = new Ext.Action({
        iconCls: "print",
        tooltip :'Imprimer la carte',
        handler: function(){
            window.print();
            return false;
        }
    });
    actions["print"] = action;
    toolbarItems.push(action);

    // Ajout de la barre de séparation
    toolbarItems.push("-");

    // Barre d'outils : Afficher l'aide    
    //action = new GeoExt.Action({
        //iconCls: "help",
        //tooltip :'Aide',
        //handler: function() {
            //var txt_help = AideHtml;
    //
            //popup_help = new GeoExt.Popup({
                //title: 'Aide sur l\'utilisation de l\'interface',
                //location: mapPanel.map,
                //map: mapPanel.map,
                //width: 600,
                //height: 540,
                //autoScroll: true,
                //html: txt_help,
                //maximizable: false, 
                //unpinnable: false, 
                //anchored: false, 
                //resizable: false,
                //modal: true 
            //});
    //
            // On ferme le Popup,quand la couche est déselectionnée 
            //popup_help.on({
                //close: function()
                //{
                    //if(OpenLayers.Util.indexOf(l_data.selectedFeatures,
                    //this.feature) > -1) {
                        //selectCtrl.unselect(this.feature);
                    //}
                //}
            //});
            //popup_help.show();
        //}
    //});
    //actions["help"] = action;
    //toolbarItems.push(action);

    // Carte
    var mapPanel = new GeoExt.MapPanel({
        id: 'carte',
        map: map,
        region: 'center',
        style:'padding-bottom: 5px;',
        tbar: [toolbarItems]
    });
 
    var fs_type_prises = {
        xtype: 'fieldset',
        title: 'Type de prises',
        id: 'fieldset-critere01',
        //width: 'auto',
        width: 197,
        autoHeigth: true,
        items: [{
            xtype: 'checkboxgroup',
            id: 'contains_group_type_prise',
            itemCls: 'x-check-group-alt',
            hideLabel: true,
            columns: 1,
            items: [{
                xtype: 'checkbox',
                boxLabel: ' <img src="./icons/prise-domUE.png" alt=""/> Domestique E/F',
                id: 'cb-domestique',
                name: 'tsocle_code__eq',
                inputValue: '4_15', 
                checked: true,
                handler: function(combo, value){
                        filterPanelItem1.search();
                }
            },{
                xtype: 'checkbox',
                boxLabel: ' <img src="./icons/prise-t1.png" alt=""/> Type 1',
                id: 'cb-type1',
                name: 'tsocle_code__eq',
                inputValue: '7', 
                checked: true,
                handler: function(combo, value){
                    if (button != true) {
                        // On applique le filtre
                        filterPanelItem1.search();
                    }
                }
            },{
                xtype: 'checkbox',
                boxLabel: ' <img src="./icons/prise-t2.png" alt=""/> Type 2',
                id: 'cb-type2',
                name: 'tsocle_code__eq',
                inputValue: '8', 
                checked: true,
                handler: function(combo, value){
                    if (button != true) {
                        filterPanelItem1.search();
                    }
                }
            },{
                xtype: 'checkbox',
                boxLabel: ' <img src="./icons/prise-t3b.png" alt=""/> Type 3',
                id: 'cb-type3',
                name: 'tsocle_code__eq',
                inputValue: '10_12', 
                checked: true,
                handler: function(combo, value){
                    if (button != true) {
                        // On applique le filtre
                        filterPanelItem1.search();
                    }
                }
            },{
                xtype: 'checkbox',
                boxLabel: ' <img src="./icons/prise-t4.png" alt=""/> Type 4',
                id: 'cb-type4',
                name: 'tsocle_code__eq',
                inputValue: '14', 
                checked: true,
                handler: function(combo, value){
                    if (button != true) {
                        // On applique le filtre
                        filterPanelItem1.search();
                    }
                }
            },{
                xtype: 'checkbox',
                boxLabel: ' <img src="./icons/prise-t4.png" alt=""/> ChAdeMO',
                id: 'cb-chademo',
                name: 'tsocle_code__eq',
                inputValue: '2', 
                checked: true,
                handler: function(combo, value){
                    if (button != true) {
                        // On applique le filtre
                        filterPanelItem1.search();
                    }
                }
            },{
                xtype: 'checkbox',
                boxLabel: '  Autres',
                id: 'cb-autres',
                name: 'tsocle_code__eq',
                inputValue: '13', 
                checked: true,
                handler: function(combo, value){
                    if (button != true) {
                        // On applique le filtre
                        filterPanelItem1.search();
                    }
                }
            }]
        },{
            xtype: 'panel',
            border: false,
            items: [{
                xtype: 'button',
                id: 'selectAllTypePrise',
                style:'float:left; padding-left:50px;',
                text: 'Toutes',
                handler: function() {
                    button = true;
                    //button_value_tsocle = this.text;
                    Ext.each(Ext.getCmp('contains_group_type_prise').items.items,
                    function(c) {
                        if (c.setValue) {
                            c.setValue('true');
                        }
                        return true;
                    },
                    Ext.getCmp('contains_group_type_prise'))
                }
            },{
                xtype: 'button',
                id: 'selectNoneTypePrise',
                style:'padding-left:10px; ',
                text: 'Aucune',
                handler: function() {
                    button = true;
                    //button_value_tsocle = this.text;
                    Ext.each(Ext.getCmp('contains_group_type_prise').items.items,
                    function(c) {
                        if (c.setValue) {
                            c.setValue('false');
                        }
                        return true;
                    },
                    Ext.getCmp('contains_group_type_prise'))
                }
            }]
        }]
    };
   var fs_type_charge = {
        xtype: 'fieldset',
        title: 'Type de recharge',
        id: 'fieldset-critere02',
        width: 197,
        autoHeigth: true,
        items: [{
            xtype: 'checkboxgroup',
            id: 'contains_group_type_recharge',
            itemCls: 'x-check-group-alt',
            hideLabel: true,
            columns: 1,
            items: [{
                xtype: 'checkbox',
                boxLabel: ' Normale (3 kVA)',
                id: 'cb-normale',
                name: 'ttrecharge_code__eq',
                inputValue: '1', 
                checked: true,
                handler: function(combo, value){ 
                    // On applique le filtre
					filterPanelItem1.search();
                    //}
                }
			},{
                xtype: 'checkbox',
                boxLabel: ' Accélérée (22 kVA)',
                id: 'cb-acceleree',
                name: 'ttrecharge_code__eq',
                inputValue: '2', 
                checked: true,
                handler: function(combo, value){
                    if (button != true) {
                        // On applique le filtre
                        filterPanelItem1.search();
                    }
                }
            },{
                xtype: 'checkbox',
                boxLabel: ' Rapide (43 kVA)',
                id: 'cb-rapide',
                name: 'ttrecharge_code__eq',
                inputValue: '3', 
                checked: true,
                handler: function(combo, value){
                    if (button != true) {
                        // On applique le filtre
                        filterPanelItem1.search();
                    }
                }
            }]
        },{
            xtype: 'panel',
            border: false,
            items: [{
                xtype: 'button',
                id: 'selectAllTypeCharge',
                style:'float:left; padding-left:50px;',
                text: 'Toutes',
                handler: function() {
                    button = true;
                    //button_value_ttrecharge = this.text;
                    Ext.each(Ext.getCmp('contains_group_type_recharge').items.items,
                    function(c) {
                        if (c.setValue) {
                            c.setValue('true');
                        }
                        return true;
                    },
                    Ext.getCmp('contains_group_type_recharge'))
                }
            },{
                xtype: 'button',
                id: 'selectNoneTypeCharge',
                style:'padding-left:10px; ',
                text: 'Aucune',
                handler: function() {
                    button = true;
                    //button_value_ttrecharge = this.text;
                    Ext.each(Ext.getCmp('contains_group_type_recharge').items.items,
                    function(c) {
                        if (c.setValue) {
                            c.setValue('false');
                        }
                        return true;
                    },
                    Ext.getCmp('contains_group_type_recharge'))
                }
            }]
        }]
    };
 
    var fs_condition_acces = {
        xtype: 'fieldset',
        title: 'Condition d\'accès',
        id: 'fieldset-critere03',
        width: 197,
        autoHeigth: true,
        items: [{
            xtype: 'fieldset',
            title: 'Horaire',
            id: 'fieldset-critere03-01',
            width: 'auto',
            collapsible: true,
            collapsed: true,
            autoHeigth: true,
            items: [{
                xtype: 'checkboxgroup',
                id: 'contains_group_condition_acces_01',
                itemCls: 'x-check-group-alt',
                hideLabel: true,
                columns: 1,
                items: [{
                    xtype: 'checkbox',
                    boxLabel: ' Horaires ouverture spécifique',
                    id: 'cb-limite',
                    name: 'tacces_code__eq',
                    inputValue: '1', 
                    checked: true,
                    handler: function(combo, value){
                        // On applique le filtre
                        filterPanelItem1.search();
                    }  
                },{
                    xtype: 'checkbox',
                    boxLabel: ' Horaires ouverture 24/24',
                    id: 'cb-nomlimite',
                    name: 'tacces_code__eq',
                    inputValue: '0', 
                    checked: true,
                    handler: function(combo, value){
                        if (button != true) {
                            // On applique le filtre
                            filterPanelItem1.search();
                        }
                    }   
                }]
            //},{
                //xtype: 'panel',
                //border: false,
                //items: [{
                    //xtype: 'button',
                    //id: 'selectAllAcces01',
                    //style:'float:left; padding-left:40px;',
                    //text: 'Toutes',
                    //handler: function() {
                        //button = true;
                        //button_value_tacces = this.text;
                        //Ext.each(Ext.getCmp('contains_group_condition_acces_01').items.items,
                        //function(c) {
                            //if (c.setValue) {
                                //c.setValue('true');
                            //}
                            //return true;
                        //},
                        //Ext.getCmp('contains_group_condition_acces_01'))
                    //}
                //},{
                    //xtype: 'button',
                    //id: 'selectNoneAcces01',
                    //style:'padding-left:10px; ',
                    //text: 'Aucune',
                    //handler: function() {
                        //button = true;
                        //button_value_tacces = this.text;
                        //Ext.each(Ext.getCmp('contains_group_condition_acces_01').items.items,
                        //function(c) {
                            //if (c.setValue) {
                                //c.setValue('false');
                            //}
                            //return true;
                        //},
                        //Ext.getCmp('contains_group_condition_acces_01'))
                    //}
                //}]
            }]
        },{
            xtype: 'fieldset',
            title: 'Coût',
            id: 'fieldset-critere03-02',
            width: 'auto',
            collapsible: true,
            collapsed: true,
            autoHeigth: true,
            items: [{
                xtype: 'checkboxgroup',
                id: 'contains_group_condition_acces_02',
                itemCls: 'x-check-group-alt',
                hideLabel: true,
                columns: 1,
                items: [{
                    xtype: 'checkbox',
                    boxLabel: ' Non spécifié',
                    id: 'cb-non-spec-cout',
                    name: 'tborne_paiement__eq',
                    inputValue: 'ns', 
                    checked: true,
                    handler: function(combo, value){
                        if (button != true) {
                            filterPanelItem1.search();
                        }
                    }  
                },{
                    xtype: 'checkbox',
                    boxLabel: ' Gratuit',
                    id: 'cb-gratuit',
                    name: 'tborne_paiement__eq',
                    inputValue: 'gratuit', 
                    checked: true,
                    handler: function(combo, value){
                        if (button != true) {
                             //On applique le filtre
                            filterPanelItem1.search();
                        }
                    }  
                },{
                    xtype: 'checkbox',
                    boxLabel: ' Payant',
                    id: 'cb-payant',
                    name: 'tborne_paiement__eq',
                    inputValue: 'payant', 
                    checked: true,
                    handler: function(combo, value){
                        if (button != true) {
                             //On applique le filtre
                            filterPanelItem1.search();
                        }
                    }   
                }]
            //},{
                //xtype: 'panel',
                //border: false,
                //items: [{
                    //xtype: 'button',
                    //id: 'selectAllAcces02',
                    //style:'float:left; padding-left:40px;',
                    //text: 'Toutes',
                    //handler: function() {
                        //button = true;
                        //button_value_tbornepaiement = this.text;
                        //Ext.each(Ext.getCmp('contains_group_condition_acces_02').items.items,
                        //function(c) {
                            //if (c.setValue) {
                                //c.setValue('true');
                            //}
                            //return true;
                        //},
                        //Ext.getCmp('contains_group_condition_acces_02'))
                    //}
                //},{
                    //xtype: 'button',
                    //id: 'selectNoneAcces02',
                    //style:'padding-left:10px; ',
                    //text: 'Aucune',
                    //handler: function() {
                        //button = true;
                        //button_value_tbornepaiement = this.text;
                        //Ext.each(Ext.getCmp('contains_group_condition_acces_02').items.items,
                        //function(c) {
                            //if (c.setValue) {
                                //c.setValue('false');
                            //}
                            //return true;
                        //},
                        //Ext.getCmp('contains_group_condition_acces_02'))
                    //}
                //}]
            }]
        }]
    };   

    //
    var protocol = new OpenLayers.Protocol({
        read: function(options) {			
            // On récupére les valeurs du filtre
            //var filters = options.filter.filters;

            //
            var filters_data = options.filter;
            var property = '',
                values_tsocle = '',
                values_ttrecharge = '',
                values_tacces = '',
                values_tbornepaiement = '';
            
            
            if (!filters_data.filters) {
                //Un filtre
                property = filters_data.property;
                var value = '';

                if (typeof(filters_data.value) == "object") {
                    for (var i = 0; i < filters_data.value.length; i++) {
                        value += filters_data.value[i]+'_';
                    } 
                    value = value.substring(0,value.length-1);
                }else{
                    value = filters_data.value;
                }
                
                switch (property)
                {
                    case 'tsocle_code': 
                        // On supprime le dernier caractere
                        var values_tsocle = value;
                        break;
                    
                    case 'ttrecharge_code': 
                        // On supprime le dernier caractere
                        var values_ttrecharge = value;
                        break;
                        
                    case 'tacces_code': 
                        // On supprime le dernier caractere
                        var values_tacces = value;
                        break;
                   
                    case 'tborne_paiement': 
                        // On supprime le dernier caractere
                        var values_tbornepaiement = value;
                        break;     
                }
            }else{
                if (filters_data.filters.length != 0) {
                    
                    for (var i = 0; i < filters_data.filters.length; i++) {
                        
                        property = filters_data.filters[i].property;
                        
                        value = '';
                        
                        if (typeof(filters_data.filters[i].value) == "object") {
                            for (var j = 0; j < filters_data.filters[i].value.length; j++) {
                                value += filters_data.filters[i].value[j]+'_';
                            } 
                            value = value.substring(0,value.length-1);
                        }else{
                            value = filters_data.filters[i].value;
                        }

                        //
                        switch (property)
                        {
                            case 'tsocle_code': 
                                 //On supprime le dernier caractere
                                var values_tsocle = value;
                                break;
                            
                            case 'ttrecharge_code': 
                                 //On supprime le dernier caractere
                                var values_ttrecharge = value;
                                break;    
                                
                            case 'tacces_code': 
                                // On supprime le dernier caractere
                                var values_tacces = value;
                                break;
                                
                            case 'tborne_paiement': 
                                // On supprime le dernier caractere
                                var values_tbornepaiement = value;
                                break;  
                        }
                    } 
                }
            }
 
            l_lieu_recharge.refresh({ force: true, params: { 'tsocle_code' : values_tsocle, 'ttrecharge_code': values_ttrecharge, 'tacces_code': values_tacces, 'tborne_paiement': values_tbornepaiement} }); 
            
            // On applique le style à la couche "vector"
            l_lieu_recharge.styleMap = vectorStyleMap;
            
            // On re dessine la couche "vector" 
            l_lieu_recharge.redraw();

            button = false;
        }
    });
    
        
    //
    var filterPanelItem1 = new GeoExt.form.FormPanel({
		labelWidth: 110,
        autoScroll: true,
        style:'padding: 5px 5px 5px 5px;',
        protocol: protocol,
        items: [
            fs_type_prises,
            fs_type_charge,
            fs_condition_acces
        ]
    });
    
    var item1 = new Ext.Panel({
        title: 'Rechercher',
        bodyStyle:'border-color: #ffffff;',
        autoScroll: true,
        items:[
            filterPanelItem1
        ]
    });

    //    
    var fs_itineraire = {
        xtype: 'fieldset',
        labelWidth: 25,
        id: 'fieldset-critereItineraire',
        bodyStyle: 'margin-top:5px;',
        width: 225,
        autoHeigth: true,
        items: [{
            //xtype: 'checkboxgroup',
            
            
            //hideLabel: true,
            //columns: 1,
            //items: [{
            xtype: 'checkbox',
            boxLabel: 'sans autoroute',
            name: 'cb_ss_autoroute__eq',
            itemCls: 'x-check-group-alt',
            style: 'margin-left: -26px',
            inputValue: 0, 
            checked: true
            //}]
         },{
            bodyStyle: 'margin-top: 5px;',
            html: ' '
        },{
            bodyStyle: 'margin-top: 15px;',
            html: 'Départ :'
        },
        new GeoExt.ux.GeoNamesSearchCombo({ 
            style:'margin-left: -30px;',
            map: map,
            width: 198,
            listWidth: 198,
            minChars: 1,
            id: 'startIt',
            name: 'startIt'
         }),{
            bodyStyle: 'margin-top: 5px;',
            html: 'Arrivée :'
        },
        new GeoExt.ux.GeoNamesSearchCombo({
            style:'margin-left: -30px;',
            map: map,
            width: 198,
            listWidth: 198,
            minChars: 1,
            id: 'endIt',
            name: 'endIt'
        },{
            bodyStyle: 'margin-top: 5px;',
            html: ' <br>'
         }),{
            bodyStyle: 'margin-top: 5px;',
            html: '<br>Point intermédiaire : <i>(facultatif)</i>'
        },
        new GeoExt.ux.GeoNamesSearchCombo({ 
            style:'margin-left: -30px;',
            map: map,
            width: 198,
            listWidth: 198,
            minChars: 1,
            id: 'wayIt',
            name: 'wayIt'
        })],
        buttons: [{
            text   : 'Réinitialiser',
            handler: function() {
                // On supprime l'itinéraire précedent
                l_itineraires.destroyFeatures();
                l_depart.destroyFeatures();
                l_arrivee.destroyFeatures();

                // On réinitialise les champs du formulaire
                filterPanelItem2.form.items.items[0].setValue = true;
                filterPanelItem2.form.items.items[1].reset();
                filterPanelItem2.form.items.items[2].reset();
                filterPanelItem2.form.items.items[3].reset();
            }
        },{
            text   : 'Valider',
            handler: function() {
                var formulaire_array = filterPanelItem2.form.items;
                // On récupére la valeur concernant l'autoroute
                autoroute = formulaire_array.items[0].checked;
                // On récupére le nom de la commune de Départ et d'Arrivée
                adresse_depart = formulaire_array.items[1].lastSelectionText;
                adresse_arrivee = formulaire_array.items[2].lastSelectionText;
                // On récupére le nom de la commune du point intermediaire
                point_intermediaire = formulaire_array.items[3].lastSelectionText;
                
                // Test pour savoir si la commune de Départ et Arrivée on bien été renseigné
                if (adresse_depart == '' || typeof(adresse_depart)== 'undefined') {
                    Ext.MessageBox.show({
                        title: 'Information manquante',
                        msg: 'Vous devez renseigner une <b>commune de départ</b>.',
                        buttons: Ext.MessageBox.OK,
                        icon: Ext.Msg.WARNING
                    });
                }else{
                    if (adresse_arrivee == "" || typeof(adresse_arrivee)== 'undefined') {
                        Ext.MessageBox.show({
                            title: 'Information manquante',
                            msg: 'Vous devez renseigner une <b>commune d\'arrivée</b>.',
                            buttons: Ext.MessageBox.OK,
                            icon: Ext.Msg.WARNING,
                            fn: function (btn) {
                                if (btn != 'ok') return;
                            }
                        });
                    }else{
                        //On supprime l'itinéraire précedent
                        l_itineraires.destroyFeatures();
                        l_depart.destroyFeatures();
                        l_arrivee.destroyFeatures();
                        
                        // On appel la fonction qui va tracer l'itinéraire
                        calcRoute(adresse_depart,adresse_arrivee, point_intermediaire, autoroute);
                    }
                }
            }          
        }]
    };

    //var filterPanelItem2 = new GeoExt.form.FormPanel({ (PRODUCTION)
    filterPanelItem2 = new GeoExt.form.FormPanel({
		labelWidth: 110,
        autoScroll: true,
        style:'padding: 5px 5px 5px 5px;',
        items: [
            fs_itineraire
        ]
    });
    
    var item2 = new Ext.Panel({        
        title: 'Itinéraire',
        bodyStyle:'border-color: #ffffff;',
        items:[
            filterPanelItem2
        ]
    });
 	
    var item3 = new Ext.Panel({
        title: 'Contact',
        html: 'Vous pouvez nous laisser un message ....',
        cls:'empty'
    }); 
    
    var accordion = new Ext.Panel({
        region:'east',
        margins:'0 0 5 2',
        bodyStyle:'border-color: #99BBE8;',
        width: 230,
        layout:'accordion',
        items: [item1, item2, item3]
    });

    // Application final
    var mainPanel = new Ext.Panel({
        width: 749,
        height: 730,
        layout: 'border',
        border: true,
        items: [
        mapPanel,
        accordion
        ,{
            region: 'south',
            height: 18,
            contentEl: 'footer'
        }
        ]
    });

    // On envoi l'application, dans l'element body
    mainPanel.render("map");

   // Appel de la fonction qui créé l'étiquette 
    var etiquette = new GeoExt.toolTip({
        map: map,
        featureLayer : vectors,
        title : "name",
        autoHeight : true,
        autoWidth : true,
        hidden: true,
        autoHide: true,
        plain: true,
        showDelay: 0,
        hideDelay: 0,
        trackMouse: true,
        animCollapse: true
     }); 
     
    // Ajout de l'element de control à la carte
    map.addControl(selectCtrl);
    selectCtrl.activate();
});
