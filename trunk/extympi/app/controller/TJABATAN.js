Ext.define('YMPI.controller.TJABATAN',{
	extend: 'Ext.app.Controller',
	views: ['MASTER.v_tjabatan'],
	models: ['m_tjabatan'],
	stores: ['s_tjabatan'],
	
	requires: ['Ext.ModelManager'],
	
	refs: [{
		ref: 'Listtjabatan',
		selector: 'Listtjabatan'
	}],


	init: function(){
		this.control({
			'Listtjabatan': {
				'afterrender': this.tjabatanAfterRender,
				'selectionchange': this.enableDelete
			},
			'Listtjabatan button[action=create]': {
				click: this.createRecord
			},
			'Listtjabatan button[action=delete]': {
				click: this.deleteRecord
			},
			'Listtjabatan button[action=xexcel]': {
				click: this.export2Excel
			},
			'Listtjabatan button[action=xpdf]': {
				click: this.export2PDF
			},
			'Listtjabatan button[action=print]': {
				click: this.printRecords
			}
		});
	},
	
	tjabatanAfterRender: function(){
		var tjabatanStore = this.getListtjabatan().getStore();
		tjabatanStore.load();
	},
	
	createRecord: function(){
		var model		= Ext.ModelMgr.getModel('YMPI.model.m_tjabatan');
		var r = Ext.ModelManager.create({
		VALIDFROM		: '',NOURUT		: '',KODEJAB		: '',GRADE		: '',NIK		: '',RPTJABATAN		: '',USERNAME		: ''}, model);
		this.getListtjabatan().getStore().insert(0, r);
		this.getListtjabatan().rowEditing.startEdit(0,0);
	},
	
	enableDelete: function(dataview, selections){
		this.getListtjabatan().down('#btndelete').setDisabled(!selections.length);
	},
	
	deleteRecord: function(dataview, selections){
		var getstore = this.getListtjabatan().getStore();
		var selection = this.getListtjabatan().getSelectionModel().getSelection()[0];
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
		var getstore = this.getListtjabatan().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_tjabatan/export2Excel',
			params: {data: jsonData},
			success: function(response){
				window.location = ('./temp/'+response.responseText);
			}
		});
	},
	
	export2PDF: function(){
		var getstore = this.getListtjabatan().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_tjabatan/export2PDF',
			params: {data: jsonData},
			success: function(response){
				window.open('./temp/tjabatan.pdf', '_blank');
			}
		});
	},
	
	printRecords: function(){
		var getstore = this.getListtjabatan().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_tjabatan/printRecords',
			params: {data: jsonData},
			success: function(response){
				var result=eval(response.responseText);
				switch(result){
				case 1:
					win = window.open('./temp/tjabatan.html','tjabatan_list','height=400,width=900,resizable=1,scrollbars=1, menubar=1');
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