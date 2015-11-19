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
			},
			'v_laptraining button[action=xexcel]': {
				click: this.export2Excel
			},
			'v_laptraining button[action=xpdf]': {
				click: this.export2PDF
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
	},
	
	export2Excel: function(){
		var getstore = this.getV_laptraining().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_td_pelatihan/laptrainingExport2Excel',
			params: {
				kodetraining: getstore.getProxy().extraParams.kodetraining,
				karikutserta: getstore.getProxy().extraParams.karikutserta
			},
			success: function(response){
				window.location = ('./temp/'+response.responseText);
				// window.location = ('./temp/daftartrainingkaryawan.xlsx');
			}
		});
	},
	
	export2PDF: function(){
		var getstore = this.getV_laptraining().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_td_pelatihan/laptrainingExport2PDF',
			params: {
				kodetraining: getstore.getProxy().extraParams.kodetraining,
				karikutserta: getstore.getProxy().extraParams.karikutserta
			},
			success: function(response){
				window.open('./temp/daftartrainingkaryawan.pdf', '_blank');
			}
		});
	}
	
});