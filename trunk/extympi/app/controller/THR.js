Ext.define('YMPI.controller.THR',{
	extend: 'Ext.app.Controller',
	views: ['TRANSAKSI.v_thr'],
	models: ['m_thr'],
	stores: ['s_thr'],
	
	requires: ['Ext.ModelManager'],
	
	refs: [{
		ref: 'Listthr',
		selector: 'Listthr'
	}],


	init: function(){
		this.control({
			'Listthr': {
				'afterrender': this.thrAfterRender,
				'selectionchange': this.enableDelete
			},
			'Listthr button[action=create]': {
				click: this.createRecord
			},
			'Listthr button[action=delete]': {
				click: this.deleteRecord
			},
			'Listthr button[action=xexcel]': {
				click: this.export2Excel
			},
			'Listthr button[action=xpdf]': {
				click: this.export2PDF
			},
			'Listthr button[action=print]': {
				click: this.printRecords
			}
		});
	},
	
	thrAfterRender: function(){
		var thrStore = this.getListthr().getStore();
		thrStore.load();
	},
	
	createRecord: function(){
		var model		= Ext.ModelMgr.getModel('YMPI.model.m_thr');
		var r = Ext.ModelManager.create({
			BULAN			: '',
			NOURUT			: '',
			TGLCUTOFF		: '',
			MSKERJADARI		: '',
			MSKERJASAMPAI	: '',
			NIK				: '',
			PEMBAGI			: '',
			PENGALI			: '',
			UPENGALI		: '',
			RPTHR			: '',
			USERNAME		: username
		}, model);
		this.getListthr().getStore().insert(0, r);
		this.getListthr().rowEditing.startEdit(0,0);
	},
	
	enableDelete: function(dataview, selections){
		this.getListthr().down('#btndelete').setDisabled(!selections.length);
	},
	
	deleteRecord: function(dataview, selections){
		var getstore = this.getListthr().getStore();
		var selection = this.getListthr().getSelectionModel().getSelection()[0];
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
		var getstore = this.getListthr().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_thr/export2Excel',
			params: {data: jsonData},
			success: function(response){
				window.location = ('./temp/'+response.responseText);
			}
		});
	},
	
	export2PDF: function(){
		var getstore = this.getListthr().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_thr/export2PDF',
			params: {data: jsonData},
			success: function(response){
				window.open('./temp/thr.pdf', '_blank');
			}
		});
	},
	
	printRecords: function(){
		var getstore = this.getListthr().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_thr/printRecords',
			params: {data: jsonData},
			success: function(response){
				var result=eval(response.responseText);
				switch(result){
				case 1:
					win = window.open('./temp/thr.html','thr_list','height=400,width=900,resizable=1,scrollbars=1, menubar=1');
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