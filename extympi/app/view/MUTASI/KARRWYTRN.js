Ext.define('YMPI.view.MUTASI.KARRWYTRN', {
	extend: 'Ext.grid.Panel',
    requires: [],
    
    itemId		: 'KARRWYTRN',
    alias       : 'widget.KARRWYTRN',
	store 		: 'riwayattraining',
    
    title		: 'riwayattraining',
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
            { header: 'Keterangan', dataIndex: 'KETERANGAN', editor: {xtype: 'textfield'} },
            { header: 'Nama Training', dataIndex: 'NAMATRAINING', flex: 2, editor: {xtype: 'textfield'} },
            { header: 'Tempat', dataIndex: 'TEMPAT', flex: 2, editor: {xtype: 'textfield'} },
            { header: 'Penyelenggara', dataIndex: 'PENYELENGGARA', flex: 2, editor: {xtype: 'textfield'} },
            { header: 'Tgl Mulai', dataIndex: 'TGLMULAI', 
            	editor: {
                    xtype: 'datefield',
                    allowBlank: false,
                    format: 'm/d/Y',
                    minValue: '01/01/2006',
                    minText: 'Cannot have a start date before the company existed!',
                    maxValue: Ext.Date.format(new Date(), 'm/d/Y')
                }},
            { header: 'Tgl Sampai', dataIndex: 'TGLSAMPAI', 
            	editor: {
                    xtype: 'datefield',
                    allowBlank: false,
                    format: 'm/d/Y',
                    minValue: '01/01/2006',
                    minText: 'Cannot have a start date before the company existed!',
                    maxValue: Ext.Date.format(new Date(), 'm/d/Y')
                }}
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
                store: 'riwayattraining',
                dock: 'bottom',
                displayInfo: false
            }
        ];
        
        this.callParent(arguments);
    }

});