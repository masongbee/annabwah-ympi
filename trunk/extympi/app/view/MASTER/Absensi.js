Ext.define('YMPI.view.MASTER.Absensi', {
	extend: 'Ext.grid.Panel',
    requires: ['YMPI.store.Absensi'],
    
    title		: 'Absensi',
    itemId		: 'Absensi',
    alias       : 'widget.Absensi',
	store 		: 'Absensi',
    columnLines : true,
    region		: 'center',    
	//height		: 495,
    frame		: true,
    //layout		: 'fit',
    margins		: 0,
    
    initComponent: function(){
    	var jenisabsenField = Ext.create('Ext.form.field.Text');
    	
    	var karStore = Ext.create('YMPI.store.Absensi');
    	var karField= new Ext.form.ComboBox({
			store: karStore,
			queryMode: 'local',
			displayField:'JENISABSEN',
			valueField: 'JENISABSEN',
	        typeAhead: false,
	        loadingText: 'Searching...',
			pageSize:10,
	        hideTrigger:false,
			allowBlank: false,
	        tpl: Ext.create('Ext.XTemplate',
                '<tpl for=".">',
                    '<div class="x-boundlist-item">[<b>{JENISABSEN}</b>] -  {JENISABSEN}</div>',
                '</tpl>'
            ),
            // template for the content inside text field
            displayTpl: Ext.create('Ext.XTemplate',
                '<tpl for=".">',
                	'[{JENISABSEN}] - {JENISABSEN}',
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
					  if((e.record.data.JENISABSEN != '') || (e.record.data.JENISABSEN != 0)){
						  jenisabsenField.setReadOnly(true);
						  e.record.data.USER_PASSWD = '';
					  }
					  
				  },
				  'canceledit': function(editor, e){
					  if((e.record.data.JENISABSEN == '') || (e.record.data.JENISABSEN == 0)){
						  editor.cancelEdit();
						  var sm = e.grid.getSelectionModel();
						  e.store.remove(sm.getSelection());
					  }
					  jenisabsenField.setReadOnly(false);
				  },
				  'validateedit': function(editor, e){
					  /*if(eval(e.record.data.KODEJAB) < 1){
						  Ext.Msg.alert('Peringatan', 'Kolom \"Jabatan\" tidak boleh \"00\".');
						  return false;
					  }
					  return true;*/
				  },
				  'afteredit': function(editor, e){
					  if((e.record.data.JENISABSEN == '') || (e.record.data.JENISABSEN == 0)){
						  Ext.Msg.alert('Peringatan', 'Kolom \"Absensi Name\" tidak boleh kosong.');
						  return false;
					  }
					  e.store.sync();
					  
					  jenisabsenField.setReadOnly(false);
					  e.record.data.USER_PASSWD = '[hidden]';
					  
					  return true;
				  }
			  }
		});
    	
        this.columns = [
            { header: 'JENIS ABSEN', dataIndex: 'JENISABSEN', field: jenisabsenField },
            { header: 'KETERANGAN', dataIndex: 'KETERANGAN', width: 250 }
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
                store: 'Absensi',
                dock: 'bottom',
                displayInfo: false
            }
        ];
        
        this.callParent(arguments);
    }

});