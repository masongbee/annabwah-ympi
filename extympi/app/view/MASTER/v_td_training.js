Ext.define('YMPI.view.MASTER.v_td_training', {
	extend: 'Ext.grid.Panel',
	requires: ['YMPI.store.s_td_training'],
	
	title		: 'td_training',
	itemId		: 'Listtd_training',
	alias       : 'widget.Listtd_training',
	store 		: 's_td_training',
	columnLines : true,
	frame		: false,
	
	margin		: 0,
	selectedIndex : -1,
	
	initComponent: function(){		
		this.columns = [
			{
				header: 'KODE',
				dataIndex: 'TDTRAINING_KODE'
			},{
				header: 'NAMA',
				dataIndex: 'TDTRAINING_NAMA'
			},{
				header: 'KETERANGAN',
				dataIndex: 'TDTRAINING_KETERANGAN'
			},{
				header: 'TDKELOMPOK',
				dataIndex: 'TDTRAINING_TDKELOMPOK_ID',
				hidden: true
			},{
				header: 'TDKELOMPOK',
				dataIndex: 'TDTRAINING_TDKELOMPOK_NAMA'
			},{
				header: 'TUJUAN',
				dataIndex: 'TDTRAINING_TUJUAN'
			},{
				header: 'JENIS',
				dataIndex: 'TDTRAINING_JENIS'
			},{
				header: 'SIFAT',
				dataIndex: 'TDTRAINING_SIFAT'
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
				store: 's_td_training',
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