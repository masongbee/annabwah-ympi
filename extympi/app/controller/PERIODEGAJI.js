Ext.define('YMPI.controller.PERIODEGAJI',{
	extend: 'Ext.app.Controller',
	views: ['MASTER.v_periodegaji'],
	models: ['m_periodegaji'],
	stores: ['s_periodegaji'],
	
	requires: ['Ext.ModelManager'],
	
	refs: [{
		ref: 'Listperiodegaji',
		selector: 'Listperiodegaji'
	}],


	init: function(){
		this.control({
			'Listperiodegaji': {
				'afterrender': this.periodegajiAfterRender,
				'selectionchange': this.enableDelete
			},
			'Listperiodegaji button[action=create]': {
				click: this.createRecord
			},
			'Listperiodegaji button[action=delete]': {
				click: this.deleteRecord
			},
			'Listperiodegaji button[action=xexcel]': {
				click: this.export2Excel
			},
			'Listperiodegaji button[action=xpdf]': {
				click: this.export2PDF
			},
			'Listperiodegaji button[action=print]': {
				click: this.printRecords
			}
		});
	},
	
	periodegajiAfterRender: function(){
		var periodegajiStore = this.getListperiodegaji().getStore();
		periodegajiStore.load();
	},
	
	createRecord: function(){
		var model		= Ext.ModelMgr.getModel('YMPI.model.m_periodegaji');
		var r = Ext.ModelManager.create({
			BULAN		: '',
			TGLMULAI	: '',
			TGLSAMPAI	: '',
			POSTING		: '',
			TGLPOSTING	: '',
			USERNAME	: username
		}, model);
		this.getListperiodegaji().getStore().insert(0, r);
		this.getListperiodegaji().rowEditing.startEdit(0,0);
	},
	
	enableDelete: function(dataview, selections){
		this.getListperiodegaji().down('#btndelete').setDisabled(!selections.length);
	},
	
	deleteRecord: function(dataview, selections){
		var getstore = this.getListperiodegaji().getStore();
		var selection = this.getListperiodegaji().getSelectionModel().getSelection()[0];
		if(selection){
			Ext.Msg.confirm('Confirmation', 'Are you sure to delete this data: BULAN = "'+selection.data.BULAN+'"?', function(btn){
				if (btn == 'yes'){
					getstore.remove(selection);
					getstore.sync();
				}
			});
			
		}
	},
	
	export2Excel: function(){
		var getstore = this.getListperiodegaji().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_periodegaji/export2Excel',
			params: {data: jsonData},
			success: function(response){
				window.location = ('./temp/'+response.responseText);
			}
		});
	},
	
	export2PDF: function(){
		var getstore = this.getListperiodegaji().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_periodegaji/export2PDF',
			params: {data: jsonData},
			success: function(response){
				window.open('./temp/periodegaji.pdf', '_blank');
			}
		});
	},
	
	printRecords: function(){
		var getstore = this.getListperiodegaji().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_periodegaji/printRecords',
			params: {data: jsonData},
			success: function(response){
				var result=eval(response.responseText);
				switch(result){
				case 1:
					win = window.open('./temp/periodegaji.html','periodegaji_list','height=400,width=900,resizable=1,scrollbars=1, menubar=1');
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