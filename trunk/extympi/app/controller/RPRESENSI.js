Ext.define('YMPI.controller.RPRESENSI',{
	extend: 'Ext.app.Controller',
	views: ['LAPORAN.v_rpresensi'],
	models: ['m_rpresensi'],
	stores: ['s_rpresensi'],
	
	requires: ['Ext.ModelManager'],
	
	refs: [{
		ref: 'Listrpresensi',
		selector: 'Listrpresensi'
	}],


	init: function(){
		this.control({
			'Listrpresensi': {
				'afterrender': this.rpresensiAfterRender,
				'selectionchange': this.enableDelete
			},
			'Listrpresensi button[action=gen]': {
				click: this.gen_report
			},
			'Listrpresensi button[action=create]': {
				click: this.createRecord
			},
			'Listrpresensi button[action=delete]': {
				click: this.deleteRecord
			},
			'Listrpresensi button[action=xexcel]': {
				click: this.export2Excel
			},
			'Listrpresensi button[action=xpdf]': {
				click: this.export2PDF
			},
			'Listrpresensi button[action=print]': {
				click: this.printRecords
			}
		});
	},
	
	gen_report: function(){
		var rpresensiStore = this.getListrpresensi().getStore();
		
		var getListrpresensi = this.getListrpresensi();
		var tglmulai = getListrpresensi.down('#tglmulai').getValue();
		var tglsampai = getListrpresensi.down('#tglsampai').getValue();
		var kodekel = getListrpresensi.down('#kodekel').getValue();
		var kodeunit = getListrpresensi.down('#kodeunit').getValue();
		
		Ext.MessageBox.show({
			msg: 'Please wait...',
			width:300,
			wait:true,
			waitConfig: {interval:1000}
		});
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_rpresensi/gen_rpresensi',
			timeout: 6000000,
			params: {
				tglmulai: tglmulai,
				tglsampai: tglsampai,
				kdkel: kodekel,
				kdunit: kodeunit
			},
			success: function(response){
				rpresensiStore.load();
				Ext.MessageBox.hide();
			}
		});
	},
	
	rpresensiAfterRender: function(){
		var rpresensiStore = this.getListrpresensi().getStore();
		rpresensiStore.load();
	},
	
	createRecord: function(){
		var model		= Ext.ModelMgr.getModel('YMPI.model.m_rpresensi');
		var r = Ext.ModelManager.create({
		RPRESENSI_ID		: '',RPRESENSI_NIK		: '',RPRESENSI_NAMA		: '',RPRESENSI_BULAN		: '',d1		: '',d2		: '',d3		: '',d4		: '',d5		: '',d6		: '',d7		: '',d8		: '',d9		: '',d10		: '',d11		: '',d12		: '',d13		: '',d14		: '',d15		: '',d16		: '',d17		: '',d18		: '',d19		: '',d20		: '',d21		: '',d22		: '',d23		: '',d24		: '',d25		: '',d26		: '',d27		: '',d28		: '',d29		: '',d30		: '',d31		: ''}, model);
		this.getListrpresensi().getStore().insert(0, r);
		this.getListrpresensi().rowEditing.startEdit(0,0);
	},
	
	enableDelete: function(dataview, selections){
		this.getListrpresensi().down('#btndelete').setDisabled(!selections.length);
	},
	
	deleteRecord: function(dataview, selections){
		var getstore = this.getListrpresensi().getStore();
		var selection = this.getListrpresensi().getSelectionModel().getSelection()[0];
		if(selection){
			Ext.Msg.confirm('Confirmation', 'Are you sure to delete this data: RPRESENSI_ID = "'+selection.data.RPRESENSI_ID+'"?', function(btn){
				if (btn == 'yes'){
					getstore.remove(selection);
					getstore.sync();
				}
			});
			
		}
	},
	
	export2Excel: function(){
		var getstore = this.getListrpresensi().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_rpresensi/export2Excel',
			params: {data: jsonData},
			success: function(response){
				window.location = ('./temp/'+response.responseText);
			}
		});
	},
	
	export2PDF: function(){
		var getstore = this.getListrpresensi().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_rpresensi/export2PDF',
			params: {data: jsonData},
			success: function(response){
				window.open('./temp/rpresensi.pdf', '_blank');
			}
		});
	},
	
	printRecords: function(){
		var getstore = this.getListrpresensi().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_rpresensi/printRecords',
			params: {data: jsonData},
			success: function(response){
				var result=eval(response.responseText);
				switch(result){
				case 1:
					win = window.open('./temp/rpresensi.html','rpresensi_list','height=400,width=900,resizable=1,scrollbars=1, menubar=1');
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