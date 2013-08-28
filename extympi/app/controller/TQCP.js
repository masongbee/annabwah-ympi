Ext.define('YMPI.controller.TQCP',{
	extend: 'Ext.app.Controller',
	views: ['MASTER.v_tqcp'],
	models: ['m_tqcp'],
	stores: ['s_tqcp'],
	
	requires: ['Ext.ModelManager'],
	
	refs: [{
		ref: 'Listtqcp',
		selector: 'Listtqcp'
	}],


	init: function(){
		this.control({
			'Listtqcp': {
				'afterrender': this.tqcpAfterRender,
				'selectionchange': this.enableDelete
			},
			'Listtqcp button[action=create]': {
				click: this.createRecord
			},
			'Listtqcp button[action=delete]': {
				click: this.deleteRecord
			},
			'Listtqcp button[action=xexcel]': {
				click: this.export2Excel
			},
			'Listtqcp button[action=xpdf]': {
				click: this.export2PDF
			},
			'Listtqcp button[action=print]': {
				click: this.printRecords
			}
		});
	},
	
	tqcpAfterRender: function(){
		var tqcpStore = this.getListtqcp().getStore();
		tqcpStore.load();
	},
	
	createRecord: function(){
		var model		= Ext.ModelMgr.getModel('YMPI.model.m_tqcp');
		var r = Ext.ModelManager.create({
			NIK			: '',
			TGLMULAI	: '',
			TGLSAMPAI	: '',
			RPQCP		: '',
			USERNAME	: username
		}, model);
		this.getListtqcp().getStore().insert(0, r);
		this.getListtqcp().rowEditing.startEdit(0,0);
	},
	
	enableDelete: function(dataview, selections){
		this.getListtqcp().down('#btndelete').setDisabled(!selections.length);
	},
	
	deleteRecord: function(dataview, selections){
		var getstore = this.getListtqcp().getStore();
		var selection = this.getListtqcp().getSelectionModel().getSelection()[0];
		if(selection){
			Ext.Msg.confirm('Confirmation', 'Are you sure to delete this data: TGLMULAI = "'+selection.data.TGLMULAI+'"?', function(btn){
				if (btn == 'yes'){
					getstore.remove(selection);
					getstore.sync();
				}
			});
			
		}
	},
	
	export2Excel: function(){
		var getstore = this.getListtqcp().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_tqcp/export2Excel',
			params: {data: jsonData},
			success: function(response){
				window.location = ('./temp/'+response.responseText);
			}
		});
	},
	
	export2PDF: function(){
		var getstore = this.getListtqcp().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_tqcp/export2PDF',
			params: {data: jsonData},
			success: function(response){
				window.open('./temp/tqcp.pdf', '_blank');
			}
		});
	},
	
	printRecords: function(){
		var getstore = this.getListtqcp().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_tqcp/printRecords',
			params: {data: jsonData},
			success: function(response){
				var result=eval(response.responseText);
				switch(result){
				case 1:
					win = window.open('./temp/tqcp.html','tqcp_list','height=400,width=900,resizable=1,scrollbars=1, menubar=1');
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