Ext.define('YMPI.controller.TMAKAN',{
	extend: 'Ext.app.Controller',
	views: ['MASTER.v_tmakan'],
	models: ['m_tmakan'],
	stores: ['s_tmakan'],
	
	requires: ['Ext.ModelManager'],
	
	refs: [{
		ref: 'Listtmakan',
		selector: 'Listtmakan'
	}],


	init: function(){
		this.control({
			'Listtmakan': {
				'afterrender': this.tmakanAfterRender,
				'selectionchange': this.enableDelete
			},
			'Listtmakan button[action=create]': {
				click: this.createRecord
			},
			'Listtmakan button[action=delete]': {
				click: this.deleteRecord
			},
			'Listtmakan button[action=xexcel]': {
				click: this.export2Excel
			},
			'Listtmakan button[action=xpdf]': {
				click: this.export2PDF
			},
			'Listtmakan button[action=print]': {
				click: this.printRecords
			}
		});
	},
	
	tmakanAfterRender: function(){
		var tmakanStore = this.getListtmakan().getStore();
		tmakanStore.load();
	},
	
	createRecord: function(){
		var model		= Ext.ModelMgr.getModel('YMPI.model.m_tmakan');
		var r = Ext.ModelManager.create({
			VALIDFROM	: '',
			VALIDTO		: '',
			NOURUT		: '',
			TGLMULAI	: '',
			TGLSAMPAI	: '',
			NIK			: '',
			GRADE		: '',
			KODEJAB		: '',
			FMAKAN		: '',
			RPTMAKAN	: '',
			USERNAME	: username
		}, model);
		this.getListtmakan().getStore().insert(0, r);
		this.getListtmakan().rowEditing.startEdit(0,0);
	},
	
	enableDelete: function(dataview, selections){
		this.getListtmakan().down('#btndelete').setDisabled(!selections.length);
	},
	
	deleteRecord: function(dataview, selections){
		var getstore = this.getListtmakan().getStore();
		var selection = this.getListtmakan().getSelectionModel().getSelection()[0];
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
		var getstore = this.getListtmakan().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_tmakan/export2Excel',
			params: {data: jsonData},
			success: function(response){
				window.location = ('./temp/'+response.responseText);
			}
		});
	},
	
	export2PDF: function(){
		var getstore = this.getListtmakan().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_tmakan/export2PDF',
			params: {data: jsonData},
			success: function(response){
				window.open('./temp/tmakan.pdf', '_blank');
			}
		});
	},
	
	printRecords: function(){
		var getstore = this.getListtmakan().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_tmakan/printRecords',
			params: {data: jsonData},
			success: function(response){
				var result=eval(response.responseText);
				switch(result){
				case 1:
					win = window.open('./temp/tmakan.html','tmakan_list','height=400,width=900,resizable=1,scrollbars=1, menubar=1');
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