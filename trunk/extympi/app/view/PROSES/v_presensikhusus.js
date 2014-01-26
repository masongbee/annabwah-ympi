Ext.define('YMPI.view.PROSES.v_presensikhusus', {
	extend: 'Ext.grid.Panel',
	requires: ['YMPI.store.s_presensikhusus'],
	
	title		: 'Presensi Khusus',
	itemId		: 'Listpresensikhusus',
	alias       : 'widget.Listpresensikhusus',
	store 		: 's_presensikhusus',
	columnLines : true,
	frame		: true,
	
	margin		: 0,
	selectedIndex: -1,
	
	initComponent: function(){
		var me = this;
		
		var ID_field = Ext.create('Ext.form.field.Number', {
			allowBlank : false,
			maxLength: 11 /* length of column name */
		});
		var NIK_field = Ext.create('Ext.form.field.Text', {
			allowBlank : false,
			maxLength: 10 /* length of column name */
		});
		
		var tglmulai_filterField = Ext.create('Ext.form.field.Date', {
			allowBlank : true,
			itemId: 'tglmulai',
			fieldLabel: 'Tgl Mulai',
			labelWidth: 52,
			name: 'TGLMULAI',
			format: 'd M, Y',
			altFormats: 'm,d,Y|Y-m-d',
			value: Ext.Date.subtract(new Date(), Ext.Date.DAY, 1),
			readOnly: false,
			width: 170,
			listeners: {
				'select': function(cb, records, e){
					var tglmulai_filter = cb.getValue();
					var tglsampai_filter = tglsampai_filterField.getValue();
					var tglm = tglmulai_filter.format("yyyy-mm-dd");
					var tgls = tglsampai_filter.format("yyyy-mm-dd");
					me.getStore().proxy.extraParams.tglmulai = tglm;
					me.getStore().proxy.extraParams.tglsampai = tgls;
					me.getStore().load();
				}
			}
		});
		var tglsampai_filterField = Ext.create('Ext.form.field.Date', {
			allowBlank : true,
			itemId: 'tglsampai',
			fieldLabel: 'Tgl Sampai',
			labelWidth: 70,
			name: 'TGLSAMPAI',
			format: 'd M, Y',
			altFormats: 'm,d,Y|Y-m-d',
			value:new Date(),
			readOnly: false,
			width: 190,
			listeners: {
				'select': function(cb, records, e){
					var tglmulai_filter = tglmulai_filterField.getValue();
					var tglsampai_filter = cb.getValue();
					var tglm = tglmulai_filter.format("yyyy-mm-dd");
					var tgls = tglsampai_filter.format("yyyy-mm-dd");
					me.getStore().proxy.extraParams.tglmulai = tglm;
					me.getStore().proxy.extraParams.tglsampai = tgls;
					me.getStore().load();
				}
			}
		});
		
		var TJMASUK_field = Ext.create('Ext.form.field.Date', {
			itemId: 'TJMASUK_field',
			allowBlank : false,
			format: 'Y-m-d H:i:s',
			//enableKeyEvents: true,
			listeners: {
				change: function(field, newValue, oldValue){
					if (field.isValid()) {
						/**
						 * Get TJMASUK ==> otomatis update TANGGAL_field
						 */
						var tjmasuk = field.getValue();
						var getyear = tjmasuk.getFullYear();
						var getmonth = tjmasuk.getMonth();
						var getdate = tjmasuk.getDate();
						var gethours = tjmasuk.getHours();
						var getminutes = tjmasuk.getMinutes();
						var getseconds = tjmasuk.getSeconds();
						var tgltjmasuk = new Date(getyear,getmonth,getdate);
						var datetimetjmasuk = new Date(getyear,getmonth,getdate,gethours,getminutes,getseconds);
						
						TANGGAL_field.setValue(tgltjmasuk);
					}
				}
			}
		});
		
		var TJKELUAR_field = Ext.create('Ext.form.field.Date', {
			itemId: 'TJKELUAR_field',
			allowBlank : false,
			format: 'Y-m-d H:i:s'
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
								url: 'c_presensikhusus/do_upload',
								waitMsg: 'Uploading your file...',
								success: function(fp, o) {
									var obj = Ext.JSON.decode(o.response.responseText);
									if (obj.skeepdata == 0) {
										Ext.Msg.alert('Success', 'Proses upload dan penambahan data telah berhasil.');
									}else{
										Ext.Msg.alert('Success', 'Proses upload dan penambahan data telah berhasil, dengan '+obj.skeepdata+' data yang tidak tersimpan.');
									}
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
		
		this.rowEditing = Ext.create('Ext.grid.plugin.RowEditing', {
			clicksToEdit: 2,
			clicksToMoveEditor: 1,
			listeners: {
				'beforeedit': function(editor, e){
					if(! (/^\s*$/).test(e.record.data.ID) || ! (/^\s*$/).test(e.record.data.NIK) ){
						
						ID_field.setReadOnly(true);	
						NIK_field.setReadOnly(true);
					}else{
						
						ID_field.setReadOnly(false);
						NIK_field.setReadOnly(false);
					}
					
				},
				'canceledit': function(editor, e){
					if((/^\s*$/).test(e.record.data.ID) || (/^\s*$/).test(e.record.data.NIK) ){
						editor.cancelEdit();
						var sm = e.grid.getSelectionModel();
						e.store.remove(sm.getSelection());
					}
				},
				'validateedit': function(editor, e){
				},
				'afteredit': function(editor, e){
					var me = this;
					if((/^\s*$/).test(e.record.data.ID) || (/^\s*$/).test(e.record.data.NIK) ){
						Ext.Msg.alert('Peringatan', 'Kolom "ID","NIK" tidak boleh kosong.');
						return false;
					}
					/* e.store.sync();
					return true; */
					var jsonData = Ext.encode(e.record.data);
					
					Ext.Ajax.request({
						method: 'POST',
						url: 'c_presensikhusus/save',
						params: {data: jsonData},
						success: function(response){
							e.store.reload({
								callback: function(){
									var newRecordIndex = e.store.findBy(
										function(record, id) {
											if (parseFloat(record.get('ID')) === e.record.data.ID && record.get('NIK') === e.record.data.NIK) {
												return true;
											}
											return false;
										}
									);
									/* me.grid.getView().select(recordIndex); */
									me.grid.getSelectionModel().select(newRecordIndex);
								}
							});
						}
					});
					return true;
				}
			}
		});
		
		this.columns = [
			{
				header: 'ID',
				dataIndex: 'ID',
				hidden: true,
				field: ID_field
			},{
				header: 'NIK',
				dataIndex: 'NIK',
				width: 100,
				field: NIK_field
			},{
				header: 'NAMASHIFT',
				dataIndex: 'NAMASHIFT',
				field: {xtype: 'textfield'}
			},{
				header: 'SHIFTKE',
				dataIndex: 'SHIFTKE',
				width: 80,
				field: {xtype: 'textfield'}
			},{
				header: 'TANGGAL',
				dataIndex: 'TANGGAL',
				width: 130,
				renderer: Ext.util.Format.dateRenderer('d M, Y'),
				field: {xtype: 'datefield',format: 'm-d-Y'}
			},{
				header: 'TJMASUK',
				dataIndex: 'TJMASUK',
				width: 160,
				renderer: function(val,metadata,record){
					if (record.data.TJMASUK == null) {
						return 'null';
					}
					return Ext.Date.format(Ext.Date.parse(val, 'Y-m-d H:i:s', true), 'Y-m-d H:i:s');
				},
				field: TJMASUK_field
			},{
				header: 'TJKELUAR',
				dataIndex: 'TJKELUAR',
				width: 160,
				renderer: function(val,metadata,record){
					if (record.data.TJKELUAR == null) {
						return 'null';
					}
					return Ext.Date.format(Ext.Date.parse(val, 'Y-m-d H:i:s', true), 'Y-m-d H:i:s');
				},
				field: TJKELUAR_field
			},{
				header: 'ASALDATA',
				dataIndex: 'ASALDATA',
				hidden: true,
				field: {xtype: 'textfield'}
			},{
				header: 'JENISABSEN',
				dataIndex: 'JENISABSEN',
				field: {xtype: 'textfield'}
			},{
				header: 'JENISLEMBUR',
				dataIndex: 'JENISLEMBUR',
				width: 120,
				field: {xtype: 'textfield'}
			},{
				header: 'EXTRADAY',
				dataIndex: 'EXTRADAY',
				field: {xtype: 'numberfield'}
			}];
		this.plugins = [this.rowEditing];
		this.dockedItems = [
			{
				xtype: 'form',
				defaultType: 'textfield',
				items: [upload_form,
					Ext.create('Ext.toolbar.Toolbar', {
						items: [{
							xtype: 'fieldcontainer',
							layout: 'hbox',
							defaultType: 'button',
							items: [tglmulai_filterField, tglsampai_filterField]
						}, '-', {
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
						}, '-', {
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
						}]
					})
				]
			},
			{
				xtype: 'pagingtoolbar',
				store: 's_presensikhusus',
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