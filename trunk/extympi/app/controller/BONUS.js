Ext.define('YMPI.controller.BONUS',{
	extend: 'Ext.app.Controller',
	views: ['TRANSAKSI.v_bonus'],
	models: ['m_bonus'],
	stores: ['s_bonus'],
	
	requires: ['Ext.ModelManager'],
	
	refs: [{
		ref: 'Listbonus',
		selector: 'Listbonus'
	}],


	init: function(){
		this.control({
			'Listbonus': {
				'afterrender': this.bonusAfterRender,
				'selectionchange': this.enableDelete
			},
			'Listbonus button[action=create]': {
				click: this.createRecord
			},
			'Listbonus button[action=delete]': {
				click: this.deleteRecord
			},
			'Listbonus button[action=xexcel]': {
				click: this.export2Excel
			},
			'Listbonus button[action=xpdf]': {
				click: this.export2PDF
			},
			'Listbonus button[action=print]': {
				click: this.printRecords
			}
		});
	},
	
	bonusAfterRender: function(){
		var bonusStore = this.getListbonus().getStore();
		bonusStore.load();
	},
	
	createRecord: function(){
		var model		= Ext.ModelMgr.getModel('YMPI.model.m_bonus');
		var r = Ext.ModelManager.create({
		BULAN		: '',NOURUT		: '',PERIODE		: '',TGLMULAI		: '',TGLSAMPAI		: '',PERSENTASE		: '',GRADE		: '',KODEJAB		: '',NIK		: '',RPBONUS		: '',FPENGALI		: '',PENGALI		: '',UPENGALI		: '',USERNAME		: ''}, model);
		this.getListbonus().getStore().insert(0, r);
		this.getListbonus().rowEditing.startEdit(0,0);
	},
	
	enableDelete: function(dataview, selections){
		this.getListbonus().down('#btndelete').setDisabled(!selections.length);
	},
	
	deleteRecord: function(dataview, selections){
		var getstore = this.getListbonus().getStore();
		var selection = this.getListbonus().getSelectionModel().getSelection()[0];
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
		var getstore = this.getListbonus().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_bonus/export2Excel',
			params: {data: jsonData},
			success: function(response){
				window.location = ('./temp/'+response.responseText);
			}
		});
	},
	
	export2PDF: function(){
		var getstore = this.getListbonus().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_bonus/export2PDF',
			params: {data: jsonData},
			success: function(response){
				window.open('./temp/bonus.pdf', '_blank');
			}
		});
	},
	
	printRecords: function(){
		var getstore = this.getListbonus().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_bonus/printRecords',
			params: {data: jsonData},
			success: function(response){
				var result=eval(response.responseText);
				switch(result){
				case 1:
					win = window.open('./temp/bonus.html','bonus_list','height=400,width=900,resizable=1,scrollbars=1, menubar=1');
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