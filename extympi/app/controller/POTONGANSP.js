Ext.define('YMPI.controller.POTONGANSP',{
	extend: 'Ext.app.Controller',
	views: ['TRANSAKSI.v_potongansp'],
	models: ['m_potongansp'],
	stores: ['s_potongansp'],
	
	requires: ['Ext.ModelManager'],
	
	refs: [{
		ref: 'Listpotongansp',
		selector: 'Listpotongansp'
	}],


	init: function(){
		this.control({
			'Listpotongansp': {
				'afterrender': this.potonganspAfterRender,
				'selectionchange': this.enableDelete
			},
			'Listpotongansp button[action=create]': {
				click: this.createRecord
			},
			'Listpotongansp button[action=delete]': {
				click: this.deleteRecord
			},
			'Listpotongansp button[action=xexcel]': {
				click: this.export2Excel
			},
			'Listpotongansp button[action=xpdf]': {
				click: this.export2PDF
			},
			'Listpotongansp button[action=print]': {
				click: this.printRecords
			}
		});
	},
	
	potonganspAfterRender: function(){
		var potonganspStore = this.getListpotongansp().getStore();
		potonganspStore.load();
	},
	
	createRecord: function(){
		var model		= Ext.ModelMgr.getModel('YMPI.model.m_potongansp');
		var r = Ext.ModelManager.create({
			VALIDFROM	: '',
			VALIDTO		: '',
			NOURUT		: '',
			BULANMULAI	: '',
			BULANSAMPAI	: '',
			KODESP		: '',
			RPPOTSP		: '',
			USERNAME	: username
		}, model);
		this.getListpotongansp().getStore().insert(0, r);
		this.getListpotongansp().rowEditing.startEdit(0,0);
	},
	
	enableDelete: function(dataview, selections){
		this.getListpotongansp().down('#btndelete').setDisabled(!selections.length);
	},
	
	deleteRecord: function(dataview, selections){
		var getstore = this.getListpotongansp().getStore();
		var selection = this.getListpotongansp().getSelectionModel().getSelection()[0];
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
		var getstore = this.getListpotongansp().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_potongansp/export2Excel',
			params: {data: jsonData},
			success: function(response){
				window.location = ('./temp/'+response.responseText);
			}
		});
	},
	
	export2PDF: function(){
		var getstore = this.getListpotongansp().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_potongansp/export2PDF',
			params: {data: jsonData},
			success: function(response){
				window.open('./temp/potongansp.pdf', '_blank');
			}
		});
	},
	
	printRecords: function(){
		var getstore = this.getListpotongansp().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_potongansp/printRecords',
			params: {data: jsonData},
			success: function(response){
				var result=eval(response.responseText);
				switch(result){
				case 1:
					win = window.open('./temp/potongansp.html','potongansp_list','height=400,width=900,resizable=1,scrollbars=1, menubar=1');
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