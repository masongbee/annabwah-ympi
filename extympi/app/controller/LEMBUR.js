Ext.define('YMPI.controller.LEMBUR',{
	extend: 'Ext.app.Controller',
	views: ['MASTER.v_lembur'],
	models: ['m_lembur'],
	stores: ['s_lembur'],
	
	requires: ['Ext.ModelManager'],
	
	refs: [{
		ref: 'Listlembur',
		selector: 'Listlembur'
	}],


	init: function(){
		this.control({
			'Listlembur': {
				'afterrender': this.lemburAfterRender,
				'selectionchange': this.enableDelete
			},
			'Listlembur button[action=create]': {
				click: this.createRecord
			},
			'Listlembur button[action=delete]': {
				click: this.deleteRecord
			},
			'Listlembur button[action=xexcel]': {
				click: this.export2Excel
			},
			'Listlembur button[action=xpdf]': {
				click: this.export2PDF
			},
			'Listlembur button[action=print]': {
				click: this.printRecords
			}
		});
	},
	
	lemburAfterRender: function(){
		var lemburStore = this.getListlembur().getStore();
		lemburStore.load();
	},
	
	createRecord: function(){
		var model		= Ext.ModelMgr.getModel('YMPI.model.m_lembur');
		var r = Ext.ModelManager.create({
		VALIDFROM		: '',NOURUT		: '',JAMDARI		: '',JAMSAMPAI		: '',JENISLEMBUR		: '',PENGALI		: '',UPENGALI		: '',USERNAME		: ''}, model);
		this.getListlembur().getStore().insert(0, r);
		this.getListlembur().rowEditing.startEdit(0,0);
	},
	
	enableDelete: function(dataview, selections){
		this.getListlembur().down('#btndelete').setDisabled(!selections.length);
	},
	
	deleteRecord: function(dataview, selections){
		var getstore = this.getListlembur().getStore();
		var selection = this.getListlembur().getSelectionModel().getSelection()[0];
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
		var getstore = this.getListlembur().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_lembur/export2Excel',
			params: {data: jsonData},
			success: function(response){
				window.location = ('./temp/'+response.responseText);
			}
		});
	},
	
	export2PDF: function(){
		var getstore = this.getListlembur().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_lembur/export2PDF',
			params: {data: jsonData},
			success: function(response){
				window.open('./temp/lembur.pdf', '_blank');
			}
		});
	},
	
	printRecords: function(){
		var getstore = this.getListlembur().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_lembur/printRecords',
			params: {data: jsonData},
			success: function(response){
				var result=eval(response.responseText);
				switch(result){
				case 1:
					win = window.open('./temp/lembur.html','lembur_list','height=400,width=900,resizable=1,scrollbars=1, menubar=1');
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