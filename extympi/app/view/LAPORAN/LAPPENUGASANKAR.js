Ext.define('YMPI.view.LAPORAN.LAPPENUGASANKAR', {
	extend		: 'Ext.form.Panel',
	itemId		: 'LAPPENUGASANKAR',
	
	alias		: 'widget.LAPPENUGASANKAR',
	
	title		: 'Daftar Penugasan Karyawan',
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
				xtype: 'v_lappenugasankar_form'
			}, {
				xtype: 'v_lappenugasankar',
				flex: 1
			}]
        });
		this.callParent(arguments);
	}
	
});