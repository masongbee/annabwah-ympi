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
            	xtype	: 'UserGroupList',
            	flex: 1
            },{
            	xtype	: 'PermissionGroupList',
            	flex: 1
            } ]
    	},{
    		region: 'south',
    		layout: {
                type : 'vbox',
                align: 'stretch'
            },
            items: [{
            	xtype	: 'UserList',
            	flex: 1
            } ]
        } ];
        
    	this.callParent(arguments);
    }

});