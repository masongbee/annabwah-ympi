Ext.define('YMPI.controller.RIWAYATSEHAT',{
	extend: 'Ext.app.Controller',
	views: ['MUTASI.v_riwayatsehat'],
	models: ['m_riwayatsehat'],
	stores: ['s_riwayatsehat'],
	
	requires: ['Ext.ModelManager'],
	
	refs: [{
		ref: 'Listriwayatsehat',
		selector: 'Listriwayatsehat'
	}],


	init: function(){
		this.control({
			'Listriwayatsehat': {
				'afterrender': this.riwayatsehatAfterRender,
				'selectionchange': this.enableDelete
			},
			'Listriwayatsehat button[action=create]': {
				click: this.createRecord
			},
			'Listriwayatsehat button[action=delete]': {
				click: this.deleteRecord
			},
			'Listriwayatsehat button[action=xexcel]': {
				click: this.export2Excel
			},
			'Listriwayatsehat button[action=xpdf]': {
				click: this.export2PDF
			},
			'Listriwayatsehat button[action=print]': {
				click: this.printRecords
			}
		});
	},
	
	riwayatsehatAfterRender: function(){
		var riwayatsehatStore = this.getListriwayatsehat().getStore();
		riwayatsehatStore.load();
	},
	
	createRecord: function(){
		var model		= Ext.ModelMgr.getModel('YMPI.model.m_riwayatsehat');
		var r = Ext.ModelManager.create({
		NIK		: '',NOURUT		: '',JENISSAKIT		: '',RINCIAN		: '',LAMA		: '',TGLRAWAT		: '',AKIBAT		: ''}, model);
		this.getListriwayatsehat().getStore().insert(0, r);
		this.getListriwayatsehat().rowEditing.startEdit(0,0);
	},
	
	enableDelete: function(dataview, selections){
		this.getListriwayatsehat().down('#btndelete').setDisabled(!selections.length);
	},
	
	deleteRecord: function(dataview, selections){
		var getstore = this.getListriwayatsehat().getStore();
		var selection = this.getListriwayatsehat().getSelectionModel().getSelection()[0];
		if(selection){
			Ext.Msg.confirm('Confirmation', 'Are you sure to delete this data: JENISSAKIT = "'+selection.data.JENISSAKIT+'"?', function(btn){
				if (btn == 'yes'){
					getstore.remove(selection);
					getstore.sync();
				}
			});
			
		}
	},
	
	export2Excel: function(){
		var getstore = this.getListriwayatsehat().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_riwayatsehat/export2Excel',
			params: {data: jsonData},
			success: function(response){
				window.location = ('./temp/'+response.responseText);
			}
		});
	},
	
	export2PDF: function(){
		var getstore = this.getListriwayatsehat().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_riwayatsehat/export2PDF',
			params: {data: jsonData},
			success: function(response){
				window.open('./temp/riwayatsehat.pdf', '_blank');
			}
		});
	},
	
	printRecords: function(){
		var getstore = this.getListriwayatsehat().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_riwayatsehat/printRecords',
			params: {data: jsonData},
			success: function(response){
				var result=eval(response.responseText);
				switch(result){
				case 1:
					win = window.open('./temp/riwayatsehat.html','riwayatsehat_list','height=400,width=900,resizable=1,scrollbars=1, menubar=1');
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