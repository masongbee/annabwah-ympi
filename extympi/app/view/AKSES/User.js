Ext.define('YMPI.view.AKSES.User', {
	extend: 'Ext.grid.Panel',
    requires: ['YMPI.store.User'],
    
    title		: 'Users',
    itemId		: 'UserList',
    alias       : 'widget.UserList',
	store 		: 'User',
    columnLines : true,
    region		: 'center',
    
    frame		: true,
    
    margins		: 0,
    
    initComponent: function(){
    	/*
    	 * Bisa menggunakan ==# var rowEditing #== atau ==# this.rowEditing #==
    	 */
    	/*var rowEditing = Ext.create('Ext.grid.plugin.RowEditing', {
			  clicksToEdit: 2
		});*/
    	var usernameField = Ext.create('Ext.form.field.Text');
    	
    	var karStore = Ext.create('YMPI.store.Karyawan');
    	var karField= new Ext.form.ComboBox({
			store: karStore,
			queryMode: 'local',
			displayField:'NAMAKAR',
			valueField: 'NIK',
	        typeAhead: false,
	        loadingText: 'Searching...',
			pageSize:10,
	        hideTrigger:false,
			allowBlank: false,
	        tpl: Ext.create('Ext.XTemplate',
                '<tpl for=".">',
                    '<div class="x-boundlist-item">[<b>{NIK}</b>] -  {NAMAKAR}</div>',
                '</tpl>'
            ),
            // template for the content inside text field
            displayTpl: Ext.create('Ext.XTemplate',
                '<tpl for=".">',
                	'[{NIK}] - {NAMAKAR}',
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
					  if((e.record.data.USER_NAME != '') || (e.record.data.USER_NAME != 0)){
						  usernameField.setReadOnly(true);
						  e.record.data.USER_PASSWD = '';
					  }
					  
				  },
				  'canceledit': function(editor, e){
					  if((e.record.data.USER_NAME == '') || (e.record.data.USER_NAME == 0)){
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
					  if((e.record.data.USER_NAME == '') || (e.record.data.USER_NAME == 0)){
						  Ext.Msg.alert('Peringatan', 'Kolom \"User Name\" tidak boleh kosong.');
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
            { header: 'User Name', dataIndex: 'USER_NAME', field: usernameField },
            { header: 'Password', dataIndex: 'USER_PASSWD', editor: {xtype: 'textfield'} },
            { header: 'NIK', dataIndex: 'NIK', width: 250 },
            { header: 'NAMA KARYAWAN', dataIndex: 'NAMAKAR', width: 250 }
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
                    disabled: false
                }]
            },
            {
                xtype: 'pagingtoolbar',
                store: 'User',
                dock: 'bottom',
                displayInfo: false
            }
        ];
        
        this.callParent(arguments);
    }

});