Ext.define('YMPI.view.MASTER.JabatanList', {
	extend: 'Ext.grid.Panel',
    requires: ['YMPI.store.Jabatan'],
    
    title		: 'Jabatan',
    itemId		: 'JabatanList',
    alias       : 'widget.JabatanList',
	store 		: 'Jabatan',
    columnLines : true,
    region		: 'center',
    
    //width		: 500,
    //height	: 300,
    frame		: true,
    
    margins		: 0,
    
    //flex		: 1,
    
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
					  if(e.record.data.KODEJAB == ''){
						  editor.cancelEdit();
						  var sm = e.grid.getSelectionModel();
						  e.store.remove(sm.getSelection());
					  }
				  },
				  'validateedit': function(editor, e){
					  /*if(eval(e.record.data.KODEJAB) < 1){
						  Ext.Msg.alert('Peringatan', 'Kolom \"Jabatan\" tidak boleh \"00\".');
						  return false;
					  }
					  return true;*/
				  },
				  'afteredit': function(editor, e){
					  if(e.record.data.KODEJAB == ''){
						  Ext.Msg.alert('Peringatan', 'Kolom \"Kode Jabatan\" tidak boleh kosong.');
						  return false;
					  }
					  e.store.sync();
					  return true;
				  }
			  }
		});
    	
        this.columns = [
            { header: 'Kode Jabatan', dataIndex: 'KODEJAB', editor: {xtype: 'textfield'} },
            { header: 'Nama Jabatan', dataIndex: 'NAMAJAB', width: 250, editor: {xtype: 'textfield'} }
        ];
        this.plugins = [this.rowEditing];
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
            },
            {
                xtype: 'pagingtoolbar',
                store: 'Jabatan',
                dock: 'bottom',
                displayInfo: false
            }
        ];
        
        /*this.listeners = {
    		'selectionchange': function(view, records) {
                this.down('#btndelete').setDisabled(!records.length);
            }
        };*/
        
        this.callParent(arguments);
    }

});