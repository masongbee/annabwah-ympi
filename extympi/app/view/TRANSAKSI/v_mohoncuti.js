Ext.define('YMPI.view.TRANSAKSI.v_mohoncuti', {
	extend: 'Ext.grid.Panel',
	requires: ['YMPI.store.s_mohoncuti'],
	
	title		: 'Permohonan Cuti',
	itemId		: 'Listmohoncuti',
	alias       : 'widget.Listmohoncuti',
	store 		: 's_mohoncuti',
	columnLines : true,
	frame		: false,
	
	margin		: 0,
	selectedIndex : -1,
	
	initComponent: function(){		
		this.columns = [
			{
				header: 'NOCUTI',
				dataIndex: 'NOCUTI'
			},{
				header: 'KODEUNIT',
				dataIndex: 'KODEUNIT'
			},{
				header: 'NIKATASAN1',
				dataIndex: 'NIKATASAN1'
			},{
				header: 'NIKATASAN2',
				dataIndex: 'NIKATASAN2'
			},{
				header: 'NIKATASAN3',
				dataIndex: 'NIKATASAN3'
			},{
				header: 'NIKHR',
				dataIndex: 'NIKHR'
			},{
				header: 'TGLATASAN1',
				dataIndex: 'TGLATASAN1',
				renderer: Ext.util.Format.dateRenderer('d M, Y')
			},{
				header: 'TGLATASAN2',
				dataIndex: 'TGLATASAN2',
				renderer: Ext.util.Format.dateRenderer('d M, Y')
			},{
				header: 'TGLATASAN3',
				dataIndex: 'TGLATASAN3',
				renderer: Ext.util.Format.dateRenderer('d M, Y')
			},{
				header: 'TGLHR',
				dataIndex: 'TGLHR',
				renderer: Ext.util.Format.dateRenderer('d M, Y')
			},{
				header: 'USERNAME',
				dataIndex: 'USERNAME'
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
				store: 's_mohoncuti',
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