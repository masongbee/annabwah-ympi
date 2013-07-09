Ext.define('YMPI.view.MASTER.JENISABSEN', {
	extend: 'Ext.tab.Panel',
	
	alias	: 'widget.JENISABSEN',
	
	title	: 'Jenis Absen',
	margins: 0,
	tabPosition: 'right',
	activeTab: 0,
	
	initComponent: function(){
		Ext.apply(this, {
            items: [{
				xtype	: 'Listjenisabsen'
			}, {
				xtype: 'v_jenisabsen_form',
				disabled: true
			}]
        });
		this.callParent(arguments);
	}
	
});