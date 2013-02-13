Ext.define('YMPI.view.dataMaster.Grade', {
	extend: 'Ext.grid.Panel',
    requires: ['YMPI.store.Grade'],
    
    title		: 'List Grade',
    itemId		: 'gradeGrid',
    alias       : 'widget.gradeGrid',
	store 		: 'Grade',
    columnLines : true,
    region		: 'center',
    
    //width		: 500,
    //height	: 300,
    frame		: true,
    
    margin		: 0,
    
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
					  /*if(eval(e.record.data.GRADE) < 1){
						  Ext.Msg.alert('Peringatan', 'Kolom \"Grade\" tidak boleh \"00\".');
						  return false;
					  }
					  return true;*/
				  },
				  'afteredit': function(editor, e){
					  if(eval(e.record.data.GRADE) < 1){
						  Ext.Msg.alert('Peringatan', 'Kolom \"Grade\" tidak boleh \"00\".');
						  return false;
					  }
					  e.store.sync();
					  return true;
				  }
			  }
		});
    	
        this.columns = [
            { header: 'Grade',  dataIndex: 'GRADE', editor: {xtype: 'textfield'} },
            { header: 'Keterangan', dataIndex: 'KETERANGAN', /*flex:1, */ width: 250, editor: {xtype: 'textfield'} }
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
                }, '-', '-',{
                	text	: 'Export Excel',
                    iconCls	: 'icon-excel',
                    action	: 'xexcel'
                }, '-',{
                	text	: 'Cetak',
                    iconCls	: 'icon-print',
                    action	: 'print'
                }]
            },
            {
                xtype: 'pagingtoolbar',
                store: 'Grade',
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