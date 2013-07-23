Ext.define('YMPI.view.MASTER.v_shiftjamkerja', {
	extend: 'Ext.grid.Panel',
	requires: ['YMPI.store.s_shiftjamkerja'],
	
	title		: 'shiftjamkerja',
	itemId		: 'Listshiftjamkerja',
	alias       : 'widget.Listshiftjamkerja',
	store 		: 's_shiftjamkerja',
	columnLines : true,
	frame		: false,
	
	margin		: 0,
	selectedIndex : -1,
	
	initComponent: function(){		
		this.columns = [
			{
				header: 'NAMASHIFT',
				dataIndex: 'NAMASHIFT'
			},{
				header: 'SHIFTKE',
				dataIndex: 'SHIFTKE'
			},{
				header: 'JENISHARI',
				dataIndex: 'JENISHARI'
			},{
				header: 'JAMDARI',
				dataIndex: 'JAMDARI'
			},{
				header: 'JAMSAMPAI',
				dataIndex: 'JAMSAMPAI'
			},{
				header: 'JAMREHAT1M',
				dataIndex: 'JAMREHAT1M'
			},{
				header: 'JAMREHAT1S',
				dataIndex: 'JAMREHAT1S'
			},{
				header: 'JAMREHAT2M',
				dataIndex: 'JAMREHAT2M'
			},{
				header: 'JAMREHAT2S',
				dataIndex: 'JAMREHAT2S'
			},{
				header: 'JAMREHAT3M',
				dataIndex: 'JAMREHAT3M'
			},{
				header: 'JAMREHAT3S',
				dataIndex: 'JAMREHAT3S'
			},{
				header: 'JAMREHAT4M',
				dataIndex: 'JAMREHAT4M'
			},{
				header: 'JAMREHAT4S',
				dataIndex: 'JAMREHAT4S'
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
				store: 's_shiftjamkerja',
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