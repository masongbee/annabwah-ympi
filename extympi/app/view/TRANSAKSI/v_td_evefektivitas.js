Ext.define('YMPI.view.TRANSAKSI.v_td_evefektivitas', {
	extend: 'Ext.grid.Panel',
	requires: ['YMPI.store.s_td_evefektivitas'],
	
	title		: 'td_evefektivitas',
	itemId		: 'Listtd_evefektivitas',
	alias       : 'widget.Listtd_evefektivitas',
	store 		: 's_td_evefektivitas',
	columnLines : true,
	frame		: false,
	
	margin		: 0,
	selectedIndex : -1,
	
	initComponent: function(){		
		this.columns = [
			{
				header: 'TDEVE_ID',
				dataIndex: 'TDEVE_ID'
			},{
				header: 'TDEVE_TDPELATIHAN_ID',
				dataIndex: 'TDEVE_TDPELATIHAN_ID'
			},{
				header: 'TDEVE_NIK',
				dataIndex: 'TDEVE_NIK'
			},{
				header: 'TDEVE_SARANEVALUATOR',
				dataIndex: 'TDEVE_SARANEVALUATOR'
			},{
				header: 'TDEVE001',
				dataIndex: 'TDEVE001'
			},{
				header: 'TDEVE002',
				dataIndex: 'TDEVE002'
			},{
				header: 'TDEVE003',
				dataIndex: 'TDEVE003'
			},{
				header: 'TDEVE004',
				dataIndex: 'TDEVE004'
			},{
				header: 'TDEVE005',
				dataIndex: 'TDEVE005'
			},{
				header: 'TDEVE006',
				dataIndex: 'TDEVE006'
			},{
				header: 'TDEVE007',
				dataIndex: 'TDEVE007'
			},{
				header: 'TDEVE008',
				dataIndex: 'TDEVE008'
			},{
				header: 'TDEVE009',
				dataIndex: 'TDEVE009'
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
				store: 's_td_evefektivitas',
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