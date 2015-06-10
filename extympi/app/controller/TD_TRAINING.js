Ext.define('YMPI.controller.TD_TRAINING',{
	extend: 'Ext.app.Controller',
	views: ['MASTER.v_td_training','MASTER.v_td_training_form'],
	models: ['m_td_training'],
	stores: ['s_td_training'],
	
	requires: ['Ext.ModelManager'],
	
	refs: [{
		ref: 'Listtd_training',
		selector: 'Listtd_training'
	}, {
		ref: 'v_td_training_form',
		selector: 'v_td_training_form'
	}, {
		ref: 'SaveBtnForm',
		selector: 'v_td_training_form #save'
	}, {
		ref: 'CreateBtnForm',
		selector: 'v_td_training_form #create'
	}, {
		ref: 'TD_TRAINING',
		selector: 'TD_TRAINING'
	}],


	init: function(){
		this.control({
			'TD_TRAINING': {
				'afterrender': this.td_trainingAfterRender
			},
			'Listtd_training': {
				'selectionchange': this.enableDelete,
				'itemdblclick': this.updateListtd_training
			},
			'Listtd_training button[action=create]': {
				click: this.createRecord
			},
			'Listtd_training button[action=delete]': {
				click: this.deleteRecord
			},
			'Listtd_training button[action=xexcel]': {
				click: this.export2Excel
			},
			'Listtd_training button[action=xpdf]': {
				click: this.export2PDF
			},
			'Listtd_training button[action=print]': {
				click: this.printRecords
			},
			'v_td_training_form button[action=save]': {
				click: this.saveV_td_training_form
			},
			'v_td_training_form button[action=create]': {
				click: this.saveV_td_training_form
			},
			'v_td_training_form button[action=cancel]': {
				click: this.cancelV_td_training_form
			}
		});
	},
	
	td_trainingAfterRender: function(){
		var td_trainingStore = this.getListtd_training().getStore();
		td_trainingStore.load();
	},
	
	createRecord: function(){
		var getListtd_training	= this.getListtd_training();
		var getV_td_training_form= this.getV_td_training_form(),
			form			= getV_td_training_form.getForm();
		var getSaveBtnForm	= this.getSaveBtnForm();
		var getCreateBtnForm	= this.getCreateBtnForm();
		
		/* grid-panel */
		getListtd_training.setDisabled(true);
        
		/* form-panel */
		form.reset();
		getV_td_training_form.down('#TDTRAINING_ID_field').setReadOnly(false);
		getSaveBtnForm.setDisabled(true);
		getCreateBtnForm.setDisabled(false);
		getV_td_training_form.setDisabled(false);
		
		this.getTD_TRAINING().setActiveTab(getV_td_training_form);		
	},
	
	enableDelete: function(dataview, selections){
		this.getListtd_training().down('#btndelete').setDisabled(!selections.length);
	},
	
	updateListtd_training: function(me, record, item, index, e){
		var getTD_TRAINING		= this.getTD_TRAINING();
		var getListtd_training	= this.getListtd_training();
		var getV_td_training_form= this.getV_td_training_form(),
			form			= getV_td_training_form.getForm();
		var getSaveBtnForm	= this.getSaveBtnForm();
		var getCreateBtnForm	= this.getCreateBtnForm();
		
		getSaveBtnForm.setDisabled(false);
		getCreateBtnForm.setDisabled(true);
		getV_td_training_form.down('#TDTRAINING_ID_field').setReadOnly(true);		
		getV_td_training_form.loadRecord(record);
		
		getListtd_training.setDisabled(true);
		getV_td_training_form.setDisabled(false);
		getTD_TRAINING.setActiveTab(getV_td_training_form);
	},
	
	deleteRecord: function(dataview, selections){
		var getstore = this.getListtd_training().getStore();
		var selection = this.getListtd_training().getSelectionModel().getSelection()[0];
		if(selection){
			Ext.Msg.confirm('Confirmation', 'Are you sure to delete this data: "TDTRAINING_ID" = "'+selection.data.TDTRAINING_ID+'"?', function(btn){
				if (btn == 'yes'){
					getstore.remove(selection);
					getstore.sync();
				}
			});
			
		}
	},
	
	export2Excel: function(){
		var getstore = this.getListtd_training().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_td_training/export2Excel',
			params: {data: jsonData},
			success: function(response){
				window.location = ('./temp/'+response.responseText);
			}
		});
	},
	
	export2PDF: function(){
		var getstore = this.getListtd_training().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_td_training/export2PDF',
			params: {data: jsonData},
			success: function(response){
				window.open('./temp/td_training.pdf', '_blank');
			}
		});
	},
	
	printRecords: function(){
		var getstore = this.getListtd_training().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_td_training/printRecords',
			params: {data: jsonData},
			success: function(response){
				var result=eval(response.responseText);
				switch(result){
				case 1:
					win = window.open('./temp/td_training.html','td_training_list','height=400,width=900,resizable=1,scrollbars=1, menubar=1');
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
	
	saveV_td_training_form: function(){
		console.log('start create');
		var getTD_TRAINING		= this.getTD_TRAINING();
		var getListtd_training 	= this.getListtd_training();
		var getV_td_training_form= this.getV_td_training_form(),
			form			= getV_td_training_form.getForm(),
			values			= getV_td_training_form.getValues();
		var store 			= this.getStore('s_td_training');
		
		if (form.isValid()) {
			var jsonData = Ext.encode(values);
			
			Ext.Ajax.request({
				method: 'POST',
				url: 'c_td_training/save',
				params: {data: jsonData},
				success: function(response){
					store.reload({
						callback: function(){
							var newRecordIndex = store.findBy(
								function(record, id) {
									if (record.get('TDTRAINING_ID') === values.TDTRAINING_ID) {
										return true;
									}
									return false;
								}
							);
							/* getListtd_training.getView().select(recordIndex); */
							getListtd_training.getSelectionModel().select(newRecordIndex);
						}
					});
					
					getV_td_training_form.setDisabled(true);
					getListtd_training.setDisabled(false);
					getTD_TRAINING.setActiveTab(getListtd_training);
				}
			});
		} else {
			console.log('tidak valid');
		};
	},
	
	createV_td_training_form: function(){
		var getTD_TRAINING		= this.getTD_TRAINING();
		var getListtd_training 	= this.getListtd_training();
		var getV_td_training_form= this.getV_td_training_form(),
			form			= getV_td_training_form.getForm(),
			values			= getV_td_training_form.getValues();
		var store 			= this.getStore('s_td_training');
		
		if (form.isValid()) {
			var jsonData = Ext.encode(values);
			
			Ext.Ajax.request({
				method: 'POST',
				url: 'c_td_training/save',
				params: {data: jsonData},
				success: function(response){
					store.reload();
					
					getV_td_training_form.setDisabled(true);
					getListtd_training.setDisabled(false);
					getTD_TRAINING.setActiveTab(getListtd_training);
				}
			});
		}
	},
	
	cancelV_td_training_form: function(){
		var getTD_TRAINING		= this.getTD_TRAINING();
		var getListtd_training	= this.getListtd_training();
		var getV_td_training_form= this.getV_td_training_form(),
			form			= getV_td_training_form.getForm();
			
		form.reset();
		getV_td_training_form.setDisabled(true);
		getListtd_training.setDisabled(false);
		getTD_TRAINING.setActiveTab(getListtd_training);
	}
	
});