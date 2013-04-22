Ext.define('YMPI.view.TRANSAKSI.permohonanijin', {
	extend: 'Ext.grid.Panel',
    requires: ['YMPI.store.permohonanijin'],
    
    title		: 'Permohonan Ijin',
    itemId		: 'permohonanijin',
    alias       : 'widget.permohonanijin',
	store 		: 'permohonanijin',
    columnLines : true,
    region		: 'center',    
	//height		: 495,
    frame		: true,
    //layout		: 'fit',
    margins		: 0,
    
    initComponent: function(){
    	var usernameField = Ext.create('Ext.form.field.Text');
    	
    	var karStore = Ext.create('YMPI.store.permohonanijin');
    	var karField= new Ext.form.ComboBox({
			store: karStore,
			queryMode: 'local',
			displayField:'NOIJIN',
			valueField: 'NOIJIN',
	        typeAhead: false,
	        loadingText: 'Searching...',
			pageSize:10,
	        hideTrigger:false,
			allowBlank: false,
	        tpl: Ext.create('Ext.XTemplate',
                '<tpl for=".">',
                    '<div class="x-boundlist-item">[<b>{NOIJIN}</b>] -  {NOIJIN}</div>',
                '</tpl>'
            ),
            // template for the content inside text field
            displayTpl: Ext.create('Ext.XTemplate',
                '<tpl for=".">',
                	'[{NOIJIN}] - {NOIJIN}',
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
					  if((e.record.data.NOIJIN != '') || (e.record.data.NOIJIN != 0)){
						  usernameField.setReadOnly(true);
						  e.record.data.USER_PASSWD = '';
					  }
					  
				  },
				  'canceledit': function(editor, e){
					  if((e.record.data.NOIJIN == '') || (e.record.data.NOIJIN == 0)){
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
					  if((e.record.data.NOIJIN == '') || (e.record.data.NOIJIN == 0)){
						  Ext.Msg.alert('Peringatan', 'Kolom \"permohonanijin Name\" tidak boleh kosong.');
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
            { header: 'NO IJIN', dataIndex: 'NOIJIN', field: usernameField },
            { header: 'NIK', dataIndex: 'NIK' },
            { header: 'JENIS ABSEN', dataIndex: 'JENISABSEN' },
            { header: 'TANGGAL', dataIndex: 'TANGGAL' },
            { header: 'JAM DARI', dataIndex: 'JAMDARI' },
            { header: 'JAM SAMPAI', dataIndex: 'JAMSAMPAI' },
            { header: 'KEMBALI', dataIndex: 'KEMBALI' },
            { header: 'DIAGNOSA', dataIndex: 'DIAGNOSA' },
            { header: 'TINDAKAN', dataIndex: 'TINDAKAN' },
            { header: 'ANJURAN', dataIndex: 'ANJURAN' },
            { header: 'PETUGAS KLINIK', dataIndex: 'PETUGASKLINIK' },
            { header: 'NIK ATASAN', dataIndex: 'NIKATASAN1' },
            { header: 'NIK PERSONALIA', dataIndex: 'NIKPERSONALIA' },
            { header: 'NIK GA', dataIndex: 'NIKGA' },
            { header: 'NIK DRIVER', dataIndex: 'NIKDRIVER' },
            { header: 'NIK SECURITY', dataIndex: 'NIKSECURITY' },
            { header: 'USER NAME', dataIndex: 'USERNAME' }
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
                store: 'permohonanijin',
                dock: 'bottom',
                displayInfo: false
            }
        ];
        
        this.callParent(arguments);
    }

});