Ext.define('YMPI.controller.UPAHPOKOK',{
	extend: 'Ext.app.Controller',
	views: ['MASTER.v_upahpokok', 'MASTER.v_upahpokok_form'],
	models: ['m_upahpokok'],
	stores: ['s_upahpokok'],
	
	requires: ['Ext.ModelManager'],
	
	refs: [{
		ref: 'Listupahpokok',
		selector: 'Listupahpokok'
	}, {
		ref: 'v_upahpokok_form',
		selector: 'v_upahpokok_form'
	}, {
		ref: 'SaveBtnForm',
		selector: 'v_upahpokok_form #save'
	}, {
		ref: 'CreateBtnForm',
		selector: 'v_upahpokok_form #create'
	}, {
		ref: 'UPAHPOKOK',
		selector: 'UPAHPOKOK'
	}],


	init: function(){
		this.control({
			'UPAHPOKOK': {
				'afterrender': this.upahpokokAfterRender
			},
			'Listupahpokok': {
				'selectionchange': this.enableDelete,
				'itemdblclick': this.updateListupahpokok
			},
			'Listupahpokok button[action=create]': {
				click: this.createRecord
			},
			'Listupahpokok button[action=delete]': {
				click: this.deleteRecord
			},
			'Listupahpokok button[action=xexcel]': {
				click: this.export2Excel
			},
			'Listupahpokok button[action=xpdf]': {
				click: this.export2PDF
			},
			'Listupahpokok button[action=print]': {
				click: this.printRecords
			},
			'v_upahpokok_form button[action=save]': {
				click: this.saveV_upahpokok_form
			},
			'v_upahpokok_form button[action=create]': {
				click: this.saveV_upahpokok_form
			},
			'v_upahpokok_form button[action=cancel]': {
				click: this.cancelV_upahpokok_form
			}
		});
	},
	
	upahpokokAfterRender: function(){
		var upahpokokStore = this.getListupahpokok().getStore();
		upahpokokStore.load();
	},
	
	createRecord: function(){
		var getListupahpokok	= this.getListupahpokok();
		var getV_upahpokok_form= this.getV_upahpokok_form(),
			form			= getV_upahpokok_form.getForm();
		var getSaveBtnForm	= this.getSaveBtnForm();
		var getCreateBtnForm	= this.getCreateBtnForm();
		
		/* grid-panel */
		getListupahpokok.setDisabled(true);
        
		/* form-panel */
		form.reset();
		getV_upahpokok_form.down('#info_id_field').setReadOnly(false);
		getSaveBtnForm.setDisabled(true);
		getCreateBtnForm.setDisabled(false);
		getV_upahpokok_form.setDisabled(false);
		
		this.getUPAHPOKOK().setActiveTab(getV_upahpokok_form);		
	},
	
	enableDelete: function(dataview, selections){
		this.getListupahpokok().down('#btndelete').setDisabled(!selections.length);
	},
	
	updateLists_info: function(me, record, item, index, e){
		var getUPAHPOKOK		= this.getUPAHPOKOK();
		var getListupahpokok	= this.getListupahpokok();
		var getV_upahpokok_form= this.getV_upahpokok_form(),
			form			= getV_upahpokok_form.getForm();
		var getSaveBtnForm	= this.getSaveBtnForm();
		var getCreateBtnForm	= this.getCreateBtnForm();
		
		getSaveBtnForm.setDisabled(false);
		getCreateBtnForm.setDisabled(true);
		
		getV_upahpokok_form.down('#info_id_field').setReadOnly(true);
		
		getV_upahpokok_form.loadRecord(record);
		
		getListupahpokok.setDisabled(true);
		getV_upahpokok_form.setDisabled(false);
		getUPAHPOKOK.setActiveTab(getV_upahpokok_form);
	},
	
	deleteRecord: function(dataview, selections){
		var getstore = this.getListupahpokok().getStore();
		var selection = this.getListupahpokok().getSelectionModel().getSelection()[0];
		if(selection){
			Ext.Msg.confirm('Confirmation', 'Are you sure to delete this data: "VALIDFROM" = "'+selection.data.VALIDFROM+'","NOURUT" = "'+selection.data.NOURUT+'"?', function(btn){
				if (btn == 'yes'){
					getstore.remove(selection);
					getstore.sync();
				}
			});
			
		}
	},
	
	export2Excel: function(){
		var getstore = this.getListupahpokok().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_upahpokok/export2Excel',
			params: {data: jsonData},
			success: function(response){
				window.location = ('./temp/'+response.responseText);
			}
		});
	},
	
	export2PDF: function(){
		var getstore = this.getListupahpokok().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_upahpokok/export2PDF',
			params: {data: jsonData},
			success: function(response){
				window.open('./temp/upahpokok.pdf', '_blank');
			}
		});
	},
	
	printRecords: function(){
		var getstore = this.getListupahpokok().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_upahpokok/printRecords',
			params: {data: jsonData},
			success: function(response){
				var result=eval(response.responseText);
				switch(result){
				case 1:
					win = window.open('./temp/upahpokok.html','upahpokok_list','height=400,width=900,resizable=1,scrollbars=1, menubar=1');
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
	
	saveV_upahpokok_form: function(){
		var getUPAHPOKOK		= this.getUPAHPOKOK();
		var getListupahpokok 	= this.getListupahpokok();
		var getV_upahpokok_form= this.getV_upahpokok_form(),
			form			= getV_upahpokok_form.getForm(),
			values			= getV_upahpokok_form.getValues();
		var store 			= this.getStore('s_upahpokok');
		
		if (form.isValid()) {
			var jsonData = Ext.encode(values);
			
			Ext.Ajax.request({
				method: 'POST',
				url: 'c_upahpokok/save',
				params: {data: jsonData},
				success: function(response){
					store.reload();
					
					getV_upahpokok_form.setDisabled(true);
					getListupahpokok.setDisabled(false);
					getUPAHPOKOK.setActiveTab(getListupahpokok);
				}
			});
		}
	},
	
	createV_upahpokok_form: function(){
		var getUPAHPOKOK		= this.getUPAHPOKOK();
		var getListupahpokok 	= this.getListupahpokok();
		var getV_upahpokok_form= this.getV_upahpokok_form(),
			form			= getV_upahpokok_form.getForm(),
			values			= getV_upahpokok_form.getValues();
		var store 			= this.getStore('s_upahpokok');
		
		if (form.isValid()) {
			var jsonData = Ext.encode(values);
			
			Ext.Ajax.request({
				method: 'POST',
				url: 'c_upahpokok/save',
				params: {data: jsonData},
				success: function(response){
					store.reload();
					
					getV_upahpokok_form.setDisabled(true);
					getListupahpokok.setDisabled(false);
					getUPAHPOKOK.setActiveTab(getListupahpokok);
				}
			});
		}
	},
	
	cancelV_upahpokok_form: function(){
		var getUPAHPOKOK		= this.getUPAHPOKOK();
		var getListupahpokok	= this.getListupahpokok();
		var getV_upahpokok_form= this.getV_upahpokok_form(),
			form			= getV_upahpokok_form.getForm();
			
		form.reset();
		getV_upahpokok_form.setDisabled(true);
		getListupahpokok.setDisabled(false);
		getUPAHPOKOK.setActiveTab(getListupahpokok);
	}
	
});