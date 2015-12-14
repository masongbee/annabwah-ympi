Ext.define('YMPI.controller.HSLSELEKSI',{
	extend: 'Ext.app.Controller',
	views: ['TRANSAKSI.v_hslseleksi'],
	models: ['m_hslseleksi'],
	stores: ['s_hslseleksi'],
	
	requires: ['Ext.ModelManager'],
	
	refs: [{
		ref: 'Listhslseleksi',
		selector: 'Listhslseleksi'
	}],


	init: function(){
		this.control({
			'Listhslseleksi': {
				'afterrender': this.hslseleksiAfterRender
			}
		});
	},
	
	hslseleksiAfterRender: function(){
		var hslseleksiStore = this.getListhslseleksi().getStore();
		hslseleksiStore.load();
	}
	
});