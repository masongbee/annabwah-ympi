Ext.define('YMPI.view.dataMaster.Jabatan', {
	extend: 'Ext.grid.Panel',
    requires: ['YMPI.store.Jabatan'],
    
    title		: 'Jabatan',
    itemId		: 'jabatanGrid',
    alias       : 'widget.jabatanGrid',
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
					  if(e.record.data.ID == 0){
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
					  if(eval(e.record.data.KODEJAB) < 1){
						  Ext.Msg.alert('Peringatan', 'Kolom \"Kode Jabatan\" tidak boleh \"00\".');
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