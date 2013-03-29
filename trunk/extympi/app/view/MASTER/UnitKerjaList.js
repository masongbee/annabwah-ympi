Ext.define('YMPI.view.MASTER.UnitKerjaList', {
	extend: 'Ext.grid.Panel',
    requires: ['YMPI.store.UnitKerja'],
    
    title		: 'Unit Kerja',
    itemId		: 'UnitKerjaList',
    alias       : 'widget.UnitKerjaList',
	store 		: 'UnitKerja',
    columnLines : true,
    //region		: 'center',
    
    //width		: 500,
    //height	: 300,
    frame		: true,
    
    margin		: '0 0 0 0',
    
    //anchor		: '-0, -0',
    
    initComponent: function(){
    	/*
    	 * Bisa menggunakan ==# var rowEditing #== atau ==# this.rowEditing #==
    	 */
    	/*var rowEditing = Ext.create('Ext.grid.plugin.RowEditing', {
			  clicksToEdit: 2
		});*/
    	var kodeunitField = Ext.create('Ext.form.field.Text');
    	
    	this.rowEditing = Ext.create('Ext.grid.plugin.RowEditing', {
			  clicksToEdit: 2,
			  clicksToMoveEditor: 1,
			  listeners: {
				  'beforeedit': function(editor, e){
					  if(e.record.data.KODEUNIT != ''){
						  kodeunitField.setReadOnly(true);
					  }
					  
				  },
				  'canceledit': function(editor, e){
					  if((e.record.data.KODEUNIT == '') || (e.record.data.KODEUNIT == 0)){
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
					  if(e.record.data.KODEUNIT == ''){
						  Ext.Msg.alert('Peringatan', 'Kolom \"Kode\" harus diisi.');
						  return false;
					  }
					  e.store.sync();
					  return true;
					  
				  }
			  }
		});
    	
        this.columns = [
            { header: 'Kode', dataIndex: 'KODEUNIT', field: kodeunitField },
            { header: 'Nama', dataIndex: 'NAMAUNIT', /*flex:1, */ width: 250, editor: {xtype: 'textfield'} }
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
                store: 'UnitKerja',
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