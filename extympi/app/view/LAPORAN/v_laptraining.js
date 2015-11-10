Ext.define('YMPI.view.LAPORAN.v_laptraining', {
	extend: 'Ext.grid.Panel',
	
	title		: 'Grid Training Karyawan',
	itemId		: 'v_laptraining',
	alias       : 'widget.v_laptraining',
	store 		: 's_laptraining',
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
				header: 'NIK',
				dataIndex: 'NIK',
				locked   : true
			},{
				header: 'NAMAKAR',
				dataIndex: 'NAMAKAR',
				locked   : true,
				width: 220
			}/*,{
				header: 'NAMASINGKAT',
				dataIndex: 'NAMASINGKAT'
			},{
				header: 'IDJAB',
				dataIndex: 'IDJAB'
			}*/,{
				header: 'JABATAN',
				dataIndex: 'NAMAJAB',
				flex: 1,
				renderer: function(value, metaData, record){
					return record.data.IDJAB+' - '+record.data.NAMAJAB;
				}
			}/*,{
				header: 'KODEUNIT',
				dataIndex: 'KODEUNIT'
			}*/,{
				header: 'UNIT',
				dataIndex: 'NAMAUNIT',
				width: 200,
				renderer: function(value, metaData, record){
					return record.data.KODEUNIT+' - '+record.data.NAMAUNIT;
				}
			}/*,{
				header: 'KODEKEL',
				dataIndex: 'KODEKEL'
			}*/,{
				header: 'KELOMPOK',
				dataIndex: 'NAMAKEL',
				width: 120
			}/*,{
				header: 'KODEJAB',
				dataIndex: 'KODEJAB'
			}*/,{
				header: 'LEVEL JABATAN',
				dataIndex: 'NAMALEVEL',
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