/**
 * Copyright (c) 2008-2009 The Open Source Geospatial Foundation
 *
 * Published under the BSD license.
 * See http://svn.geoext.org/core/trunk/geoext/license.txt for the full text
 * of the license.
 */

/** api: (define)
 *  module = GeoExt.ux
 *  class = ShortcutCombo
 *  base_link = `Ext.form.ComboBox <http://extjs.com/deploy/dev/docs/?class=Ext.form.ComboBox>`_
 */

Ext.namespace("GeoExt.ux");

GeoExt.ux.ShortcutCombo = Ext.extend(Ext.form.ComboBox, {
    /** api: config[map]
     *  ``OpenLayers.Map or Object``  A configured map or a configuration object
     *  for the map constructor, required only if :attr:`zoom` is set to
     *  value greater than or equal to 0.
     */
    /** private: property[map]
     *  ``OpenLayers.Map``  The map object.
     */
    map: null,

    /** api: config[width]
     *  ``String`` Width of the combo. Default: 200
     */
    width: 200,

    /** api: config[store]
     *  ``Ext.data.Store``: Store containing the data.
     */
    store: null,

    /** api: config[valueField]
     *  ``String``Value field of the combo. Default: value
     */
    valueField: 'value',

    /** api: config[valueField]
     *  ``String`` Display field of the combo. Default: text
     */
    displayField:'text',

    /** api: config[bboxField]
     *  ``String`` Name of the bbox field of the store. Default: bbox
     */
    bboxField: 'bbox',

    /** api: config[valueField]
     *  ``String`` Display field of the combo. Default: text
     */
    //lonField: 'lon',

    /** api: config[valueField]
     *  ``String`` Display field of the combo. Default: text
     */
    //latField: 'lat',

    /** api: config[valueField]
     *  ``String`` Display field of the combo. Default: text
     */
    zoomField: 'zoom',

    /** api: config[bboxSrs]
     *  ``String`` EPSG code of the bbox bounds. Default: EPSG:900913
     */
    bboxSrs: 'EPSG:900913',
    //bboxSrs: 'EPSG:4326',

    /** private: property[name]
     *  ``String`` Name of the shortcut combo. Default: shortcutcombo
     */
    name: 'shortcutcombo',

    /** private: property[mode]
     *  ``String`` mode. Default: local
     */
    mode: 'local',

    /** private: property[triggerAction]
     *  ``String`` triggerAction. Default: all
     */
    triggerAction: 'all',

    /** private: property[emptyText]
     *  ``String`` Empty text. Default: Select a shortcut ...
     */
    //emptyText:OpenLayers.i18n('Select a shortcut ...'),
    emptyText:OpenLayers.i18n('Zooms prédéfinis ...'),

    /** private: property[typeAhead]
     *  ``Boolean`` typeAhead. Default: true
     */
    typeAhead: true,

    /** private: property[minChars]
     *  ``Number`` Minimal number of characters
     */
    minChars: 1,

    /** private: constructor
     */
    /*initComponent: function() {
        GeoExt.ux.ShortcutCombo.superclass.initComponent.apply(this, arguments);
        if (!this.store) {
            this.store = GeoExt.ux.ShortcutCombo.countryStore;
        }
        this.on("select", function(combo, record, index) {
            var position = record.get(this.bboxField);
            position.transform(
                    new OpenLayers.Projection(this.bboxSrs),
                    this.map.getProjectionObject()
                    );
            this.map.zoomToExtent(position);
        }, this);
    }*/

    initComponent: function() {
        GeoExt.ux.ShortcutCombo.superclass.initComponent.apply(this, arguments);
        if (!this.store) {
            this.store = GeoExt.ux.ShortcutCombo.countryStore;
        }
        this.on("select", function(combo, record, index) {
	    //// Récupération des valeurs, des paramétres
	    var position = record.get(this.bboxField);
	    var zoom = record.get(this.zoomField);

	    ////
            position.transform(
                    new OpenLayers.Projection(this.bboxSrs),
                    this.map.getProjectionObject()
                    );
            this.map.setCenter(position, zoom); //// Recentre sur un point
        }, this);
    }
});

//// 
GeoExt.ux.ShortcutCombo.countryStore = new Ext.data.SimpleStore({
    fields: ['value', 'text', 'bbox', 'zoom'],
    data : [
    ['D', 'Deux-Sèvres', new OpenLayers.LonLat(-35772, 5865777), 1],
    ['C', 'Charente', new OpenLayers.LonLat(26904, 5728802), 1],
    //['C', 'Charente', new OpenLayers.LonLat(23236, 5729108), 1],
    ['CM', 'Charente-Maritime', new OpenLayers.LonLat(-81707, 5736722), 1],
    //['CM', 'Charente-Maritime', new OpenLayers.LonLat(-85883, 5737172), 1],
    ['V', 'Vienne', new OpenLayers.LonLat(56489, 5878223), 1],
    //['V', 'Vienne', new OpenLayers.LonLat(50448, 5880669), 1],
    ['PCH', 'Région Poitou-Charentes', new OpenLayers.LonLat(50448, 5880669), 0]
    ]
});

/** api: xtype = gxux_shortcutcombo */
Ext.reg('gxux_shortcutcombo', GeoExt.ux.ShortcutCombo);

