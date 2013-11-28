Ext.define('YMPI.view.MASTER.SHIFTJAMKERJA', {
	extend: 'Ext.panel.Panel',
	
	alias	: 'widget.SHIFTJAMKERJA',
	
	title	: 'SHIFT JAM KERJA',
	
	margins: 0,
	//tabPosition: 'right',
	activeTab: 0,
	
	layout: 'border',
    initComponent: function(){
		Ext.apply(this, {
            items: [{
				itemId: 'center',
				region: 'center',     // center region is required, no width/height specified
				//xtype: 'tabpanel',
				//tabPosition: 'right',
				items: [{
					xtype	: 'Listshift'
				}, {
					xtype: 'Listdetilshift'
				}]
			}, {
				//title: 'South Region is resizable',
				itemId: 'south',
				region: 'south',     // position for region
				xtype: 'panel',
				height: 250,
				split: true,         // enable resizing
				margins: '0 0 0 0',
				layout: 'border',
				items:[{
					xtype: 'tabpanel',
					region: 'center',
					margins: '0 0 0 0',
					tabPosition: 'right',
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