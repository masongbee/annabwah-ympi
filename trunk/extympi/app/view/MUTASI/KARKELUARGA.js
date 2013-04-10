Ext.define('YMPI.view.MUTASI.KARKELUARGA', {
	extend: 'Ext.grid.Panel',
    requires: [],
    
    title		: 'User Group',
    itemId		: 'KARKELUARGA',
    alias       : 'widget.KARKELUARGA',
	store 		: 'Keluarga',
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
						  }
					  });
					  return true;
				  }
			  }
		});
    	
        this.columns = [
            { header: 'No. Urut', dataIndex: 'NOURUT', editor: {xtype: 'textfield'} },
            { header: 'Status', dataIndex: 'STATUSKEL', editor: {xtype: 'textfield'} },
            { header: 'Nama', dataIndex: 'NAMAKEL', flex: 1, editor: {xtype: 'textfield'} },
            { header: 'L/P', dataIndex: 'JENISKEL', 
            	editor: new Ext.form.field.ComboBox({
                    typeAhead: true,
                    triggerAction: 'all',
                    selectOnTab: true,
                    store: [
                        ['L','Laki-laki'],
                        ['P','Perempuan']
                    ],
                    lazyRender: true,
                    listClass: 'x-combo-list-small'
                })},
            { header: 'Alamat', dataIndex: 'ALAMAT', editor: {xtype: 'textfield'} },
            { header: 'Tmpt Lahir', dataIndex: 'TMPLAHIR', editor: {xtype: 'textfield'} },
            { header: 'Tgl Lahir', dataIndex: 'TGLLAHIR', 
            	editor: {
                    xtype: 'datefield',
                    allowBlank: false,
                    format: 'm/d/Y',
                    minValue: '01/01/2006',
                    minText: 'Cannot have a start date before the company existed!',
                    maxValue: Ext.Date.format(new Date(), 'm/d/Y')
                }},
            { header: 'Jaminan SPKK?', dataIndex: 'TANGGUNGSPKK', width: 200, 
            	editor: {
                    xtype: 'checkbox',
                    cls: 'x-grid-checkheader-editor'
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
                store: 'Keluarga',
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