Ext.define('YMPI.view.TRANSAKSI.MOHONCUTI', {
	extend: 'Ext.panel.Panel',
	
	alias	: 'widget.MOHONCUTI',
	
	title	: 'Permohonan Cuti',
	margins: 0,
	//tabPosition: 'right',
	activeTab: 0,
	
	layout: 'border',
	
	initComponent: function(){
		Ext.apply(this, {
            items: [{
				itemId: 'center',
				region: 'center',     // center region is required, no width/height specified
				xtype: 'tabpanel',
				tabPosition: 'right',
				items: [{
					xtype	: 'Listmohoncuti'
				}, {
					xtype: 'v_mohoncuti_form',
					disabled: true
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
					itemId: 'rinciancutitabs',
					xtype: 'tabpanel',
					region: 'center',
					margins: '0 0 0 0',
					tabPosition: 'top',
					activeTab: 0,
					items: [{
						xtype: 'Listrinciancuti'
					}, {
						xtype: 'v_rinciancuti_form'
					}]
				}]
			}]
        });
		this.callParent(arguments);
	}
	
});