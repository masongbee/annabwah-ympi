Ext.define('YMPI.view.MUTASI.KARSKILL', {
	extend: 'Ext.grid.Panel',
    requires: [],
    
    itemId		: 'KARSKILL',
    alias       : 'widget.KARSKILL',
    store 		: 'Skill',
    
    title		: 'Keahlian',
    columnLines : true,
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
    	
    	var PermissionsStore 	= Ext.create('YMPI.store.Permissions');
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
							  PermissionsStore.load({
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
						  }
					  });
					  return true;
				  }
			  }
		});
    	
        this.columns = [
            { header: 'No. Urut', dataIndex: 'NOURUT', editor: {xtype: 'textfield'} },
            { header: 'Nama Keahlian', dataIndex: 'NAMASKILL', flex: 1, editor: {xtype: 'textfield'} },
            { header: 'Keterangan', dataIndex: 'KETERANGAN', flex: 2, editor: {xtype: 'textfield'} }
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
                store: 'Skill',
                dock: 'bottom',
                displayInfo: false
            }
        ];
        
        this.callParent(arguments);
    }

});