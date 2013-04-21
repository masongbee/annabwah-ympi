Ext.define('YMPILogin.view.Kategori', {
	extend	: 'Ext.form.Panel',
	
	alias	: 'widget.Kategori',
	
	//title		: 'Create/Update Karyawan',
    frame		: false,
    bodyPadding	: 0,
    autoScroll	: true,
    collapsed	: false,
    
    initComponent: function(){
        Ext.apply(this, {
            //width: 550,
            fieldDefaults: {
                labelAlign: 'right',
                labelWidth: 120,
                msgTarget: 'qtip'
            },

            items: [{
                xtype: 'image',
                src:'./assets/images/logoapp/absensi.png',
                padding: 1,
                width: 120,
                height: 120,
                listeners: {
                    render: function(cmp) {
                        Ext.create('Ext.tip.ToolTip', {
                            target: cmp.el,
                            html: "<b>read-only</b>:Read-only users will have read only access to all pages<br> "
                        });
                    }
                }
            }]

        });
        
        this.callParent();
    },
    
});