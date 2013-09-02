Ext.define('YMPI.controller.JABATAN',{
	extend: 'Ext.app.Controller',
	views: ['MASTER.v_jabatan'],
	models: ['m_jabatan'],
	stores: ['s_jabatan'],
	
	requires: ['Ext.ModelManager'],
	
	refs: [{
		ref: 'Listjabatan',
		selector: 'Listjabatan'
	}],


	init: function(){
		this.control({
			'Listjabatan': {
				'afterrender': this.jabatanAfterRender,
				'selectionchange': this.enableDelete
			},
			'Listjabatan button[action=create]': {
				click: this.createRecord
			},
			'Listjabatan button[action=delete]': {
				click: this.deleteRecord
			},
			'Listjabatan button[action=xexcel]': {
				click: this.export2Excel
			},
			'Listjabatan button[action=xpdf]': {
				click: this.export2PDF
			},
			'Listjabatan button[action=print]': {
				click: this.printRecords
			}
		});
	},
	
	jabatanAfterRender: function(){
		var jabatanStore = this.getListjabatan().getStore();
		jabatanStore.load();
	},
	
	createRecord: function(){
		var model		= Ext.ModelMgr.getModel('YMPI.model.m_jabatan');
		var r = Ext.ModelManager.create({
			IDJAB			: '001',
			KODEJAB			: '',
			NAMAJAB			: '',
			HITUNGLEMBUR	: false,
			KOMPENCUTI		: true,
			KODEAKUN		: ''
		}, model);
		this.getListjabatan().getStore().insert(0, r);
		this.getListjabatan().rowEditing.startEdit(0,0);
	},
	
	enableDelete: function(dataview, selections){
		this.getListjabatan().down('#btndelete').setDisabled(!selections.length);
	},
	
	deleteRecord: function(dataview, selections){
		var getstore = this.getListjabatan().getStore();
		var selection = this.getListjabatan().getSelectionModel().getSelection()[0];
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
		var getstore = this.getListjabatan().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_jabatan/export2Excel',
			params: {data: jsonData},
			success: function(response){
				window.location = ('./temp/'+response.responseText);
			}
		});
	},
	
	export2PDF: function(){
		var getstore = this.getListjabatan().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_jabatan/export2PDF',
			params: {data: jsonData},
			success: function(response){
				window.open('./temp/jabatan.pdf', '_blank');
			}
		});
	},
	
	printRecords: function(){
		var getstore = this.getListjabatan().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_jabatan/printRecords',
			params: {data: jsonData},
			success: function(response){
				var result=eval(response.responseText);
				switch(result){
				case 1:
					win = window.open('./temp/jabatan.html','jabatan_list','height=400,width=900,resizable=1,scrollbars=1, menubar=1');
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