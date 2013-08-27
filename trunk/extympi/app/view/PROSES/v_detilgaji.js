Ext.define('YMPI.view.PROSES.v_detilgaji', {
	extend: 'Ext.grid.Panel',
	requires: ['YMPI.store.s_detilgaji'],
	
	//title		: 'detilgaji',
	itemId		: 'Listdetilgaji',
	alias       : 'widget.Listdetilgaji',
	store 		: 's_detilgaji',
	columnLines : true,
	frame		: true,
	height		: 140,
	
	margin		: 0,
	selectedIndex: -1,
	
	initComponent: function(){
		Ext.apply(this, {
			columns: [
				{
					header: 'BULAN',
					dataIndex: 'BULAN'
				},{
					header: 'NIK',
					dataIndex: 'NIK'
				},{
					header: 'NOREVISI',
					dataIndex: 'NOREVISI'
				},{
					header: 'RPUPAHPOKOK',
					dataIndex: 'RPUPAHPOKOK',
					align: 'right',
					renderer: function(value){
						return Ext.util.Format.currency(value, '&nbsp;', 2);
					}
				},{
					header: 'RPTJABATAN',
					dataIndex: 'RPTJABATAN',
					align: 'right',
					renderer: function(value){
						return Ext.util.Format.currency(value, '&nbsp;', 2);
					}
				},{
					header: 'RPTANAK',
					dataIndex: 'RPTANAK',
					align: 'right',
					renderer: function(value){
						return Ext.util.Format.currency(value, '&nbsp;', 2);
					}
				},{
					header: 'RPTISTRI',
					dataIndex: 'RPTISTRI',
					align: 'right',
					renderer: function(value){
						return Ext.util.Format.currency(value, '&nbsp;', 2);
					}
				},{
					header: 'RPTBHS',
					dataIndex: 'RPTBHS',
					align: 'right',
					renderer: function(value){
						return Ext.util.Format.currency(value, '&nbsp;', 2);
					}
				},{
					header: 'RPTTRANSPORT',
					dataIndex: 'RPTTRANSPORT',
					align: 'right',
					renderer: function(value){
						return Ext.util.Format.currency(value, '&nbsp;', 2);
					}
				},{
					header: 'RPTSHIFT',
					dataIndex: 'RPTSHIFT',
					align: 'right',
					renderer: function(value){
						return Ext.util.Format.currency(value, '&nbsp;', 2);
					}
				},{
					header: 'RPTPEKERJAAN',
					dataIndex: 'RPTPEKERJAAN',
					align: 'right',
					renderer: function(value){
						return Ext.util.Format.currency(value, '&nbsp;', 2);
					}
				},{
					header: 'RPTQCP',
					dataIndex: 'RPTQCP',
					align: 'right',
					renderer: function(value){
						return Ext.util.Format.currency(value, '&nbsp;', 2);
					}
				},{
					header: 'RPTLEMBUR',
					dataIndex: 'RPTLEMBUR',
					align: 'right',
					renderer: function(value){
						return Ext.util.Format.currency(value, '&nbsp;', 2);
					}
				},{
					header: 'RPIDISIPLIN',
					dataIndex: 'RPIDISIPLIN',
					align: 'right',
					renderer: function(value){
						return Ext.util.Format.currency(value, '&nbsp;', 2);
					}
				},{
					header: 'RPTHADIR',
					dataIndex: 'RPTHADIR',
					align: 'right',
					renderer: function(value){
						return Ext.util.Format.currency(value, '&nbsp;', 2);
					}
				},{
					header: 'RPKOMPEN',
					dataIndex: 'RPKOMPEN',
					align: 'right',
					renderer: function(value){
						return Ext.util.Format.currency(value, '&nbsp;', 2);
					}
				},{
					header: 'RPTMAKAN',
					dataIndex: 'RPTMAKAN',
					align: 'right',
					renderer: function(value){
						return Ext.util.Format.currency(value, '&nbsp;', 2);
					}
				},{
					header: 'RPTSIMPATI',
					dataIndex: 'RPTSIMPATI',
					align: 'right',
					renderer: function(value){
						return Ext.util.Format.currency(value, '&nbsp;', 2);
					}
				},{
					header: 'RPTHR',
					dataIndex: 'RPTHR',
					align: 'right',
					renderer: function(value){
						return Ext.util.Format.currency(value, '&nbsp;', 2);
					}
				},{
					header: 'RPBONUS',
					dataIndex: 'RPBONUS',
					align: 'right',
					renderer: function(value){
						return Ext.util.Format.currency(value, '&nbsp;', 2);
					}
				},{
					header: 'RPTKACAMATA',
					dataIndex: 'RPTKACAMATA',
					align: 'right',
					renderer: function(value){
						return Ext.util.Format.currency(value, '&nbsp;', 2);
					}
				},{
					header: 'RPTAMBAHAN1',
					dataIndex: 'RPTAMBAHAN1',
					align: 'right',
					renderer: function(value){
						return Ext.util.Format.currency(value, '&nbsp;', 2);
					}
				},{
					header: 'RPTAMBAHAN2',
					dataIndex: 'RPTAMBAHAN2',
					align: 'right',
					renderer: function(value){
						return Ext.util.Format.currency(value, '&nbsp;', 2);
					}
				},{
					header: 'RPTAMBAHAN3',
					dataIndex: 'RPTAMBAHAN3',
					align: 'right',
					renderer: function(value){
						return Ext.util.Format.currency(value, '&nbsp;', 2);
					}
				},{
					header: 'RPTAMBAHAN4',
					dataIndex: 'RPTAMBAHAN4',
					align: 'right',
					renderer: function(value){
						return Ext.util.Format.currency(value, '&nbsp;', 2);
					}
				},{
					header: 'RPTAMBAHAN5',
					dataIndex: 'RPTAMBAHAN5',
					align: 'right',
					renderer: function(value){
						return Ext.util.Format.currency(value, '&nbsp;', 2);
					}
				},{
					header: 'RPTAMBAHANLAIN',
					dataIndex: 'RPTAMBAHANLAIN',
					align: 'right',
					renderer: function(value){
						return Ext.util.Format.currency(value, '&nbsp;', 2);
					}
				},{
					header: 'RPPUPAHPOKOK',
					dataIndex: 'RPPUPAHPOKOK',
					align: 'right',
					renderer: function(value){
						return Ext.util.Format.currency(value, '&nbsp;', 2);
					}
				},{
					header: 'RPPMAKAN',
					dataIndex: 'RPPMAKAN',
					align: 'right',
					renderer: function(value){
						return Ext.util.Format.currency(value, '&nbsp;', 2);
					}
				},{
					header: 'RPPTRANSPORT',
					dataIndex: 'RPPTRANSPORT',
					align: 'right',
					renderer: function(value){
						return Ext.util.Format.currency(value, '&nbsp;', 2);
					}
				},{
					header: 'RPPJAMSOSTEK',
					dataIndex: 'RPPJAMSOSTEK',
					align: 'right',
					renderer: function(value){
						return Ext.util.Format.currency(value, '&nbsp;', 2);
					}
				},{
					header: 'RPCICILAN1',
					dataIndex: 'RPCICILAN1',
					align: 'right',
					renderer: function(value){
						return Ext.util.Format.currency(value, '&nbsp;', 2);
					}
				},{
					header: 'RPCICILAN2',
					dataIndex: 'RPCICILAN2',
					align: 'right',
					renderer: function(value){
						return Ext.util.Format.currency(value, '&nbsp;', 2);
					}
				},{
					header: 'RPPOTONGAN1',
					dataIndex: 'RPPOTONGAN1',
					align: 'right',
					renderer: function(value){
						return Ext.util.Format.currency(value, '&nbsp;', 2);
					}
				},{
					header: 'RPPOTONGAN2',
					dataIndex: 'RPPOTONGAN2',
					align: 'right',
					renderer: function(value){
						return Ext.util.Format.currency(value, '&nbsp;', 2);
					}
				},{
					header: 'RPPOTONGAN3',
					dataIndex: 'RPPOTONGAN3',
					align: 'right',
					renderer: function(value){
						return Ext.util.Format.currency(value, '&nbsp;', 2);
					}
				},{
					header: 'RPPOTONGAN4',
					dataIndex: 'RPPOTONGAN4',
					align: 'right',
					renderer: function(value){
						return Ext.util.Format.currency(value, '&nbsp;', 2);
					}
				},{
					header: 'RPPOTONGAN5',
					dataIndex: 'RPPOTONGAN5',
					align: 'right',
					renderer: function(value){
						return Ext.util.Format.currency(value, '&nbsp;', 2);
					}
				},{
					header: 'RPPOTONGANLAIN',
					dataIndex: 'RPPOTONGANLAIN',
					align: 'right',
					renderer: function(value){
						return Ext.util.Format.currency(value, '&nbsp;', 2);
					}
				}]
		});
		
		this.callParent(arguments);
		
		this.on('itemclick', this.gridSelection);
		this.getView().on('refresh', this.refreshSelection, this);
	},
	
	gridSelection: function(me, record, item, index, e, eOpts){
		this.selectedIndex = index;
		this.getView().saveScrollState();
	},
	
	refreshSelection: function() {
        this.getSelectionModel().select(this.selectedIndex);
    }

});