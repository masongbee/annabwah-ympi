Ext.define('YMPI.view.LAPORAN.v_laptraining_form', {
	extend	: 'Ext.form.Panel',
	
	alias	: 'widget.v_laptraining_form',
	
	// title		: 'Filter',
    bodyPadding	: 5,
    autoScroll	: true,
	//comboFilter	: [],
    
    initComponent: function(){
		var me = this;

		/* STORE start */
		var ikutserta_store = Ext.create('Ext.data.Store', {
    	    fields: ['value', 'display'],
    	    data : [
    	        {"value":"Y", "display":"Sudah Mengikuti"},
    	        {"value":"T", "display":"Belum Mengikuti"}
    	    ]
    	});
		/* STORE end */

		/*
		 * Deklarasi variable setiap field
		 */
		var TRAINING_field = Ext.create('Ext.form.ComboBox', {
			name: 'KODETRAINING',
			fieldLabel: 'Training',
			store: 's_jenistraining',
			queryMode: 'local',
			displayField: 'NAMATRAINING',
			valueField: 'KODETRAINING',
			emptyText: 'Daftar Training',
			allowBlank: false
		});
		var KARYAWANIKUTSERTA_field = Ext.create('Ext.form.field.ComboBox', {
			name: 'KARIKUTSERTA', /* column name of table */
			fieldLabel: 'Keikutsertaan Karyawan',
			store: ikutserta_store,
			queryMode: 'local',
			displayField: 'display',
			valueField: 'value',
			value: 'Y',
			allowBlank: false,
			labelWidth: 180
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
					//left column
					xtype: 'form',
					bodyStyle: 'border-width: 0px;',
					columnWidth:0.49,
					items: [
						TRAINING_field
					]
				} ,{
					xtype: 'splitter',
					columnWidth:0.02
				} ,{
					//right column
					xtype: 'form',
					bodyStyle: 'border-width: 0px;',
					columnWidth:0.49,
					items: [
						KARYAWANIKUTSERTA_field
					]
				}]
			}],
			
	        buttons: [{
                iconCls: 'icon-reset',
                text: 'Search',
                action: 'searchall'
            }]
        });
        
        this.callParent();
    }
});