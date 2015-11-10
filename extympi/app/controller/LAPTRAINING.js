Ext.define('YMPI.controller.LAPTRAINING',{
	extend: 'Ext.app.Controller',
	views: ['LAPORAN.v_laptraining','LAPORAN.v_laptraining_form'],
	models: ['m_jenistraining','m_td_pelatihan'],
	stores: ['s_jenistraining','s_laptraining'],
	
	refs: [{
		ref: 'V_laptraining_form',
		selector: 'v_laptraining_form'
	}, {
		ref: 'V_laptraining',
		selector: 'v_laptraining'
	}],

	init: function(){
		this.control({
			'LAPTRAINING': {
				'afterrender': this.laptrainingAfterRender
			},
			'v_laptraining_form button[action=searchall]': {
				click: this.v_laptraining_formSearch
			}
		});
	},

	laptrainingAfterRender: function(){
		var jenistrainingStore = this.getStore('s_jenistraining');
		jenistrainingStore.load();
	},

	v_laptraining_formSearch: function(){
		var getV_laptraining_form 	= this.getV_laptraining_form(),
			form				= getV_laptraining_form.getForm(),
			values				= getV_laptraining_form.getValues();
		var getV_laptraining		= this.getV_laptraining(),
		 	laptrainingStore 		= getV_laptraining.getStore();
		
		if (form.isValid()) {
			laptrainingStore.getProxy().extraParams.kodetraining = values.KODETRAINING;
			laptrainingStore.getProxy().extraParams.karikutserta = values.KARIKUTSERTA;
			laptrainingStore.load();
		}
	}
	
});