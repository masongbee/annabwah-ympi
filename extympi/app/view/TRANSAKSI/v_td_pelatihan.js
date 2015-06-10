Ext.define('YMPI.view.TRANSAKSI.v_td_pelatihan', {
	extend: 'Ext.grid.Panel',
	requires: ['YMPI.store.s_td_pelatihan'],
	
	title		: 'td_pelatihan',
	itemId		: 'Listtd_pelatihan',
	alias       : 'widget.Listtd_pelatihan',
	store 		: 's_td_pelatihan',
	columnLines : true,
	frame		: false,
	
	margin		: 0,
	selectedIndex : -1,
	
	initComponent: function(){		
		this.columns = [
			{
				header: 'TDPELATIHAN_ID',
				dataIndex: 'TDPELATIHAN_ID',
				hidden: true
			},{
				header: 'NO',
				dataIndex: 'TDPELATIHAN_NO'
			},{
				header: 'TANGGAL',
				dataIndex: 'TDPELATIHAN_TANGGAL',
				renderer: Ext.util.Format.dateRenderer('d M, Y')
			},{
				header: 'TDPELATIHAN_DIBUAT',
				dataIndex: 'TDPELATIHAN_DIBUAT',
				hidden: true
			},{
				header: 'DIBUAT',
				dataIndex: 'TDPELATIHAN_DIBUAT_NAMA'
			},{
				header: 'TDPELATIHAN_DIPERIKSA',
				dataIndex: 'TDPELATIHAN_DIPERIKSA',
				hidden: true
			},{
				header: 'DIPERIKSA',
				dataIndex: 'TDPELATIHAN_DIPERIKSA_NAMA'
			},{
				header: 'TDPELATIHAN_DIKETAHUI',
				dataIndex: 'TDPELATIHAN_DIKETAHUI',
				hidden: true
			},{
				header: 'DIKETAHUI',
				dataIndex: 'TDPELATIHAN_DIKETAHUI_NAMA'
			},{
				header: 'TDPELATIHAN_DISETUJUI01',
				dataIndex: 'TDPELATIHAN_DISETUJUI01',
				hidden: true
			},{
				header: 'DISETUJUI01',
				dataIndex: 'TDPELATIHAN_DISETUJUI01_NAMA'
			},{
				header: 'TDPELATIHAN_DISETUJUI02',
				dataIndex: 'TDPELATIHAN_DISETUJUI02',
				hidden: true
			},{
				header: 'DISETUJUI02',
				dataIndex: 'TDPELATIHAN_DISETUJUI02_NAMA'
			},{
				header: 'TDPELATIHAN_DISETUJUI03',
				dataIndex: 'TDPELATIHAN_DISETUJUI03',
				hidden: true
			},{
				header: 'DISETUJUI03',
				dataIndex: 'TDPELATIHAN_DISETUJUI03_NAMA'
			},{
				header: 'TDPELATIHAN_TDTRAINING_ID',
				dataIndex: 'TDPELATIHAN_TDTRAINING_ID',
				hidden: true
			},{
				header: 'NAMA TRAINING',
				dataIndex: 'TDPELATIHAN_TDTRAINING_NAMA'
			},{
				header: 'TDPELATIHAN_TDKELOMPOK_ID',
				dataIndex: 'TDPELATIHAN_TDKELOMPOK_ID',
				hidden: true
			},{
				header: 'TDPELATIHAN_TDKELOMPOK_NAMA',
				dataIndex: 'TDPELATIHAN_TDKELOMPOK_NAMA'
			},{
				header: 'TUJUAN TRAINING',
				dataIndex: 'TDPELATIHAN_TDTRAINING_TUJUAN'
			},{
				header: 'JENIS TRAINING',
				dataIndex: 'TDPELATIHAN_TDTRAINING_JENIS'
			},{
				header: 'SIFAT TRAINING',
				dataIndex: 'TDPELATIHAN_TDTRAINING_SIFAT'
			},{
				header: 'PESERTA',
				dataIndex: 'TDPELATIHAN_PESERTA'
			},{
				header: 'JUMLAH PESERTA',
				dataIndex: 'TDPELATIHAN_PESERTA_JUMLAH'
			},{
				header: 'TDPELATIHAN_DURASI',
				dataIndex: 'TDPELATIHAN_DURASI'
			},{
				header: 'BIAYA PLAN',
				dataIndex: 'TDPELATIHAN_BIAYA_PLAN',
				align: 'right',
				renderer: function(value){
					return Ext.util.Format.currency(value, 'Rp ', 2);
				}
			},{
				header: 'AKTUAL',
				dataIndex: 'TDPELATIHAN_BIAYA_AKTUAL',
				align: 'right',
				renderer: function(value){
					return Ext.util.Format.currency(value, 'Rp ', 2);
				}
			},{
				header: 'BALANCE',
				dataIndex: 'TDPELATIHAN_BIAYA_BALANCE',
				align: 'right',
				renderer: function(value){
					return Ext.util.Format.currency(value, 'Rp ', 2);
				}
			},{
				header: 'TDPELATIHAN_TDTRAINER_ID',
				dataIndex: 'TDPELATIHAN_TDTRAINER_ID',
				hidden: true
			},{
				header: 'TRAINER',
				dataIndex: 'TDPELATIHAN_TDTRAINER_NAMA'
			},{
				header: 'EV. REAKSI',
				dataIndex: 'TDPELATIHAN_EVREAKSI'
			},{
				header: 'EV. EFFECTIVITAS',
				dataIndex: 'TDPELATIHAN_EVEFFECTIVITAS'
			}];
		this.dockedItems = [
			Ext.create('Ext.toolbar.Toolbar', {
				items: [{
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
			}),
			{
				xtype: 'pagingtoolbar',
				store: 's_td_pelatihan',
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