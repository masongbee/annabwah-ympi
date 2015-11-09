Ext.define('YMPI.view.TRANSAKSI.TD_PELATIHAN', {
	extend: 'Ext.tab.Panel',
	
	alias	: 'widget.TD_PELATIHAN',
	
	title	: 'td_pelatihan',
	margins: 0,
	tabPosition: 'right',
	activeTab: 0,
	
	initComponent: function(){
		Ext.apply(this, {
            items: [{
				xtype	: 'Listtd_pelatihan'
			}]
        });
		this.callParent(arguments);
	}
	
});