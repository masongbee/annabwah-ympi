Ext.define('YMPI.view.PROSES.v_hitungpresensi', {
	extend: 'Ext.grid.Panel',
	requires: ['YMPI.store.s_hitungpresensi'],
	
	title		: 'hitungpresensi',
	itemId		: 'Listhitungpresensi',
	alias       : 'widget.Listhitungpresensi',
	store 		: 's_hitungpresensi',
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
				url: 'c_hitungpresensi/get_periodegaji',
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
				header: 'NIK',
				dataIndex: 'NIK'
			},{
				header: 'BULAN',
				dataIndex: 'BULAN'
			},{
				header: 'TANGGAL',
				dataIndex: 'TANGGAL',
				renderer: Ext.util.Format.dateRenderer('d M, Y')
			},{
				header: 'JENISABSEN',
				dataIndex: 'JENISABSEN'
			},{
				header: 'HARIKERJA',
				dataIndex: 'HARIKERJA'
			},{
				header: 'JAMKERJA',
				dataIndex: 'JAMKERJA'
			},{
				header: 'JAMLEMBUR',
				dataIndex: 'JAMLEMBUR'
			},{
				header: 'JAMKURANG',
				dataIndex: 'JAMKURANG'
			},{
				header: 'JAMBOLOS',
				dataIndex: 'JAMBOLOS'
			},{
				header: 'EXTRADAY',
				dataIndex: 'EXTRADAY'
			},{
				header: 'TERLAMBAT',
				dataIndex: 'TERLAMBAT'
			},{
				header: 'PLGLBHAWAL',
				dataIndex: 'PLGLBHAWAL'
			},{
				header: 'USERNAME',
				dataIndex: 'USERNAME'
			},{
				header: 'POSTING',
				dataIndex: 'POSTING'
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
							text	: 'Hitung Presensi',
							iconCls	: 'icon-calc',
							action	: 'hitungpresensi'
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
							text	: 'Cetak',
							iconCls	: 'icon-print',
							action	: 'print'
						}]
					}]
				}),
				{
					xtype: 'pagingtoolbar',
					store: 's_hitungpresensi',
					dock: 'bottom',
					displayInfo: true
				}
			]
		});
		
		this.callParent(arguments);
		
		//this.on('itemclick', this.gridSelection);
		//this.getView().on('refresh', this.refreshSelection, this);
	},
	
	gridSelection: function(me, record, item, index, e, eOpts){
		this.selectedIndex = index;
		this.getView().saveScrollState();
	},
	
	refreshSelection: function() {
        this.getSelectionModel().select(this.selectedIndex);
    }

});