Ext.define('YMPI.view.TRANSAKSI.v_splembur', {
	extend: 'Ext.grid.Panel',
	requires: ['YMPI.store.s_splembur'],
	
	title		: 'Surat Perintah Lembur',
	itemId		: 'Listsplembur',
	alias       : 'widget.Listsplembur',
	store 		: 's_splembur',
	columnLines : true,
	frame		: false,
	
	margin		: 0,
	selectedIndex : -1,
	
	initComponent: function(){		
		this.columns = [
			{
				header: 'NOLEMBUR',
				dataIndex: 'NOLEMBUR'
			},{
				header: 'KODEUNIT',
				dataIndex: 'KODEUNIT'
			},{
				header: 'TANGGAL',
				dataIndex: 'TANGGAL'
				//renderer: Ext.util.Format.dateRenderer('d M, Y H:i:s')
			},{
				header: 'KEPERLUAN',
				dataIndex: 'KEPERLUAN'
			},{
				header: 'NIKUSUL',
				dataIndex: 'NIKUSUL'
			},{
				header: 'NIKSETUJU',
				dataIndex: 'NIKSETUJU'
			},{
				header: 'NIKDIKETAHUI',
				dataIndex: 'NIKDIKETAHUI', hidden: true
			},{
				header: 'NIKPERSONALIA',
				dataIndex: 'NIKPERSONALIA', hidden: true
			},{
				header: 'TGLSETUJU',
				dataIndex: 'TGLSETUJU',
				renderer: Ext.util.Format.dateRenderer('d M, Y')
			},{
				header: 'TGLPERSONALIA',
				dataIndex: 'TGLPERSONALIA',
				renderer: Ext.util.Format.dateRenderer('d M, Y'), hidden: true
			},{
				header: 'USERNAME',
				dataIndex: 'USERNAME', hidden: true
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
				store: 's_splembur',
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