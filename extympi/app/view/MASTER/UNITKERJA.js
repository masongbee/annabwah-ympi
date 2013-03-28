Ext.define('YMPI.view.MASTER.UNITKERJA', {
	extend: 'Ext.form.Panel',
	
	layout: {
        type: 'hbox',
        align: 'stretch'
    },
    initComponent: function(){
    	this.items = [{
        	xtype	: 'UnitKerjaList',
        	flex: 1
        },{
        	xtype	: 'JabatanList',
        	flex: 1
        } ];
        
        this.callParent(arguments);
    }

});