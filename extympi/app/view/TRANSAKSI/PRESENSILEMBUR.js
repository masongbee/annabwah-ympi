/*Ext.define('YMPI.view.TRANSAKSI.PRESENSILEMBUR', {
	extend: 'Ext.tab.Panel',
	
	alias	: 'widget.PRESENSILEMBUR',
	
	title	: 'presensilembur',
	margins: 0,
	tabPosition: 'right',
	activeTab: 0,
	
	initComponent: function(){
		Ext.apply(this, {
            items: [{
				xtype	: 'Listpresensilembur'
			}, {
				xtype: 'v_presensilembur_form',
				disabled: true
			}]
        });
		this.callParent(arguments);
	}
	
});*/

Ext.define('YMPI.view.TRANSAKSI.PRESENSILEMBUR', {
	extend: 'Ext.form.Panel',
	
	bodyPadding: 0,
	layout: 'border',
    initComponent: function(){
    	this.items = [{
    		region: 'north',
    		layout: {
                type : 'hbox',
                align: 'stretch'
            },
    		items: [{
            	xtype	: 'v_presensilembur_form',
            	flex: 1
            }]
    	},{
    		region: 'center',
    		layout: {
                type : 'vbox',
                align: 'stretch'
            },
            items: [{
            	xtype	: 'Listpresensilembur',
            	flex: 1
            } ]
        }];
        
    	this.callParent(arguments);
    }

});