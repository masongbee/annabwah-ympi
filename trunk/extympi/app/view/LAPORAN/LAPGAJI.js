Ext.define('YMPI.view.LAPORAN.LAPGAJI', {
	extend		: 'Ext.form.Panel',
	itemId		: 'LAPGAJI',
	
	alias		: 'widget.LAPGAJI',
	
	title		: 'Laporan Upah Karyawan',
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
				xtype: 'v_lapgaji_form'
			}, {
				xtype: 'v_lapgaji',
				flex: 1
			}]
        });
		this.callParent(arguments);
	}
	
});