Ext.define('YMPI.view.MASTER.v_cutitahunan', {
	extend: 'Ext.grid.Panel',
	requires: ['YMPI.store.s_cutitahunan'],
	
	title		: 'cutitahunan',
	itemId		: 'Listcutitahunan',
	alias       : 'widget.Listcutitahunan',
	store 		: 's_cutitahunan',
	columnLines : true,
	frame		: false,
	
	margin		: 0,
	selectedIndex : -1,
	
	initComponent: function(){		
		this.columns = [
			{
				header: 'NIK',
				dataIndex: 'NIK'
			},{
				header: 'TAHUN',
				dataIndex: 'TAHUN'
			},{
				header: 'TANGGAL',
				dataIndex: 'TANGGAL',
				renderer: Ext.util.Format.dateRenderer('d M, Y')
			},{
				header: 'JENISCUTI',
				dataIndex: 'JENISCUTI'
			},{
				header: 'JMLCUTI',
				dataIndex: 'JMLCUTI'
			},{
				header: 'SISACUTI',
				dataIndex: 'SISACUTI'
			},{
				header: 'DIKOMPENSASI',
				dataIndex: 'DIKOMPENSASI'
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
				store: 's_cutitahunan',
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