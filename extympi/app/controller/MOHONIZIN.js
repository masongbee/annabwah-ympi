Ext.define('YMPI.controller.MOHONIZIN',{
	extend: 'Ext.app.Controller',
	views: ['TRANSAKSI.v_mohonizin'],
	models: ['m_mohonizin'],
	stores: ['s_mohonizin'],
	
	requires: ['Ext.ModelManager'],
	
	refs: [{
		ref: 'Listmohonizin',
		selector: 'Listmohonizin'
	}],


	init: function(){
		this.control({
			'Listmohonizin': {
				'afterrender': this.mohonizinAfterRender,
				'selectionchange': this.enableDelete
			},
			'Listmohonizin button[action=create]': {
				click: this.createRecord
			},
			'Listmohonizin button[action=delete]': {
				click: this.deleteRecord
			},
			'Listmohonizin button[action=xexcel]': {
				click: this.export2Excel
			},
			'Listmohonizin button[action=xpdf]': {
				click: this.export2PDF
			},
			'Listmohonizin button[action=print]': {
				click: this.printRecords
			}
		});
	},
	
	mohonizinAfterRender: function(){
		var mohonizinStore = this.getListmohonizin().getStore();
		mohonizinStore.load();
	},
	
	createRecord: function(){
		var model		= Ext.ModelMgr.getModel('YMPI.model.m_mohonizin');
		var r = Ext.ModelManager.create({
		NOIJIN		: '',NIK		: '',JENISABSEN		: '',TANGGAL		: '',JAMDARI		: '',JAMSAMPAI		: '',KEMBALI		: '',DIAGNOSA		: '',TINDAKAN		: '',ANJURAN		: '',PETUGASKLINIK		: '',NIKATASAN1		: '',NIKPERSONALIA		: '',NIKGA		: '',NIKDRIVER		: '',NIKSECURITY		: '',USERNAME		: ''}, model);
		this.getListmohonizin().getStore().insert(0, r);
		this.getListmohonizin().rowEditing.startEdit(0,0);
	},
	
	enableDelete: function(dataview, selections){
		this.getListmohonizin().down('#btndelete').setDisabled(!selections.length);
	},
	
	deleteRecord: function(dataview, selections){
		var getstore = this.getListmohonizin().getStore();
		var selection = this.getListmohonizin().getSelectionModel().getSelection()[0];
		if(selection){
			Ext.Msg.confirm('Confirmation', 'Are you sure to delete this data: NOIJIN = "'+selection.data.NOIJIN+'"?', function(btn){
				if (btn == 'yes'){
					getstore.remove(selection);
					getstore.sync();
				}
			});
			
		}
	},
	
	export2Excel: function(){
		var getstore = this.getListmohonizin().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_mohonizin/export2Excel',
			params: {data: jsonData},
			success: function(response){
				window.location = ('./temp/'+response.responseText);
			}
		});
	},
	
	export2PDF: function(){
		var getstore = this.getListmohonizin().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_mohonizin/export2PDF',
			params: {data: jsonData},
			success: function(response){
				window.open('./temp/mohonizin.pdf', '_blank');
			}
		});
	},
	
	printRecords: function(){
		var getstore = this.getListmohonizin().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_mohonizin/printRecords',
			params: {data: jsonData},
			success: function(response){
				var result=eval(response.responseText);
				switch(result){
				case 1:
					win = window.open('./temp/mohonizin.html','mohonizin_list','height=400,width=900,resizable=1,scrollbars=1, menubar=1');
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