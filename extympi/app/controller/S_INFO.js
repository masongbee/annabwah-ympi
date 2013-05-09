Ext.define('YMPI.controller.S_INFO',{
	extend: 'Ext.app.Controller',
	views: ['AKSES.v_s_info', 'AKSES.v_s_info_form'],
	models: ['m_s_info'],
	stores: ['s_s_info'],
	
	requires: ['Ext.ModelManager'],
	
	refs: [{
		ref: 'Lists_info',
		selector: 'Lists_info'
	}, {
		ref: 'v_s_info_form',
		selector: 'v_s_info_form'
	}, {
		ref: 'EastPanel',
		selector: 'S_INFO #east-region-container'
	}, {
		ref: 'SaveBtnForm',
		selector: 'v_s_info_form #save'
	}, {
		ref: 'CreateBtnForm',
		selector: 'v_s_info_form #create'
	}, {
		ref: 'S_INFO',
		selector: 'S_INFO'
	}],


	init: function(){
		this.control({
			'S_INFO': {
				'afterrender': this.s_infoAfterRender
			},
			'Lists_info': {
				'selectionchange': this.enableDelete,
				'itemdblclick': this.updateLists_info
			},
			'Lists_info button[action=create]': {
				click: this.createLists_info
			},
			'Lists_info button[action=delete]': {
				click: this.deleteRecord
			},
			'Lists_info button[action=xexcel]': {
				click: this.export2Excel
			},
			'Lists_info button[action=xpdf]': {
				click: this.export2PDF
			},
			'Lists_info button[action=print]': {
				click: this.printRecords
			},
			'v_s_info_form button[action=save]': {
				click: this.saveV_s_info_form
			},
			'v_s_info_form button[action=create]': {
				click: this.saveV_s_info_form
			},
			'v_s_info_form button[action=cancel]': {
				click: this.cancelV_s_info_form
			}
		});
	},
	
	s_infoAfterRender: function(){
		var s_infoStore 	= this.getLists_info().getStore();
		
		s_infoStore.load();
	},
	
	enableDelete: function(dataview, selections){
		this.getLists_info().down('#delete').setDisabled(!selections.length);
	},
	
	updateLists_info: function(me, record, item, index, e){
		var getS_INFO		= this.getS_INFO();
		var getLists_info	= this.getLists_info();
		var getV_s_info_form= this.getV_s_info_form(),
			form			= getV_s_info_form.getForm();
		var getSaveBtnForm	= this.getSaveBtnForm();
		var getCreateBtnForm	= this.getCreateBtnForm();
		
		getSaveBtnForm.setDisabled(false);
		getCreateBtnForm.setDisabled(true);
		
		getV_s_info_form.down('#info_id_field').setReadOnly(true);
		
		getV_s_info_form.loadRecord(record);
		
		getLists_info.setDisabled(true);
		getV_s_info_form.setDisabled(false);
		getS_INFO.setActiveTab(getV_s_info_form);
	},
	
	createLists_info: function(){
		var getLists_info	= this.getLists_info();
		var getV_s_info_form= this.getV_s_info_form(),
			form			= getV_s_info_form.getForm();
		var getSaveBtnForm	= this.getSaveBtnForm();
		var getCreateBtnForm	= this.getCreateBtnForm();
		
		/* grid-panel */
		getLists_info.setDisabled(true);
        
		/* form-panel */
		form.reset();
		getV_s_info_form.down('#info_id_field').setReadOnly(false);
		getSaveBtnForm.setDisabled(true);
		getCreateBtnForm.setDisabled(false);
		getV_s_info_form.setDisabled(false);
		
		this.getS_INFO().setActiveTab(getV_s_info_form);
	},
	
	deleteRecord: function(dataview, selections){
		var getstore = this.getLists_info().getStore();
		var selection = this.getLists_info().getSelectionModel().getSelection()[0];
		if(selection){
			Ext.Msg.confirm('Confirmation', 'Are you sure to delete this data: INFO_ID = "'+selection.data.INFO_ID+'"?', function(btn){
				if (btn == 'yes'){
					getstore.remove(selection);
					getstore.sync();
				}
			});
			
		}
	},
	
	export2Excel: function(){
		var getstore = this.getLists_info().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_s_info/export2Excel',
			params: {data: jsonData},
			success: function(response){
				window.location = ('./temp/'+response.responseText);
			}
		});
	},
	
	export2PDF: function(){
		var getstore = this.getLists_info().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_s_info/export2PDF',
			params: {data: jsonData},
			success: function(response){
				window.open('./temp/s_info.pdf', '_blank');
			}
		});
	},
	
	printRecords: function(){
		var getstore = this.getLists_info().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_s_info/printRecords',
			params: {data: jsonData},
			success: function(response){
				var result=eval(response.responseText);
				switch(result){
				case 1:
					win = window.open('./temp/s_info.html','s_info_list','height=400,width=900,resizable=1,scrollbars=1, menubar=1');
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
	
	saveV_s_info_form: function(){
		var getS_INFO		= this.getS_INFO();
		var getLists_info 	= this.getLists_info();
		var getV_s_info_form= this.getV_s_info_form(),
			form			= getV_s_info_form.getForm(),
			values			= getV_s_info_form.getValues();
		var store 			= this.getStore('s_s_info');
		
		if (form.isValid()) {
			var jsonData = Ext.encode(values);
			
			Ext.Ajax.request({
				method: 'POST',
				url: 'c_s_info/save',
				params: {data: jsonData},
				success: function(response){
					store.reload();
					
					getV_s_info_form.setDisabled(true);
					getLists_info.setDisabled(false);
					getS_INFO.setActiveTab(getLists_info);
				}
			});
		}
	},
	
	createV_s_info_form: function(){
		var getS_INFO		= this.getS_INFO();
		var getLists_info 	= this.getLists_info();
		var getV_s_info_form= this.getV_s_info_form(),
			form			= getV_s_info_form.getForm(),
			values			= getV_s_info_form.getValues();
		var store 			= this.getStore('s_s_info');
		
		if (form.isValid()) {
			var jsonData = Ext.encode(values);
			
			Ext.Ajax.request({
				method: 'POST',
				url: 'c_s_info/save',
				params: {data: jsonData},
				success: function(response){
					store.reload();
					
					getV_s_info_form.setDisabled(true);
					getLists_info.setDisabled(false);
					getS_INFO.setActiveTab(getLists_info);
				}
			});
		}
	},
	
	cancelV_s_info_form: function(){
		var getS_INFO		= this.getS_INFO();
		var getLists_info 	= this.getLists_info();
		var getV_s_info_form= this.getV_s_info_form(),
			form			= getV_s_info_form.getForm();
			
		form.reset();
		getV_s_info_form.setDisabled(true);
		getLists_info.setDisabled(false);
		getS_INFO.setActiveTab(getLists_info);
	}
	
});