Ext.define('YMPI.view.LAPORAN.LAPTRAINING', {
	extend		: 'Ext.form.Panel',
	itemId		: 'LAPTRAINING',
	
	alias		: 'widget.LAPTRAINING',
	
	title		: 'Laporan Training Karyawan',
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
				xtype: 'v_laptraining_form'
			}, {
				xtype: 'v_laptraining',
				flex: 1
			}]
        });
		this.callParent(arguments);
	}
	
});