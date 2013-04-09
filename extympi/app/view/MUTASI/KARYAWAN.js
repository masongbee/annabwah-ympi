/*
Ext.Loader.setConfig({
    enabled: true
});

Ext.require([
    'Ext.grid.*',
    'Ext.data.*'
]);

Ext.define('YMPI.view.MUTASI.KARYAWAN', {
	extend: 'Ext.form.Panel',
	
	requires: ['YMPI.view.MUTASI.KaryawanList', 'YMPI.view.MUTASI.KaryawanForm'],
	
	layout: {
        type: 'vbox',
        align: 'stretch'
    },
    initComponent: function(){
    	this.items = [{
        	xtype	: 'KaryawanList',
        	flex: 1
        },{
        	xtype	: 'KaryawanForm',
        	flex: 1
        } ];
        
        this.callParent(arguments);
    }

});
*/

Ext.define('YMPI.view.MUTASI.KARYAWAN', {
	extend	: 'Ext.panel.Panel',
	requires: ['YMPI.view.MUTASI.ArrayGrid', 'YMPI.view.MUTASI.KaryawanForm'],
	
	alias	: 'widget.KARYAWAN',
	
    layout: 'border',
    
	initComponent: function(){
		this.items = [{
	        //title: 'South Region is resizable',
	        region: 'south',     // position for region
	        xtype: 'panel',
	        height: 200,
	        split: true,         // enable resizing
	        margins: '0 0 0 0',
	        layout: 'border',
	        items:[{
	        	xtype: 'tabpanel',
                region: 'center',
                margins: '0 0 0 0',
                tabPosition: 'top',
                activeTab: 0,
                items: [{
                	title: 'Array Grid',
                	xtype: 'array-grid'
                }]
	        }]
	    },{
	        // xtype: 'panel' implied by default
	        title: 'Create/Update Karyawan',
	        region:'east',
	        xtype: 'panel',
	        margins: '0 0 0 5',
	        width: '70%',
	        collapsible: true,   // make collapsible
	        id: 'west-region-container',
	        layout: 'fit',
	        items: [{xtype: 'KaryawanForm'}]
	    },{
	        title: 'Karyawan',
	        region: 'center',     // center region is required, no width/height specified
	        xtype: 'panel',
	        layout: 'fit',
	        margins: '0 0 0 0',
	        items: [{xtype: 'KaryawanList'}]
	    }];
		
        this.callParent(arguments);
    }
    
});