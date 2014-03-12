Ext.define('YMPI.view.AKSES.USERMANAGE', {
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
            	xtype	: 'UserGroup',
            	flex: 1
            },{
            	xtype	: 'Permission',
            	flex: 1
            } ]
    	},{
    		region: 'north',
    		layout: {
                type : 'vbox',
                align: 'stretch'
            },
            items: [{
            	xtype	: 'User',
            	flex: 1
            } ]
        } ];
        
    	this.callParent(arguments);
    }

});