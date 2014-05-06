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

		var bulan_store = Ext.create('Ext.data.Store', {
			fields: [
                {name: 'BULAN', type: 'string', mapping: 'BULAN'},
                {name: 'BULAN_GAJI', type: 'string', mapping: 'BULAN_GAJI'},
				{name: 'TGLMULAI', type: 'date', dateFormat: 'Y-m-d',mapping: 'TGLMULAI'},
				{name: 'TGLSAMPAI', type: 'date', dateFormat: 'Y-m-d',mapping: 'TGLSAMPAI'}
            ],
			proxy: {
				type: 'ajax',
				url: 'c_hitungpresensi/get_periodegaji',
				reader: {
					type: 'json',
					root: 'data'
				}
			},
			autoLoad: true
		});
		
		var NIK_field = Ext.create('Ext.form.ComboBox', {
			store: 'YMPI.store.s_karyawan',
			queryMode: 'remote',
			displayField:'NIK',
			valueField: 'NIK',
	        typeAhead: false,
	        loadingText: 'Searching...',
			//pageSize:10,
	        hideTrigger: false,
			allowBlank: false,
	        /*tpl: Ext.create('Ext.XTemplate',
                '<tpl for=".">',
                    '<div class="x-boundlist-item">[<b>{NIK}</b>] - {NAMAKAR}</div>',
                '</tpl>'
            ),
            // template for the content inside text field
            displayTpl: Ext.create('Ext.XTemplate',
                '<tpl for=".">',
                	'[{NIK}] - {NAMAKAR}',
                '</tpl>'
            ),*/
	        itemSelector: 'div.search-item',
			triggerAction: 'all',
			lazyRender:true,
			listClass: 'x-combo-list-small',
			anchor:'100%',
			forceSelection:true,
			listeners: {
				select: function(field, records, e){
					NAMAKAR_field.setValue(records[0].data.NAMAKAR);
				}
			}
		});
		var NAMAKAR_field = Ext.create('Ext.form.field.Text', {
			allowBlank : true,
			readOnly:true
		});
		var BULAN_field = Ext.create('Ext.form.field.Month', {
			allowBlank : false,
			format: 'M, Y'
		});
		var SATLEMBUR_field = Ext.create('Ext.ux.form.NumericField', {
			useThousandSeparator: true,
			decimalPrecision: 2,
			alwaysDisplayDecimals: true,
			currencySymbol: '',
			thousandSeparator: '.',
			decimalSeparator: ',',
			readOnly: false
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
		var bulan_filterField = Ext.create('Ext.form.ComboBox', {
			itemId: 'bulan_filter',
			fieldLabel: '<b>Bulan Gaji</b>',
			labelWidth: 60,
			store: bulan_store,
			queryMode: 'local',
			displayField: 'BULAN_GAJI',
			//value : Ext.Date.format(new Date(),'M, Y'),
			valueField: 'BULAN',
			emptyText: 'Bulan',
			width: 180,
			hidden: false,
			listeners: {
				select: function(combo, records){
					me.getStore().proxy.extraParams.bulan = combo.getValue();
					
					me.getStore().load();
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
					if(! (/^\s*$/).test(e.record.data.NIK) ){
						NIK_field.setReadOnly(true);
					}else{
						NIK_field.setReadOnly(false);
					}
					
				},
				'canceledit': function(editor, e){
					if((/^\s*$/).test(e.record.data.NIK) ){
						editor.cancelEdit();
						var sm = e.grid.getSelectionModel();
						e.store.remove(sm.getSelection());
					}
				},
				'validateedit': function(editor, e){
				},
				'afteredit': function(editor, e){
					var me = this;
					if((/^\s*$/).test(e.record.data.NIK) ){
						Ext.Msg.alert('Peringatan', 'Kolom "NIK" tidak boleh kosong.');
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
				header: 'NIK',
				dataIndex: 'NIK',
				width: 120,
				field: NIK_field
			},{
				header: 'NAMA',
				dataIndex: 'NAMAKAR',
				width: 200,
				field: NAMAKAR_field
			},{
				header: 'BULAN',
				dataIndex: 'BULAN',
				width: 120,
				renderer: Ext.util.Format.dateRenderer('M, Y'),
				field: BULAN_field
			},{
				header: 'HARIKERJA',
				dataIndex: 'HARIKERJA',
				width: 90,
				field: {xtype: 'numberfield'}
			},{
				header: 'EXTRADAY',
				dataIndex: 'EXTRADAY',
				width: 90,
				field: {xtype: 'numberfield'}
			},{
				header: 'XPOTONG',
				dataIndex: 'XPOTONG',
				width: 90,
				field: {xtype: 'numberfield'}
			},{
				header: 'SATLEMBUR',
				dataIndex: 'SATLEMBUR',
				width: 90,
				field: SATLEMBUR_field
			},{
				header: 'JMLJAMKURANG',
				dataIndex: 'JMLJAMKURANG',
				width: 90,
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
							items: [bulan_filterField]
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