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
		var filtersCfg = {
			ftype: 'filters',
			// encode and local configuration options defined previously for easier reuse
			encode: true, // json encode the filter query
			local: true   // defaults to false (remote filtering)
		};
		
		this.columns = [
			{
				header: 'NOCUTI',
				dataIndex: 'NOCUTI'
			},{
				header: 'KODEUNIT',
				dataIndex: 'KODEUNIT', hidden:true
			},{
				header: 'PENGUSUL',
				dataIndex: 'NIKATASAN1', xtype:'templatecolumn', tpl:'{NIKATASAN1} - {NAMAATASAN1}', flex:1
			},{
				header: 'TGL. PENGUSUL',
				dataIndex: 'TGLATASAN1',
				width: 120,
				filterable: true,
				renderer: Ext.util.Format.dateRenderer('d-M-Y')
				//renderer: Ext.util.Format.dateRenderer('d-m-Y H:s:i')
			},{
				header: 'ATASAN',
				dataIndex: 'NIKATASAN2', xtype:'templatecolumn', tpl:'{NIKATASAN2} - {NAMAATASAN2}', flex:1
			},{
				header: 'TGL. ATASAN',
				dataIndex: 'TGLATASAN2'
				//renderer: Ext.util.Format.dateRenderer('d-m-Y H:s:i')
			},{
				header: 'ADMIN HRD',
				dataIndex: 'NIKHR', xtype:'templatecolumn', tpl:'{NIKHR} - {NAMAHR}', flex:1
			},{
				header: 'TGL. ADMIN HRD',
				dataIndex: 'TGLHR',
				width: 140
				//renderer: Ext.util.Format.dateRenderer('d-m-Y H:s:i')
			},{
				header: 'STATUS CUTI',
				dataIndex: 'STATUSCUTI'
			},{
				header: 'USERNAME',
				dataIndex: 'USERNAME', hidden : true
			}];
		this.features = [filtersCfg];
		this.dockedItems = [
			Ext.create('Ext.toolbar.Toolbar', {
				items: [{
					xtype: 'fieldcontainer',
					layout: 'hbox',
					defaultType: 'button',
					items: [{
						itemId	: 'btnadd',
						text	: 'Add',
						iconCls	: 'icon-add',
						action	: 'create',
						disabled: true
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