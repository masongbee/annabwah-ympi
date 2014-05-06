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
				dataIndex: 'kartustock_barang_nama',
				width: 120,
				sortable: false
			},{
				header: 'NIK',
				dataIndex: 'kartustock_no',
				width: 140,
				sortable: false
			},{
				header: 'Nama',
				dataIndex: 'kartustock_no',
				width: 140,
				sortable: false
			},{
				header: 'Bagian',
				dataIndex: 'kartustock_no',
				width: 140,
				sortable: false
			},{
				header: 'Tgl. Masuk',
				dataIndex: 'kartustock_tanggal',
				width: 150,
				renderer: Ext.util.Format.dateRenderer('d-M-Y H:i:s'),
				sortable: false
			},{
				header: 'Status',
				dataIndex: 'kartustock_no',
				width: 140,
				sortable: false
			},{
				header: 'Jabatan',
				dataIndex: 'kartustock_no',
				width: 140,
				sortable: false
			},{
				header: 'Grd',
				dataIndex: 'kartustock_no',
				width: 140,
				sortable: false
			},{
				header: 'Kode Tunj. Kel',
				dataIndex: 'kartustock_no',
				width: 140,
				sortable: false
			},{
				header: 'Upah Pokok',
				dataIndex: 'kartustock_no',
				width: 140,
				sortable: false,
				align: 'right',
				style: 'text-align:center',
				renderer: function(value){
					return Ext.util.Format.currency(value, ' ', 0);
				}
			},{
				header: 'Upah Lembur',
				dataIndex: 'kartustock_no',
				width: 140,
				sortable: false,
				align: 'right',
				style: 'text-align:center',
				renderer: function(value){
					return Ext.util.Format.currency(value, ' ', 0);
				}
			},{
				header: 'Tunj. Tetap',
				dataIndex: 'kartustock_no',
				width: 140,
				sortable: false,
				align: 'right',
				style: 'text-align:center',
				renderer: function(value){
					return Ext.util.Format.currency(value, ' ', 0);
				}
			},{
				header: 'Tunj. Tidak Tetap',
				dataIndex: 'kartustock_no',
				width: 140,
				sortable: false,
				align: 'right',
				style: 'text-align:center',
				renderer: function(value){
					return Ext.util.Format.currency(value, ' ', 0);
				}
			},{
				header: 'Pendapatan Non Upah',
				dataIndex: 'kartustock_no',
				width: 140,
				sortable: false,
				align: 'right',
				style: 'text-align:center',
				renderer: function(value){
					return Ext.util.Format.currency(value, ' ', 0);
				}
			},{
				header: 'THR',
				dataIndex: 'kartustock_no',
				width: 140,
				sortable: false,
				align: 'right',
				style: 'text-align:center',
				renderer: function(value){
					return Ext.util.Format.currency(value, ' ', 0);
				}
			},{
				header: 'Total Pendapatan',
				dataIndex: 'kartustock_no',
				width: 140,
				sortable: false,
				align: 'right',
				style: 'text-align:center',
				renderer: function(value){
					return Ext.util.Format.currency(value, ' ', 0);
				}
			},{
				header: 'Potongan Upah',
				dataIndex: 'kartustock_no',
				width: 140,
				sortable: false,
				align: 'right',
				style: 'text-align:center',
				renderer: function(value){
					return Ext.util.Format.currency(value, ' ', 0);
				}
			},{
				header: 'Iuran',
				dataIndex: 'kartustock_no',
				width: 140,
				sortable: false,
				align: 'right',
				style: 'text-align:center',
				renderer: function(value){
					return Ext.util.Format.currency(value, ' ', 0);
				}
			},{
				header: 'Potongan Pinjaman',
				dataIndex: 'kartustock_no',
				width: 140,
				sortable: false,
				align: 'right',
				style: 'text-align:center',
				renderer: function(value){
					return Ext.util.Format.currency(value, ' ', 0);
				}
			},{
				header: 'Total Potongan',
				dataIndex: 'kartustock_no',
				width: 140,
				sortable: false,
				align: 'right',
				style: 'text-align:center',
				renderer: function(value){
					return Ext.util.Format.currency(value, ' ', 0);
				}
			},{
				header: 'Pendapatan Bersih',
				dataIndex: 'kartustock_no',
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