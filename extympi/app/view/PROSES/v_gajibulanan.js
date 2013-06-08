Ext.define('YMPI.view.PROSES.v_gajibulanan', {
	extend: 'Ext.grid.Panel',
	requires: ['YMPI.store.s_gajibulanan'],
	
	title		: 'gajibulanan',
	itemId		: 'Listgajibulanan',
	alias       : 'widget.Listgajibulanan',
	store 		: 's_gajibulanan',
	columnLines : true,
	frame		: true,
	
	margin		: 0,
	//selectedIndex: -1,
	selectedRecords: [],
	
	initComponent: function(){
		var bulan_store = Ext.create('Ext.data.Store', {
			fields: [
                {name: 'BULAN', type: 'string', mapping: 'BULAN'},
                {name: 'BULAN_GAJI', type: 'string', mapping: 'BULAN_GAJI'},
				{name: 'TGLMULAI', type: 'date', dateFormat: 'Y-m-d',mapping: 'TGLMULAI'},
				{name: 'TGLSAMPAI', type: 'date', dateFormat: 'Y-m-d',mapping: 'TGLSAMPAI'}
            ],
			proxy: {
				type: 'ajax',
				url: 'c_gajibulanan/get_periodegaji',
				reader: {
					type: 'json',
					root: 'data'
				}
			},
			autoLoad: true
		});
		var bulan_filterField = Ext.create('Ext.form.ComboBox', {
			itemId: 'bulan_filter',
			fieldLabel: '<b>Bulan Gaji</b>',
			labelWidth: 60,
			store: bulan_store,
			queryMode: 'local',
			displayField: 'BULAN_GAJI',
			valueField: 'BULAN',
			emptyText: 'Bulan',
			width: 180,
			listeners: {
				select: function(combo, records){
					tglmulai_filterField.setValue(records[0].data.TGLMULAI);
					tglsampai_filterField.setValue(records[0].data.TGLSAMPAI);
				}
			}
		});
		var tglmulai_filterField = Ext.create('Ext.form.field.Date', {
			itemId: 'tglmulai',
			fieldLabel: 'Tgl Mulai',
			labelWidth: 55,
			name: 'TGLMULAI',
			format: 'd M, Y',
			readOnly: true,
			width: 180
		});
		var tglsampai_filterField = Ext.create('Ext.form.field.Date', {
			itemId: 'tglsampai',
			fieldLabel: 'Tgl Sampai',
			labelWidth: 70,
			name: 'TGLSAMPAI',
			format: 'd M, Y',
			readOnly: true,
			width: 180
		});
		
		Ext.apply(this, {
			columns: [
				{
					header: 'BULAN',
					dataIndex: 'BULAN'
				},{
					header: 'NIK',
					dataIndex: 'NIK'
				},{
					header: 'RPUPAHPOKOK',
					dataIndex: 'RPUPAHPOKOK',
					align: 'right',
					renderer: function(value){
						return Ext.util.Format.currency(value, 'Rp ', 2);
					}
				},{
					header: 'RPTUNJTETAP',
					dataIndex: 'RPTUNJTETAP',
					align: 'right',
					renderer: function(value){
						return Ext.util.Format.currency(value, 'Rp ', 2);
					}
				},{
					header: 'RPTUNJTDKTTP',
					dataIndex: 'RPTUNJTDKTTP',
					align: 'right',
					renderer: function(value){
						return Ext.util.Format.currency(value, 'Rp ', 2);
					}
				},{
					header: 'RPNONUPAH',
					dataIndex: 'RPNONUPAH',
					align: 'right',
					renderer: function(value){
						return Ext.util.Format.currency(value, 'Rp ', 2);
					}
				},{
					header: 'RPPOTONGAN',
					dataIndex: 'RPPOTONGAN',
					align: 'right',
					renderer: function(value){
						return Ext.util.Format.currency(value, 'Rp ', 2);
					}
				},{
					header: 'RPTAMBAHAN',
					dataIndex: 'RPTAMBAHAN',
					align: 'right',
					renderer: function(value){
						return Ext.util.Format.currency(value, 'Rp ', 2);
					}
				},{
					header: 'RPTOTGAJI',
					dataIndex: 'RPTOTGAJI',
					align: 'right',
					renderer: function(value){
						return Ext.util.Format.currency(value, 'Rp ', 2);
					}
				},{
					header: 'NOACCKAR',
					dataIndex: 'NOACCKAR'
				},{
					header: 'NAMABANK',
					dataIndex: 'NAMABANK'
				},{
					header: 'TGLDIBAYAR',
					dataIndex: 'TGLDIBAYAR',
					renderer: Ext.util.Format.dateRenderer('d M, Y')
				},{
					header: 'USERNAME',
					dataIndex: 'USERNAME'
				}],
			dockedItems: [
				Ext.create('Ext.toolbar.Toolbar', {
					items: [{
						xtype: 'fieldcontainer',
						layout: 'hbox',
						defaultType: 'button',
						items: [bulan_filterField, {
							xtype: 'splitter'
						}, tglmulai_filterField, {
							xtype: 'splitter'
						}, tglsampai_filterField, {
							xtype: 'splitter'
						}, {
							text	: 'Hitung Gaji',
							iconCls	: 'icon-calc',
							action	: 'hitunggaji'
						}]
					}, '-', {
						xtype: 'fieldcontainer',
						layout: 'hbox',
						defaultType: 'button',
						items: [{
							text	: 'Detil Gaji',
							iconCls	: 'icon-grid-detail',
							action	: 'detilgaji'
						}, {
							xtype: 'splitter'
						}, {
							text	: 'Export Excel',
							iconCls	: 'icon-excel',
							action	: 'xexcel'
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
					store: 's_gajibulanan',
					dock: 'bottom',
					displayInfo: true
				}
			]
		});
		
		this.callParent(arguments);
		
		//this.on('itemclick', this.gridSelection);
		//this.getStore().on('beforeload', this.rememberSelection, this);
		//this.getView().on('refresh', this.refreshSelection, this);
	}/*,
	
	rememberSelection: function(sm, records) {
		this.selectedRecords = this.getSelectionModel().getSelection();
		this.getView().saveScrollState();
	},
	
	refreshSelection: function() {
		if (0 >= this.selectedRecords.length) {
			return;
		}
		
		var newRecordsToSelect = [];
		for (var i = 0; i < this.selectedRecords.length; i++) {
			record = this.getStore().getById(this.selectedRecords[i].getId());
			if (!Ext.isEmpty(record)) {
				newRecordsToSelect.push(record);
			}
		}
		
		this.getSelectionModel().select(newRecordsToSelect);
		//Ext.defer(this.setScrollTop, 30, this, [this.getView().scrollState.top]);
	}
	
	gridSelection: function(me, record, item, index, e, eOpts){
		this.selectedIndex = index;
		this.getView().saveScrollState();
	},
	
	refreshSelection: function() {
        this.getSelectionModel().select(this.selectedIndex);
    }*/

});