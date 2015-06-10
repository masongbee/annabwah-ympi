Ext.define('YMPI.controller.TD_KELOMPOK',{
	extend: 'Ext.app.Controller',
	views: ['MASTER.v_td_kelompok'],
	models: ['m_td_kelompok'],
	stores: ['s_td_kelompok'],
	
	requires: ['Ext.ModelManager'],
	
	refs: [{
		ref: 'Listtd_kelompok',
		selector: 'Listtd_kelompok'
	}],


	init: function(){
		this.control({
			'Listtd_kelompok': {
				'afterrender': this.td_kelompokAfterRender,
				'selectionchange': this.enableDelete
			},
			'Listtd_kelompok button[action=create]': {
				click: this.createRecord
			},
			'Listtd_kelompok button[action=delete]': {
				click: this.deleteRecord
			},
			'Listtd_kelompok button[action=xexcel]': {
				click: this.export2Excel
			},
			'Listtd_kelompok button[action=xpdf]': {
				click: this.export2PDF
			},
			'Listtd_kelompok button[action=print]': {
				click: this.printRecords
			}
		});
	},
	
	td_kelompokAfterRender: function(){
		var td_kelompokStore = this.getListtd_kelompok().getStore();
		td_kelompokStore.load();
	},
	
	createRecord: function(){
		var model		= Ext.ModelMgr.getModel('YMPI.model.m_td_kelompok');
		var r = Ext.ModelManager.create({
		TDKELOMPOK_ID		: '',TDKELOMPOK_KODE		: '',TDKELOMPOK_NAMA		: '',TDKELOMPOK_KETERANGAN		: '',TDKELOMPOK_CREATED_BY		: '',TDKELOMPOK_CREATED_DATE		: '',TDKELOMPOK_UPDATED_BY		: '',TDKELOMPOK_UPDATED_DATE		: '',TDKELOMPOK_REVISED		: ''}, model);
		this.getListtd_kelompok().getStore().insert(0, r);
		this.getListtd_kelompok().rowEditing.startEdit(0,0);
	},
	
	enableDelete: function(dataview, selections){
		this.getListtd_kelompok().down('#btndelete').setDisabled(!selections.length);
	},
	
	deleteRecord: function(dataview, selections){
		var getstore = this.getListtd_kelompok().getStore();
		var selection = this.getListtd_kelompok().getSelectionModel().getSelection()[0];
		if(selection){
			Ext.Msg.confirm('Confirmation', 'Are you sure to delete this data: TDKELOMPOK_ID = "'+selection.data.TDKELOMPOK_ID+'"?', function(btn){
				if (btn == 'yes'){
					getstore.remove(selection);
					getstore.sync();
				}
			});
			
		}
	},
	
	export2Excel: function(){
		var getstore = this.getListtd_kelompok().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_td_kelompok/export2Excel',
			params: {data: jsonData},
			success: function(response){
				window.location = ('./temp/'+response.responseText);
			}
		});
	},
	
	export2PDF: function(){
		var getstore = this.getListtd_kelompok().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_td_kelompok/export2PDF',
			params: {data: jsonData},
			success: function(response){
				window.open('./temp/td_kelompok.pdf', '_blank');
			}
		});
	},
	
	printRecords: function(){
		var getstore = this.getListtd_kelompok().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_td_kelompok/printRecords',
			params: {data: jsonData},
			success: function(response){
				var result=eval(response.responseText);
				switch(result){
				case 1:
					win = window.open('./temp/td_kelompok.html','td_kelompok_list','height=400,width=900,resizable=1,scrollbars=1, menubar=1');
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