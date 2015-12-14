Ext.define('YMPI.controller.LAPSELEKSIKAR',{
	extend: 'Ext.app.Controller',
	views: ['LAPORAN.v_lapseleksikar','LAPORAN.v_lapseleksikar_form'],
	models: ['m_lapposisilowongan','m_lapseleksikar'],
	stores: ['s_lapposisilowongan','s_lapseleksikar','s_lowongan','s_laplevellowongan','s_jnsseleksi'],
	
	refs: [{
		ref: 'V_lapseleksikar_form',
		selector: 'v_lapseleksikar_form'
	}, {
		ref: 'V_lapseleksikar',
		selector: 'v_lapseleksikar'
	}],

	init: function(){
		this.control({
			'LAPSELEKSIKAR': {
				'afterrender': this.lapseleksikarAfterRender
			},
			'v_lapseleksikar_form button[action=searchall]': {
				click: this.v_lapseleksikar_formSearch
			},
			'v_lapseleksikar button[action=xexcel]': {
				click: this.export2Excel
			},
			'v_lapseleksikar button[action=xpdf]': {
				click: this.export2PDF
			}
		});
	},

	lapseleksikarAfterRender: function(){
		var lowonganStore = this.getStore('s_lowongan');
		lowonganStore.load();
		var jnsseleksiStore = this.getStore('s_jnsseleksi');
		jnsseleksiStore.load();
	},

	v_lapseleksikar_formSearch: function(){
		var getV_lapseleksikar_form 	= this.getV_lapseleksikar_form(),
			form				= getV_lapseleksikar_form.getForm(),
			values				= getV_lapseleksikar_form.getValues();
		var getV_lapseleksikar		= this.getV_lapseleksikar(),
		 	lapseleksikarStore 		= getV_lapseleksikar.getStore();
		
		if (form.isValid()) {
			lapseleksikarStore.getProxy().extraParams.gellow      = values.GELLOW;
			lapseleksikarStore.getProxy().extraParams.idjab       = values.IDJAB;
			lapseleksikarStore.getProxy().extraParams.kodejab     = values.KODEJAB;
			lapseleksikarStore.getProxy().extraParams.kodeseleksi = values.KODESELEKSI;
			lapseleksikarStore.load();
		}
	},
	
	export2Excel: function(){
		var getstore = this.getV_lapseleksikar().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_tahapseleksi/lapseleksikarExport2Excel',
			params: {
				gellow: getstore.getProxy().extraParams.gellow,
				idjab: getstore.getProxy().extraParams.idjab,
				kodejab: getstore.getProxy().extraParams.kodejab,
				kodeseleksi: getstore.getProxy().extraParams.kodeseleksi
			},
			success: function(response){
				window.location = ('./temp/'+response.responseText);
			}
		});
	},
	
	export2PDF: function(){
		var getstore = this.getV_lapseleksikar().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_tahapseleksi/lapseleksikarExport2PDF',
			params: {
				gellow: getstore.getProxy().extraParams.gellow,
				idjab: getstore.getProxy().extraParams.idjab,
				kodejab: getstore.getProxy().extraParams.kodejab,
				kodeseleksi: getstore.getProxy().extraParams.kodeseleksi
			},
			success: function(response){
				window.open('./temp/daftarseleksikaryawan.pdf', '_blank');
			}
		});
	}
	
});