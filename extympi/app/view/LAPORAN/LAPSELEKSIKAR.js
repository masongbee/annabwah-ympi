Ext.define('YMPI.view.LAPORAN.LAPSELEKSIKAR', {
	extend		: 'Ext.form.Panel',
	itemId		: 'LAPSELEKSIKAR',
	
	alias		: 'widget.LAPSELEKSIKAR',
	
	title		: 'Daftar Karyawan per Seleksi',
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
				xtype: 'v_lapseleksikar_form'
			}, {
				xtype: 'v_lapseleksikar',
				flex: 1
			}]
        });
		this.callParent(arguments);
	}
	
});