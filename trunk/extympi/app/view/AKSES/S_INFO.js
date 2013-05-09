/*Ext.define('YMPI.view.AKSES.S_INFO', {
	extend: 'Ext.form.Panel',
	
	alias	: 'widget.S_INFO',
	
	title	: 'Info Setting',
	bodyPadding: 0,
	layout: 'border',
	initComponent: function(){
		var tabs = Ext.create('Ext.tab.Panel', {
            region: 'center',
            
            margins: 0,
            tabPosition: 'right',
            activeTab: 0,
            items: [{
				xtype	: 'Lists_info'
			}, {
				xtype: 'v_s_info_form',
				disabled: true
			}]
        });
        
        Ext.apply(this, {
            items: [tabs]
        });
		
		this.callParent(arguments);
	}
	
});*/

Ext.define('YMPI.view.AKSES.S_INFO', {
	extend: 'Ext.tab.Panel',
	
	alias	: 'widget.S_INFO',
	
	title	: 'Info Setting',
	margins: 0,
	tabPosition: 'right',
	activeTab: 0,
	
	initComponent: function(){
		Ext.apply(this, {
            items: [{
				xtype	: 'Lists_info'
			}, {
				xtype: 'v_s_info_form',
				disabled: true
			}]
        });
		this.callParent(arguments);
	}
});