Ext.define('YMPI.controller.GPASS',{
	extend: 'Ext.app.Controller',
	views: ['AKSES.GPASS'],
	models: [],
	stores: [],
	
	requires: ['Ext.ModelManager'],
	
	refs: [{
		ref: 'V_s_gpass_form',
		selector: 'v_s_gpass_form'
	}],


	init: function(){
		this.control({
			'v_s_gpass_form button[action=save]': {
				click: this.saveV_s_gpass_form
			}
		});
	},
	
	saveV_s_gpass_form: function(){
		var getV_s_gpass_form	= this.getV_s_gpass_form(),
			form				= getV_s_gpass_form.getForm(),
			values				= getV_s_gpass_form.getValues();
		
		if (form.isValid()) {
			var jsonData = Ext.encode(values);
			console.log(jsonData);
			Ext.Ajax.request({
				method: 'POST',
				url: 'c_s_gpass/save',
				params: {data: jsonData},
				success: function(response){
					var obj = Ext.decode(response.responseText);
					form.reset();
					Ext.Msg.alert('Info', obj.message);
					if (obj.nilai == 3) {
						var redirect = 'home';
						window.location = redirect;
					}
					
				}
			});
		}
	}
	
});