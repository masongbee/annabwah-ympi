Ext.Loader.setConfig({
    enabled: true
});

Ext.require([
    'Ext.grid.*',
    'Ext.data.*'
]);

Ext.define('YMPI.view.MUTASI.KARYAWAN', {
	extend: 'Ext.form.Panel',
	
	requires: ['YMPI.view.MUTASI.KaryawanList', 'YMPI.view.MUTASI.KaryawanForm'],
	
	layout: {
        type: 'vbox',
        align: 'stretch'
    },
    initComponent: function(){
    	this.items = [{
        	xtype	: 'KaryawanList',
        	flex: 1
        },{
        	xtype	: 'KaryawanForm',
        	flex: 1
        } ];
        
        this.callParent(arguments);
    }

});
