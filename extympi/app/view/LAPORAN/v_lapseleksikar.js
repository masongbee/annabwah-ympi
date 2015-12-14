Ext.define('YMPI.view.LAPORAN.v_lapseleksikar', {
	extend: 'Ext.grid.Panel',
	
	title		: 'Daftar Karyawan per Seleksi',
	itemId		: 'v_lapseleksikar',
	alias       : 'widget.v_lapseleksikar',
	store 		: 's_lapseleksikar',
	columnLines : true,
	frame		: false,
	autoScroll	: true,
	margin		: 0,
	selectedIndex : -1,

	plugins: 'bufferedrenderer',
	
	initComponent: function(){		
		this.features = [{
            id: 'group',
            ftype: 'groupingsummary',
            groupHeaderTpl: '{name}',
            hideGroupedHeader: true,
            enableGroupingMenu: false
        }];
		this.columns = [
			{
				header: 'KTP',
				dataIndex: 'KTP',
				locked   : true
			},{
				header: 'NAMAPELAMAR',
				dataIndex: 'NAMAPELAMAR',
				locked   : true,
				width: 220
			},{
				header: 'JABATAN',
				dataIndex: 'NAMAJAB',
				flex: 1,
				renderer: function(value, metaData, record){
					return record.data.IDJAB+' - '+record.data.NAMAJAB;
				}
			},{
				header: 'UNIT',
				dataIndex: 'NAMAUNIT',
				width: 200,
				renderer: function(value, metaData, record){
					return record.data.KODEUNIT+' - '+record.data.NAMAUNIT;
				}
			},{
				header: 'LEVEL JABATAN',
				dataIndex: 'NAMALEVEL',
				width: 120
			},{
				header: 'KODESELEKSI',
				dataIndex: 'KODESELEKSI',
				width: 120,
				renderer: function(value, metaData, record){
					return record.data.KODESELEKSI+' - '+record.data.NAMASELEKSI;
				}
			},{
				header: 'STATUS',
				dataIndex: 'LULUS',
				width: 120
			}];
		this.dockedItems = [
			Ext.create('Ext.toolbar.Toolbar', {
				items: [{
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
					}]
				}]
			})/*,
			{
				xtype: 'pagingtoolbar',
				store: 's_l_kartustock',
				dock: 'bottom',
				displayInfo: true
			}*/
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