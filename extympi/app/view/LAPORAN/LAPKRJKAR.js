Ext.define('YMPI.view.LAPORAN.LAPKRJKAR', {
	extend		: 'Ext.form.Panel',
	itemId		: 'LAPKRJKAR',
	
	alias		: 'widget.LAPKRJKAR',
	
	title		: 'Daftar Kinerja Karyawan',
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
				xtype: 'v_lapkrjkar_form'
			}, {
				xtype: 'v_lapkrjkar',
				flex: 1
			}]
        });
		this.callParent(arguments);
	}
	
});