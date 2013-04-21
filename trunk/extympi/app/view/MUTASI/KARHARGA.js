Ext.define('YMPI.view.MUTASI.KARHARGA', {
	extend: 'Ext.grid.Panel',
    requires: [],
    
    itemId		: 'KARHARGA',
    alias       : 'widget.KARHARGA',
	store 		: 'penghargaan',
    
    title		: 'penghargaan',
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
            { header: 'Penghargaan', dataIndex: 'PENGHARGAAN', editor: {xtype: 'textfield'} },
            { header: 'Bulan', dataIndex: 'BULAN', flex: 1, editor: {xtype: 'textfield'} },
            { header: 'Tahun', dataIndex: 'TAHUN', flex: 2, editor: {xtype: 'textfield'} }
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
                store: 'penghargaan',
                dock: 'bottom',
                displayInfo: false
            }
        ];
        
        this.callParent(arguments);
    }

});