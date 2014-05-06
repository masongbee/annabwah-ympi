Ext.define('YMPI.controller.LAPGAJI',{
	extend: 'Ext.app.Controller',
	views: ['LAPORAN.v_lapgaji','LAPORAN.v_lapgaji_form'],
	models: ['m_lapgaji'],
	stores: ['s_lapgaji'],
	
	refs: [{
		ref: 'V_lapgaji_form',
		selector: 'v_lapgaji_form'
	}, {
		ref: 'V_lapgaji',
		selector: 'v_lapgaji'
	}],

	init: function(){
		this.control({
			'v_lapgaji_form button[action=searchall]': {
				click: this.v_lapgaji_formSearch
			}
		});
	},

	v_lapgaji_formSearch: function(){
		var getV_lapgaji_form 	= this.getV_lapgaji_form(),
			form				= getV_lapgaji_form.getForm(),
			values				= getV_lapgaji_form.getValues();
		var getV_lapgaji		= this.getV_lapgaji(),
		 	lapgajiStore 		= getV_lapgaji.getStore();
		getV_lapgaji.bulangaji = values.bulangaji;
		getV_lapgaji.grade     = values.grade;
		
		if (form.isValid()) {
			var jsonData = Ext.encode(values);
			
			lapgajiStore.getProxy().extraParams.data = jsonData;
			lapgajiStore.load();
		}
	}
	
});