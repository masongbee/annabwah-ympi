Ext.define('YMPI.view.TRANSAKSI.v_permohonancuti', {
	extend: 'Ext.grid.Panel',
	requires: ['YMPI.store.s_permohonancuti'],
	
	title		: 'permohonancuti',
	itemId		: 'Listpermohonancuti',
	alias       : 'widget.Listpermohonancuti',
	store 		: 's_permohonancuti',
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
				dataIndex: 'KODEUNIT', hidden:true
			},{
				header: 'PEMOHON',
				dataIndex: 'NIKATASAN1', xtype:'templatecolumn', tpl:'{NIKATASAN1} - {NAMAATASAN1}', flex:1
			},{
				header: 'TGL MOHON',
				dataIndex: 'TGLATASAN1'
				//renderer: Ext.util.Format.dateRenderer('d-m-Y H:s:i')
			},{
				header: 'DISETUJUI',
				dataIndex: 'NIKATASAN2', xtype:'templatecolumn', tpl:'{NIKATASAN2} - {NAMAATASAN2}', flex:1
			},{
				header: 'TGL SETUJU',
				dataIndex: 'TGLATASAN2'
				//renderer: Ext.util.Format.dateRenderer('d-m-Y H:s:i')
			},{
				header: 'DITETAPKAN',
				dataIndex: 'NIKHR', xtype:'templatecolumn', tpl:'{NIKHR} - {NAMAHR}', flex:1
			},{
				header: 'TGL TETAP/BATAL',
				dataIndex: 'TGLHR'
				//renderer: Ext.util.Format.dateRenderer('d-m-Y H:s:i')
			},{
				header: 'STATUS CUTI',
				dataIndex: 'STATUSCUTI'
			},{
				header: 'USERNAME',
				dataIndex: 'USERNAME', hidden : true
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
				store: 's_permohonancuti',
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