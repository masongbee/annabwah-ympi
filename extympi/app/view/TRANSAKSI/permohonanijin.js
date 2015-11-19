Ext.define('YMPI.view.TRANSAKSI.PERMOHONANIJIN', {
	extend: 'Ext.tab.Panel',
	
	alias	: 'widget.PERMOHONANIJIN',
	
	title	: 'Absensi',
	margins: 0,
	tabPosition: 'right',
	activeTab: 0,
	
	initComponent: function(){
		Ext.apply(this, {
            items: [{
				xtype	: 'Listpermohonanijin'
			}, {
				xtype: 'v_permohonanijin_form',
				disabled: true
			}]
        });
		this.callParent(arguments);
	}
	
});