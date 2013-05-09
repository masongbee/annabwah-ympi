Ext.define('YMPI.controller.UPAHPOKOK',{
	extend: 'Ext.app.Controller',
	views: ['MASTER.v_upahpokok'],
	models: ['m_upahpokok'],
	stores: ['s_upahpokok'],
	
	requires: ['Ext.ModelManager'],
	
	refs: [{
		ref: 'Listupahpokok',
		selector: 'Listupahpokok'
	}],


	init: function(){
		this.control({
			'Listupahpokok': {
				'afterrender': this.upahpokokAfterRender,
				'selectionchange': this.enableDelete
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
			}
		});
	},
	
	upahpokokAfterRender: function(){
		var upahpokokStore = this.getListupahpokok().getStore();
		upahpokokStore.load();
	},
	
	createRecord: function(){
		var model		= Ext.ModelMgr.getModel('YMPI.model.m_upahpokok');
		var r = Ext.ModelManager.create({
			VALIDFROM	: '',
			NOURUT		: '',
			GRADE		: '',
			KODEJAB		: '',
			NIK		: '',
			RPUPAHPOKOK	: '',
			USERNAME	: username}, model);
		this.getListupahpokok().getStore().insert(0, r);
		this.getListupahpokok().rowEditing.startEdit(0,0);
	},
	
	enableDelete: function(dataview, selections){
		this.getListupahpokok().down('#btndelete').setDisabled(!selections.length);
	},
	
	deleteRecord: function(dataview, selections){
		var getstore = this.getListupahpokok().getStore();
		var selection = this.getListupahpokok().getSelectionModel().getSelection()[0];
		if(selection){
			Ext.Msg.confirm('Confirmation', 'Are you sure to delete this data: NOURUT = "'+selection.data.NOURUT+'"?', function(btn){
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
	}
	
});