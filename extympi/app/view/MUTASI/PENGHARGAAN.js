Ext.define('YMPI.view.MUTASI.PENGHARGAAN', {
	extend: 'Ext.form.Panel',
	
	bodyPadding: 0,
	layout: 'border',
	initComponent: function(){
		this.items = [{
			region: 'center',
			layout: {
				type : 'hbox',
				align: 'stretch'
			},
			items: [{
				xtype	: 'Listpenghargaan',
				flex: 1
			}]
		}];
		
		this.callParent(arguments);
	}
	
});