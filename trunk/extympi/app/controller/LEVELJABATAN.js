Ext.define('YMPI.controller.LEVELJABATAN',{
	extend: 'Ext.app.Controller',
	views: ['MASTER.v_leveljabatan'],
	models: ['m_leveljabatan'],
	stores: ['s_leveljabatan'],
	
	requires: ['Ext.ModelManager'],
	
	refs: [{
		ref: 'Listleveljabatan',
		selector: 'Listleveljabatan'
	}],


	init: function(){
		this.control({
			'Listleveljabatan': {
				'afterrender': this.leveljabatanAfterRender,
				'selectionchange': this.enableDelete
			},
			'Listleveljabatan button[action=create]': {
				click: this.createRecord
			},
			'Listleveljabatan button[action=delete]': {
				click: this.deleteRecord
			},
			'Listleveljabatan button[action=xexcel]': {
				click: this.export2Excel
			},
			'Listleveljabatan button[action=xpdf]': {
				click: this.export2PDF
			},
			'Listleveljabatan button[action=print]': {
				click: this.printRecords
			}
		});
	},
	
	leveljabatanAfterRender: function(){
		var leveljabatanStore = this.getListleveljabatan().getStore();
		leveljabatanStore.load();
	},
	
	createRecord: function(){
		var model		= Ext.ModelMgr.getModel('YMPI.model.m_leveljabatan');
		var r = Ext.ModelManager.create({
		KODEJAB		: '',NAMALEVEL		: ''}, model);
		this.getListleveljabatan().getStore().insert(0, r);
		this.getListleveljabatan().rowEditing.startEdit(0,0);
	},
	
	enableDelete: function(dataview, selections){
		this.getListleveljabatan().down('#btndelete').setDisabled(!selections.length);
	},
	
	deleteRecord: function(dataview, selections){
		var getstore = this.getListleveljabatan().getStore();
		var selection = this.getListleveljabatan().getSelectionModel().getSelection()[0];
		if(selection){
			Ext.Msg.confirm('Confirmation', 'Are you sure to delete this data: KODEJAB = "'+selection.data.KODEJAB+'"?', function(btn){
				if (btn == 'yes'){
					getstore.remove(selection);
					getstore.sync();
				}
			});
			
		}
	},
	
	export2Excel: function(){
		var getstore = this.getListleveljabatan().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_leveljabatan/export2Excel',
			params: {data: jsonData},
			success: function(response){
				window.location = ('./temp/'+response.responseText);
			}
		});
	},
	
	export2PDF: function(){
		var getstore = this.getListleveljabatan().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_leveljabatan/export2PDF',
			params: {data: jsonData},
			success: function(response){
				window.open('./temp/leveljabatan.pdf', '_blank');
			}
		});
	},
	
	printRecords: function(){
		var getstore = this.getListleveljabatan().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_leveljabatan/printRecords',
			params: {data: jsonData},
			success: function(response){
				var result=eval(response.responseText);
				switch(result){
				case 1:
					win = window.open('./temp/leveljabatan.html','leveljabatan_list','height=400,width=900,resizable=1,scrollbars=1, menubar=1');
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