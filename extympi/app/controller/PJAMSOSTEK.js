Ext.define('YMPI.controller.PJAMSOSTEK',{
	extend: 'Ext.app.Controller',
	views: ['MASTER.v_pjamsostek'],
	models: ['m_pjamsostek'],
	stores: ['s_pjamsostek'],
	
	requires: ['Ext.ModelManager'],
	
	refs: [{
		ref: 'Listpjamsostek',
		selector: 'Listpjamsostek'
	}],


	init: function(){
		this.control({
			'Listpjamsostek': {
				'afterrender': this.pjamsostekAfterRender,
				'selectionchange': this.enableDelete
			},
			'Listpjamsostek button[action=create]': {
				click: this.createRecord
			},
			'Listpjamsostek button[action=delete]': {
				click: this.deleteRecord
			},
			'Listpjamsostek button[action=xexcel]': {
				click: this.export2Excel
			},
			'Listpjamsostek button[action=xpdf]': {
				click: this.export2PDF
			},
			'Listpjamsostek button[action=print]': {
				click: this.printRecords
			}
		});
	},
	
	pjamsostekAfterRender: function(){
		var pjamsostekStore = this.getListpjamsostek().getStore();
		pjamsostekStore.load();
	},
	
	createRecord: function(){
		var model		= Ext.ModelMgr.getModel('YMPI.model.m_pjamsostek');
		var r = Ext.ModelManager.create({
			VALIDFROM	: '',
			NOURUT		: '',
			BULANMULAI	: '',
			BULANSAMPAI	: '',
			NIK			: '',
			GRADE		: '',
			KODEJAB		: '',
			PERSENTASE	: '',
			USERNAME	: username
		}, model);
		this.getListpjamsostek().getStore().insert(0, r);
		this.getListpjamsostek().rowEditing.startEdit(0,0);
	},
	
	enableDelete: function(dataview, selections){
		this.getListpjamsostek().down('#btndelete').setDisabled(!selections.length);
	},
	
	deleteRecord: function(dataview, selections){
		var getstore = this.getListpjamsostek().getStore();
		var selection = this.getListpjamsostek().getSelectionModel().getSelection()[0];
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
		var getstore = this.getListpjamsostek().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_pjamsostek/export2Excel',
			params: {data: jsonData},
			success: function(response){
				window.location = ('./temp/'+response.responseText);
			}
		});
	},
	
	export2PDF: function(){
		var getstore = this.getListpjamsostek().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_pjamsostek/export2PDF',
			params: {data: jsonData},
			success: function(response){
				window.open('./temp/pjamsostek.pdf', '_blank');
			}
		});
	},
	
	printRecords: function(){
		var getstore = this.getListpjamsostek().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_pjamsostek/printRecords',
			params: {data: jsonData},
			success: function(response){
				var result=eval(response.responseText);
				switch(result){
				case 1:
					win = window.open('./temp/pjamsostek.html','pjamsostek_list','height=400,width=900,resizable=1,scrollbars=1, menubar=1');
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