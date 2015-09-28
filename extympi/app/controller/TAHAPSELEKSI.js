Ext.define('YMPI.controller.TAHAPSELEKSI',{
	extend: 'Ext.app.Controller',
	views: ['TRANSAKSI.v_tahapseleksi'],
	models: ['m_tahapseleksi'],
	stores: ['s_tahapseleksi'],
	
	requires: ['Ext.ModelManager'],
	
	refs: [{
		ref: 'Listtahapseleksi',
		selector: 'Listtahapseleksi'
	}],


	init: function(){
		this.control({
			'Listtahapseleksi': {
				'afterrender': this.tahapseleksiAfterRender
			}
		});
	},
	
	tahapseleksiAfterRender: function(){
		var tahapseleksiStore = this.getListtahapseleksi().getStore();
		tahapseleksiStore.load();
	}
	
});