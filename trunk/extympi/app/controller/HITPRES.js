Ext.define('YMPI.controller.HITPRES',{
	extend: 'Ext.app.Controller',
	views: ['PROSES.hitungpresensi','PROSES.periodegaji'],
	models: ['hitungpresensi','periodegaji'],
	stores: ['hitungpresensi','periodegaji'],
	
	//requires: ['YMPI.view.PROSES.hitungpresensi'],
	
	refs: [{
		ref: 'hitungpresensi',
		selector: 'hitungpresensi'
	},{
		ref: 'periodegaji',
		selector: 'periodegaji'
	}],
	
	init: function(){
		this.control({
			'hitungpresensi': {
				'afterrender': this.LoadStore
			},
			'periodegaji': {				
				'selectionchange': this.pilihsel
			},
			'periodegaji button[action=Hitung]': {
				click: this.hitungPresensi
			},
			'hitungpresensi button[action=create]': {
				click: this.createRecordGroup
			},
			'hitungpresensi button[action=delete]': {
				click: this.deleteRecordGroup
			}
		});
	},
	
	LoadStore : function() {
		console.info('Load Store');
		var getHitungpresensiStore = this.getHitungpresensi().getStore();
		getHitungpresensiStore.load();
		var getPeriodegajiStore = this.getPeriodegaji().getStore();
		getPeriodegajiStore.load();
	},
	
	hitungPresensi : function(dv,sel) {
		var sel = this.getPeriodegaji().getSelectionModel().getSelection()[0];
		console.info('HITUNG PRESENSI'+sel.data.BULAN);		
		this.LoadStore();
	},
	
	pilihsel: function(dv, sel){
		var sel = this.getPeriodegaji().getSelectionModel().getSelection()[0];
		console.info('HITUNG PRESENSI pada Bulan : '+sel.data.BULAN);
		var msg = function(title, msg) {
			Ext.Msg.show({
				title: title,
				msg: msg,
				minWidth: 200,
				modal: true,
				icon: Ext.Msg.INFO,
				buttons: Ext.Msg.OK
			});
		};
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_hitungpresensi/JamKerja/'+sel.data.BULAN,
			waitMsg: 'Hitung Presensi...',
			success: function(response){
				msg('Success', 'Data Added');
				//msg('Login Success', action.response.responseText);
			},
			failure: function(response) {
				msg('Failed','Data Fail');
				//msg('Login Failed', action.response.responseText);
			}
		});
	},
	
	createRecordGroup: function(){
		var model		= Ext.ModelMgr.getModel('YMPI.model.hitungpresensi');
		var grid 		= this.getHitungpresensi();
		var selections 	= grid.getSelectionModel().getSelection();
		var index 		= 0;
		var r = Ext.ModelManager.create({
			GROUP_ID	: 0,
		    GROUP_NAME	: '',
		    GROUP_DESC	: ''
		}, model);
		grid.getStore().insert(index, r);
		grid.rowEditing.startEdit(index,0);
	},
	
	deleteRecordGroup: function(dataview, selections){
		var getHitungpresensi = this.getHitungpresensi(),
			getHitungpresensiStore = getHitungpresensi.getStore();
		var selection = this.getHitungpresensi().getSelectionModel().getSelection()[0];
		if(selection){
			Ext.Msg.confirm('Confirmation', 'Are you sure to delete this data: Group = \"'+selection.data.GROUP_NAME+'\"?', function(btn){
			    if (btn == 'yes'){
			    	getHitungpresensi.down('#btndelete').setDisabled(true);
			    	
			    	getHitungpresensiStore.remove(selection);
			    	getHitungpresensiStore.sync();
			    }
			});
			
		}
	}
});