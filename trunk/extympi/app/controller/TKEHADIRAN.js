Ext.define('YMPI.controller.TKEHADIRAN',{
	extend: 'Ext.app.Controller',
	views: ['MASTER.v_tkehadiran'],
	models: ['m_tkehadiran'],
	stores: ['s_tkehadiran'],
	
	requires: ['Ext.ModelManager'],
	
	refs: [{
		ref: 'Listtkehadiran',
		selector: 'Listtkehadiran'
	}],


	init: function(){
		this.control({
			'Listtkehadiran': {
				'afterrender': this.tkehadiranAfterRender,
				'selectionchange': this.enableDelete
			},
			'Listtkehadiran button[action=create]': {
				click: this.createRecord
			},
			'Listtkehadiran button[action=delete]': {
				click: this.deleteRecord
			},
			'Listtkehadiran button[action=xexcel]': {
				click: this.export2Excel
			},
			'Listtkehadiran button[action=xpdf]': {
				click: this.export2PDF
			},
			'Listtkehadiran button[action=print]': {
				click: this.printRecords
			}
		});
	},
	
	tkehadiranAfterRender: function(){
		var tkehadiranStore = this.getListtkehadiran().getStore();
		tkehadiranStore.load();
	},
	
	createRecord: function(){
		var model		= Ext.ModelMgr.getModel('YMPI.model.m_tkehadiran');
		var r = Ext.ModelManager.create({
			BULAN		: '',
			NIK			: '',
			RPTHADIR	: '',
			KETERANGAN	: '',
			USERNAME	: username
		}, model);
		this.getListtkehadiran().getStore().insert(0, r);
		this.getListtkehadiran().rowEditing.startEdit(0,0);
	},
	
	enableDelete: function(dataview, selections){
		this.getListtkehadiran().down('#btndelete').setDisabled(!selections.length);
	},
	
	deleteRecord: function(dataview, selections){
		var getstore = this.getListtkehadiran().getStore();
		var selection = this.getListtkehadiran().getSelectionModel().getSelection()[0];
		if(selection){
			Ext.Msg.confirm('Confirmation', 'Are you sure to delete this data: NIK = "'+selection.data.NIK+'"?', function(btn){
				if (btn == 'yes'){
					getstore.remove(selection);
					getstore.sync();
				}
			});
			
		}
	},
	
	export2Excel: function(){
		var getstore = this.getListtkehadiran().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_tkehadiran/export2Excel',
			params: {data: jsonData},
			success: function(response){
				window.location = ('./temp/'+response.responseText);
			}
		});
	},
	
	export2PDF: function(){
		var getstore = this.getListtkehadiran().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_tkehadiran/export2PDF',
			params: {data: jsonData},
			success: function(response){
				window.open('./temp/tkehadiran.pdf', '_blank');
			}
		});
	},
	
	printRecords: function(){
		var getstore = this.getListtkehadiran().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_tkehadiran/printRecords',
			params: {data: jsonData},
			success: function(response){
				var result=eval(response.responseText);
				switch(result){
				case 1:
					win = window.open('./temp/tkehadiran.html','tkehadiran_list','height=400,width=900,resizable=1,scrollbars=1, menubar=1');
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