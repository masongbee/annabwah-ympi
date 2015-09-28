Ext.define('YMPI.view.TRANSAKSI.v_rekapjemputan', {
	extend: 'Ext.grid.Panel',
	requires: ['YMPI.store.s_rekapjemputan'],
	
	title		: 'rekapjemputan',
	itemId		: 'Listrekapjemputan',
	alias       : 'widget.Listrekapjemputan',
	store 		: 's_rekapjemputan',
	columnLines : true,
	frame		: true,
	
	margin		: 0,
	selectedIndex: -1,
	
	initComponent: function(){
		var me = this;

		/*STORE start*/
		var nik_store = Ext.create('YMPI.store.s_karyawan', {
			autoLoad: true
		});
		/*STORE end*/

		var NIK_field = Ext.create('Ext.form.ComboBox', {
			store: nik_store,
			queryMode: 'remote',
			displayField:'NAMAKAR',
			valueField: 'NIK',
	        typeAhead: false,
	        loadingText: 'Searching...',
			pageSize:10,
	        hideTrigger: false,
			allowBlank: false,
	        tpl: Ext.create('Ext.XTemplate',
                '<tpl for=".">',
                    '<div class="x-boundlist-item">[<b>{NIK}</b>] - {NAMAKAR}</div>',
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
			anchor:'100%',
			forceSelection:true
		});
		var BULAN_field = Ext.create('Ext.form.field.Month', {
			allowBlank : false,
			format: 'M, Y'
		});
		var JMLJEMPUT_field = Ext.create('Ext.form.field.Number', {
			allowBlank : true,
			maxLength: 11
		});
		this.rowEditing = Ext.create('Ext.grid.plugin.RowEditing', {
			clicksToEdit: 2,
			clicksToMoveEditor: 1,
			listeners: {
				'beforeedit': function(editor, e){
					if(! (/^\s*$/).test(e.record.data.BULAN) || ! (/^\s*$/).test(e.record.data.NIK) ){
						BULAN_field.setReadOnly(true);
						NIK_field.setReadOnly(true);
					}else{
						BULAN_field.setReadOnly(false);
						NIK_field.setReadOnly(false);
					}
					
				},
				'canceledit': function(editor, e){
					if((/^\s*$/).test(e.record.data.BULAN) || (/^\s*$/).test(e.record.data.NIK) ){
						editor.cancelEdit();
						var sm = e.grid.getSelectionModel();
						e.store.remove(sm.getSelection());
					}
				},
				'validateedit': function(editor, e){
				},
				'afteredit': function(editor, e){
					var me = this;
					if((/^\s*$/).test(e.record.data.BULAN) ){
						Ext.Msg.alert('Peringatan', 'Kolom "BULAN" tidak boleh kosong.');
						return false;
					}
					var jsonData = Ext.encode(e.record.data);
					
					Ext.Ajax.request({
						method: 'POST',
						url: 'c_rekapjemputan/save',
						params: {data: jsonData},
						success: function(response){
							e.store.reload({
								callback: function(){
									var newRecordIndex = e.store.findBy(
										function(record, id) {
											if (record.get('BULAN') === e.record.data.BULAN && parseFloat(record.get('NIK')) === e.record.data.NIK) {
												return true;
											}
											return false;
										}
									);
									me.grid.getSelectionModel().select(newRecordIndex);
								}
							});
						}
					});
					return true;
				}
			}
		});
		var upload_form = Ext.create('Ext.form.Panel', {
			width: 300,
			frame: false,
			bodyPadding: 0,
			
			items: [{
				xtype: 'fieldcontainer',
				layout: 'hbox',
				items: [{
					xtype: 'filefield',
					emptyText: 'Select a file to upload',
					name: 'userfile',
					width: 220
				},{
					xtype: 'splitter'
				},{
					xtype: 'button',
					text: 'Upload',
					handler: function(){
						var form = this.up('form').getForm();
						if(form.isValid()){
							form.submit({
								url: 'c_rekapjemputan/do_upload',
								waitMsg: 'Uploading your file...',
								success: function(fp, o) {
									var obj = Ext.JSON.decode(o.response.responseText);
									Ext.Msg.alert('Success', 'Proses upload dan penambahan data telah berhasil, dengan '+obj.skeepdata+' data yang tidak tersimpan.');
									me.getStore().reload();
								},
								failure: function() {
									Ext.Msg.alert("Error", Ext.JSON.decode(this.response.responseText).msg);
								}
							});
						}
					}
				}]
			}]
		});
		
		this.columns = [
			{
				header: 'NIK',
				dataIndex: 'NIK',
				width: 319,
				field: NIK_field
			},{
				header: 'BULAN',
				dataIndex: 'BULAN',
				width: 120,
				field: BULAN_field
			},{
				header: 'JMLJEMPUT',
				dataIndex: 'JMLJEMPUT',
				width: 100,
				field: JMLJEMPUT_field
			},{
				header: 'KETERANGAN',
				dataIndex: 'KETERANGAN',
				flex: 1,
				field: {
					xtype: 'textfield'
				}
			}];
		this.plugins = [this.rowEditing];
		this.dockedItems = [
			Ext.create('Ext.toolbar.Toolbar', {
				items: [{
					xtype: 'fieldcontainer',
					layout: 'hbox',
					defaultType: 'button',
					items: [{
						text	: 'Add',
						iconCls	: 'icon-add',
						action	: 'create'
					}, {
						xtype: 'splitter'
					}, {
						itemId	: 'btndelete',
						text	: 'Delete',
						iconCls	: 'icon-remove',
						action	: 'delete',
						disabled: true
					}]
				}/*, '-', {
					xtype: 'fieldcontainer',
					layout: 'hbox',
					defaultType: 'button',
					items: [{
						text	: 'Export Excel',
						iconCls	: 'icon-excel',
						action	: 'xexcel'
					}, {
						xtype: 'splitter'
					}, {
						text	: 'Export PDF',
						iconCls	: 'icon-pdf',
						action	: 'xpdf'
					}, {
						xtype: 'splitter'
					}, {
						text	: 'Cetak',
						iconCls	: 'icon-print',
						action	: 'print'
					}]
				}*/, '-', upload_form]
			}),
			{
				xtype: 'pagingtoolbar',
				store: 's_rekapjemputan',
				dock: 'bottom',
				displayInfo: true
			}
		];
		this.callParent(arguments);
		
		this.on('itemclick', this.gridSelection);
		this.getView().on('refresh', this.refreshSelection, this);
	},
	
	gridSelection: function(me, record, item, index, e, eOpts){
		this.selectedIndex = index;
		this.getView().saveScrollState();
	},
	
	refreshSelection: function() {
        this.getSelectionModel().select(this.selectedIndex);
    }

});