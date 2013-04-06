Ext.define('YMPI.view.PROSES.IMPORTPRES', {
	extend: 'Ext.form.Panel',
	
	bodyPadding: 0,
	layout: 'border',
    initComponent: function(){
    	this.items = [{
    		region: 'center',
    		layout: {
                type : 'hbox',
                align: 'stretch'
            },
    		items: [{
            	xtype	: 'Presensi',
            	flex: 1
            }]
    	},{
    		region: 'south',
    		layout: {
                type : 'vbox',
                align: 'stretch'
            },
            items: [{
            	xtype	: 'UserGroup',
            	flex: 1
            } ]
        } ];
        
    	this.callParent(arguments);
    }

});