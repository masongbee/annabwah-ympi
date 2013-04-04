Ext.define('YMPI.view.PROSES.Test', {
	extend: 'Ext.grid.Panel',
    
    title		: 'Test',
    itemId		: 'Test',
    alias       : 'widget.Test',
	store 		: 'Test',
    columnLines : true,
    region		: 'center',
    
    frame		: true,
    
    margins		: 0,
    
    initComponent: function(){
    	this.dockedItems = [
            {
            	xtype: 'toolbar',
            	frame: true,
                items: [{
                	itemId	: 'btnadd',
                    text	: 'Add',
                    iconCls	: 'icon-add',
                    action	: 'create',
                    disabled: true
                }, '-', {
                    itemId	: 'btndelete',
                    text	: 'Delete',
                    iconCls	: 'icon-remove',
                    action	: 'delete',
                    disabled: true
                }]
            }
        ];
        
        this.callParent(arguments);
    }

});