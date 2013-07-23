Ext.define('YMPI.view.TRANSAKSI.PRESENSILEMBUR', {
	extend: 'Ext.tab.Panel',
	
	alias	: 'widget.PRESENSILEMBUR',
	
	title	: 'presensilembur',
	margins: 0,
	tabPosition: 'right',
	activeTab: 0,
	
	initComponent: function(){
		Ext.apply(this, {
            items: [{
				xtype	: 'Listpresensilembur'
			}, {
				xtype: 'v_presensilembur_form',
				disabled: true
			}]
        });
		this.callParent(arguments);
	}
	
});