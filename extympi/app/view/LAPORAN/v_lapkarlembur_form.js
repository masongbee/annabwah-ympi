Ext.define('YMPI.view.LAPORAN.v_lapkarlembur_form', {
	extend	: 'Ext.form.Panel',
	
	alias	: 'widget.v_lapkarlembur_form',
	
	// title		: 'Filter',
    bodyPadding	: 5,
    autoScroll	: true,
	//comboFilter	: [],
    
    initComponent: function(){
		var me = this;

		/*
		 * Deklarasi variable setiap field
		 */
		var MONTH_field = Ext.create('Ext.form.field.Month', {
			name: 'MONTH',
			fieldLabel: 'Lembur Bulan',
			format: 'F, Y',
			submitFormat: 'Y-m-d',
			value: new Date(),
			allowBlank: false
		});
		
        Ext.apply(this, {
            fieldDefaults: {
                labelAlign: 'right',
                labelWidth: 120,
                msgTarget: 'qtip',
				anchor: '100%'
            },
			defaultType: 'textfield',
			listeners: {
				afterrender: function(){
					//me.down(detail_tabs).setDisabled(true);
				}
			},
            items: [{
				xtype: 'form',
				bodyStyle: 'border-width: 0px;',
				layout: 'column',
				items: [{
					xtype: 'form',
					bodyStyle: 'border-width: 0px;',
					columnWidth:0.49,
					items: [
						MONTH_field
					]
				}]
			}],
			
	        buttons: [{
                text	: 'Export Excel',
				iconCls	: 'icon-excel',
				action	: 'xexcel'
            }]
        });
        
        this.callParent();
    }
});