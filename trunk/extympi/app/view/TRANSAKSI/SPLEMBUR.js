Ext.define('YMPI.view.TRANSAKSI.SPLEMBUR', {
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
            	xtype	: 'lembur',
            	flex: 1
            }]
    	},{
    		region: 'center',
    		layout: {
                type : 'vbox',
                align: 'stretch'
            },
            items: [{
            	xtype	: 'rencanalembur',
            	flex: 1
            } ]
        } ];
        
    	this.callParent(arguments);
    }

});