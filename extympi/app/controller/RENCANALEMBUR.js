Ext.define('YMPI.controller.RENCANALEMBUR',{
	extend: 'Ext.app.Controller',
	views: ['TRANSAKSI.v_rencanalembur'],
	models: ['m_rencanalembur'],
	stores: ['s_rencanalembur'],
	
	requires: ['Ext.ModelManager'],
	
	refs: [{
		ref: 'Listrencanalembur',
		selector: 'Listrencanalembur'
	}, {
		ref: 'Listsplembur',
		selector: 'Listsplembur'
	}],


	init: function(){
		this.control({
			'Listrencanalembur': {
				'afterrender': this.rencanalemburAfterRender,
				'selectionchange': this.enableDelete
			},
			'Listrencanalembur button[action=create]': {
				click: this.createRecord
			},
			'Listrencanalembur button[action=delete]': {
				click: this.deleteRecord
			},
			'Listrencanalembur button[action=xexcel]': {
				click: this.export2Excel
			},
			'Listrencanalembur button[action=xpdf]': {
				click: this.export2PDF
			},
			'Listrencanalembur button[action=print]': {
				click: this.printRecords
			}
		});
	},
	
	rencanalemburAfterRender: function(){
		//var rencanalemburStore = this.getListrencanalembur().getStore();
		//rencanalemburStore.load();
	},
	
	createRecord: function(){
		var select_nik = this.getListsplembur().getSelectionModel().getSelection()[0];
		var model		= Ext.ModelMgr.getModel('YMPI.model.m_rencanalembur');
		var r = Ext.ModelManager.create({
		NOLEMBUR	: select_nik.data.NOLEMBUR,
		NOURUT		: '',
		NIK			: '',
		TJMASUK		: '',
		TJKELUAR	: '',
		ANTARJEMPUT	: 'T',
		MAKAN		: 'T',
		JENISLEMBUR	: ''}, model);
		this.getListrencanalembur().getStore().insert(0, r);
		this.getListrencanalembur().rowEditing.startEdit(0,0);
	},
	
	enableDelete: function(dataview, selections){
		this.getListrencanalembur().down('#btndelete').setDisabled(!selections.length);
	},
	
	deleteRecord: function(dataview, selections){
		var getstore = this.getListrencanalembur().getStore();
		var selection = this.getListrencanalembur().getSelectionModel().getSelection()[0];
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
		var getstore = this.getListrencanalembur().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_rencanalembur/export2Excel',
			params: {data: jsonData},
			success: function(response){
				window.location = ('./temp/'+response.responseText);
			}
		});
	},
	
	export2PDF: function(){
		var getstore = this.getListrencanalembur().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_rencanalembur/export2PDF',
			params: {data: jsonData},
			success: function(response){
				window.open('./temp/rencanalembur.pdf', '_blank');
			}
		});
	},
	
	printRecords: function(){
		var getstore = this.getListrencanalembur().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_rencanalembur/printRecords',
			params: {data: jsonData},
			success: function(response){
				var result=eval(response.responseText);
				switch(result){
				case 1:
					win = window.open('./temp/rencanalembur.html','rencanalembur_list','height=400,width=900,resizable=1,scrollbars=1, menubar=1');
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