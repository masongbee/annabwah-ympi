Ext.define('YMPI.view.file.UserManager', {
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
            	xtype	: 'UserGroupGrid',
            	flex: 1
            },{
            	xtype	: 'PermissionGroupGrid',
            	flex: 1
            } ]
    	},{
    		region: 'south',
    		layout: {
                type : 'vbox',
                align: 'stretch'
            },
            items: [{
            	xtype	: 'UserGrid',
            	flex: 1
            } ]
        } ];
        
    	this.callParent(arguments);
    }

});