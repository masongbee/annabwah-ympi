Ext.define('YMPI.view.MASTER.UPAHPOKOK', {
	extend: 'Ext.tab.Panel',
	
	alias	: 'widget.UPAHPOKOK',
	
	title	: 'upahpokok',
	margins: 0,
	tabPosition: 'right',
	activeTab: 0,
	
	initComponent: function(){
		Ext.apply(this, {
            items: [{
				xtype	: 'Listupahpokok'
			}, {
				xtype: 'v_upahpokok_form',
				disabled: true
			}]
        });
		this.callParent(arguments);
	}
	
});