Ext.define('YMPI.view.LAPORAN.v_lapgaji', {
	extend: 'Ext.grid.Panel',
	
	title		: 'Grid Upah Karyawan',
	itemId		: 'v_lapgaji',
	alias       : 'widget.v_lapgaji',
	store 		: 's_lapgaji',
	columnLines : true,
	frame		: false,
	autoScroll	: true,
	margin		: 0,
	selectedIndex : -1,

	plugins: 'bufferedrenderer',
	
	initComponent: function(){		
		this.features = [{
            id: 'group',
            ftype: 'groupingsummary',
            groupHeaderTpl: '{name}',
            hideGroupedHeader: true,
            enableGroupingMenu: false
        }];
		this.columns = [
			{
				header: 'No',
				dataIndex: 'SERIAL_NUMBER',
				width: 40,
				sortable: false
			},{
				header: 'NIK',
				dataIndex: 'NIK',
				width: 80,
				sortable: false
			},{
				header: 'Nama',
				dataIndex: 'NAMAKAR',
				width: 200,
				sortable: false
			},{
				header: 'Bagian',
				dataIndex: 'SINGKATAN',
				width: 140,
				sortable: false
			},{
				header: 'Tgl. Masuk',
				dataIndex: 'TGLMASUK',
				width: 90,
				renderer: Ext.util.Format.dateRenderer('d-M-Y'),
				sortable: false
			},{
				header: 'Status',
				dataIndex: 'STATUS',
				width: 70,
				sortable: false
			},{
				header: 'Jabatan',
				dataIndex: 'NAMALEVEL',
				width: 140,
				sortable: false
			},{
				header: 'Grd',
				dataIndex: 'GRADE',
				width: 50,
				sortable: false
			},{
				header: 'Kode Tunj. Kel',
				dataIndex: 'STATTUNKEL',
				width: 120,
				sortable: false
			},{
				header: 'Upah Pokok',
				dataIndex: 'RPUPAHPOKOK',
				width: 100,
				sortable: false,
				align: 'right',
				style: 'text-align:center',
				renderer: function(value){
					return Ext.util.Format.currency(value, ' ', 0);
				}
			},{
				header: 'Upah Lembur',
				dataIndex: 'RPTLEMBUR',
				width: 100,
				sortable: false,
				align: 'right',
				style: 'text-align:center',
				renderer: function(value){
					return Ext.util.Format.currency(value, ' ', 0);
				}
			},{
				header: 'Tunj. Tetap',
				dataIndex: 'RPTUNJTETAP',
				width: 140,
				sortable: false,
				align: 'right',
				style: 'text-align:center',
				renderer: function(value){
					return Ext.util.Format.currency(value, ' ', 0);
				}
			},{
				header: 'Tunj. Tidak Tetap',
				dataIndex: 'RPTUNJTDKTTP',
				width: 140,
				sortable: false,
				align: 'right',
				style: 'text-align:center',
				renderer: function(value){
					return Ext.util.Format.currency(value, ' ', 0);
				}
			},{
				header: 'Pendapatan Non Upah',
				dataIndex: 'RPNONUPAH',
				width: 160,
				sortable: false,
				align: 'right',
				style: 'text-align:center',
				renderer: function(value){
					return Ext.util.Format.currency(value, ' ', 0);
				}
			},{
				header: 'THR',
				dataIndex: 'RPTHR',
				width: 140,
				sortable: false,
				align: 'right',
				style: 'text-align:center',
				renderer: function(value){
					return Ext.util.Format.currency(value, ' ', 0);
				}
			},{
				header: 'Total Pendapatan',
				dataIndex: 'TOTALPENDAPATAN',
				width: 140,
				sortable: false,
				align: 'right',
				style: 'text-align:center',
				renderer: function(value){
					return Ext.util.Format.currency(value, ' ', 0);
				}
			},{
				header: 'Potongan Upah',
				dataIndex: 'RPPUPAHPOKOK',
				width: 140,
				sortable: false,
				align: 'right',
				style: 'text-align:center',
				renderer: function(value){
					return Ext.util.Format.currency(value, ' ', 0);
				}
			},{
				header: 'Iuran',
				dataIndex: 'IURAN',
				width: 140,
				sortable: false,
				align: 'right',
				style: 'text-align:center',
				renderer: function(value){
					return Ext.util.Format.currency(value, ' ', 0);
				}
			},{
				header: 'Potongan Pinjaman',
				dataIndex: 'PINJAMAN',
				width: 140,
				sortable: false,
				align: 'right',
				style: 'text-align:center',
				renderer: function(value){
					return Ext.util.Format.currency(value, ' ', 0);
				}
			},{
				header: 'Total Potongan',
				dataIndex: 'TOTALPOTONGAN',
				width: 140,
				sortable: false,
				align: 'right',
				style: 'text-align:center',
				renderer: function(value){
					return Ext.util.Format.currency(value, ' ', 0);
				}
			},{
				header: 'Pendapatan Bersih',
				dataIndex: 'PENDAPATANBERSIH',
				width: 140,
				sortable: false,
				align: 'right',
				style: 'text-align:center',
				renderer: function(value){
					return Ext.util.Format.currency(value, ' ', 0);
				}
			}];
		this.dockedItems = [
			Ext.create('Ext.toolbar.Toolbar', {
				items: [{
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
			})/*,
			{
				xtype: 'pagingtoolbar',
				store: 's_l_kartustock',
				dock: 'bottom',
				displayInfo: true
			}*/
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