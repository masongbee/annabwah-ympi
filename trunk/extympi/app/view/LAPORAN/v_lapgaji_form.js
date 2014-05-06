Ext.define('YMPI.view.LAPORAN.v_lapgaji_form', {
	extend	: 'Ext.form.Panel',
	
	alias	: 'widget.v_lapgaji_form',
	
	// title		: 'Filter',
    bodyPadding	: 5,
    autoScroll	: true,
	//comboFilter	: [],
    
    initComponent: function(){
		var me = this;

		/* STORE start */
		var bulan_store = Ext.create('Ext.data.Store', {
			fields: [
                {name: 'BULAN', type: 'string', mapping: 'BULAN'},
                {name: 'BULAN_GAJI', type: 'string', mapping: 'BULAN_GAJI'},
				{name: 'TGLMULAI', type: 'date', dateFormat: 'Y-m-d',mapping: 'TGLMULAI'},
				{name: 'TGLSAMPAI', type: 'date', dateFormat: 'Y-m-d',mapping: 'TGLSAMPAI'}
            ],
			proxy: {
				type: 'ajax',
				url: 'c_gajibulanan/get_periodegaji',
				reader: {
					type: 'json',
					root: 'data'
				}
			},
			autoLoad: true
		});
		var grade_store = Ext.create('YMPI.store.s_grade', {
			autoLoad: true
		});
		/* STORE end */

		/*
		 * Deklarasi variable setiap field
		 */
		var BULANGAJI_field = Ext.create('Ext.form.ComboBox', {
			name: 'bulangaji',
			fieldLabel: 'Bulan Gaji',
			store: bulan_store,
			queryMode: 'local',
			displayField: 'BULAN_GAJI',
			valueField: 'BULAN',
			emptyText: 'Bulan'
		});
		var GRADE_field = Ext.create('Ext.form.ComboBox', {
			name: 'grade',
			fieldLabel: 'Grade',
			store: grade_store,
			queryMode: 'local',
			displayField: 'GRADE',
			valueField: 'GRADE',
	        typeAhead: true,
	        multiSelect: true,
	        forceSelection: true
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
						BULANGAJI_field
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
						GRADE_field
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