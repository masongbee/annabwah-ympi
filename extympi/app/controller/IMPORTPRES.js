Ext.define('YMPI.controller.IMPORTPRES',{
	extend: 'Ext.app.Controller',
	views: ['PROSES.Presensi','PROSES.ImportPresensi','PROSES.Testku'],
	models: ['Presensi'],
	stores: ['Presensi'],
	
	//requires: ['YMPI.view.PROSES.Presensi'],
	
	refs: [{
		ref: 'Presensi',
		selector: 'Presensi'
	},{
		ref: 'Presensi',
		selector: 'Presensi'
	}]
});