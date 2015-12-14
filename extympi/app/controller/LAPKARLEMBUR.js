Ext.define('YMPI.controller.LAPKARLEMBUR',{
	extend: 'Ext.app.Controller',
	views: ['LAPORAN.v_lapkarlembur','LAPORAN.v_lapkarlembur_form'],
	models: ['m_lapkarlembur'],
	stores: ['s_lapkarlembur'],
	
	refs: [{
		ref: 'V_lapkarlembur_form',
		selector: 'v_lapkarlembur_form'
	},{
		ref: 'V_lapkarlembur',
		selector: 'v_lapkarlembur'
	}],

	init: function(){
		this.control({
			'v_lapkarlembur_form button[action=searchall]': {
				click: this.v_lapkarlembur_formSearch
			},
			'v_lapkarlembur button[action=xexcel]': {
				click: this.v_lapkarlemburExport2Excel
			}
		});
	},

	v_lapkarlembur_formSearch: function(){
		var getV_lapkarlembur_form	= this.getV_lapkarlembur_form(),
			form					= getV_lapkarlembur_form.getForm(),
			values					= getV_lapkarlembur_form.getValues();
		var grid = this.getV_lapkarlembur(),
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
	            url     : 'c_lapkarlembur/lapkarlembur',
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

	v_lapkarlemburExport2Excel: function(){
		var getV_lapkarlembur_form	= this.getV_lapkarlembur_form(),
			form					= getV_lapkarlembur_form.getForm(),
			values					= getV_lapkarlembur_form.getValues();
		var grid = this.getV_lapkarlembur(),
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
				url: 'c_lapkarlembur/export2Excel',
				params: {data: jsonData},
				timeout: 600000,
				success: function(response){
					Ext.MessageBox.hide();
					// window.location = ('./temp/'+response.responseText);
					window.location = ('./temp/lapkarlembur.xlsx');
				},
				failure: function(form, action) {
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

	v_lapkarlemburExport2PDF: function(){
		var getV_lapkarlembur_form	= this.getV_lapkarlembur_form(),
			form					= getV_lapkarlembur_form.getForm(),
			values					= getV_lapkarlembur_form.getValues();
		
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
				url: 'c_lapkarlembur/export2PDF',
				params: {data: jsonData},
				timeout: 600000,
				success: function(response){
					Ext.MessageBox.hide();
					// window.location = ('./temp/'+response.responseText);
					window.open('./temp/lapkarlembur.pdf', '_blank');
				},
				failure: function(form, action) {
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

	}
	
});