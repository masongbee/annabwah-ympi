
var filters = {
    ftype: 'filters',
    autoReload: true, //don't reload automatically
	encode: true, // json encode the filter query
	local: false,   // defaults to false (remote filtering)
    // filters may be configured through the plugin,
    // or in the column definition within the headers configuration
    filters: [{
        type: 'numeric',
        dataIndex: 'HARIKERJA'
    }, {
        type: 'numeric',
        dataIndex: 'JAMKERJA'
    }, {
        type: 'numeric',
        dataIndex: 'JAMLEMBUR'
    }, {
        type: 'numeric',
        dataIndex: 'JAMKURANG'
    }, {
        type: 'numeric',
        dataIndex: 'JAMBOLOS'
    }, {
        type: 'numeric',
        dataIndex: 'TERLAMBAT'
    }, {
        type: 'numeric',
        dataIndex: 'PLGLBHAWAL'
    }, {
        type: 'string',
        dataIndex: 'NAMA'
    }]
};

Ext.define('YMPI.view.PROSES.v_hitungpresensi', {
	extend: 'Ext.grid.Panel',
	requires: ['YMPI.store.s_hitungpresensi',
			'Ext.ux.grid.FiltersFeature',
			'Ext.ux.ajax.JsonSimlet',
			'Ext.ux.ajax.SimManager'
			],
	
	title		: 'Hitung Presensi',
	itemId		: 'Listhitungpresensi',
	alias       : 'widget.Listhitungpresensi',
	store 		: 's_hitungpresensi',
	columnLines : true,
	frame		: true,
	
	margin		: 0,
	//selectedIndex: -1,
	selectedRecords: [],
	
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
		
		var bulan_filterField = Ext.create('Ext.form.ComboBox', {
			itemId: 'bulan_filter',
			fieldLabel: '<b>Bulan Gaji</b>',
			labelWidth: 60,
			store: bulan_store,
			queryMode: 'local',
			displayField: 'BULAN_GAJI',
			value : Ext.Date.format(new Date(),'M, Y'),
			valueField: 'BULAN',
			emptyText: 'Bulan',
			width: 180,
			listeners: {
				select: function(combo, records){
					tglmulai_filterField.setValue(records[0].data.TGLMULAI);
					tglsampai_filterField.setValue(records[0].data.TGLSAMPAI);
					
					var filter = "Range";		
					var tglmulai_filter = records[0].data.TGLMULAI;
					var tglsampai_filter = records[0].data.TGLSAMPAI;
					var tglm = tglmulai_filter.format("yyyy-mm-dd");
					var tgls = tglsampai_filter.format("yyyy-mm-dd");
					me.getStore().proxy.extraParams.tglmulai = tglm;
					me.getStore().proxy.extraParams.tglsampai = tgls;
					
					me.getStore().proxy.extraParams.saring = filter;
					me.getStore().load();
				}
			}
		});
		var tglmulai_filterField = Ext.create('Ext.form.field.Date', {
			itemId: 'tglmulai',
			fieldLabel: 'Tgl Mulai',
			labelWidth: 55,
			name: 'TGLMULAI',
			format: 'd M, Y',
			altFormats: 'm,d,Y|Y-m-d',
			value: Ext.Date.subtract(new Date(), Ext.Date.DAY, 30),
			readOnly: true,
			width: 180
		});
		var tglsampai_filterField = Ext.create('Ext.form.field.Date', {
			itemId: 'tglsampai',
			fieldLabel: 'Tgl Sampai',
			labelWidth: 70,
			name: 'TGLSAMPAI',
			format: 'd M, Y',
			altFormats: 'm,d,Y|Y-m-d',
			value: new Date(),
			readOnly: true,
			width: 180
		});
		
		var docktool = Ext.create('Ext.toolbar.Paging', {
			store: 's_hitungpresensi',
			dock: 'bottom',
			displayInfo: true
		});
		
		Ext.apply(this, {
		columns: [
			{
				header: 'TANGGAL',
				dataIndex: 'TANGGAL',width: 140,
				filterable: true, hidden: false,
				renderer : function(val,metadata,record) {
					var tgl = new Date(val);
					if (record.data.JAMKURANG >= 2 ) {
						return '<span style="color:blue;">' + Ext.Date.format(tgl,'D, d M Y') + '</span>';
					}
					else if (record.data.EXTRADAY == 1 ) {
						return '<span style="color:green;">' + Ext.Date.format(tgl,'D, d M Y') + '</span>';
					}
					else if (record.data.JENISABSEN == 'AL' ) {
						return '<span style="color:red;">' + Ext.Date.format(tgl,'D, d M Y') + '</span>';
					}
					return Ext.Date.format(tgl,'D, d M Y');
				}
			},{
				header: 'BULAN',
				dataIndex: 'BULAN',
				filterable: true, hidden: true,
				renderer : function(val,metadata,record) {
					if (record.data.JAMKURANG >= 2 ) {
						return '<span style="color:blue;">' + val + '</span>';
					}
					else if (record.data.EXTRADAY == 1 ) {
						return '<span style="color:green;">' + val + '</span>';
					}
					else if (record.data.JENISABSEN == 'AL' ) {
						return '<span style="color:red;">' + val + '</span>';
					}
					return val;
				}
			},{
				header: 'NIK',
				dataIndex: 'NIK',
				filterable: true, hidden: false,
				renderer : function(val,metadata,record) {
					if (record.data.JAMKURANG >= 2 ) {
						return '<span style="color:blue;">' + val + '</span>';
					}
					else if (record.data.EXTRADAY == 1 ) {
						return '<span style="color:green;">' + val + '</span>';
					}
					else if (record.data.JENISABSEN == 'AL' ) {
						return '<span style="color:red;">' + val + '</span>';
					}
					return val;
				}
			},{
				header: 'NAMA',
				dataIndex: 'NAMAKAR',
				filterable: true, hidden: false,
				renderer : function(val,metadata,record) {
					if (record.data.JAMKURANG >= 2 ) {
						return '<span style="color:blue;">' + val + '</span>';
					}
					else if (record.data.EXTRADAY == 1 ) {
						return '<span style="color:green;">' + val + '</span>';
					}
					else if (record.data.JENISABSEN == 'AL' ) {
						return '<span style="color:red;">' + val + '</span>';
					}
					return val;
				}
			},{
				header: 'NAMA UNIT',
				dataIndex: 'NAMAUNIT',
				filterable: true, hidden: true,
				renderer : function(val,metadata,record) {
					if (record.data.JAMKURANG >= 2 ) {
						return '<span style="color:blue;">' + val + '</span>';
					}
					else if (record.data.EXTRADAY == 1 ) {
						return '<span style="color:green;">' + val + '</span>';
					}
					else if (record.data.JENISABSEN == 'AL' ) {
						return '<span style="color:red;">' + val + '</span>';
					}
					return val;
				}
			},{
				header: 'KODE KELOMPOK',
				dataIndex: 'KODEKEL',
				filterable: true, hidden: true,
				renderer : function(val,metadata,record) {
					if (record.data.JAMKURANG >= 2 ) {
						return '<span style="color:blue;">' + val + '</span>';
					}
					else if (record.data.EXTRADAY == 1 ) {
						return '<span style="color:green;">' + val + '</span>';
					}
					else if (record.data.JENISABSEN == 'AL' ) {
						return '<span style="color:red;">' + val + '</span>';
					}
					return val;
				}
			},{
				header: 'JENISABSEN',
				dataIndex: 'JENISABSEN',
				filterable: true, hidden: false,
				renderer : function(val,metadata,record) {
					if (record.data.JAMKURANG >= 2 ) {
						return '<span style="color:blue;">' + val + '</span>';
					}
					else if (record.data.EXTRADAY == 1 ) {
						return '<span style="color:green;">' + val + '</span>';
					}
					else if (record.data.JENISABSEN == 'AL' ) {
						return '<span style="color:red;">' + val + '</span>';
					}
					return val;
				}
			},{
				header: 'JAMKERJA',
				dataIndex: 'JAMKERJA', align: 'right',
				//filter: true,
				renderer : function(val,metadata,record) {
					if (record.data.JAMKURANG >= 2 ) {
						return '<span style="color:blue;">' + val + '</span>';
					}
					else if (record.data.EXTRADAY == 1 ) {
						return '<span style="color:green;">' + val + '</span>';
					}
					else if (record.data.JENISABSEN == 'AL' ) {
						return '<span style="color:red;">' + val + '</span>';
					}
					return val;
				}
			},{
				header: 'HARIKERJA',
				dataIndex: 'HARIKERJA',align: 'right',
				//filter: true,
				renderer : function(val,metadata,record) {
					if (record.data.JAMKURANG >= 2 ) {
						return '<span style="color:blue;">' + val + ' h' + '</span>';
					}
					else if (record.data.EXTRADAY == 1 ) {
						return '<span style="color:green;">' + val + ' h' + '</span>';
					}
					else if (record.data.JENISABSEN == 'AL' ) {
						return '<span style="color:red;">' + val + ' h' + '</span>';
					}
					return val + ' h';
				}
			},{
				header: 'JENIS LEMBUR',
				dataIndex: 'JENISLEMBUR', 
				filterable: true,
				renderer : function(val,metadata,record) {
					if (record.data.JAMKURANG >= 2 ) {
						return '<span style="color:blue;">' + val + '</span>';
					}
					else if (record.data.EXTRADAY == 1 ) {
						return '<span style="color:green;">' + val + '</span>';
					}
					else if (record.data.JENISABSEN == 'AL' ) {
						return '<span style="color:red;">' + val + '</span>';
					}
					return val;
				}
			},{
				header: 'JAMLEMBUR',
				dataIndex: 'JAMLEMBUR', align: 'right',
				//filter: true,
				renderer : function(val,metadata,record) {
					if (record.data.JAMKURANG >= 2 ) {
						return '<span style="color:blue;">' + val + '</span>';
					}
					else if (record.data.EXTRADAY == 1 ) {
						return '<span style="color:green;">' + val + '</span>';
					}
					else if (record.data.JENISABSEN == 'AL' ) {
						return '<span style="color:red;">' + val + '</span>';
					}
					return val;
				}
			},{
				header: 'SATLEMBUR',
				dataIndex: 'SATLEMBUR', align: 'right',
				filterable: true,
				renderer : function(val,metadata,record) {
					if (record.data.JAMKURANG >= 2 ) {
						return '<span style="color:blue;">' + val + '</span>';
					}
					else if (record.data.EXTRADAY == 1 ) {
						return '<span style="color:green;">' + val + '</span>';
					}
					else if (record.data.JENISABSEN == 'AL' ) {
						return '<span style="color:red;">' + val + '</span>';
					}
					return val;
				}
			},{
				header: 'JAMKURANG',
				dataIndex: 'JAMKURANG', align: 'right',
				//filter: true, 
				renderer : function(val,metadata,record) {
					if (record.data.JAMKURANG >= 2 ) {
						return '<span style="color:blue;">' + val + '</span>';
					}
					else if (record.data.EXTRADAY == 1 ) {
						return '<span style="color:green;">' + val + '</span>';
					}
					else if (record.data.JENISABSEN == 'AL' ) {
						return '<span style="color:red;">' + val + '</span>';
					}
					return val;
				}
			},{
				header: 'EXTRADAY',
				dataIndex: 'EXTRADAY', align: 'right',
				filterable: true,
				renderer : function(val,metadata,record) {
					if (record.data.JAMKURANG >= 2 ) {
						return '<span style="color:blue;">' + val + '</span>';
					}
					else if (record.data.EXTRADAY == 1 ) {
						return '<span style="color:green;">' + val + '</span>';
					}
					else if (record.data.JENISABSEN == 'AL' ) {
						return '<span style="color:red;">' + val + '</span>';
					}
					return val;
				}
			},{
				header: 'TERLAMBAT',
				dataIndex: 'TERLAMBAT', align: 'right',
				//filter: true,
				renderer : function(val,metadata,record) {
					if (record.data.JAMKURANG >= 2 ) {
						return '<span style="color:blue;">' + val + ' m' + '</span>';
					}
					else if (record.data.EXTRADAY == 1 ) {
						return '<span style="color:green;">' + val + ' m' + '</span>';
					}
					else if (record.data.JENISABSEN == 'AL' ) {
						return '<span style="color:red;">' + val + ' m' + '</span>';
					}
					return val;
				}
			},{
				header: 'PLGLBHAWAL',
				dataIndex: 'PLGLBHAWAL', align: 'right',
				//filter: true,
				renderer : function(val,metadata,record) {
					if (record.data.JAMKURANG >= 2 ) {
						return '<span style="color:blue;">' + val + ' m' + '</span>';
					}
					else if (record.data.EXTRADAY == 1 ) {
						return '<span style="color:green;">' + val + ' m' + '</span>';
					}
					else if (record.data.JENISABSEN == 'AL' ) {
						return '<span style="color:red;">' + val + ' m' + '</span>';
					}
					return val;
				}
			},{
				header: 'IZINPRIBADI',
				dataIndex: 'IZINPRIBADI',
				filterable: true,
				renderer : function(val,metadata,record) {
					if (record.data.JAMKURANG >= 2 ) {
						return '<span style="color:blue;">' + val + '</span>';
					}
					else if (record.data.EXTRADAY == 1 ) {
						return '<span style="color:green;">' + val + '</span>';
					}
					else if (record.data.JENISABSEN == 'AL' ) {
						return '<span style="color:red;">' + val + '</span>';
					}
					return val;
				}
			},{
				header: 'USERNAME',
				dataIndex: 'USERNAME',
				filterable: true, hidden: true
			},{
				header: 'POSTING',
				dataIndex: 'POSTING',
				filterable: true, hidden: true
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
							itemId	: 'btnHitung',
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
				}),docktool
			],		
		features : [filters]
		});

		docktool.add([
			'->',
			{
				text: 'Clear Filter Data',
				handler: function () {
					//this.filters.clearFilters();
					console.info("Clear Filter data");
					me.filters.clearFilters();
				} 
			}  
		]);
		this.callParent(arguments);
		//console.info(this.filters);
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