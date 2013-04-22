Ext.define('YMPI.view.TRANSAKSI.rencanalembur', {
	extend: 'Ext.grid.Panel',
    requires: ['YMPI.store.rencanalembur'],
    
    title		: 'Rencana Lembur',
    itemId		: 'rencanalembur',
    alias       : 'widget.rencanalembur',
	store 		: 'rencanalembur',
    columnLines : true,
    region		: 'center',    
	//height		: 495,
    frame		: true,
    //layout		: 'fit',
    margins		: 0,
    
    initComponent: function(){
    	var usernameField = Ext.create('Ext.form.field.Text');
    	
    	var karStore = Ext.create('YMPI.store.rencanalembur');
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
						  Ext.Msg.alert('Peringatan', 'Kolom \"rencanalembur Name\" tidak boleh kosong.');
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
            { header: 'NO URUT', dataIndex: 'NOURUT', editor: {xtype: 'textfield'} },
            { header: 'NIK', dataIndex: 'NIK', editor: {xtype: 'textfield'} },
            { header: 'TJ MASUK', dataIndex: 'TJMASUK', editor: {xtype: 'textfield'} },
            { header: 'TJ KELUAR', dataIndex: 'TJKELUAR', editor: {xtype: 'textfield'} },
            { header: 'ANTAR JEMPUT', dataIndex: 'ANTARJEMPUT', editor: {xtype: 'textfield'} },
            { header: 'MAKAN', dataIndex: 'MAKAN', editor: {xtype: 'textfield'} }
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
                store: 'rencanalembur',
                dock: 'bottom',
                displayInfo: false
            }
        ];
        
        this.callParent(arguments);
    }

});