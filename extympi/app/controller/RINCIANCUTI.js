Ext.define('YMPI.controller.RINCIANCUTI',{
	extend: 'Ext.app.Controller',
	views: ['TRANSAKSI.v_rinciancuti','TRANSAKSI.v_rinciancuti_form'],
	models: ['m_rinciancuti'],
	stores: ['s_rinciancuti'],
	
	requires: ['Ext.ModelManager'],
	
	refs: [{
		ref: 'Listrinciancuti',
		selector: 'Listrinciancuti'
	}, {
		ref: 'v_rinciancuti_form',
		selector: 'v_rinciancuti_form'
	}, {
		ref: 'SaveBtnForm',
		selector: 'v_rinciancuti_form #save'
	}, {
		ref: 'CreateBtnForm',
		selector: 'v_rinciancuti_form #create'
	}, {
		ref: 'RINCIANCUTI',
		selector: 'RINCIANCUTI'
	}],


	init: function(){
		this.control({
			'RINCIANCUTI': {
				'afterrender': this.rinciancutiAfterRender
			},
			'Listrinciancuti': {
				'selectionchange': this.enableDeleteRCuti,
				'itemdblclick': this.updateListrinciancuti
			},
			'Listrinciancuti button[action=create]': {
				click: this.createRecordRincianCuti
			},
			'Listrinciancuti button[action=delete]': {
				click: this.deleteRecordRincianCuti
			},
			'Listrinciancuti button[action=xexcel]': {
				click: this.export2Excel
			},
			'Listrinciancuti button[action=xpdf]': {
				click: this.export2PDF
			},
			'Listrinciancuti button[action=print]': {
				click: this.printRecords
			},
			'v_rinciancuti_form button[action=save]': {
				click: this.saveV_rinciancuti_form
			},
			'v_rinciancuti_form button[action=create]': {
				click: this.saveV_rinciancuti_form
			},
			'v_rinciancuti_form button[action=cancel]': {
				click: this.cancelV_rinciancuti_form
			}
		});
	},
	
	rinciancutiAfterRender: function(){
		var rinciancutiStore = this.getListrinciancuti().getStore();
		rinciancutiStore.load();
	},
	
	createRecordRincianCuti: function(){
		var getListrinciancuti	= this.getListrinciancuti();
		var getV_rinciancuti_form= this.getV_rinciancuti_form(),
			form			= getV_rinciancuti_form.getForm();
		var getSaveBtnForm	= this.getSaveBtnForm();
		var getCreateBtnForm	= this.getCreateBtnForm();
		
		/* grid-panel */
		getListrinciancuti.setDisabled(true);
        
		/* form-panel */
		form.reset();
		getV_rinciancuti_form.down('#NOCUTI_field').setReadOnly(false);getV_rinciancuti_form.down('#NOURUT_field').setReadOnly(false);
		getSaveBtnForm.setDisabled(true);
		getCreateBtnForm.setDisabled(false);
		getV_rinciancuti_form.setDisabled(false);
		
		this.getRINCIANCUTI().setActiveTab(getV_rinciancuti_form);		
	},
	
	enableDeleteRCuti: function(dataview, selections){
		this.getListrinciancuti().down('#btndelete').setDisabled(!selections.length);
	},
	
	updateListrinciancuti: function(me, record, item, index, e){
		var getRINCIANCUTI		= this.getRINCIANCUTI();
		var getListrinciancuti	= this.getListrinciancuti();
		var getV_rinciancuti_form= this.getV_rinciancuti_form(),
			form			= getV_rinciancuti_form.getForm();
		var getSaveBtnForm	= this.getSaveBtnForm();
		var getCreateBtnForm	= this.getCreateBtnForm();
		
		getSaveBtnForm.setDisabled(false);
		getCreateBtnForm.setDisabled(true);
		getV_rinciancuti_form.down('#NOCUTI_field').setReadOnly(true);getV_rinciancuti_form.down('#NOURUT_field').setReadOnly(true);		
		getV_rinciancuti_form.loadRecord(record);
		
		getListrinciancuti.setDisabled(true);
		getV_rinciancuti_form.setDisabled(false);
		getRINCIANCUTI.setActiveTab(getV_rinciancuti_form);
	},
	
	deleteRecordRincianCuti: function(dataview, selections){
		var getstore = this.getListrinciancuti().getStore();
		var selection = this.getListrinciancuti().getSelectionModel().getSelection()[0];
		if(selection){
			Ext.Msg.confirm('Confirmation', 'Are you sure to delete this data: "NOCUTI" = "'+selection.data.NOCUTI+'","NOURUT" = "'+selection.data.NOURUT+'"?', function(btn){
				if (btn == 'yes'){
					getstore.remove(selection);
					getstore.sync();
				}
			});
			
		}
	},
	
	export2Excel: function(){
		var getstore = this.getListrinciancuti().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_rinciancuti/export2Excel',
			params: {data: jsonData},
			success: function(response){
				window.location = ('./temp/'+response.responseText);
			}
		});
	},
	
	export2PDF: function(){
		var getstore = this.getListrinciancuti().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_rinciancuti/export2PDF',
			params: {data: jsonData},
			success: function(response){
				window.open('./temp/rinciancuti.pdf', '_blank');
			}
		});
	},
	
	printRecords: function(){
		var getstore = this.getListrinciancuti().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_rinciancuti/printRecords',
			params: {data: jsonData},
			success: function(response){
				var result=eval(response.responseText);
				switch(result){
				case 1:
					win = window.open('./temp/rinciancuti.html','rinciancuti_list','height=400,width=900,resizable=1,scrollbars=1, menubar=1');
					break;
				default:
					Ext.MessageBox.show({
						title: 'Warning',
						msg: 'Unable to print the grid!',
						buttons: Ext.MessageBox.OK,
						animEl: 'save',
						icon: Ext.MessageBox.WARNING
					});
					break;
				}  
			}
		});
	},
	
	saveV_rinciancuti_form: function(){
		var getRINCIANCUTI		= this.getRINCIANCUTI();
		var getListrinciancuti 	= this.getListrinciancuti();
		var getV_rinciancuti_form= this.getV_rinciancuti_form(),
			form			= getV_rinciancuti_form.getForm(),
			values			= getV_rinciancuti_form.getValues();
		var store 			= this.getStore('s_rinciancuti');
		
		if (form.isValid()) {
			var jsonData = Ext.encode(values);
			
			Ext.Ajax.request({
				method: 'POST',
				url: 'c_rinciancuti/save',
				params: {data: jsonData},
				success: function(response){
					store.reload({
						callback: function(){
							var newRecordIndex = store.findBy(
								function(record, id) {
									if (record.get('NOCUTI') === values.NOCUTI && record.get('NOURUT') === values.NOURUT) {
										return true;
									}
									return false;
								}
							);
							/* getListrinciancuti.getView().select(recordIndex); */
							getListrinciancuti.getSelectionModel().select(newRecordIndex);
						}
					});
					
					getV_rinciancuti_form.setDisabled(true);
					getListrinciancuti.setDisabled(false);
					getRINCIANCUTI.setActiveTab(getListrinciancuti);
				}
			});
		}
	},
	
	createV_rinciancuti_form: function(){
		var getRINCIANCUTI		= this.getRINCIANCUTI();
		var getListrinciancuti 	= this.getListrinciancuti();
		var getV_rinciancuti_form= this.getV_rinciancuti_form(),
			form			= getV_rinciancuti_form.getForm(),
			values			= getV_rinciancuti_form.getValues();
		var store 			= this.getStore('s_rinciancuti');
		
		if (form.isValid()) {
			var jsonData = Ext.encode(values);
			
			Ext.Ajax.request({
				method: 'POST',
				url: 'c_rinciancuti/save',
				params: {data: jsonData},
				success: function(response){
					store.reload();
					
					getV_rinciancuti_form.setDisabled(true);
					getListrinciancuti.setDisabled(false);
					getRINCIANCUTI.setActiveTab(getListrinciancuti);
				}
			});
		}
	},
	
	cancelV_rinciancuti_form: function(){
		var getRINCIANCUTI		= this.getRINCIANCUTI();
		var getListrinciancuti	= this.getListrinciancuti();
		var getV_rinciancuti_form= this.getV_rinciancuti_form(),
			form			= getV_rinciancuti_form.getForm();
			
		form.reset();
		getV_rinciancuti_form.setDisabled(true);
		getListrinciancuti.setDisabled(false);
		getRINCIANCUTI.setActiveTab(getListrinciancuti);
	}
	
});