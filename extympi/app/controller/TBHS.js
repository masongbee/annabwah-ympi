Ext.define('YMPI.controller.TBHS',{
	extend: 'Ext.app.Controller',
	views: ['MASTER.v_tbhs'],
	models: ['m_tbhs'],
	stores: ['s_tbhs'],
	
	requires: ['Ext.ModelManager'],
	
	refs: [{
		ref: 'Listtbhs',
		selector: 'Listtbhs'
	}],


	init: function(){
		this.control({
			'Listtbhs': {
				'afterrender': this.tbhsAfterRender,
				'selectionchange': this.enableDelete
			},
			'Listtbhs button[action=create]': {
				click: this.createRecord
			},
			'Listtbhs button[action=delete]': {
				click: this.deleteRecord
			},
			'Listtbhs button[action=xexcel]': {
				click: this.export2Excel
			},
			'Listtbhs button[action=xpdf]': {
				click: this.export2PDF
			},
			'Listtbhs button[action=print]': {
				click: this.printRecords
			}
		});
	},
	
	tbhsAfterRender: function(){
		var tbhsStore = this.getListtbhs().getStore();
		tbhsStore.load();
	},
	
	createRecord: function(){
		var model		= Ext.ModelMgr.getModel('YMPI.model.m_tbhs');
		var r = Ext.ModelManager.create({
			VALIDFROM	: '',
			NOURUT		: '',
			GRADE		: '',
			KODEJAB		: '',
			RPTBHS		: '',
			USERNAME	: username
		}, model);
		this.getListtbhs().getStore().insert(0, r);
		this.getListtbhs().rowEditing.startEdit(0,0);
	},
	
	enableDelete: function(dataview, selections){
		this.getListtbhs().down('#btndelete').setDisabled(!selections.length);
	},
	
	deleteRecord: function(dataview, selections){
		var getstore = this.getListtbhs().getStore();
		var selection = this.getListtbhs().getSelectionModel().getSelection()[0];
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
		var getstore = this.getListtbhs().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_tbhs/export2Excel',
			params: {data: jsonData},
			success: function(response){
				window.location = ('./temp/'+response.responseText);
			}
		});
	},
	
	export2PDF: function(){
		var getstore = this.getListtbhs().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_tbhs/export2PDF',
			params: {data: jsonData},
			success: function(response){
				window.open('./temp/tbhs.pdf', '_blank');
			}
		});
	},
	
	printRecords: function(){
		var getstore = this.getListtbhs().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_tbhs/printRecords',
			params: {data: jsonData},
			success: function(response){
				var result=eval(response.responseText);
				switch(result){
				case 1:
					win = window.open('./temp/tbhs.html','tbhs_list','height=400,width=900,resizable=1,scrollbars=1, menubar=1');
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