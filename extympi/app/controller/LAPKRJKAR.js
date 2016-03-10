Ext.define('YMPI.controller.LAPKRJKAR',{
	extend: 'Ext.app.Controller',
	views: ['LAPORAN.v_lapkrjkar','LAPORAN.v_lapkrjkar_form'],
	models: ['m_lapkrjkar','m_karyawan'],
	stores: ['s_lapkrjkar','s_karyawan'],
	
	refs: [{
		ref: 'V_lapkrjkar_form',
		selector: 'v_lapkrjkar_form'
	}, {
		ref: 'V_lapkrjkar',
		selector: 'v_lapkrjkar'
	}],

	init: function(){
		this.control({
			'LAPKRJKAR': {
				'afterrender': this.lapkrjkarAfterRender
			},
			'v_lapkrjkar_form button[action=searchall]': {
				click: this.v_lapkrjkar_formSearch
			},
			'v_lapkrjkar button[action=xexcel]': {
				click: this.export2Excel
			},
			'v_lapkrjkar button[action=xpdf]': {
				click: this.export2PDF
			}
		});
	},

	lapkrjkarAfterRender: function(){
		var getV_lapkrjkar_form = this.getV_lapkrjkar_form();
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

				getV_lapkrjkar_form.down('#NIK_field').bindStore(karyawanStoreCopy);
				getV_lapkrjkar_form.down('#NIK_field').select(karyawanStoreCopy.getAt(karyawanStoreCopy.findExact('NIK','0000000000')));
		    }
		});
		
	},

	v_lapkrjkar_formSearch: function(){
		var getV_lapkrjkar_form	= this.getV_lapkrjkar_form(),
			form					= getV_lapkrjkar_form.getForm(),
			values					= getV_lapkrjkar_form.getValues();
		var grid = this.getV_lapkrjkar(),
			store = grid.getStore();
		
		Ext.MessageBox.show({
           	msg: 'Proses...',
           	progressText: 'Proses...',
           	width:300,
           	wait:true,
           	waitConfig: {interval:1000}
   		});

   		if (form.isValid()) {
			var jsonData = Ext.encode(values);
			
			Ext.Ajax.request({
				method: 'POST',
	            url     : 'c_tkinerja/lapkrjkar',
	            params: {data: jsonData},
				timeout: 600000,
	            success: function(response){
	            	Ext.MessageBox.hide();

	            	var data = Ext.JSON.decode(response.responseText);

	                store.model.setFields(data.fields);
	                grid.reconfigure(store, data.columns);
	                store.loadRawData(data.data, false);
	            },
	            failure: function(response){
	            	Ext.MessageBox.hide();

	  				console.log(response);
	  				Ext.Msg.show({
						title: 'Error',
						msg: 'Database error...',
						minWidth: 200,
						modal: true,
						icon: Ext.Msg.WARNING,
						buttons: Ext.Msg.OK
					});
	            }
	        });
		}
	},
	
	export2Excel: function(){
		var getstore = this.getV_lapkrjkar().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_rekapjemputan/lapkrjkarExport2Excel',
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
		var getstore = this.getV_lapkrjkar().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_rekapjemputan/lapkrjkarExport2PDF',
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