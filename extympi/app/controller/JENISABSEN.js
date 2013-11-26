Ext.define('YMPI.controller.JENISABSEN',{
	extend: 'Ext.app.Controller',
	views: ['MASTER.v_jenisabsen','MASTER.v_jenisabsen_form'],
	models: ['m_jenisabsen'],
	stores: ['s_jenisabsen'],
	
	requires: ['Ext.ModelManager'],
	
	refs: [{
		ref: 'Listjenisabsen',
		selector: 'Listjenisabsen'
	}, {
		ref: 'v_jenisabsen_form',
		selector: 'v_jenisabsen_form'
	}, {
		ref: 'SaveBtnForm',
		selector: 'v_jenisabsen_form #save'
	}, {
		ref: 'CreateBtnForm',
		selector: 'v_jenisabsen_form #create'
	}, {
		ref: 'JENISABSEN',
		selector: 'JENISABSEN'
	}],


	init: function(){
		this.control({
			'JENISABSEN': {
				'afterrender': this.jenisabsenAfterRender
			},
			'Listjenisabsen': {
				'selectionchange': this.enableDelete,
				'itemdblclick': this.updateListjenisabsen
			},
			'Listjenisabsen button[action=create]': {
				click: this.createRecord
			},
			'Listjenisabsen button[action=delete]': {
				click: this.deleteRecord
			},
			'Listjenisabsen button[action=xexcel]': {
				click: this.export2Excel
			},
			'Listjenisabsen button[action=xpdf]': {
				click: this.export2PDF
			},
			'Listjenisabsen button[action=print]': {
				click: this.printRecords
			},
			'v_jenisabsen_form button[action=save]': {
				click: this.saveV_jenisabsen_form
			},
			'v_jenisabsen_form button[action=create]': {
				click: this.createV_jenisabsen_form
			},
			'v_jenisabsen_form button[action=cancel]': {
				click: this.cancelV_jenisabsen_form
			}
		});
	},
	
	jenisabsenAfterRender: function(){
		var jenisabsenStore = this.getListjenisabsen().getStore();
		jenisabsenStore.load();
	},
	
	createRecord: function(){
		var getListjenisabsen	= this.getListjenisabsen();
		var getV_jenisabsen_form= this.getV_jenisabsen_form(),
			form			= getV_jenisabsen_form.getForm();
		var getSaveBtnForm	= this.getSaveBtnForm();
		var getCreateBtnForm	= this.getCreateBtnForm();
		/* grid-panel */
		getListjenisabsen.setDisabled(true);
        
		/* form-panel */
		form.reset();
		getV_jenisabsen_form.down('#JENISABSEN_field').setReadOnly(false);
		getSaveBtnForm.setDisabled(true);
		getCreateBtnForm.setDisabled(false);
		getV_jenisabsen_form.setDisabled(false);
		this.getJENISABSEN().setActiveTab(getV_jenisabsen_form);		
	},
	
	enableDelete: function(dataview, selections){
		this.getListjenisabsen().down('#btndelete').setDisabled(!selections.length);
	},
	
	updateListjenisabsen: function(me, record, item, index, e){
		var getJENISABSEN		= this.getJENISABSEN();
		var getListjenisabsen	= this.getListjenisabsen();
		var getV_jenisabsen_form= this.getV_jenisabsen_form(),
			form			= getV_jenisabsen_form.getForm();
		var getSaveBtnForm	= this.getSaveBtnForm();
		var getCreateBtnForm	= this.getCreateBtnForm();
		var store 			= this.getStore('s_jenisabsen');
		
		getSaveBtnForm.setDisabled(false);
		getCreateBtnForm.setDisabled(true);
		getV_jenisabsen_form.down('#JENISABSEN_field').setReadOnly(true);		
		getV_jenisabsen_form.loadRecord(record);
		
		getListjenisabsen.setDisabled(true);
		getV_jenisabsen_form.setDisabled(false);
		getJENISABSEN.setActiveTab(getV_jenisabsen_form);
		this.jenisabsenAfterRender();
	},
	
	deleteRecord: function(dataview, selections){
		var getstore = this.getListjenisabsen().getStore();
		var selection = this.getListjenisabsen().getSelectionModel().getSelection()[0];
		if(selection){
			Ext.Msg.confirm('Confirmation', 'Are you sure to delete this data: "JENISABSEN" = "'+selection.data.JENISABSEN+'"?', function(btn){
				if (btn == 'yes'){
					getstore.remove(selection);
					getstore.sync();
				}
			});
			
		}
	},
	
	export2Excel: function(){
		var getstore = this.getListjenisabsen().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_jenisabsen/export2Excel',
			params: {data: jsonData},
			success: function(response){
				window.location = ('./temp/'+response.responseText);
			}
		});
	},
	
	export2PDF: function(){
		var getstore = this.getListjenisabsen().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_jenisabsen/export2PDF',
			params: {data: jsonData},
			success: function(response){
				window.open('./temp/jenisabsen.pdf', '_blank');
			}
		});
	},
	
	printRecords: function(){
		var getstore = this.getListjenisabsen().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_jenisabsen/printRecords',
			params: {data: jsonData},
			success: function(response){
				var result=eval(response.responseText);
				switch(result){
				case 1:
					win = window.open('./temp/jenisabsen.html','jenisabsen_list','height=400,width=900,resizable=1,scrollbars=1, menubar=1');
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
	
	saveV_jenisabsen_form: function(){
		var getJENISABSEN		= this.getJENISABSEN();
		var getListjenisabsen 	= this.getListjenisabsen();
		var getV_jenisabsen_form= this.getV_jenisabsen_form(),
			form			= getV_jenisabsen_form.getForm(),
			values			= getV_jenisabsen_form.getValues();
		var jenisabsen_store = this.getStore('s_jenisabsen');
		
		var msg = function(title, msg) {
			Ext.Msg.show({
				title: title,
				msg: msg,
				minWidth: 200,
				modal: true,
				icon: Ext.Msg.INFO,
				buttons: Ext.Msg.OK
			});
		};
		
		if (form.isValid()) {
			var jsonData = Ext.encode(values);
			
			Ext.Ajax.request({
				method: 'POST',
				url: 'c_jenisabsen/update',
				params: {data: jsonData},
				success: function(response){
				var objS = Ext.JSON.decode(response.responseText);
					
					if(objS.success == 'TRUE')
					{
						console.info(response.responseText);
							store.reload({
								callback: function(){
									var newRecordIndex = store.findBy(
										function(record, id) {
											if (record.get('JENISABSEN') === values.JENISABSEN) {
												return true;
											}
											return false;
										}
									);
									/* getListjenisabsen.getView().select(recordIndex); */
									getListjenisabsen.getSelectionModel().select(newRecordIndex);
								}
							});
												
							jenisabsen_store.reload();
							getV_jenisabsen_form.setDisabled(true);
							getListjenisabsen.setDisabled(false);
							getJENISABSEN.setActiveTab(getListjenisabsen);
						//msg('Success', objS.message);	
					}
					else
					{
						console.info(response.responseText);
											
						jenisabsen_store.reload();
						getV_jenisabsen_form.setDisabled(true);
						getListjenisabsen.setDisabled(false);
						getJENISABSEN.setActiveTab(getListjenisabsen);
						//msg('Failed',objS.message);
					}
				},
				failure: function(response) {
					var objS = Ext.JSON.decode(response.responseText);
					console.info(response.responseText);
					msg('Failed',objS.message);
				}
			});
		}
	},
	
	createV_jenisabsen_form: function(){
		var getJENISABSEN		= this.getJENISABSEN();
		var getListjenisabsen 	= this.getListjenisabsen();
		var getV_jenisabsen_form= this.getV_jenisabsen_form(),
			form			= getV_jenisabsen_form.getForm(),
			values			= getV_jenisabsen_form.getValues();
		var store 			= this.getStore('s_jenisabsen');
		
		//var me = this;
		
		var msg = function(title, msg) {
			Ext.Msg.show({
				title: title,
				msg: msg,
				minWidth: 200,
				modal: true,
				icon: Ext.Msg.INFO,
				buttons: Ext.Msg.OK,
				fn:function(){
					store.reload();
				}
			});
		};
		
		if (form.isValid()) {
			var jsonData = Ext.encode(values);
			
			Ext.Ajax.request({
				method: 'POST',
				url: 'c_jenisabsen/save',
				params: {data: jsonData},
				success: function(response){
					var objS = Ext.JSON.decode(response.responseText);
					if(objS.success == 'TRUE')
					{
						console.info(response.responseText);
						store.reload();
						//me.jenisabsenAfterRender();
						getV_jenisabsen_form.setDisabled(true);
						getListjenisabsen.setDisabled(false);
						getJENISABSEN.setActiveTab(getListjenisabsen);
						msg('Success', objS.message);
						this.jenisabsenAfterRender();
					}
					else
					{
						console.info(response.responseText);
						
						getV_jenisabsen_form.setDisabled(true);
						getListjenisabsen.setDisabled(false);
						getJENISABSEN.setActiveTab(getListjenisabsen);
						msg('Failed',objS.message);
					}
				},
				failure: function(response) {
					var objS = Ext.JSON.decode(response.responseText);
					console.info(response.responseText);
					msg('Failed',objS.message);
				}
			});
		}
	},
	
	cancelV_jenisabsen_form: function(){
		var getJENISABSEN		= this.getJENISABSEN();
		var getListjenisabsen	= this.getListjenisabsen();
		var getV_jenisabsen_form= this.getV_jenisabsen_form(),
			form			= getV_jenisabsen_form.getForm();
			
		form.reset();
		getV_jenisabsen_form.setDisabled(true);
		getListjenisabsen.setDisabled(false);
		getJENISABSEN.setActiveTab(getListjenisabsen);
	}
	
});