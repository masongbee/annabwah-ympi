Ext.define('YMPI.view.MASTER.v_upahpokok', {
	extend: 'Ext.grid.Panel',
	requires: ['YMPI.store.s_upahpokok'],
	
	title		: 'upahpokok',
	itemId		: 'Listupahpokok',
	alias       : 'widget.Listupahpokok',
	store 		: 's_upahpokok',
	columnLines : true,
	frame		: false,
	
	margin		: 0,
	
	selectedIndex: -1,
	
	initComponent: function(){		
		this.columns = [
			{ header: 'VALIDFROM', dataIndex: 'VALIDFROM', renderer: Ext.util.Format.dateRenderer('d M, Y')},{ header: 'NOURUT', dataIndex: 'NOURUT'},{ header: 'GRADE', dataIndex: 'GRADE'},{ header: 'KODEJAB', dataIndex: 'KODEJAB'},{ header: 'NIK', dataIndex: 'NIK'},{ header: 'RPUPAHPOKOK', dataIndex: 'RPUPAHPOKOK', align: 'right',
				renderer: function(value){
					return Ext.util.Format.currency(value, 'Rp ', 2);
				}},{ header: 'USERNAME', dataIndex: 'USERNAME'}];
		this.dockedItems = [
			{
				xtype: 'toolbar',
				frame: true,
				items: [{
					text	: 'Add',
					iconCls	: 'icon-add',
					action	: 'create'
				}, {
					itemId	: 'btndelete',
					text	: 'Delete',
					iconCls	: 'icon-remove',
					action	: 'delete',
					disabled: true
				}, '-',{
					text	: 'Export Excel',
					iconCls	: 'icon-excel',
					action	: 'xexcel'
				}, {
					text	: 'Export PDF',
					iconCls	: 'icon-pdf',
					action	: 'xpdf'
				}, {
					text	: 'Cetak',
					iconCls	: 'icon-print',
					action	: 'print'
				}]
			},
			{
				xtype: 'pagingtoolbar',
				store: 's_upahpokok',
				dock: 'bottom',
				displayInfo: false
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