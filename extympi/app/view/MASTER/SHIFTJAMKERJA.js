Ext.define('YMPI.view.MASTER.SHIFTJAMKERJA', {
	extend: 'Ext.panel.Panel',	
	alias	: 'widget.SHIFTJAMKERJA',
	
	//title	: 'SHIFT JAM KERJA',
	
	margins: 0,
	//tabPosition: 'right',
	activeTab: 0,
	layout: 'border',
    initComponent: function(){
		Ext.apply(this, {
            items: [{
				itemId: 'center',
				xtype: 'panel',
				region: 'center',
				layout: {
					type : 'hbox',
					align: 'stretch'
				},
				items: [{
					xtype	: 'Listshift',
					flex: 1
				},{
					xtype	: 'Listdetilshift',
					flex: 1
				} ]
			},{
				region: 'south',
				layout: {
					type : 'vbox',
					align: 'stretch'
				},
				items: [{
					itemId: 'south',
					xtype: 'tabpanel',
					//region: 'center',
					margins: '0 0 0 0',
					//tabPosition: 'right',
					activeTab: 0,
					items: [{
						xtype: 'Listshiftjamkerja'
					}, {
						xtype: 'v_shiftjamkerja_form',
						disabled: true
					}]
				}]
			}]
        });
		this.callParent(arguments);
	}

});


/*Ext.define('YMPI.view.MASTER.SHIFTJAMKERJA', {
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
	
});*/