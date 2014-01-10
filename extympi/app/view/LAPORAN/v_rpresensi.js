Ext.define('YMPI.view.LAPORAN.v_rpresensi', {
	extend: 'Ext.grid.Panel',
	requires: ['YMPI.store.s_rpresensi'],
	
	title		: 'Report Presensi / Absensi',
	itemId		: 'Listrpresensi',
	alias       : 'widget.Listrpresensi',
	store 		: 's_rpresensi',
	columnLines : true,
	frame		: true,
	
	margin		: 0,
	selectedIndex: -1,
	
	plugins: 'bufferedrenderer',
	initComponent: function(){
		var me = this;
		
		var filters = {
			ftype: 'filters',
			// encode and local configuration options defined previously for easier reuse
			encode: true, // json encode the filter query
			local: false   // defaults to false (remote filtering)
		};
		
		/* STORE start */
		var kodekel_store = Ext.create('YMPI.store.s_kelompok', {
			autoLoad: true
		});
		var kodeunit_store = Ext.create('YMPI.store.s_unitkerja', {
			autoLoad: true
		});
		/* STORE end */
		var RPRESENSI_ID_field = Ext.create('Ext.form.field.Number', {
			allowBlank : false,
			maxLength: 11 /* length of column name */
		});
		var tglmulai_filterField = Ext.create('Ext.form.field.Date', {
			itemId: 'tglmulai',
			fieldLabel: 'Tgl Mulai',
			labelWidth: 70,
			name: 'TGLMULAI',
			format: 'd M, Y',
			readOnly: false,
			width: 200
		});
		var tglsampai_filterField = Ext.create('Ext.form.field.Date', {
			itemId: 'tglsampai',
			fieldLabel: 'Tgl Sampai',
			labelWidth: 70,
			name: 'TGLSAMPAI',
			format: 'd M, Y',
			readOnly: false,
			width: 200
		});
		var KODEKEL_field = Ext.create('Ext.form.field.ComboBox', {
			itemId: 'kodekel',
			fieldLabel: 'Kelompok',
			store: kodekel_store,
			queryMode: 'local',
			displayField: 'NAMAKEL',
			valueField: 'KODEKEL',
			allowBlank: true,
			width: 360
		});
		var KODEUNIT_field = Ext.create('Ext.form.field.ComboBox', {
			itemId: 'kodeunit',
			fieldLabel: 'Unit Kerja',
			store: kodeunit_store,
			queryMode: 'local',
			displayField: 'NAMAUNIT',
			valueField: 'KODEUNIT',
			allowBlank: true,
			width: 360
		});
		
		this.rowEditing = Ext.create('Ext.grid.plugin.RowEditing', {
			clicksToEdit: 2,
			clicksToMoveEditor: 1,
			listeners: {
				'beforeedit': function(editor, e){
					if(! (/^\s*$/).test(e.record.data.RPRESENSI_ID) ){
						
						RPRESENSI_ID_field.setReadOnly(true);
					}else{
						
						RPRESENSI_ID_field.setReadOnly(false);
					}
					
				},
				'canceledit': function(editor, e){
					if((/^\s*$/).test(e.record.data.RPRESENSI_ID) ){
						editor.cancelEdit();
						var sm = e.grid.getSelectionModel();
						e.store.remove(sm.getSelection());
					}
				},
				'validateedit': function(editor, e){
				},
				'afteredit': function(editor, e){
					var me = this;
					if((/^\s*$/).test(e.record.data.RPRESENSI_ID) ){
						Ext.Msg.alert('Peringatan', 'Kolom "RPRESENSI_ID" tidak boleh kosong.');
						return false;
					}
					/* e.store.sync();
					return true; */
					var jsonData = Ext.encode(e.record.data);
					
					Ext.Ajax.request({
						method: 'POST',
						url: 'c_rpresensi/save',
						params: {data: jsonData},
						success: function(response){
							e.store.reload({
								callback: function(){
									var newRecordIndex = e.store.findBy(
										function(record, id) {
											if (parseFloat(record.get('RPRESENSI_ID')) === e.record.data.RPRESENSI_ID) {
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
				header: 'RPRESENSI_ID',
				dataIndex: 'RPRESENSI_ID',
				hidden: true
			},{
				header: 'NIK',
				dataIndex: 'RPRESENSI_NIK',
				width: 100
			},{
				header: 'NAMA',
				dataIndex: 'RPRESENSI_NAMA',
				width: 150
			},{
				header: 'Kelompok',
				dataIndex: 'NAMAKEL',
				width: 150,
				filter: {
					type: 'string'
				}
			},{
				header: 'Unit Kerja',
				dataIndex: 'NAMAUNIT',
				width: 150,
				filter: {
					type: 'string'
				}
			},{
				header: 'BULAN',
				dataIndex: 'RPRESENSI_BULAN',
				width: 90,
				renderer: Ext.util.Format.dateRenderer('M, Y')
			},{
				header: 'd1',
				dataIndex: 'd1',
				width: 50
			},{
				header: 'd2',
				dataIndex: 'd2',
				width: 50
			},{
				header: 'd3',
				dataIndex: 'd3',
				width: 50
			},{
				header: 'd4',
				dataIndex: 'd4',
				width: 50
			},{
				header: 'd5',
				dataIndex: 'd5',
				width: 50
			},{
				header: 'd6',
				dataIndex: 'd6',
				width: 50
			},{
				header: 'd7',
				dataIndex: 'd7',
				width: 50
			},{
				header: 'd8',
				dataIndex: 'd8',
				width: 50
			},{
				header: 'd9',
				dataIndex: 'd9',
				width: 50
			},{
				header: 'd10',
				dataIndex: 'd10',
				width: 50
			},{
				header: 'd11',
				dataIndex: 'd11',
				width: 50
			},{
				header: 'd12',
				dataIndex: 'd12',
				width: 50
			},{
				header: 'd13',
				dataIndex: 'd13',
				width: 50
			},{
				header: 'd14',
				dataIndex: 'd14',
				width: 50
			},{
				header: 'd15',
				dataIndex: 'd15',
				width: 50
			},{
				header: 'd16',
				dataIndex: 'd16',
				width: 50
			},{
				header: 'd17',
				dataIndex: 'd17',
				width: 50
			},{
				header: 'd18',
				dataIndex: 'd18',
				width: 50
			},{
				header: 'd19',
				dataIndex: 'd19',
				width: 50
			},{
				header: 'd20',
				dataIndex: 'd20',
				width: 50
			},{
				header: 'd21',
				dataIndex: 'd21',
				width: 50
			},{
				header: 'd22',
				dataIndex: 'd22',
				width: 50
			},{
				header: 'd23',
				dataIndex: 'd23',
				width: 50
			},{
				header: 'd24',
				dataIndex: 'd24',
				width: 50
			},{
				header: 'd25',
				dataIndex: 'd25',
				width: 50
			},{
				header: 'd26',
				dataIndex: 'd26',
				width: 50
			},{
				header: 'd27',
				dataIndex: 'd27',
				width: 50
			},{
				header: 'd28',
				dataIndex: 'd28',
				width: 50
			},{
				header: 'd29',
				dataIndex: 'd29',
				width: 50
			},{
				header: 'd30',
				dataIndex: 'd30',
				width: 50
			},{
				header: 'd31',
				dataIndex: 'd31',
				width: 50
			}];
		//this.plugins = [this.rowEditing, 'bufferedrenderer'];
		this.features = [filters];
		this.dockedItems = [
			Ext.create('Ext.toolbar.Toolbar', {
				items: [{
					xtype: 'fieldcontainer',
					layout: 'hbox',
					defaultType: 'button',
					items: [tglmulai_filterField, {
						xtype: 'splitter'
					}, tglsampai_filterField]
				}, '-', {
					xtype: 'fieldcontainer',
					layout: 'hbox',
					defaultType: 'button',
					items: [{
						text	: 'Generate',
						iconCls	: 'icon-plugin',
						action	: 'gen'
					}, {
						xtype: 'splitter'
					},{
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
				store: 's_rpresensi',
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