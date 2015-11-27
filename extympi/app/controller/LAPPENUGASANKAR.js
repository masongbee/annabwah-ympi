Ext.define('YMPI.controller.LAPPENUGASANKAR',{
	extend: 'Ext.app.Controller',
	views: ['LAPORAN.v_lappenugasankar','LAPORAN.v_lappenugasankar_form'],
	models: ['m_rekapjemputan','m_karyawan'],
	stores: ['s_lappenugasankar','s_karyawan'],
	
	refs: [{
		ref: 'V_lappenugasankar_form',
		selector: 'v_lappenugasankar_form'
	}, {
		ref: 'V_lappenugasankar',
		selector: 'v_lappenugasankar'
	}],

	init: function(){
		this.control({
			'LAPPENUGASANKAR': {
				'afterrender': this.lappenugasankarAfterRender
			},
			'v_lappenugasankar_form button[action=searchall]': {
				click: this.v_lappenugasankar_formSearch
			},
			'v_lappenugasankar button[action=xexcel]': {
				click: this.export2Excel
			},
			'v_lappenugasankar button[action=xpdf]': {
				click: this.export2PDF
			}
		});
	},

	lappenugasankarAfterRender: function(){
		
	},

	v_lappenugasankar_formSearch: function(){
		var getV_lappenugasankar_form 	= this.getV_lappenugasankar_form(),
			form				= getV_lappenugasankar_form.getForm(),
			values				= getV_lappenugasankar_form.getValues();
		var getV_lappenugasankar		= this.getV_lappenugasankar(),
		 	lappenugasankarStore 		= getV_lappenugasankar.getStore();
		
		if (form.isValid()) {
			lappenugasankarStore.getProxy().extraParams.bulan = values.BULAN;
			lappenugasankarStore.getProxy().extraParams.tglmulai = values.TGLMULAI;
			lappenugasankarStore.getProxy().extraParams.tglsampai = values.TGLSAMPAI;
			lappenugasankarStore.load();
		}
	},
	
	export2Excel: function(){
		var getstore = this.getV_lappenugasankar().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_rekapjemputan/lappenugasankarExport2Excel',
			params: {
				bulan: getstore.getProxy().extraParams.bulan,
				nik: getstore.getProxy().extraParams.nik
			},
			success: function(response){
				window.location = ('./temp/'+response.responseText);
				// window.location = ('./temp/daftartrainingkaryawan.xlsx');
			}
		});
	},
	
	export2PDF: function(){
		var getstore = this.getV_lappenugasankar().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_rekapjemputan/lappenugasankarExport2PDF',
			params: {
				bulan: getstore.getProxy().extraParams.bulan,
				nik: getstore.getProxy().extraParams.nik
			},
			success: function(response){
				window.open('./temp/daftarjemputankaryawan.pdf', '_blank');
			}
		});
	}
	
});