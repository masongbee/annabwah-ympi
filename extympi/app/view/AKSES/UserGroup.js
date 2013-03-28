Ext.define('YMPI.view.AKSES.UserGroup', {
	extend: 'Ext.grid.Panel',
    requires: ['YMPI.store.UserGroup'],
    
    title		: 'User Group',
    itemId		: 'UserGroupList',
    alias       : 'widget.UserGroupList',
	store 		: 'UserGroup',
    columnLines : true,
    region		: 'center',
    
    frame		: true,
    
    margins		: 0,
    
    initComponent: function(){
    	/*
    	 * Bisa menggunakan ==# var rowEditing #== atau ==# this.rowEditing #==
    	 */
    	/*var rowEditing = Ext.create('Ext.grid.plugin.RowEditing', {
			  clicksToEdit: 2
		});*/
    	var permissionstore 	= Ext.create('YMPI.store.PermissionGroup');
    	var permissiongrouplist	= Ext.create('YMPI.view.AKSES.PermissionGroup');
    	var userlist			= Ext.create('YMPI.view.AKSES.User');
    	this.rowEditing = Ext.create('Ext.grid.plugin.RowEditing', {
			  clicksToEdit: 2,
			  clicksToMoveEditor: 1,
			  listeners: {
				  'canceledit': function(editor, e){
					  if((e.record.data.GROUP_NAME == '')){
						  editor.cancelEdit();
						  var sm = e.grid.getSelectionModel();
						  e.store.remove(sm.getSelection());
					  }
				  },
				  'afteredit': function(editor, e){
					  if(e.record.data.GROUP_NAME == ''){
						  Ext.Msg.alert('Peringatan', 'Kolom \"Nama Group\" tidak boleh kosong.');
						  return false;
					  }
					  permissiongrouplist.disable();
					  userlist.disable();
					  e.store.sync({
						  callback: function(records, options, success){
							  var getGroupId = options.operations.create[0].data.GROUP_ID;
							  console.log(getGroupId);
							  permissionstore.load({
								  params: {
									  group_id: getGroupId
								  }
							  });
							  
							  permissiongrouplist.enable();
							  userlist.enable();
						  }
					  });
					  return true;
				  }
			  }
		});
    	
        this.columns = [
            { header: 'Nama Group', dataIndex: 'GROUP_NAME', editor: {xtype: 'textfield'} },
            { header: 'Keterangan', dataIndex: 'GROUP_DESC', flex: 1, editor: {xtype: 'textfield'} }
        ];
        this.plugins = [this.rowEditing];
        this.dockedItems = [
            {
            	xtype: 'toolbar',
            	frame: true,
                items: [{
                    text	: 'Add',
                    iconCls	: 'icon-add',
                    action	: 'create'
                }, '-', {
                    itemId	: 'btndelete',
                    text	: 'Delete',
                    iconCls	: 'icon-remove',
                    action	: 'delete',
                    disabled: true
                }]
            },
            {
                xtype: 'pagingtoolbar',
                store: 'UserGroup',
                dock: 'bottom',
                displayInfo: false
            }
        ];
        
        this.callParent(arguments);
    }

});