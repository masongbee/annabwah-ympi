Ext.define('YMPI.view.TRANSAKSI.TD_EVEFEKTIVITAS', {
	extend: 'Ext.tab.Panel',
	
	alias	: 'widget.TD_EVEFEKTIVITAS',
	
	title	: 'td_evefektivitas',
	margins: 0,
	tabPosition: 'right',
	activeTab: 0,
	
	initComponent: function(){
		Ext.apply(this, {
            items: [{
				xtype	: 'Listtd_evefektivitas'
			}, {
				xtype: 'v_td_evefektivitas_form',
				disabled: true
			}]
        });
		this.callParent(arguments);
	}
	
});