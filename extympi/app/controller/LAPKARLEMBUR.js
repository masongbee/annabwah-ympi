Ext.define('YMPI.controller.LAPKARLEMBUR',{
	extend: 'Ext.app.Controller',
	views: ['LAPORAN.v_lapkarlembur_form'],
	models: [],
	stores: [],
	
	refs: [{
		ref: 'V_lapkarlembur_form',
		selector: 'v_lapkarlembur_form'
	}],

	init: function(){
		this.control({
			'v_lapkarlembur_form button[action=xexcel]': {
				click: this.v_lapkarlembur_formExport2Excel
			}/*,
			'v_lapkarlembur_form button[action=xpdf]': {
				click: this.v_lapkarlembur_formExport2PDF
			}*/
		});
	},

	v_lapkarlembur_formExport2Excel: function(){
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

	v_lapkarlembur_formExport2PDF: function(){
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