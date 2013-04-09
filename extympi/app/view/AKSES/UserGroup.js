Ext.define('YMPI.view.AKSES.UserGroup', {
	extend: 'Ext.grid.Panel',
    requires: ['YMPI.store.UserGroup'],
    
    title		: 'User Group',
    itemId		: 'UserGroup',
    alias       : 'widget.UserGroup',
	store 		: 'UserGroup',
    columnLines : true,
    region		: 'center',
    
    frame		: true,
    
    margins		: 0,
    
    selectedRecords: [],
    
    initComponent: function(){
    	/*
    	 * Bisa menggunakan ==# var rowEditing #== atau ==# this.rowEditing #==
    	 */
    	/*var rowEditing = Ext.create('Ext.grid.plugin.RowEditing', {
			  clicksToEdit: 2
		});*/
    	
    	var PermissionGroupStore 	= Ext.create('YMPI.store.PermissionGroup');
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
					  e.store.sync({
						  success: function(rec, op){
							  var rs = rec.proxy.reader.jsonData.data;
							  var getGroupId = rs.GROUP_ID;
							  console.log(getGroupId);
							  PermissionGroupStore.load({
								  params: {
									  GROUP_ID: getGroupId
								  }
							  });
							  e.store.loadPage(1,{
								  callback: function(){
									  var sm = e.grid.getSelectionModel();
									  sm.select(0);
								  }
							  });
							  /*var sm = e.grid.getSelectionModel();
							  //var selection = sm.getSelection();
							  //e.store.remove(selection);
							  e.store.removeAt(0);
							  console.log(rs);
							  e.store.insert(0, rs);
							  sm.select(0);*/
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
        
        this.getStore().on('beforeload', this.rememberSelection, this);
        this.getView().on('refresh', this.refreshSelection, this);
    },
    
    rememberSelection: function(selModel, selectedRecords) {
        this.selectedRecords = this.getSelectionModel().getSelection();
        this.getView().saveScrollState();
    },
    refreshSelection: function() {
        if (0 >= this.selectedRecords.length) {
            return;
        }

        var newRecordsToSelect = [];
        for (var i = 0; i < this.selectedRecords.length; i++) {
            record = this.getStore().getById(this.selectedRecords[i].getId());
            if (!Ext.isEmpty(record)) {
                newRecordsToSelect.push(record);
            }
        }

        this.getSelectionModel().select(newRecordsToSelect);   /*Ext.defer(this.setScrollTop, 30, this, [this.getView().scrollState.top]);*/
    }

});