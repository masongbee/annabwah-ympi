Ext.define('YMPI.view.PROSES.IMPORTPRES', {
	extend: 'Ext.form.Panel',
	
	bodyPadding: 0,
	layout: 'border',
    initComponent: function(){
    	this.items = [{
    		region: 'north',
    		layout: {
                type : 'hbox',
                align: 'top'
            },
    		items: [{
            	xtype	: 'ImportPresensi',
            	flex: 1
            }]
    	},{
    		region: 'center',
    		layout: {
                type : 'vbox',
                align: 'stretch'
            },
            items: [{
            	xtype	: 'Presensi',
            	flex: 1
            } ]
        } ];
        
    	this.callParent(arguments);
    }

});