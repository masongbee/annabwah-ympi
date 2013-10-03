Ext.define('YMPI.controller.NAMETAG',{
	extend: 'Ext.app.Controller',
	views: ['MASTER.v_nametag'],
	models: ['m_nametag'],
	stores: ['s_nametag'],
	
	requires: ['Ext.ModelManager'],
	
	refs: [{
		ref: 'Listnametag',
		selector: 'Listnametag'
	}],


	init: function(){
		this.control({
			'Listnametag': {
				'afterrender': this.nametagAfterRender,
				'selectionchange': this.enableDelete
			},
			'Listnametag button[action=create]': {
				click: this.createRecord
			},
			'Listnametag button[action=delete]': {
				click: this.deleteRecord
			},
			'Listnametag button[action=xexcel]': {
				click: this.export2Excel
			},
			'Listnametag button[action=xpdf]': {
				click: this.export2PDF
			},
			'Listnametag button[action=print]': {
				click: this.printRecords
			}
		});
	},
	
	nametagAfterRender: function(){
		var nametagStore = this.getListnametag().getStore();
		nametagStore.load();
	},
	
	createRecord: function(){
		var model		= Ext.ModelMgr.getModel('YMPI.model.m_nametag');
		var r = Ext.ModelManager.create({
			IDTAG		: '',
			KODEJAB		: '',
			NAMAJAB		: '',
			WARNATAGR	: '',
			WARNATAGG	: '',
			WARNATAGB	: ''
		}, model);
		this.getListnametag().getStore().insert(0, r);
		this.getListnametag().rowEditing.startEdit(0,0);
	},
	
	enableDelete: function(dataview, selections){
		this.getListnametag().down('#btndelete').setDisabled(!selections.length);
	},
	
	deleteRecord: function(dataview, selections){
		var getstore = this.getListnametag().getStore();
		var selection = this.getListnametag().getSelectionModel().getSelection()[0];
		if(selection){
			Ext.Msg.confirm('Confirmation', 'Are you sure to delete this data: KODEJAB = "'+selection.data.KODEJAB+'"?', function(btn){
				if (btn == 'yes'){
					getstore.remove(selection);
					getstore.sync();
				}
			});
			
		}
	},
	
	export2Excel: function(){
		var getstore = this.getListnametag().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_nametag/export2Excel',
			params: {data: jsonData},
			success: function(response){
				window.location = ('./temp/'+response.responseText);
			}
		});
	},
	
	export2PDF: function(){
		var getstore = this.getListnametag().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_nametag/export2PDF',
			params: {data: jsonData},
			success: function(response){
				window.open('./temp/nametag.pdf', '_blank');
			}
		});
	},
	
	printRecords: function(){
		var getstore = this.getListnametag().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_nametag/printRecords',
			params: {data: jsonData},
			success: function(response){
				var result=eval(response.responseText);
				switch(result){
				case 1:
					win = window.open('./temp/nametag.html','nametag_list','height=400,width=900,resizable=1,scrollbars=1, menubar=1');
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