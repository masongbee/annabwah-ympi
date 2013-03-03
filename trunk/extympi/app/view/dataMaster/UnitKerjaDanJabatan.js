Ext.define('YMPI.view.dataMaster.UnitKerjaDanJabatan', {
	extend: 'Ext.form.Panel',
	
	layout: {
        type: 'hbox',
        align: 'stretch'
    },
    initComponent: function(){
    	this.items = [{
        	xtype	: 'unitKerjaGrid',
        	flex: 1
        },{
        	xtype	: 'jabatanGrid',
        	flex: 1
        } ];
        /*this.items = [{
        	xtype	: 'unitKerjaGrid',
        	pack: 'left',
        	anchor	: '50%, -0'
        },{
        	xtype	: 'jabatanGrid',
        	pack: 'right',
        	anchor	: '50%, -0'
        } ];*/
        
        this.callParent(arguments);
    }

});