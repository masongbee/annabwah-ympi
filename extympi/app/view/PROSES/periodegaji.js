Ext.define('YMPI.view.PROSES.periodegaji', {
	extend: 'Ext.grid.Panel',
    requires: ['YMPI.store.periodegaji'],
    
    title		: 'Periode Gaji',
    itemId		: 'periodegaji',
    alias       : 'widget.periodegaji',
	store 		: 'periodegaji',
    columnLines : true,
    region		: 'center',    
	//height		: 495,
    frame		: true,
    //layout		: 'fit',
    margins		: 0,
    
    initComponent: function(){
    	var usernameField = Ext.create('Ext.form.field.Text');
    	
    	var karStore = Ext.create('YMPI.store.periodegaji');
    	var karField= new Ext.form.ComboBox({
			store: karStore,
			queryMode: 'local',
			displayField:'NIK',
			valueField: 'NIK',
	        typeAhead: false,
	        loadingText: 'Searching...',
			pageSize:10,
	        hideTrigger:false,
			allowBlank: false,
	        tpl: Ext.create('Ext.XTemplate',
                '<tpl for=".">',
                    '<div class="x-boundlist-item">[<b>{NIK}</b>] -  {NIK}</div>',
                '</tpl>'
            ),
            // template for the content inside text field
            displayTpl: Ext.create('Ext.XTemplate',
                '<tpl for=".">',
                	'[{NIK}] - {NIK}',
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
					  if((e.record.data.NIK != '') || (e.record.data.NIK != 0)){
						  usernameField.setReadOnly(true);
						  e.record.data.USER_PASSWD = '';
					  }
					  
				  },
				  'canceledit': function(editor, e){
					  if((e.record.data.NIK == '') || (e.record.data.NIK == 0)){
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
					  if((e.record.data.NIK == '') || (e.record.data.NIK == 0)){
						  Ext.Msg.alert('Peringatan', 'Kolom \"periodegaji Name\" tidak boleh kosong.');
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
            { header: 'NIK', dataIndex: 'NIK', field: usernameField },
            { header: 'BULAN', dataIndex: 'BULAN', width: 250 },
            { header: 'TANGGAL', dataIndex: 'TANGGAL', width: 250 },
            { header: 'JENIS ABSEN', dataIndex: 'JENISABSEN', width: 250 },
            { header: 'JAM KERJA', dataIndex: 'JAMKERJA', width: 250 },
            { header: 'JAM LEMBUR', dataIndex: 'JAMLEMBUR', width: 250 },
            { header: 'JAM KURANG', dataIndex: 'JAMKURANG', width: 250 },
            { header: 'JAM BOLOS', dataIndex: 'JAMBOLOS', width: 250 },
            { header: 'TERLAMBAT', dataIndex: 'TERLAMBAT', width: 250 },
            { header: 'PLGLBH AWAL', dataIndex: 'PLGLBHAWAL', width: 250 },
            { header: 'USER NAME', dataIndex: 'USERNAME', width: 250 }
        ];
        this.plugins = [this.rowEditing];
        this.dockedItems = [
            {
            	xtype: 'toolbar',
            	frame: true,
                items: [{
                	itemId	: 'btnproses',
                    text	: 'Hitung',
                    //iconCls	: 'icon-add',
                    action	: 'Hitung',
                    disabled: false
                },{
                	itemId	: 'btnposting',
                    text	: 'Posting',
                    //iconCls	: 'icon-add',
                    action	: 'Posting',
                    disabled: false
                }]
            },
            {
                xtype: 'pagingtoolbar',
                store: 'periodegaji',
                dock: 'bottom',
                displayInfo: false
            }
        ];
        
        this.callParent(arguments);
    }

});