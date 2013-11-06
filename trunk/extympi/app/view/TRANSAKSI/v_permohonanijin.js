Ext.define('YMPI.view.TRANSAKSI.v_permohonanijin', {
	extend: 'Ext.grid.Panel',
	requires: ['YMPI.store.s_permohonanijin'],
	
	title		: 'permohonanijin',
	itemId		: 'Listpermohonanijin',
	alias       : 'widget.Listpermohonanijin',
	store 		: 's_permohonanijin',
	columnLines : true,
	frame		: false,
	
	margin		: 0,
	selectedIndex : -1,
	
	initComponent: function(){		
		this.columns = [
			{
				header: 'NOIJIN',
				dataIndex: 'NOIJIN',filterable: true, hidden: false
			},{
				header: 'KAR.IJIN',
				dataIndex: 'NIK',filterable: true, hidden: false
			},{
				header: 'JENISABSEN',
				dataIndex: 'JENISABSEN',filterable: true, hidden: false
			},{
				header: 'TANGGAL',
				dataIndex: 'TANGGAL',width: 140,
				renderer: Ext.util.Format.dateRenderer('D, d M Y'),filterable: true, hidden: false
			},{
				header: 'JAMDARI',
				dataIndex: 'JAMDARI',filterable: true, hidden: false
			},{
				header: 'JAMSAMPAI',
				dataIndex: 'JAMSAMPAI',filterable: true, hidden: false
			},{
				header: 'KEMBALI',
				dataIndex: 'KEMBALI',filterable: true, hidden: false
			},{
				header: 'AMBILCUTI',
				dataIndex: 'AMBILCUTI',filterable: true, hidden: false
			},{
				header: 'DIAGNOSA',
				dataIndex: 'DIAGNOSA',filterable: true, hidden: true
			},{
				header: 'TINDAKAN',
				dataIndex: 'TINDAKAN',filterable: true, hidden: true
			},{
				header: 'ANJURAN',
				dataIndex: 'ANJURAN',filterable: true, hidden: true
			},{
				header: 'PETUGASKLINIK',
				dataIndex: 'PETUGASKLINIK',filterable: true, hidden: true
			},{
				header: 'NIKATASAN1',
				dataIndex: 'NIKATASAN1',filterable: true, hidden: false
			},{
				header: 'NIKPERSONALIA',
				dataIndex: 'NIKPERSONALIA',filterable: true, hidden: false
			},{
				header: 'STATUS IJIN',
				dataIndex: 'STATUSIJIN',filterable: true, hidden: false
			},{
				header: 'NIKGA',
				dataIndex: 'NIKGA',filterable: true, hidden: true
			},{
				header: 'NIKDRIVER',
				dataIndex: 'NIKDRIVER',filterable: true, hidden: true
			},{
				header: 'NIKSECURITY',
				dataIndex: 'NIKSECURITY',filterable: true, hidden: true
			},{
				header: 'USERNAME',
				dataIndex: 'USERNAME',filterable: true, hidden: true
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
				store: 's_permohonanijin',
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