Ext.define('YMPI.view.TRANSAKSI.permohonancuti', {
	extend: 'Ext.grid.Panel',
    requires: ['YMPI.store.permohonancuti'],
    
    title		: 'Permohonan Cuti',
    itemId		: 'permohonancuti',
    alias       : 'widget.permohonancuti',
	store 		: 'permohonancuti',
    columnLines : true,
    region		: 'center',    
	//height		: 495,
    frame		: true,
    //layout		: 'fit',
    margins		: 0,
    
    initComponent: function(){
    	var usernameField = Ext.create('Ext.form.field.Text');
    	
    	var karStore = Ext.create('YMPI.store.permohonancuti');
    	var karField= new Ext.form.ComboBox({
			store: karStore,
			queryMode: 'local',
			displayField:'NOCUTI',
			valueField: 'NOCUTI',
	        typeAhead: false,
	        loadingText: 'Searching...',
			pageSize:10,
	        hideTrigger:false,
			allowBlank: false,
	        tpl: Ext.create('Ext.XTemplate',
                '<tpl for=".">',
                    '<div class="x-boundlist-item">[<b>{NOCUTI}</b>] -  {NOCUTI}</div>',
                '</tpl>'
            ),
            // template for the content inside text field
            displayTpl: Ext.create('Ext.XTemplate',
                '<tpl for=".">',
                	'[{NOCUTI}] - {NOCUTI}',
                '</tpl>'
            ),
	        itemSelector: 'div.search-item',
			triggerAction: 'all',
			lazyRender:true,
			listClass: 'x-combo-list-small',
			anchor:'95%',
			forceSelection:true
		});
    	
    	this.rowEditing = Ext.create('Ext.grid.plugin.RowEditing', {
			  clicksToEdit: 2,
			  clicksToMoveEditor: 1,
			  listeners: {
				  'beforeedit': function(editor, e){
					  if((e.record.data.NOCUTI != '') || (e.record.data.NOCUTI != 0)){
						  usernameField.setReadOnly(true);
						  e.record.data.USER_PASSWD = '';
					  }
					  
				  },
				  'canceledit': function(editor, e){
					  if((e.record.data.NOCUTI == '') || (e.record.data.NOCUTI == 0)){
						  editor.cancelEdit();
						  var sm = e.grid.getSelectionModel();
						  e.store.remove(sm.getSelection());
					  }
					  usernameField.setReadOnly(false);
				  },
				  'validateedit': function(editor, e){
					  /*if(eval(e.record.data.KODEJAB) < 1){
						  Ext.Msg.alert('Peringatan', 'Kolom \"Jabatan\" tidak boleh \"00\".');
						  return false;
					  }
					  return true;*/
				  },
				  'afteredit': function(editor, e){
					  if((e.record.data.NOCUTI == '') || (e.record.data.NOCUTI == 0)){
						  Ext.Msg.alert('Peringatan', 'Kolom \"permohonancuti Name\" tidak boleh kosong.');
						  return false;
					  }
					  e.store.sync();
					  
					  usernameField.setReadOnly(false);
					  e.record.data.USER_PASSWD = '[hidden]';
					  
					  return true;
				  }
			  }
		});
    	
        this.columns = [
            { header: 'NO CUTI', dataIndex: 'NOCUTI', field: usernameField },
            { header: 'KODE UNIT', dataIndex: 'KODEUNIT' },
            { header: 'NIK ATASAN 1', dataIndex: 'NIKATASAN1' },
            { header: 'NIK ATASAN 2', dataIndex: 'NIKATASAN2' },
            { header: 'NIK ATASAN 3', dataIndex: 'NIKATASAN3' },
            { header: 'NIK HR', dataIndex: 'NIKHR' },
            { header: 'TGL ATASAN 1', dataIndex: 'TGLATASAN1' },
            { header: 'TGL ATASAN 2', dataIndex: 'TGLATASAN2' },
            { header: 'TGL ATASAN 3', dataIndex: 'TGLATASAN3' },
            { header: 'TGL HR', dataIndex: 'TGLHR' },
            { header: 'USERNAME', dataIndex: 'USERNAME' }
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
                store: 'permohonancuti',
                dock: 'bottom',
                displayInfo: false
            }
        ];
        
        this.callParent(arguments);
    }

});