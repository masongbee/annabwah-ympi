Ext.define('YMPI.controller.LAPNAMETAG',{
	extend: 'Ext.app.Controller',
	views: ['LAPORAN.v_lapnametag'],
	models: ['m_karyawan'],
	stores: ['s_karyawan'],
	
	requires: ['Ext.ModelManager'],
	
	refs: [{
		ref: 'Listlapnametag',
		selector: 'Listlapnametag'
	}],


	init: function(){
		this.control({
			'Listlapnametag': {
				'afterrender': this.lapnametagAfterRender
			},
			'Listlapnametag button[action=print]': {
				click: this.printRecords
			}
		});
	},
	
	lapnametagAfterRender: function(){
		var lapnametagStore = this.getListlapnametag().getStore();
		lapnametagStore.load();
	},
	
	printRecords: function(){
		var getstore = this.getListlapnametag().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_lapnametag/printRecords',
			params: {data: jsonData},
			success: function(response){
				var result=eval(response.responseText);
				switch(result){
				case 1:
					win = window.open('./temp/lapnametag.html','lapnametag_list','height=400,width=900,resizable=1,scrollbars=1, menubar=1');
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