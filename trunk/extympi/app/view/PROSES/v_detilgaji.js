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
						return Ext.util.Format.currency(value, 'Rp ', 2);
					}
				},{
					header: 'RPTANAK',
					dataIndex: 'RPTANAK',
					align: 'right',
					renderer: function(value){
						return Ext.util.Format.currency(value, 'Rp ', 2);
					}
				},{
					header: 'RPTBHS',
					dataIndex: 'RPTBHS',
					align: 'right',
					renderer: function(value){
						return Ext.util.Format.currency(value, 'Rp ', 2);
					}
				},{
					header: 'RPTHR',
					dataIndex: 'RPTHR',
					align: 'right',
					renderer: function(value){
						return Ext.util.Format.currency(value, 'Rp ', 2);
					}
				},{
					header: 'RPTISTRI',
					dataIndex: 'RPTISTRI',
					align: 'right',
					renderer: function(value){
						return Ext.util.Format.currency(value, 'Rp ', 2);
					}
				},{
					header: 'RPTJABATAN',
					dataIndex: 'RPTJABATAN',
					align: 'right',
					renderer: function(value){
						return Ext.util.Format.currency(value, 'Rp ', 2);
					}
				},{
					header: 'RPTPEKERJAAN',
					dataIndex: 'RPTPEKERJAAN',
					align: 'right',
					renderer: function(value){
						return Ext.util.Format.currency(value, 'Rp ', 2);
					}
				},{
					header: 'RPTSHIFT',
					dataIndex: 'RPTSHIFT',
					align: 'right',
					renderer: function(value){
						return Ext.util.Format.currency(value, 'Rp ', 2);
					}
				},{
					header: 'RPTTRANSPORT',
					dataIndex: 'RPTTRANSPORT',
					align: 'right',
					renderer: function(value){
						return Ext.util.Format.currency(value, 'Rp ', 2);
					}
				},{
					header: 'RPBONUS',
					dataIndex: 'RPBONUS',
					align: 'right',
					renderer: function(value){
						return Ext.util.Format.currency(value, 'Rp ', 2);
					}
				},{
					header: 'RPIDISIPLIN',
					dataIndex: 'RPIDISIPLIN',
					align: 'right',
					renderer: function(value){
						return Ext.util.Format.currency(value, 'Rp ', 2);
					}
				},{
					header: 'RPTLEMBUR',
					dataIndex: 'RPTLEMBUR',
					align: 'right',
					renderer: function(value){
						return Ext.util.Format.currency(value, 'Rp ', 2);
					}
				},{
					header: 'RPTKACAMATA',
					dataIndex: 'RPTKACAMATA',
					align: 'right',
					renderer: function(value){
						return Ext.util.Format.currency(value, 'Rp ', 2);
					}
				},{
					header: 'RPTSIMPATI',
					dataIndex: 'RPTSIMPATI',
					align: 'right',
					renderer: function(value){
						return Ext.util.Format.currency(value, 'Rp ', 2);
					}
				},{
					header: 'RPTMAKAN',
					dataIndex: 'RPTMAKAN',
					align: 'right',
					renderer: function(value){
						return Ext.util.Format.currency(value, 'Rp ', 2);
					}
				},{
					header: 'RPPSKORSING',
					dataIndex: 'RPPSKORSING',
					align: 'right',
					renderer: function(value){
						return Ext.util.Format.currency(value, 'Rp ', 2);
					}
				},{
					header: 'RPPSAKITCUTI',
					dataIndex: 'RPPSAKITCUTI',
					align: 'right',
					renderer: function(value){
						return Ext.util.Format.currency(value, 'Rp ', 2);
					}
				},{
					header: 'RPPJAMSOSTEK',
					dataIndex: 'RPPJAMSOSTEK',
					align: 'right',
					renderer: function(value){
						return Ext.util.Format.currency(value, 'Rp ', 2);
					}
				},{
					header: 'RPPOTONGAN',
					dataIndex: 'RPPOTONGAN',
					align: 'right',
					renderer: function(value){
						return Ext.util.Format.currency(value, 'Rp ', 2);
					}
				},{
					header: 'RPTAMBAHAN',
					dataIndex: 'RPTAMBAHAN',
					align: 'right',
					renderer: function(value){
						return Ext.util.Format.currency(value, 'Rp ', 2);
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