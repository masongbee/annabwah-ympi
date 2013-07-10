Ext.define('YMPI.view.MASTER.CUTITAHUNAN', {
	extend: 'Ext.tab.Panel',
	
	alias	: 'widget.CUTITAHUNAN',
	
	title	: 'cutitahunan',
	margins: 0,
	tabPosition: 'right',
	activeTab: 0,
	
	initComponent: function(){
		Ext.apply(this, {
            items: [{
				xtype	: 'Listcutitahunan'
			}, {
				xtype: 'v_cutitahunan_form',
				disabled: true
			}]
        });
		this.callParent(arguments);
	}
	
});