Ext.define('YMPI.view.PROSES.v_detilgaji', {
	extend: 'Ext.grid.Panel',
	requires: ['YMPI.store.s_detilgaji'],
	
	title		: 'detilgaji',
	itemId		: 'Listdetilgaji',
	alias       : 'widget.Listdetilgaji',
	store 		: 's_detilgaji',
	columnLines : true,
	frame		: true,
	
	margin		: 0,
	selectedIndex: -1,
	
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
				url: 'c_detilgaji/get_periodegaji',
				reader: {
					type: 'json',
					root: 'data'
				}
			},
			autoLoad: true
		});
		var bulan_filterField = Ext.create('Ext.form.ComboBox', {
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
			fieldLabel: 'Tgl Mulai',
			labelWidth: 55,
			name: 'TGLMULAI',
			format: 'd M, Y',
			readOnly: true,
			width: 180
		});
		var tglsampai_filterField = Ext.create('Ext.form.field.Date', {
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
					header: 'NOREVISI',
					dataIndex: 'NOREVISI'
				},{
					header: 'RPUPAHPOKOK',
					dataIndex: 'RPUPAHPOKOK',
					align: 'right',
					renderer: function(value){
						return Ext.util.Format.currency(value, 'Rp ', 2);
					}
				},{
					header: 'RPTANAK',
					dataIndex: 'RPTANAK',
					align: 'right',
					renderer: function(value){
						return Ext.util.Format.currency(value, 'Rp ', 2);
					}
				},{
					header: 'RPTBHS',
					dataIndex: 'RPTBHS',
					align: 'right',
					renderer: function(value){
						return Ext.util.Format.currency(value, 'Rp ', 2);
					}
				},{
					header: 'RPTHR',
					dataIndex: 'RPTHR',
					align: 'right',
					renderer: function(value){
						return Ext.util.Format.currency(value, 'Rp ', 2);
					}
				},{
					header: 'RPTISTRI',
					dataIndex: 'RPTISTRI',
					align: 'right',
					renderer: function(value){
						return Ext.util.Format.currency(value, 'Rp ', 2);
					}
				},{
					header: 'RPTJABATAN',
					dataIndex: 'RPTJABATAN',
					align: 'right',
					renderer: function(value){
						return Ext.util.Format.currency(value, 'Rp ', 2);
					}
				},{
					header: 'RPTPEKERJAAN',
					dataIndex: 'RPTPEKERJAAN',
					align: 'right',
					renderer: function(value){
						return Ext.util.Format.currency(value, 'Rp ', 2);
					}
				},{
					header: 'RPTSHIFT',
					dataIndex: 'RPTSHIFT',
					align: 'right',
					renderer: function(value){
						return Ext.util.Format.currency(value, 'Rp ', 2);
					}
				},{
					header: 'RPTTRANSPORT',
					dataIndex: 'RPTTRANSPORT',
					align: 'right',
					renderer: function(value){
						return Ext.util.Format.currency(value, 'Rp ', 2);
					}
				},{
					header: 'RPBONUS',
					dataIndex: 'RPBONUS',
					align: 'right',
					renderer: function(value){
						return Ext.util.Format.currency(value, 'Rp ', 2);
					}
				},{
					header: 'RPIDISIPLIN',
					dataIndex: 'RPIDISIPLIN',
					align: 'right',
					renderer: function(value){
						return Ext.util.Format.currency(value, 'Rp ', 2);
					}
				},{
					header: 'RPTLEMBUR',
					dataIndex: 'RPTLEMBUR',
					align: 'right',
					renderer: function(value){
						return Ext.util.Format.currency(value, 'Rp ', 2);
					}
				},{
					header: 'RPTKACAMATA',
					dataIndex: 'RPTKACAMATA',
					align: 'right',
					renderer: function(value){
						return Ext.util.Format.currency(value, 'Rp ', 2);
					}
				},{
					header: 'RPTSIMPATI',
					dataIndex: 'RPTSIMPATI',
					align: 'right',
					renderer: function(value){
						return Ext.util.Format.currency(value, 'Rp ', 2);
					}
				},{
					header: 'RPTMAKAN',
					dataIndex: 'RPTMAKAN',
					align: 'right',
					renderer: function(value){
						return Ext.util.Format.currency(value, 'Rp ', 2);
					}
				},{
					header: 'RPPSKORSING',
					dataIndex: 'RPPSKORSING',
					align: 'right',
					renderer: function(value){
						return Ext.util.Format.currency(value, 'Rp ', 2);
					}
				},{
					header: 'RPPSAKITCUTI',
					dataIndex: 'RPPSAKITCUTI',
					align: 'right',
					renderer: function(value){
						return Ext.util.Format.currency(value, 'Rp ', 2);
					}
				},{
					header: 'RPPJAMSOSTEK',
					dataIndex: 'RPPJAMSOSTEK',
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
							iconCls	: 'icon-add',
							action	: 'hitunggaji'
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
				}),
				{
					xtype: 'pagingtoolbar',
					store: 's_detilgaji',
					dock: 'bottom',
					displayInfo: true
				}
			]
		});
		
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