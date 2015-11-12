Ext.define('YMPI.view.LAPORAN.LAPKARLEMBUR', {
	extend		: 'Ext.form.Panel',
	itemId		: 'LAPKARLEMBUR',
	
	alias		: 'widget.LAPKARLEMBUR',
	
	title		: 'Daftar Karyawan Lembur',
	margins		: 0,
	closable	: true,
	layout		: {
	    type: 'vbox',
	    align : 'stretch',
	    pack  : 'start'
	},
	initComponent: function(){
		Ext.apply(this, {
			items: [{
				xtype: 'v_lapkarlembur_form'
			}]
        });
		this.callParent(arguments);
	}
	
});