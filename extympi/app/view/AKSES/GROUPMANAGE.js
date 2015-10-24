Ext.define('YMPI.view.AKSES.GROUPMANAGE', {
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
    	}];
        
    	this.callParent(arguments);
    }

});