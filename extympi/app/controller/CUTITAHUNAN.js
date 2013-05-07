Ext.define('YMPI.controller.CUTITAHUNAN',{
	extend: 'Ext.app.Controller',
	views: ['MASTER.v_cutitahunan'],
	models: ['m_cutitahunan'],
	stores: ['s_cutitahunan'],
	
	requires: ['Ext.ModelManager'],
	
	refs: [{
		ref: 'Listcutitahunan',
		selector: 'Listcutitahunan'
	}],


	init: function(){
		this.control({
			'Listcutitahunan': {
				'afterrender': this.cutitahunanAfterRender,
				'selectionchange': this.enableDelete
			},
			'Listcutitahunan button[action=create]': {
				click: this.createRecord
			},
			'Listcutitahunan button[action=delete]': {
				click: this.deleteRecord
			},
			'Listcutitahunan button[action=xexcel]': {
				click: this.export2Excel
			},
			'Listcutitahunan button[action=xpdf]': {
				click: this.export2PDF
			},
			'Listcutitahunan button[action=print]': {
				click: this.printRecords
			}
		});
	},
	
	cutitahunanAfterRender: function(){
		var cutitahunanStore = this.getListcutitahunan().getStore();
		cutitahunanStore.load();
	},
	
	createRecord: function(){
		var model		= Ext.ModelMgr.getModel('YMPI.model.m_cutitahunan');
		var r = Ext.ModelManager.create({
		NIK		: '',TAHUN		: '',TANGGAL		: '',JENISCUTI		: '',JMLCUTI		: '',SISACUTI		: '',DIKOMPENSASI		: '',USERNAME		: ''}, model);
		this.getListcutitahunan().getStore().insert(0, r);
		this.getListcutitahunan().rowEditing.startEdit(0,0);
	},
	
	enableDelete: function(dataview, selections){
		this.getListcutitahunan().down('#btndelete').setDisabled(!selections.length);
	},
	
	deleteRecord: function(dataview, selections){
		var getstore = this.getListcutitahunan().getStore();
		var selection = this.getListcutitahunan().getSelectionModel().getSelection()[0];
		if(selection){
			Ext.Msg.confirm('Confirmation', 'Are you sure to delete this data: TANGGAL = "'+selection.data.TANGGAL+'"?', function(btn){
				if (btn == 'yes'){
					getstore.remove(selection);
					getstore.sync();
				}
			});
			
		}
	},
	
	export2Excel: function(){
		var getstore = this.getListcutitahunan().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_cutitahunan/export2Excel',
			params: {data: jsonData},
			success: function(response){
				window.location = ('./temp/'+response.responseText);
			}
		});
	},
	
	export2PDF: function(){
		var getstore = this.getListcutitahunan().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_cutitahunan/export2PDF',
			params: {data: jsonData},
			success: function(response){
				window.open('./temp/cutitahunan.pdf', '_blank');
			}
		});
	},
	
	printRecords: function(){
		var getstore = this.getListcutitahunan().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_cutitahunan/printRecords',
			params: {data: jsonData},
			success: function(response){
				var result=eval(response.responseText);
				switch(result){
				case 1:
					win = window.open('./temp/cutitahunan.html','cutitahunan_list','height=400,width=900,resizable=1,scrollbars=1, menubar=1');
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