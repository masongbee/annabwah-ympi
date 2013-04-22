Ext.define('YMPI.view.TRANSAKSI.lembur', {
	extend: 'Ext.grid.Panel',
    requires: ['YMPI.store.lembur'],
    
    title		: 'Lembur',
    itemId		: 'lembur',
    alias       : 'widget.lembur',
	store 		: 'lembur',
    columnLines : true,
    region		: 'center',    
	//height		: 495,
    frame		: true,
    //layout		: 'fit',
    margins		: 0,
    
    initComponent: function(){
    	var usernameField = Ext.create('Ext.form.field.Text');
    	
    	var karStore = Ext.create('YMPI.store.lembur');
    	var karField= new Ext.form.ComboBox({
			store: karStore,
			queryMode: 'local',
			displayField:'NOLEMBUR',
			valueField: 'NOLEMBUR',
	        typeAhead: false,
	        loadingText: 'Searching...',
			pageSize:10,
	        hideTrigger:false,
			allowBlank: false,
	        tpl: Ext.create('Ext.XTemplate',
                '<tpl for=".">',
                    '<div class="x-boundlist-item">[<b>{NOLEMBUR}</b>] -  {NOLEMBUR}</div>',
                '</tpl>'
            ),
            // template for the content inside text field
            displayTpl: Ext.create('Ext.XTemplate',
                '<tpl for=".">',
                	'[{NOLEMBUR}] - {NOLEMBUR}',
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
					  if((e.record.data.NOLEMBUR != '') || (e.record.data.NOLEMBUR != 0)){
						  usernameField.setReadOnly(true);
						  e.record.data.USER_PASSWD = '';
					  }
					  
				  },
				  'canceledit': function(editor, e){
					  if((e.record.data.NOLEMBUR == '') || (e.record.data.NOLEMBUR == 0)){
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
					  if((e.record.data.NOLEMBUR == '') || (e.record.data.NOLEMBUR == 0)){
						  Ext.Msg.alert('Peringatan', 'Kolom \"lembur Name\" tidak boleh kosong.');
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
            { header: 'NO LEMBUR', dataIndex: 'NOLEMBUR', field: usernameField },
            { header: 'KODE UNIT', dataIndex: 'KODEUNIT', editor: {xtype: 'textfield'} },
            { header: 'TANGGAL', dataIndex: 'TANGGAL', editor: {xtype: 'textfield'} },
            { header: 'KEPERLUAN', dataIndex: 'KEPERLUAN', editor: {xtype: 'textfield'} },
            { header: 'NIKUSUL', dataIndex: 'NIKUSUL', editor: {xtype: 'textfield'} },
            { header: 'NIK SETUJU', dataIndex: 'NIKSETUJU', editor: {xtype: 'textfield'} },
            { header: 'NIK DIKETAHUI', dataIndex: 'NIKDIKETAHUI', editor: {xtype: 'textfield'} },
            { header: 'NIK PERSONALIA', dataIndex: 'NIKPERSONALIA', editor: {xtype: 'textfield'} },
            { header: 'TGL SETUJU', dataIndex: 'TGLSETUJU', editor: {xtype: 'textfield'} },
            { header: 'TGL PERSONALIA', dataIndex: 'TGLPERSONALIA', editor: {xtype: 'textfield'} },
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
                    disabled: false
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
                store: 'lembur',
                dock: 'bottom',
                displayInfo: false
            }
        ];
        
        this.callParent(arguments);
    }

});