Ext.define('YMPI.controller.CUTITAHUNAN',{
	extend: 'Ext.app.Controller',
	views: ['MASTER.v_cutitahunan'],
	models: ['m_cutitahunan'],
	stores: ['s_cutitahunan'],
	
	requires: ['Ext.ModelManager'],
	
	refs: [{
		ref: 'Listcutitahunan',
		selector: 'Listcutitahunan'
	}],


	init: function(){
		this.control({
			'Listcutitahunan': {
				'afterrender': this.cutitahunanAfterRender,
				'selectionchange': this.enableHangusKompen
			},
			'Listcutitahunan button[action=create]': {
				click: this.createRecord
			},
			'Listcutitahunan button[action=xexcel]': {
				click: this.export2Excel
			},
			'Listcutitahunan button[action=xpdf]': {
				click: this.export2PDF
			},
			'Listcutitahunan button[action=print]': {
				click: this.printRecords
			},
			'Listcutitahunan button[action=generate]': {
				click: this.generate
			},
			'Listcutitahunan button[action=hangusall]': {
				click: this.hangusall
			},
			'Listcutitahunan button[action=kompensasiall]': {
				click: this.kompensasiall
			},
			'Listcutitahunan button[action=hangus]': {
				click: this.hangus
			},
			'Listcutitahunan button[action=kompensasi]': {
				click: this.kompensasi
			}
		});
	},
	
	cutitahunanAfterRender: function(){
		var cutitahunanStore = this.getListcutitahunan().getStore();
		cutitahunanStore.load();
	},
	
	enableHangusKompen: function(dataview, selections){
		this.getListcutitahunan().down('#btnhangus').setDisabled(!selections.length);
		this.getListcutitahunan().down('#btnkompensasi').setDisabled(!selections.length);
		
		/*var arrData = [];
		for (var i=0; i<selections.length; i++) {
			arrData.push(selections[i].data);
		}
		console.log(arrData);*/
		//var jsonData = Ext.encode(e.record.data);
	},
	
	createRecord: function(){
		var model		= Ext.ModelMgr.getModel('YMPI.model.m_cutitahunan');
		var r = Ext.ModelManager.create({
			NIK			: '',
			TAHUN		: '',
			TANGGAL		: '',
			JENISCUTI	: '',
			JMLCUTI		: '',
			SISACUTI	: '',
			DIKOMPENSASI: '',
			USERNAME	: username
		}, model);
		this.getListcutitahunan().getStore().insert(0, r);
		this.getListcutitahunan().rowEditing.startEdit(0,0);
	},
	
	export2Excel: function(){
		var getstore = this.getListcutitahunan().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_cutitahunan/export2Excel',
			params: {data: jsonData},
			success: function(response){
				window.location = ('./temp/'+response.responseText);
			}
		});
	},
	
	export2PDF: function(){
		var getstore = this.getListcutitahunan().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_cutitahunan/export2PDF',
			params: {data: jsonData},
			success: function(response){
				window.open('./temp/cutitahunan.pdf', '_blank');
			}
		});
	},
	
	printRecords: function(){
		var getstore = this.getListcutitahunan().getStore();
		var jsonData = Ext.encode(Ext.pluck(getstore.data.items, 'data'));
		
		Ext.Ajax.request({
			method: 'POST',
			url: 'c_cutitahunan/printRecords',
			params: {data: jsonData},
			success: function(response){
				var result=eval(response.responseText);
				switch(result){
				case 1:
					win = window.open('./temp/cutitahunan.html','cutitahunan_list','height=400,width=900,resizable=1,scrollbars=1, menubar=1');
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
	},
	
	generate: function(){
		var cutitahunanStore = this.getListcutitahunan().getStore();
		
		var monthNames = [ "January", "February", "March", "April", "May", "June",
			"July", "August", "September", "October", "November", "December" ];
		
		var now = new Date();
		var getyear = now.getFullYear();
		var getmonth = monthNames[now.getMonth()];
		var messagedate = getmonth+' '+getyear;
		Ext.MessageBox.show({
			title: 'Confirm',
			msg: 'Generate Cuti Tahunan Per '+messagedate+'?',
			width: 400,
			buttons: Ext.Msg.YESNO,
			fn: function(btn){
				if (btn == 'yes') {
					console.log('button yes');
					Ext.Ajax.request({
						method: 'POST',
						url: 'c_cutitahunan/generate',
						//params: {data: jsonData},
						success: function(response){
							var rs = Ext.JSON.decode(response.responseText);
							Ext.Msg.alert('OK', rs.message);
							cutitahunanStore.load();
						},
						failure: function(response){
							var rs = Ext.JSON.decode(response.responseText);
							Ext.Msg.alert('OK', 'Generate gagal.');
						}
					});
				}else{
					console.log('button no');
				}
			},
			closable:false,
			icon: Ext.Msg.QUESTION
		});
	},
	
	hangusall: function(){
		var cutitahunanStore = this.getListcutitahunan().getStore();
		
		Ext.MessageBox.show({
			title: 'Confirm',
			msg: 'Hanguskan Cuti Tahunan yang masih tersisa dan sudah 1 tahun terlewat?',
			width: 400,
			buttons: Ext.Msg.YESNO,
			fn: function(btn){
				if (btn == 'yes') {
					console.log('button yes');
					Ext.Ajax.request({
						method: 'POST',
						url: 'c_cutitahunan/hangusall',
						//params: {data: jsonData},
						success: function(response){
							var rs = Ext.JSON.decode(response.responseText);
							Ext.Msg.alert('OK', rs.message);
							cutitahunanStore.load();
						},
						failure: function(response){
							var rs = Ext.JSON.decode(response.responseText);
							Ext.Msg.alert('OK', 'Cuti Tahunan gagal dihanguskan.');
						}
					});
				}else{
					console.log('button no');
				}
			},
			closable:false,
			icon: Ext.Msg.QUESTION
		});
	},
	
	kompensasiall: function(){
		var cutitahunanStore = this.getListcutitahunan().getStore();
		
		Ext.MessageBox.show({
			title: 'Confirm',
			msg: 'Semua Cuti Tahunan yang masih tersisa akan dikompensasi?',
			width: 400,
			buttons: Ext.Msg.YESNO,
			fn: function(btn){
				if (btn == 'yes') {
					console.log('button yes');
					Ext.Ajax.request({
						method: 'POST',
						url: 'c_cutitahunan/kompensasiall',
						//params: {data: jsonData},
						success: function(response){
							var rs = Ext.JSON.decode(response.responseText);
							Ext.Msg.alert('OK', rs.message);
							cutitahunanStore.load();
						},
						failure: function(response){
							var rs = Ext.JSON.decode(response.responseText);
							Ext.Msg.alert('OK', 'Cuti Tahunan gagal dikompensasi.');
						}
					});
				}else{
					console.log('button no');
				}
			},
			closable:false,
			icon: Ext.Msg.QUESTION
		});
	},
	
	hangus: function(){
		var cutitahunanStore = this.getListcutitahunan().getStore();
		var selections = this.getListcutitahunan().getSelectionModel().getSelection();
		
		var arrData = [];
		for (var i=0; i<selections.length; i++) {
			arrData.push(selections[i].data);
		}
		var jsonData = Ext.encode(arrData);
		
		Ext.MessageBox.show({
			title: 'Confirm',
			msg: 'Hanguskan Cuti Tahunan terpilih?',
			width: 400,
			buttons: Ext.Msg.YESNO,
			fn: function(btn){
				if (btn == 'yes') {
					console.log('button yes');
					Ext.Ajax.request({
						method: 'POST',
						url: 'c_cutitahunan/hangus',
						params: {data: jsonData},
						success: function(response){
							var rs = Ext.JSON.decode(response.responseText);
							Ext.Msg.alert('OK', rs.message);
							cutitahunanStore.load();
						},
						failure: function(response){
							var rs = Ext.JSON.decode(response.responseText);
							Ext.Msg.alert('OK', 'Cuti Tahunan gagal dihanguskan.');
						}
					});
				}else{
					console.log('button no');
				}
			},
			closable:false,
			icon: Ext.Msg.QUESTION
		});
	},
	
	kompensasi: function(){
		var cutitahunanStore = this.getListcutitahunan().getStore();
		var selections = this.getListcutitahunan().getSelectionModel().getSelection();
		
		var arrData = [];
		for (var i=0; i<selections.length; i++) {
			arrData.push(selections[i].data);
		}
		var jsonData = Ext.encode(arrData);
		
		Ext.MessageBox.show({
			title: 'Confirm',
			msg: 'Semua Cuti Tahunan yang masih tersisa akan dikompensasi?',
			width: 400,
			buttons: Ext.Msg.YESNO,
			fn: function(btn){
				if (btn == 'yes') {
					console.log('button yes');
					Ext.Ajax.request({
						method: 'POST',
						url: 'c_cutitahunan/kompensasi',
						params: {data: jsonData},
						success: function(response){
							var rs = Ext.JSON.decode(response.responseText);
							Ext.Msg.alert('OK', rs.message);
							cutitahunanStore.load();
						},
						failure: function(response){
							var rs = Ext.JSON.decode(response.responseText);
							Ext.Msg.alert('OK', 'Cuti Tahunan gagal dikompensasi.');
						}
					});
				}else{
					console.log('button no');
				}
			},
			closable:false,
			icon: Ext.Msg.QUESTION
		});
	}
	
});