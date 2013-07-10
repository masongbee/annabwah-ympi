Ext.define('YMPI.controller.CUTITAHUNAN',{
	extend: 'Ext.app.Controller',
	views: ['MASTER.v_cutitahunan','MASTER.v_cutitahunan_form'],
	models: ['m_cutitahunan'],
	stores: ['s_cutitahunan'],
	
	requires: ['Ext.ModelManager'],
	
	refs: [{
		ref: 'Listcutitahunan',
		selector: 'Listcutitahunan'
	}, {
		ref: 'v_cutitahunan_form',
		selector: 'v_cutitahunan_form'
	}, {
		ref: 'SaveBtnForm',
		selector: 'v_cutitahunan_form #save'
	}, {
		ref: 'CreateBtnForm',
		selector: 'v_cutitahunan_form #create'
	}, {
		ref: 'CUTITAHUNAN',
		selector: 'CUTITAHUNAN'
	}],


	init: function(){
		this.control({
			'CUTITAHUNAN': {
				'afterrender': this.cutitahunanAfterRender
			},
			'Listcutitahunan': {
				'selectionchange': this.enableDelete,
				'itemdblclick': this.updateListcutitahunan
			},
			'Listcutitahunan button[action=create]': {
				click: this.createRecord
			},
			'Listcutitahunan button[action=delete]': {
				click: this.deleteRecord
			},
			'Listcutitahunan button[action=xexcel]': {
				click: this.export2Excel
			},
			'Listcutitahunan button[action=xpdf]': {
				click: this.export2PDF
			},
			'Listcutitahunan button[action=print]': {
				click: this.printRecords
			},
			'v_cutitahunan_form button[action=save]': {
				click: this.saveV_cutitahunan_form
			},
			'v_cutitahunan_form button[action=create]': {
				click: this.saveV_cutitahunan_form
			},
			'v_cutitahunan_form button[action=cancel]': {
				click: this.cancelV_cutitahunan_form
			}
		});
	},
	
	cutitahunanAfterRender: function(){
		var cutitahunanStore = this.getListcutitahunan().getStore();
		cutitahunanStore.load();
	},
	
	createRecord: function(){
		var getListcutitahunan	= this.getListcutitahunan();
		var getV_cutitahunan_form= this.getV_cutitahunan_form(),
			form			= getV_cutitahunan_form.getForm();
		var getSaveBtnForm	= this.getSaveBtnForm();
		var getCreateBtnForm	= this.getCreateBtnForm();
		
		/* grid-panel */
		getListcutitahunan.setDisabled(true);
        
		/* form-panel */
		form.reset();
		getV_cutitahunan_form.down('#NIK_field').setReadOnly(false);getV_cutitahunan_form.down('#TAHUN_field').setReadOnly(false);getV_cutitahunan_form.down('#TANGGAL_field').setReadOnly(false);
		getSaveBtnForm.setDisabled(true);
		getCreateBtnForm.setDisabled(false);
		getV_cutitahunan_form.setDisabled(false);
		
		this.getCUTITAHUNAN().setActiveTab(getV_cutitahunan_form);		
	},
	
	enableDelete: function(dataview, selections){
		this.getListcutitahunan().down('#btndelete').setDisabled(!selections.length);
	},
	
	updateListcutitahunan: function(me, record, item, index, e){
		var getCUTITAHUNAN		= this.getCUTITAHUNAN();
		var getListcutitahunan	= this.getListcutitahunan();
		var getV_cutitahunan_form= this.getV_cutitahunan_form(),
			form			= getV_cutitahunan_form.getForm();
		var getSaveBtnForm	= this.getSaveBtnForm();
		var getCreateBtnForm	= this.getCreateBtnForm();
		
		getSaveBtnForm.setDisabled(false);
		getCreateBtnForm.setDisabled(true);
		getV_cutitahunan_form.down('#NIK_field').setReadOnly(true);getV_cutitahunan_form.down('#TAHUN_field').setReadOnly(true);getV_cutitahunan_form.down('#TANGGAL_field').setReadOnly(true);		
		getV_cutitahunan_form.loadRecord(record);
		
		getListcutitahunan.setDisabled(true);
		getV_cutitahunan_form.setDisabled(false);
		getCUTITAHUNAN.setActiveTab(getV_cutitahunan_form);
	},
	
	deleteRecord: function(dataview, selections){
		var getstore = this.getListcutitahunan().getStore();
		var selection = this.getListcutitahunan().getSelectionModel().getSelection()[0];
		if(selection){
			Ext.Msg.confirm('Confirmation', 'Are you sure to delete this data: "NIK" = "'+selection.data.NIK+'","TAHUN" = "'+selection.data.TAHUN+'","TANGGAL" = "'+selection.data.TANGGAL+'"?', function(btn){
				if (btn == 'yes'){
					getstore.remove(selection);
					getstore.sync();
				}
			});
			
		}
	},
	
	export2Excel: function(){
		var getstore = this.getListcutitahunan().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_cutitahunan/export2Excel',
			params: {data: jsonData},
			success: function(response){
				window.location = ('./temp/'+response.responseText);
			}
		});
	},
	
	export2PDF: function(){
		var getstore = this.getListcutitahunan().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_cutitahunan/export2PDF',
			params: {data: jsonData},
			success: function(response){
				window.open('./temp/cutitahunan.pdf', '_blank');
			}
		});
	},
	
	printRecords: function(){
		var getstore = this.getListcutitahunan().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_cutitahunan/printRecords',
			params: {data: jsonData},
			success: function(response){
				var result=eval(response.responseText);
				switch(result){
				case 1:
					win = window.open('./temp/cutitahunan.html','cutitahunan_list','height=400,width=900,resizable=1,scrollbars=1, menubar=1');
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
	
	saveV_cutitahunan_form: function(){
		var getCUTITAHUNAN		= this.getCUTITAHUNAN();
		var getListcutitahunan 	= this.getListcutitahunan();
		var getV_cutitahunan_form= this.getV_cutitahunan_form(),
			form			= getV_cutitahunan_form.getForm(),
			values			= getV_cutitahunan_form.getValues();
		var store 			= this.getStore('s_cutitahunan');
		
		if (form.isValid()) {
			var jsonData = Ext.encode(values);
			
			Ext.Ajax.request({
				method: 'POST',
				url: 'c_cutitahunan/save',
				params: {data: jsonData},
				success: function(response){
					store.reload({
						callback: function(){
							var newRecordIndex = store.findBy(
								function(record, id) {
									if (record.get('NIK') === values.NIK && record.get('TAHUN') === values.TAHUN && (new Date(record.get('TANGGAL'))).format('yyyy-mm-dd') === (new Date(values.TANGGAL)).format('yyyy-mm-dd')) {
										return true;
									}
									return false;
								}
							);
							/* getListcutitahunan.getView().select(recordIndex); */
							getListcutitahunan.getSelectionModel().select(newRecordIndex);
						}
					});
					
					getV_cutitahunan_form.setDisabled(true);
					getListcutitahunan.setDisabled(false);
					getCUTITAHUNAN.setActiveTab(getListcutitahunan);
				}
			});
		}
	},
	
	createV_cutitahunan_form: function(){
		var getCUTITAHUNAN		= this.getCUTITAHUNAN();
		var getListcutitahunan 	= this.getListcutitahunan();
		var getV_cutitahunan_form= this.getV_cutitahunan_form(),
			form			= getV_cutitahunan_form.getForm(),
			values			= getV_cutitahunan_form.getValues();
		var store 			= this.getStore('s_cutitahunan');
		
		if (form.isValid()) {
			var jsonData = Ext.encode(values);
			
			Ext.Ajax.request({
				method: 'POST',
				url: 'c_cutitahunan/save',
				params: {data: jsonData},
				success: function(response){
					store.reload();
					
					getV_cutitahunan_form.setDisabled(true);
					getListcutitahunan.setDisabled(false);
					getCUTITAHUNAN.setActiveTab(getListcutitahunan);
				}
			});
		}
	},
	
	cancelV_cutitahunan_form: function(){
		var getCUTITAHUNAN		= this.getCUTITAHUNAN();
		var getListcutitahunan	= this.getListcutitahunan();
		var getV_cutitahunan_form= this.getV_cutitahunan_form(),
			form			= getV_cutitahunan_form.getForm();
			
		form.reset();
		getV_cutitahunan_form.setDisabled(true);
		getListcutitahunan.setDisabled(false);
		getCUTITAHUNAN.setActiveTab(getListcutitahunan);
	}
	
});