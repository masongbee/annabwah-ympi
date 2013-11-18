Ext.define('YMPI.view.TRANSAKSI.RINCIANCUTI', {
	extend: 'Ext.tab.Panel',
	
	alias	: 'widget.RINCIANCUTI',
	
	title	: 'rinciancuti',
	margins: 0,
	tabPosition: 'right',
	activeTab: 0,
	
	initComponent: function(){
		Ext.apply(this, {
            items: [{
				xtype	: 'Listrinciancuti'
			}, {
				xtype: 'v_rinciancuti_form',
				disabled: true
			}]
        });
		this.callParent(arguments);
	}
	
});