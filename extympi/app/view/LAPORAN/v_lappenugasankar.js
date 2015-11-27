Ext.define('YMPI.view.LAPORAN.v_lappenugasankar', {
	extend: 'Ext.grid.Panel',
	
	title		: 'Hasil Pencarian',
	itemId		: 'v_lappenugasankar',
	alias       : 'widget.v_lappenugasankar',
	store 		: 's_lappenugasankar',
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
				header: 'NOTUGAS',
				dataIndex: 'NOTUGAS',
				width: 100
			},{
				header: 'NIK',
				dataIndex: 'NIK',
				width: 319,
				renderer: function(value, metaData, record){
					return '['+record.data.NIK+'] - '+record.data.NAMAKAR;
				}
			},{
				header: 'TGLMULAI',
				dataIndex: 'TGLMULAI',
				renderer: Ext.util.Format.dateRenderer('d M, Y')
			},{
				header: 'TGLSAMPAI',
				dataIndex: 'TGLSAMPAI',
				renderer: Ext.util.Format.dateRenderer('d M, Y')
			},{
				header: 'LAMA',
				dataIndex: 'LAMA',
				width: 60
			},{
				header: 'PENGUSUL',
				dataIndex: 'NIKATASAN1',
				width: 260,
				renderer: function(value, metaData, record){
					return '['+record.data.NIKATASAN1+'] - '+record.data.NAMAKARATASAN1;
				}
			},{
				header: 'NIKPERSONALIA',
				dataIndex: 'NIKPERSONALIA',
				width: 260,
				renderer: function(value, metaData, record){
					return '['+record.data.NIKPERSONALIA+'] - '+record.data.NAMAKARHR;
				}
			},{
				header: 'KOTA',
				dataIndex: 'KOTA',
				width: 120
			},{
				header: 'RINCIANTUGAS',
				dataIndex: 'RINCIANTUGAS',
				width: 220
			},{
				header: 'KETERANGAN',
				dataIndex: 'KETERANGAN',
				width: 220
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