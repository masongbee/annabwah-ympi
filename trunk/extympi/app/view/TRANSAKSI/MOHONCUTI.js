Ext.define('YMPI.view.TRANSAKSI.MOHONCUTI', {
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
            	xtype	: 'permohonancuti',
            	flex: 1
            }]
    	},{
    		region: 'center',
    		layout: {
                type : 'vbox',
                align: 'stretch'
            },
            items: [{
            	xtype	: 'rinciancuti',
            	flex: 1
            } ]
        } ];
        
    	this.callParent(arguments);
    }

});