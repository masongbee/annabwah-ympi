
var filtersCfg = {
    ftype: 'filters',
    autoReload: false, //don't reload automatically
	encode: false, // json encode the filter query
	local: true,   // defaults to false (remote filtering)
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
    }, {
        type: 'list',
        dataIndex: 'EXTRADAY'
    }, {
        type: 'boolean',
        dataIndex: 'visible'
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
		Ext.ux.ajax.SimManager.init({
			delay: 300,
			defaultSimlet: null
		}).register({
			'myData': {
				data: [
					['0', 'Normal'],
					['1', 'Lembur']
				],
				stype: 'json'
			}
		});
		
		var optionsStore = Ext.create('Ext.data.Store', {
			fields: ['id', 'text'],
			proxy: {
				type: 'ajax',
				url: 'myData',
				reader: 'array'
			}
		});
		
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
			altFormats: 'm,d,Y|Y-m-d',
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
				header: 'NIK',
				dataIndex: 'NIK',
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
				header: 'NAMA',
				dataIndex: 'NAMA',
				filter: true,
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
				header: 'BULAN',
				dataIndex: 'BULAN',
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
				header: 'TANGGAL',
				dataIndex: 'TANGGAL',
				filterable: true,
				renderer : function(val,metadata,record) {
					var tgl = new Date(val);
					if (record.data.JAMKURANG >= 2 ) {
						return '<span style="color:blue;">' + Ext.Date.format(tgl,'d M, Y') + '</span>';
					}
					else if (record.data.EXTRADAY == 1 ) {
						return '<span style="color:green;">' + Ext.Date.format(tgl,'d M, Y') + '</span>';
					}
					else if (record.data.JENISABSEN == 'AL' ) {
						return '<span style="color:red;">' + Ext.Date.format(tgl,'d M, Y') + '</span>';
					}
					return Ext.Date.format(tgl,'d M, Y');
				}
			},{
				header: 'JENISABSEN',
				dataIndex: 'JENISABSEN',
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
				header: 'HARIKERJA',
				dataIndex: 'HARIKERJA',
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
				header: 'JAMKERJA',
				dataIndex: 'JAMKERJA',
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
				header: 'JAMLEMBUR',
				dataIndex: 'JAMLEMBUR',
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
				header: 'JAMKURANG',
				dataIndex: 'JAMKURANG',
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
				header: 'JAMBOLOS',
				dataIndex: 'JAMBOLOS',
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
				dataIndex: 'EXTRADAY',
				filter: {
					type: 'list',
					store: optionsStore
				},
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
				dataIndex: 'TERLAMBAT',
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
				header: 'PLGLBHAWAL',
				dataIndex: 'PLGLBHAWAL',
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
				}),docktool
			],		
		features : [filtersCfg]
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