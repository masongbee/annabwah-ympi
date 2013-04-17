Ext.define('YMPI.view.PROSES.HITPRES', {
	extend: 'Ext.form.Panel',
	
	bodyPadding: 0,
	layout: 'border',
    initComponent: function(){
    	this.items = [{
    		region: 'north',
    		layout: {
                type : 'hbox',
                align: 'stretch'
            },
    		items: [{
            	xtype	: 'periodegaji',
            	flex: 1
            }]
    	},{
    		region: 'center',
    		layout: {
                type : 'vbox',
                align: 'stretch'
            },
            items: [{
            	xtype	: 'hitungpresensi',
            	flex: 1
            } ]
        }];
        
    	this.callParent(arguments);
    }

});