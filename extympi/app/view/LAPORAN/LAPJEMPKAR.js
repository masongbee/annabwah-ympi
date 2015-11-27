Ext.define('YMPI.view.LAPORAN.LAPJEMPKAR', {
	extend		: 'Ext.form.Panel',
	itemId		: 'LAPJEMPKAR',
	
	alias		: 'widget.LAPJEMPKAR',
	
	title		: 'Daftar Jemputan Karyawan',
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
				xtype: 'v_lapjempkar_form'
			}, {
				xtype: 'v_lapjempkar',
				flex: 1
			}]
        });
		this.callParent(arguments);
	}
	
});