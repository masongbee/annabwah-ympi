Ext.define('YMPI.view.TRANSAKSI.v_presensilembur', {
	extend: 'Ext.grid.Panel',
	requires: ['YMPI.store.s_presensilembur'],
	
	title		: 'presensilembur',
	itemId		: 'Listpresensilembur',
	alias       : 'widget.Listpresensilembur',
	store 		: 's_presensilembur',
	columnLines : true,
	frame		: false,
	
	margin		: 0,
	selectedIndex : -1,
	
	initComponent: function(){
		var me = this;
		
		var upload_form = Ext.create('Ext.form.Panel', {
			width: 300,
			//height: 20,
			frame: false,
			bodyPadding: 0,
			bodyStyle: {
				marginTop: '3px'
			},
			
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
								url: 'c_presensilembur/do_upload',
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

		var unitkerja_filterField = Ext.create('Ext.form.field.Checkbox', {
			itemId		: 'unitkerja_filterField',
			boxLabel	: 'All Unit, ',
			name		: 'ALLUNIT',
			inputValue	: 'y',
			hidden		: true,
			listeners	: {
				'change': function(thisfield, newValue, oldValue, e){
					if (newValue) {
						me.getStore().proxy.extraParams.allunit = 'y';
						me.getStore().load();
					} else{
						me.getStore().proxy.extraParams.allunit = '';
						me.getStore().load();
					};
				}
			}
		});

		var tgllembur_filterField = Ext.create('Ext.form.field.Date', {
			allowBlank : true,
			fieldLabel: 'Tgl Lembur',
			labelWidth: 70,
			name: 'TGLLEMBUR',
			format: 'd M, Y',
			altFormats: 'm,d,Y|Y-m-d',
			value:new Date(),
			readOnly: false,
			width: 190,
			listeners: {
				'select': function(cb, records, e){
					var tanggal_lembur_filter = cb.getValue();
					var tanggal_lembur = tanggal_lembur_filter.format("yyyy-mm-dd");
					me.getStore().proxy.extraParams.tgllembur = tanggal_lembur;
					me.getStore().load();
				}
			}
		});
		
		this.columns = [
			{
				header: 'NIK',
				dataIndex: 'NIK'
			},{
				header: 'NAMA',
				dataIndex: 'NAMA',
				width: 200
			},{
				header: 'TJMASUK',
				dataIndex: 'TJMASUK',
				//renderer: Ext.util.Format.dateRenderer('d M, Y'),
				format: 'Y-m-d H:i:s',
				width: 150
			},{
				header: 'NOLEMBUR',
				dataIndex: 'NOLEMBUR'
			},{
				header: 'NOURUT',
				dataIndex: 'NOURUT'
			},{
				header: 'JENISLEMBUR',
				dataIndex: 'JENISLEMBUR'
			}];
		this.dockedItems = [
			Ext.create('Ext.toolbar.Toolbar', {
				items: [{
					xtype: 'fieldcontainer',
					layout: 'hbox',
					defaultType: 'button',
					items: [upload_form]
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
				}, '-', unitkerja_filterField, tgllembur_filterField, '-', {
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
			}),
			{
				xtype: 'pagingtoolbar',
				store: 's_presensilembur',
				dock: 'bottom',
				displayInfo: true
			}
		];
		this.callParent(arguments);
		
		this.on('itemclick', this.gridSelection);
		this.getView().on('refresh', this.refreshSelection, this);
	},	
	
	gridSelection: function(me, record, item, index, e, eOpts){
		//me.getSelectionModel().select(index);
		this.selectedIndex = index;
		this.getView().saveScrollState();
	},
	
	refreshSelection: function() {
        this.getSelectionModel().select(this.selectedIndex);   /*Ext.defer(this.setScrollTop, 30, this, [this.getView().scrollState.top]);*/
    }

});