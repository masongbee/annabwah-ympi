Ext.define('YMPI.controller.TD_PELATIHAN',{
	extend: 'Ext.app.Controller',
	views: ['TRANSAKSI.v_td_pelatihan'],
	models: ['m_td_pelatihan'],
	stores: ['s_td_pelatihan'],
	
	requires: ['Ext.ModelManager'],
	
	refs: [{
		ref: 'Listtd_pelatihan',
		selector: 'Listtd_pelatihan'
	}],


	init: function(){
		this.control({
			'TD_PELATIHAN': {
				'afterrender': this.td_pelatihanAfterRender
			}
		});
	},
	
	td_pelatihanAfterRender: function(){
		var td_pelatihanStore = this.getListtd_pelatihan().getStore();
		td_pelatihanStore.load();
	}
	
});