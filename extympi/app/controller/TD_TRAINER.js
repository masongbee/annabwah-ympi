Ext.define('YMPI.controller.TD_TRAINER',{
	extend: 'Ext.app.Controller',
	views: ['MASTER.v_td_trainer'],
	models: ['m_td_trainer'],
	stores: ['s_td_trainer'],
	
	requires: ['Ext.ModelManager'],
	
	refs: [{
		ref: 'Listtd_trainer',
		selector: 'Listtd_trainer'
	}],


	init: function(){
		this.control({
			'Listtd_trainer': {
				'afterrender': this.td_trainerAfterRender,
				'selectionchange': this.enableDelete
			},
			'Listtd_trainer button[action=create]': {
				click: this.createRecord
			},
			'Listtd_trainer button[action=delete]': {
				click: this.deleteRecord
			},
			'Listtd_trainer button[action=xexcel]': {
				click: this.export2Excel
			},
			'Listtd_trainer button[action=xpdf]': {
				click: this.export2PDF
			},
			'Listtd_trainer button[action=print]': {
				click: this.printRecords
			}
		});
	},
	
	td_trainerAfterRender: function(){
		var td_trainerStore = this.getListtd_trainer().getStore();
		td_trainerStore.load();
	},
	
	createRecord: function(){
		var model		= Ext.ModelMgr.getModel('YMPI.model.m_td_trainer');
		var r = Ext.ModelManager.create({
		TDTRAINER_ID		: '',TDTRAINER_KODE		: '',TDTRAINER_NAMA		: '',TDTRAINER_KETERANGAN		: '',TDTRAINER_CREATED_BY		: '',TDTRAINER_CREATED_DATE		: '',TDTRAINER_UPDATED_BY		: '',TDTRAINER_UPDATED_DATE		: '',TDTRAINER_REVISED		: ''}, model);
		this.getListtd_trainer().getStore().insert(0, r);
		this.getListtd_trainer().rowEditing.startEdit(0,0);
	},
	
	enableDelete: function(dataview, selections){
		this.getListtd_trainer().down('#btndelete').setDisabled(!selections.length);
	},
	
	deleteRecord: function(dataview, selections){
		var getstore = this.getListtd_trainer().getStore();
		var selection = this.getListtd_trainer().getSelectionModel().getSelection()[0];
		if(selection){
			Ext.Msg.confirm('Confirmation', 'Are you sure to delete this data: TDTRAINER_ID = "'+selection.data.TDTRAINER_ID+'"?', function(btn){
				if (btn == 'yes'){
					getstore.remove(selection);
					getstore.sync();
				}
			});
			
		}
	},
	
	export2Excel: function(){
		var getstore = this.getListtd_trainer().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_td_trainer/export2Excel',
			params: {data: jsonData},
			success: function(response){
				window.location = ('./temp/'+response.responseText);
			}
		});
	},
	
	export2PDF: function(){
		var getstore = this.getListtd_trainer().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_td_trainer/export2PDF',
			params: {data: jsonData},
			success: function(response){
				window.open('./temp/td_trainer.pdf', '_blank');
			}
		});
	},
	
	printRecords: function(){
		var getstore = this.getListtd_trainer().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_td_trainer/printRecords',
			params: {data: jsonData},
			success: function(response){
				var result=eval(response.responseText);
				switch(result){
				case 1:
					win = window.open('./temp/td_trainer.html','td_trainer_list','height=400,width=900,resizable=1,scrollbars=1, menubar=1');
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