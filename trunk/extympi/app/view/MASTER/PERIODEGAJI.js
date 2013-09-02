Ext.define('YMPI.view.MASTER.PERIODEGAJI', {
	extend: 'Ext.tab.Panel',
	
	alias	: 'widget.PERIODEGAJI',
	
	title	: 'periodegaji',
	margins: 0,
	tabPosition: 'right',
	activeTab: 0,
	
	initComponent: function(){
		Ext.apply(this, {
            items: [{
				xtype	: 'Listperiodegaji'
			}, {
				xtype: 'v_periodegaji_form',
				disabled: true
			}]
        });
		this.callParent(arguments);
	}
	
});