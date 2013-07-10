Ext.define('YMPI.view.MUTASI.KARYAWAN', {
	extend: 'Ext.panel.Panel',
	
	alias	: 'widget.KARYAWAN',
	
	title	: 'karyawan',
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
					xtype	: 'Listkaryawan'
				}, {
					xtype: 'v_karyawan_form',
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
					xtype: 'tabpanel',
					region: 'center',
					margins: '0 0 0 0',
					tabPosition: 'top',
					activeTab: 0,
					items: [{
						xtype: 'Listkeluarga'
					}, {
						xtype: 'Listskill'
					}, {
						xtype: 'Listriwayatkerja'
					}, {
						xtype: 'Listriwayatkerjaympi'
					}, {
						xtype: 'Listriwayattraining'
					}]
				}]
			}]
        });
		this.callParent(arguments);
	}
	
});