Ext.define('YMPI.controller.PERIODEGAJI',{
	extend: 'Ext.app.Controller',
	views: ['MASTER.v_periodegaji','MASTER.v_periodegaji_form'],
	models: ['m_periodegaji'],
	stores: ['s_periodegaji'],
	
	requires: ['Ext.ModelManager'],
	
	refs: [{
		ref: 'Listperiodegaji',
		selector: 'Listperiodegaji'
	}, {
		ref: 'v_periodegaji_form',
		selector: 'v_periodegaji_form'
	}, {
		ref: 'SaveBtnForm',
		selector: 'v_periodegaji_form #save'
	}, {
		ref: 'CreateBtnForm',
		selector: 'v_periodegaji_form #create'
	}, {
		ref: 'PERIODEGAJI',
		selector: 'PERIODEGAJI'
	}],


	init: function(){
		this.control({
			'PERIODEGAJI': {
				'afterrender': this.periodegajiAfterRender
			},
			'Listperiodegaji': {
				'selectionchange': this.enableDelete,
				'itemdblclick': this.updateListperiodegaji
			},
			'Listperiodegaji button[action=create]': {
				click: this.createRecord
			},
			'Listperiodegaji button[action=delete]': {
				click: this.deleteRecord
			},
			'Listperiodegaji button[action=xexcel]': {
				click: this.export2Excel
			},
			'Listperiodegaji button[action=xpdf]': {
				click: this.export2PDF
			},
			'Listperiodegaji button[action=print]': {
				click: this.printRecords
			},
			'v_periodegaji_form button[action=save]': {
				click: this.saveV_periodegaji_form
			},
			'v_periodegaji_form button[action=create]': {
				click: this.saveV_periodegaji_form
			},
			'v_periodegaji_form button[action=cancel]': {
				click: this.cancelV_periodegaji_form
			}
		});
	},
	
	periodegajiAfterRender: function(){
		var periodegajiStore = this.getListperiodegaji().getStore();
		periodegajiStore.load();
	},
	
	createRecord: function(){
		var getListperiodegaji	= this.getListperiodegaji();
		var getV_periodegaji_form= this.getV_periodegaji_form(),
			form			= getV_periodegaji_form.getForm();
		var getSaveBtnForm	= this.getSaveBtnForm();
		var getCreateBtnForm	= this.getCreateBtnForm();
		
		/* grid-panel */
		getListperiodegaji.setDisabled(true);
        
		/* form-panel */
		form.reset();
		getV_periodegaji_form.down('#BULAN_field').setReadOnly(false);
		getSaveBtnForm.setDisabled(true);
		getCreateBtnForm.setDisabled(false);
		getV_periodegaji_form.setDisabled(false);
		
		this.getPERIODEGAJI().setActiveTab(getV_periodegaji_form);		
	},
	
	enableDelete: function(dataview, selections){
		this.getListperiodegaji().down('#btndelete').setDisabled(!selections.length);
	},
	
	updateListperiodegaji: function(me, record, item, index, e){
		var getPERIODEGAJI		= this.getPERIODEGAJI();
		var getListperiodegaji	= this.getListperiodegaji();
		var getV_periodegaji_form= this.getV_periodegaji_form(),
			form			= getV_periodegaji_form.getForm();
		var getSaveBtnForm	= this.getSaveBtnForm();
		var getCreateBtnForm	= this.getCreateBtnForm();
		
		getSaveBtnForm.setDisabled(false);
		getCreateBtnForm.setDisabled(true);
		getV_periodegaji_form.down('#BULAN_field').setReadOnly(true);		
		getV_periodegaji_form.loadRecord(record);
		
		getListperiodegaji.setDisabled(true);
		getV_periodegaji_form.setDisabled(false);
		getPERIODEGAJI.setActiveTab(getV_periodegaji_form);
	},
	
	deleteRecord: function(dataview, selections){
		var getstore = this.getListperiodegaji().getStore();
		var selection = this.getListperiodegaji().getSelectionModel().getSelection()[0];
		if(selection){
			Ext.Msg.confirm('Confirmation', 'Are you sure to delete this data: "BULAN" = "'+selection.data.BULAN+'"?', function(btn){
				if (btn == 'yes'){
					getstore.remove(selection);
					getstore.sync();
				}
			});
			
		}
	},
	
	export2Excel: function(){
		var getstore = this.getListperiodegaji().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_periodegaji/export2Excel',
			params: {data: jsonData},
			success: function(response){
				window.location = ('./temp/'+response.responseText);
			}
		});
	},
	
	export2PDF: function(){
		var getstore = this.getListperiodegaji().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_periodegaji/export2PDF',
			params: {data: jsonData},
			success: function(response){
				window.open('./temp/periodegaji.pdf', '_blank');
			}
		});
	},
	
	printRecords: function(){
		var getstore = this.getListperiodegaji().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_periodegaji/printRecords',
			params: {data: jsonData},
			success: function(response){
				var result=eval(response.responseText);
				switch(result){
				case 1:
					win = window.open('./temp/periodegaji.html','periodegaji_list','height=400,width=900,resizable=1,scrollbars=1, menubar=1');
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
	
	saveV_periodegaji_form: function(){
		var getPERIODEGAJI		= this.getPERIODEGAJI();
		var getListperiodegaji 	= this.getListperiodegaji();
		var getV_periodegaji_form= this.getV_periodegaji_form(),
			form			= getV_periodegaji_form.getForm(),
			values			= getV_periodegaji_form.getValues();
		var store 			= this.getStore('s_periodegaji');
		
		if (form.isValid()) {
			var jsonData = Ext.encode(values);
			
			Ext.Ajax.request({
				method: 'POST',
				url: 'c_periodegaji/save',
				params: {data: jsonData},
				success: function(response){
					store.reload({
						callback: function(){
							var newRecordIndex = store.findBy(
								function(record, id) {
									if (record.get('BULAN') === values.BULAN) {
										return true;
									}
									return false;
								}
							);
							/* getListperiodegaji.getView().select(recordIndex); */
							getListperiodegaji.getSelectionModel().select(newRecordIndex);
						}
					});
					
					getV_periodegaji_form.setDisabled(true);
					getListperiodegaji.setDisabled(false);
					getPERIODEGAJI.setActiveTab(getListperiodegaji);
				}
			});
		}
	},
	
	createV_periodegaji_form: function(){
		var getPERIODEGAJI		= this.getPERIODEGAJI();
		var getListperiodegaji 	= this.getListperiodegaji();
		var getV_periodegaji_form= this.getV_periodegaji_form(),
			form			= getV_periodegaji_form.getForm(),
			values			= getV_periodegaji_form.getValues();
		var store 			= this.getStore('s_periodegaji');
		
		if (form.isValid()) {
			var jsonData = Ext.encode(values);
			
			Ext.Ajax.request({
				method: 'POST',
				url: 'c_periodegaji/save',
				params: {data: jsonData},
				success: function(response){
					store.reload();
					
					getV_periodegaji_form.setDisabled(true);
					getListperiodegaji.setDisabled(false);
					getPERIODEGAJI.setActiveTab(getListperiodegaji);
				}
			});
		}
	},
	
	cancelV_periodegaji_form: function(){
		var getPERIODEGAJI		= this.getPERIODEGAJI();
		var getListperiodegaji	= this.getListperiodegaji();
		var getV_periodegaji_form= this.getV_periodegaji_form(),
			form			= getV_periodegaji_form.getForm();
			
		form.reset();
		getV_periodegaji_form.setDisabled(true);
		getListperiodegaji.setDisabled(false);
		getPERIODEGAJI.setActiveTab(getListperiodegaji);
	}
	
});