Ext.define('YMPI.controller.LAPJEMPKAR',{
	extend: 'Ext.app.Controller',
	views: ['LAPORAN.v_lapjempkar','LAPORAN.v_lapjempkar_form'],
	models: ['m_rekapjemputan','m_karyawan'],
	stores: ['s_lapjempkar','s_karyawan'],
	
	refs: [{
		ref: 'V_lapjempkar_form',
		selector: 'v_lapjempkar_form'
	}, {
		ref: 'V_lapjempkar',
		selector: 'v_lapjempkar'
	}],

	init: function(){
		this.control({
			'LAPJEMPKAR': {
				'afterrender': this.lapjempkarAfterRender
			},
			'v_lapjempkar_form button[action=searchall]': {
				click: this.v_lapjempkar_formSearch
			},
			'v_lapjempkar button[action=xexcel]': {
				click: this.export2Excel
			},
			'v_lapjempkar button[action=xpdf]': {
				click: this.export2PDF
			}
		});
	},

	lapjempkarAfterRender: function(){
		var getV_lapjempkar_form = this.getV_lapjempkar_form();
		var karyawanStore = this.getStore('s_karyawan');
		var arrkaryawan = [];
		karyawanStore.load({
		    scope: this,
		    callback: function(records, operation, success) {
		        // the operation object
		        // contains all of the details of the load operation
		        karyawanStore.each(function(r){
					// console.log(r);
					arrkaryawan.push(r.copy());
				});

				var karyawanStoreCopy = Ext.create('Ext.data.Store', {
					model: 'YMPI.model.m_karyawan'
				});
				karyawanStoreCopy.add({NIK: '0000000000', NAMAKAR: 'All'});
				karyawanStoreCopy.add(arrkaryawan);

				getV_lapjempkar_form.down('#NIK_field').bindStore(karyawanStoreCopy);
				getV_lapjempkar_form.down('#NIK_field').select(karyawanStoreCopy.getAt(karyawanStoreCopy.findExact('NIK','0000000000')));
		    }
		});
		
	},

	v_lapjempkar_formSearch: function(){
		var getV_lapjempkar_form 	= this.getV_lapjempkar_form(),
			form				= getV_lapjempkar_form.getForm(),
			values				= getV_lapjempkar_form.getValues();
		var getV_lapjempkar		= this.getV_lapjempkar(),
		 	lapjempkarStore 		= getV_lapjempkar.getStore();
		
		if (form.isValid()) {
			lapjempkarStore.getProxy().extraParams.bulan = values.BULAN;
			lapjempkarStore.getProxy().extraParams.nik = values.NIK;
			lapjempkarStore.load();
		}
	},
	
	export2Excel: function(){
		var getstore = this.getV_lapjempkar().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_rekapjemputan/lapjempkarExport2Excel',
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
		var getstore = this.getV_lapjempkar().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_rekapjemputan/lapjempkarExport2PDF',
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