Ext.define('YMPI.controller.INSDISIPLIN',{
	extend: 'Ext.app.Controller',
	views: ['MASTER.v_insdisiplin'],
	models: ['m_insdisiplin'],
	stores: ['s_insdisiplin'],
	
	requires: ['Ext.ModelManager'],
	
	refs: [{
		ref: 'Listinsdisiplin',
		selector: 'Listinsdisiplin'
	}],


	init: function(){
		this.control({
			'Listinsdisiplin': {
				'afterrender': this.insdisiplinAfterRender,
				'selectionchange': this.enableDelete
			},
			'Listinsdisiplin button[action=create]': {
				click: this.createRecord
			},
			'Listinsdisiplin button[action=delete]': {
				click: this.deleteRecord
			},
			'Listinsdisiplin button[action=xexcel]': {
				click: this.export2Excel
			},
			'Listinsdisiplin button[action=xpdf]': {
				click: this.export2PDF
			},
			'Listinsdisiplin button[action=print]': {
				click: this.printRecords
			}
		});
	},
	
	insdisiplinAfterRender: function(){
		var insdisiplinStore = this.getListinsdisiplin().getStore();
		insdisiplinStore.load();
	},
	
	createRecord: function(){
		var model		= Ext.ModelMgr.getModel('YMPI.model.m_insdisiplin');
		var r = Ext.ModelManager.create({
			VALIDFROM	: '',
			VALIDTO		: '',
			NOURUT		: '',
			BULANMULAI	: '',
			BULANSAMPAI	: '',
			GRADE		: '',
			KODEJAB		: '',
			FABSEN		: '',
			RPIDISIPLIN	: '',
			USERNAME	: username
		}, model);
		this.getListinsdisiplin().getStore().insert(0, r);
		this.getListinsdisiplin().rowEditing.startEdit(0,0);
	},
	
	enableDelete: function(dataview, selections){
		this.getListinsdisiplin().down('#btndelete').setDisabled(!selections.length);
	},
	
	deleteRecord: function(dataview, selections){
		var getstore = this.getListinsdisiplin().getStore();
		var selection = this.getListinsdisiplin().getSelectionModel().getSelection()[0];
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
		var getstore = this.getListinsdisiplin().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_insdisiplin/export2Excel',
			params: {data: jsonData},
			success: function(response){
				window.location = ('./temp/'+response.responseText);
			}
		});
	},
	
	export2PDF: function(){
		var getstore = this.getListinsdisiplin().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_insdisiplin/export2PDF',
			params: {data: jsonData},
			success: function(response){
				window.open('./temp/insdisiplin.pdf', '_blank');
			}
		});
	},
	
	printRecords: function(){
		var getstore = this.getListinsdisiplin().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_insdisiplin/printRecords',
			params: {data: jsonData},
			success: function(response){
				var result=eval(response.responseText);
				switch(result){
				case 1:
					win = window.open('./temp/insdisiplin.html','insdisiplin_list','height=400,width=900,resizable=1,scrollbars=1, menubar=1');
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