Ext.define('YMPI.controller.TRMAKAN',{
	extend: 'Ext.app.Controller',
	views: ['TRANSAKSI.v_trmakan'],
	models: ['m_trmakan'],
	stores: ['s_trmakan'],
	
	requires: ['Ext.ModelManager'],
	
	refs: [{
		ref: 'Listtrmakan',
		selector: 'Listtrmakan'
	}],


	init: function(){
		this.control({
			'Listtrmakan': {
				'afterrender': this.trmakanAfterRender,
				'selectionchange': this.enableDelete
			},
			'Listtrmakan button[action=create]': {
				click: this.createRecord
			},
			'Listtrmakan button[action=delete]': {
				click: this.deleteRecord
			},
			'Listtrmakan button[action=genramadhan]': {
				click: this.genRamadhan
			},
			'Listtrmakan button[action=xexcel]': {
				click: this.export2Excel
			},
			'Listtrmakan button[action=xpdf]': {
				click: this.export2PDF
			},
			'Listtrmakan button[action=print]': {
				click: this.printRecords
			}
		});
	},
	
	trmakanAfterRender: function(){
		var trmakanStore = this.getListtrmakan().getStore();
		trmakanStore.load();
	},
	
	createRecord: function(){
		var model		= Ext.ModelMgr.getModel('YMPI.model.m_trmakan');
		var r = Ext.ModelManager.create({
			NIK			: '',
			TANGGAL		: '',
			FMAKAN		: '',
			RPTMAKAN	: '',
			RPPMAKAN	: '',
			KETERANGAN	: '',
			USERNAME	: username
		}, model);
		this.getListtrmakan().getStore().insert(0, r);
		this.getListtrmakan().rowEditing.startEdit(0,0);
	},
	
	enableDelete: function(dataview, selections){
		this.getListtrmakan().down('#btndelete').setDisabled(!selections.length);
	},
	
	deleteRecord: function(dataview, selections){
		var getstore = this.getListtrmakan().getStore();
		var selection = this.getListtrmakan().getSelectionModel().getSelection()[0];
		if(selection){
			Ext.Msg.confirm('Confirmation', 'Are you sure to delete this data: TANGGAL = "'+selection.data.TANGGAL+'"?', function(btn){
				if (btn == 'yes'){
					getstore.remove(selection);
					getstore.sync();
				}
			});
			
		}
	},
	
	genRamadhan: function(){
		var getListtrmakan = this.getListtrmakan(),
			getListtrmakanStore = getListtrmakan.getStore();
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_trmakan/gen_ramadhan',
			success: function(response){
				getListtrmakanStore.reload();
			}
		});
	},
	
	export2Excel: function(){
		var getstore = this.getListtrmakan().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_trmakan/export2Excel',
			params: {data: jsonData},
			success: function(response){
				window.location = ('./temp/'+response.responseText);
			}
		});
	},
	
	export2PDF: function(){
		var getstore = this.getListtrmakan().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_trmakan/export2PDF',
			params: {data: jsonData},
			success: function(response){
				window.open('./temp/trmakan.pdf', '_blank');
			}
		});
	},
	
	printRecords: function(){
		var getstore = this.getListtrmakan().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_trmakan/printRecords',
			params: {data: jsonData},
			success: function(response){
				var result=eval(response.responseText);
				switch(result){
				case 1:
					win = window.open('./temp/trmakan.html','trmakan_list','height=400,width=900,resizable=1,scrollbars=1, menubar=1');
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