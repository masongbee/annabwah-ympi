Ext.define('YMPI.controller.RINCIANCUTI',{
	extend: 'Ext.app.Controller',
	views: ['TRANSAKSI.v_rinciancuti'],
	models: ['m_rinciancuti'],
	stores: ['s_rinciancuti'],
	
	requires: ['Ext.ModelManager'],
	
	refs: [{
		ref: 'Listrinciancuti',
		selector: 'Listrinciancuti'
	},{
		ref: 'Listmohoncuti',
		selector: 'Listmohoncuti'
	}],


	init: function(){
		this.control({
			'Listrinciancuti': {
				'afterrender': this.rinciancutiAfterRender,
				'selectionchange': this.enableDelete
			},
			'Listrinciancuti button[action=create]': {
				click: this.createRecord
			},
			'Listrinciancuti button[action=delete]': {
				click: this.deleteRecord
			},
			'Listrinciancuti button[action=xexcel]': {
				click: this.export2Excel
			},
			'Listrinciancuti button[action=xpdf]': {
				click: this.export2PDF
			},
			'Listrinciancuti button[action=print]': {
				click: this.printRecords
			}
		});
	},
	
	rinciancutiAfterRender: function(){
		//var rinciancutiStore = this.getListrinciancuti().getStore();
		//rinciancutiStore.load();
	},
	
	createRecord: function(){
		var select_cuti = this.getListmohoncuti().getSelectionModel().getSelection()[0];
		var model		= Ext.ModelMgr.getModel('YMPI.model.m_rinciancuti');
		var r = Ext.ModelManager.create({
		NOCUTI		: select_cuti.data.NOCUTI,
		NOURUT		: '',
		NIK			: '',
		JENISABSEN	: '',
		LAMA		: '',
		TGLMULAI	: '',
		TGLSAMPAI	: '',
		SISACUTI	: ''}, model);
		this.getListrinciancuti().getStore().insert(0, r);
		this.getListrinciancuti().rowEditing.startEdit(0,0);
	},
	
	enableDelete: function(dataview, selections){
		this.getListrinciancuti().down('#btndelete').setDisabled(!selections.length);
	},
	
	deleteRecord: function(dataview, selections){
		var getstore = this.getListrinciancuti().getStore();
		var selection = this.getListrinciancuti().getSelectionModel().getSelection()[0];
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
		var getstore = this.getListrinciancuti().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_rinciancuti/export2Excel',
			params: {data: jsonData},
			success: function(response){
				window.location = ('./temp/'+response.responseText);
			}
		});
	},
	
	export2PDF: function(){
		var getstore = this.getListrinciancuti().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_rinciancuti/export2PDF',
			params: {data: jsonData},
			success: function(response){
				window.open('./temp/rinciancuti.pdf', '_blank');
			}
		});
	},
	
	printRecords: function(){
		var getstore = this.getListrinciancuti().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_rinciancuti/printRecords',
			params: {data: jsonData},
			success: function(response){
				var result=eval(response.responseText);
				switch(result){
				case 1:
					win = window.open('./temp/rinciancuti.html','rinciancuti_list','height=400,width=900,resizable=1,scrollbars=1, menubar=1');
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