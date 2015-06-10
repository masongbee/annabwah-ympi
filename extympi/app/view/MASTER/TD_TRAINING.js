Ext.define('YMPI.view.MASTER.TD_TRAINING', {
	extend: 'Ext.tab.Panel',
	
	alias	: 'widget.TD_TRAINING',
	
	title	: 'td_training',
	margins: 0,
	tabPosition: 'right',
	activeTab: 0,
	
	initComponent: function(){
		Ext.apply(this, {
            items: [{
				xtype	: 'Listtd_training'
			}, {
				xtype: 'v_td_training_form',
				disabled: true
			}]
        });
		this.callParent(arguments);
	}
	
});