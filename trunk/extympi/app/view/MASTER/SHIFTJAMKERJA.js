Ext.define('YMPI.view.MASTER.SHIFTJAMKERJA', {
	extend: 'Ext.tab.Panel',
	
	alias	: 'widget.SHIFTJAMKERJA',
	
	title	: 'shiftjamkerja',
	margins: 0,
	tabPosition: 'right',
	activeTab: 0,
	
	initComponent: function(){
		Ext.apply(this, {
            items: [{
				xtype	: 'Listshiftjamkerja'
			}, {
				xtype: 'v_shiftjamkerja_form',
				disabled: true
			}]
        });
		this.callParent(arguments);
	}
	
});