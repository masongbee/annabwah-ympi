Ext.define('YMPI.controller.TD_EVEFEKTIVITAS',{
	extend: 'Ext.app.Controller',
	views: ['TRANSAKSI.v_td_evefektivitas','TRANSAKSI.v_td_evefektivitas_form'],
	models: ['m_td_evefektivitas'],
	stores: ['s_td_evefektivitas'],
	
	requires: ['Ext.ModelManager'],
	
	refs: [{
		ref: 'Listtd_evefektivitas',
		selector: 'Listtd_evefektivitas'
	}, {
		ref: 'v_td_evefektivitas_form',
		selector: 'v_td_evefektivitas_form'
	}, {
		ref: 'SaveBtnForm',
		selector: 'v_td_evefektivitas_form #save'
	}, {
		ref: 'CreateBtnForm',
		selector: 'v_td_evefektivitas_form #create'
	}, {
		ref: 'TD_EVEFEKTIVITAS',
		selector: 'TD_EVEFEKTIVITAS'
	}],


	init: function(){
		this.control({
			'TD_EVEFEKTIVITAS': {
				'afterrender': this.td_evefektivitasAfterRender
			},
			'Listtd_evefektivitas': {
				'selectionchange': this.enableDelete,
				'itemdblclick': this.updateListtd_evefektivitas
			},
			'Listtd_evefektivitas button[action=create]': {
				click: this.createRecord
			},
			'Listtd_evefektivitas button[action=delete]': {
				click: this.deleteRecord
			},
			'Listtd_evefektivitas button[action=xexcel]': {
				click: this.export2Excel
			},
			'Listtd_evefektivitas button[action=xpdf]': {
				click: this.export2PDF
			},
			'Listtd_evefektivitas button[action=print]': {
				click: this.printRecords
			},
			'v_td_evefektivitas_form button[action=save]': {
				click: this.saveV_td_evefektivitas_form
			},
			'v_td_evefektivitas_form button[action=create]': {
				click: this.saveV_td_evefektivitas_form
			},
			'v_td_evefektivitas_form button[action=cancel]': {
				click: this.cancelV_td_evefektivitas_form
			}
		});
	},
	
	td_evefektivitasAfterRender: function(){
		var td_evefektivitasStore = this.getListtd_evefektivitas().getStore();
		td_evefektivitasStore.load();
	},
	
	createRecord: function(){
		var getListtd_evefektivitas	= this.getListtd_evefektivitas();
		var getV_td_evefektivitas_form= this.getV_td_evefektivitas_form(),
			form			= getV_td_evefektivitas_form.getForm();
		var getSaveBtnForm	= this.getSaveBtnForm();
		var getCreateBtnForm	= this.getCreateBtnForm();
		
		/* grid-panel */
		getListtd_evefektivitas.setDisabled(true);
        
		/* form-panel */
		form.reset();
		getV_td_evefektivitas_form.down('#TDEVE_ID_field').setReadOnly(false);
		getSaveBtnForm.setDisabled(true);
		getCreateBtnForm.setDisabled(false);
		getV_td_evefektivitas_form.setDisabled(false);
		
		this.getTD_EVEFEKTIVITAS().setActiveTab(getV_td_evefektivitas_form);		
	},
	
	enableDelete: function(dataview, selections){
		this.getListtd_evefektivitas().down('#btndelete').setDisabled(!selections.length);
	},
	
	updateListtd_evefektivitas: function(me, record, item, index, e){
		var getTD_EVEFEKTIVITAS		= this.getTD_EVEFEKTIVITAS();
		var getListtd_evefektivitas	= this.getListtd_evefektivitas();
		var getV_td_evefektivitas_form= this.getV_td_evefektivitas_form(),
			form			= getV_td_evefektivitas_form.getForm();
		var getSaveBtnForm	= this.getSaveBtnForm();
		var getCreateBtnForm	= this.getCreateBtnForm();
		
		getSaveBtnForm.setDisabled(false);
		getCreateBtnForm.setDisabled(true);
		getV_td_evefektivitas_form.down('#TDEVE_ID_field').setReadOnly(true);		
		getV_td_evefektivitas_form.loadRecord(record);
		
		getListtd_evefektivitas.setDisabled(true);
		getV_td_evefektivitas_form.setDisabled(false);
		getTD_EVEFEKTIVITAS.setActiveTab(getV_td_evefektivitas_form);
	},
	
	deleteRecord: function(dataview, selections){
		var getstore = this.getListtd_evefektivitas().getStore();
		var selection = this.getListtd_evefektivitas().getSelectionModel().getSelection()[0];
		if(selection){
			Ext.Msg.confirm('Confirmation', 'Are you sure to delete this data: "TDEVE_ID" = "'+selection.data.TDEVE_ID+'"?', function(btn){
				if (btn == 'yes'){
					getstore.remove(selection);
					getstore.sync();
				}
			});
			
		}
	},
	
	export2Excel: function(){
		var getstore = this.getListtd_evefektivitas().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_td_evefektivitas/export2Excel',
			params: {data: jsonData},
			success: function(response){
				window.location = ('./temp/'+response.responseText);
			}
		});
	},
	
	export2PDF: function(){
		var getstore = this.getListtd_evefektivitas().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_td_evefektivitas/export2PDF',
			params: {data: jsonData},
			success: function(response){
				window.open('./temp/td_evefektivitas.pdf', '_blank');
			}
		});
	},
	
	printRecords: function(){
		var getstore = this.getListtd_evefektivitas().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_td_evefektivitas/printRecords',
			params: {data: jsonData},
			success: function(response){
				var result=eval(response.responseText);
				switch(result){
				case 1:
					win = window.open('./temp/td_evefektivitas.html','td_evefektivitas_list','height=400,width=900,resizable=1,scrollbars=1, menubar=1');
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
	
	saveV_td_evefektivitas_form: function(){
		var getTD_EVEFEKTIVITAS		= this.getTD_EVEFEKTIVITAS();
		var getListtd_evefektivitas 	= this.getListtd_evefektivitas();
		var getV_td_evefektivitas_form= this.getV_td_evefektivitas_form(),
			form			= getV_td_evefektivitas_form.getForm(),
			values			= getV_td_evefektivitas_form.getValues();
		var store 			= this.getStore('s_td_evefektivitas');
		
		if (form.isValid()) {
			var jsonData = Ext.encode(values);
			
			Ext.Ajax.request({
				method: 'POST',
				url: 'c_td_evefektivitas/save',
				params: {data: jsonData},
				success: function(response){
					store.reload({
						callback: function(){
							var newRecordIndex = store.findBy(
								function(record, id) {
									if (record.get('TDEVE_ID') === values.TDEVE_ID) {
										return true;
									}
									return false;
								}
							);
							/* getListtd_evefektivitas.getView().select(recordIndex); */
							getListtd_evefektivitas.getSelectionModel().select(newRecordIndex);
						}
					});
					
					getV_td_evefektivitas_form.setDisabled(true);
					getListtd_evefektivitas.setDisabled(false);
					getTD_EVEFEKTIVITAS.setActiveTab(getListtd_evefektivitas);
				}
			});
		}
	},
	
	createV_td_evefektivitas_form: function(){
		var getTD_EVEFEKTIVITAS		= this.getTD_EVEFEKTIVITAS();
		var getListtd_evefektivitas 	= this.getListtd_evefektivitas();
		var getV_td_evefektivitas_form= this.getV_td_evefektivitas_form(),
			form			= getV_td_evefektivitas_form.getForm(),
			values			= getV_td_evefektivitas_form.getValues();
		var store 			= this.getStore('s_td_evefektivitas');
		
		if (form.isValid()) {
			var jsonData = Ext.encode(values);
			
			Ext.Ajax.request({
				method: 'POST',
				url: 'c_td_evefektivitas/save',
				params: {data: jsonData},
				success: function(response){
					store.reload();
					
					getV_td_evefektivitas_form.setDisabled(true);
					getListtd_evefektivitas.setDisabled(false);
					getTD_EVEFEKTIVITAS.setActiveTab(getListtd_evefektivitas);
				}
			});
		}
	},
	
	cancelV_td_evefektivitas_form: function(){
		var getTD_EVEFEKTIVITAS		= this.getTD_EVEFEKTIVITAS();
		var getListtd_evefektivitas	= this.getListtd_evefektivitas();
		var getV_td_evefektivitas_form= this.getV_td_evefektivitas_form(),
			form			= getV_td_evefektivitas_form.getForm();
			
		form.reset();
		getV_td_evefektivitas_form.setDisabled(true);
		getListtd_evefektivitas.setDisabled(false);
		getTD_EVEFEKTIVITAS.setActiveTab(getListtd_evefektivitas);
	}
	
});