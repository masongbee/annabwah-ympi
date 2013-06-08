Ext.define('YMPI.view.PROSES.HITUNGGAJI', {
	extend: 'Ext.form.Panel',
	
	alias	: 'widget.HITUNGGAJI',
	
	bodyPadding: 0,
	layout: 'border',
	initComponent: function(){
		/*this.items = [{
			region: 'center',
			layout: {
				type : 'hbox',
				align: 'stretch'
			},
			items: [{
				xtype	: 'Listgajibulanan',
				flex: 1
			}]
		}];*/
		Ext.apply(this, {
            items: [{
				region: 'center',
				layout: {
					type : 'vbox',
					align: 'stretch'
				},
				items: [{
					xtype	: 'Listgajibulanan',
					flex: 1
				}, {
					title: 'Detil Gaji',
					xtype: 'panel',
					autoHeight: true,
					items:[{
						itemId	: 'detilgaji_panel',
						xtype	: 'Listdetilgaji'
					}]
					
				}]
			}]
        });
		
		this.callParent(arguments);
	}
	
});