Ext.define('YMPI.view.TRANSAKSI.MOHONIZIN', {
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
            	xtype	: 'permohonanijin',
            	flex: 1
            }]
    	} ];
        
    	this.callParent(arguments);
    }

});