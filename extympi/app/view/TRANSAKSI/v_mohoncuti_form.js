Ext.define('YMPI.view.TRANSAKSI.v_mohoncuti_form', {
	extend	: 'Ext.form.Panel',
	
	alias	: 'widget.v_mohoncuti_form',
	
	region:'east',
	id: 'east-region-container',
	
	title		: 'Create/Update Cuti',
    bodyPadding	: 5,
    autoScroll	: true,
    
    initComponent: function(){
		/* STORE start */	
		var unit_store = Ext.create('YMPI.store.s_unitkerja');	
		var nik_store = Ext.create('YMPI.store.s_karyawan');
		
		/* STORE end */
    	/*
		 * Deklarasi variable setiap field
		 */
		 
		var NOCUTI_field = Ext.create('Ext.form.field.Text', {
			itemId: 'NOCUTI_field',
			name: 'NOCUTI', /* column name of table */
			fieldLabel: 'NOCUTI',
			allowBlank: false /* jika primary_key */,
			maxLength: 7 /* length of column name */
		});
		var KODEUNIT_field = Ext.create('Ext.form.field.ComboBox', {
			name: 'KODEUNIT', /* column name of table */
			fieldLabel: 'Kode Unit <font color=red>(*)</font>',
			store: unit_store,
			queryMode: 'local',
			displayField: 'NAMAUNIT',
			valueField: 'KODEUNIT',
			tpl: Ext.create('Ext.XTemplate',
                '<tpl for=".">',
                    '<div class="x-boundlist-item">{KODEUNIT} - {NAMAUNIT_TREE}</div>',
                '</tpl>'
            ),
			allowBlank: false
		});
		var NIKATASAN1_field = Ext.create('Ext.form.field.ComboBox', {
			name: 'NIKATASAN1', /* column name of table */
			fieldLabel: 'NIKATASAN1',
			store: nik_store,
			queryMode: 'local',
			//displayField: 'NAMAKAR',
			valueField: 'NIK',
			tpl: Ext.create('Ext.XTemplate',
				'<tpl for=".">',
					'<div class="x-boundlist-item">{NIK} - {NAMAKAR}</div>',
				'</tpl>'
			),
			displayTpl: Ext.create('Ext.XTemplate',
				'<tpl for=".">',
					'{NIK} - {NAMAKAR}',
				'</tpl>'
			)
		});
		var NIKATASAN2_field = Ext.create('Ext.form.field.ComboBox', {
			name: 'NIKATASAN2', /* column name of table */
			fieldLabel: 'NIKATASAN2',
			store: nik_store,
			queryMode: 'local',
			//displayField: 'NAMAKAR',
			valueField: 'NIK',
			tpl: Ext.create('Ext.XTemplate',
				'<tpl for=".">',
					'<div class="x-boundlist-item">{NIK} - {NAMAKAR}</div>',
				'</tpl>'
			),
			displayTpl: Ext.create('Ext.XTemplate',
				'<tpl for=".">',
					'{NIK} - {NAMAKAR}',
				'</tpl>'
			)
		});
		var NIKATASAN3_field = Ext.create('Ext.form.field.ComboBox', {
			name: 'NIKATASAN3', /* column name of table */
			fieldLabel: 'NIKATASAN3',
			store: nik_store,
			queryMode: 'local',
			//displayField: 'NAMAKAR',
			valueField: 'NIK',
			tpl: Ext.create('Ext.XTemplate',
				'<tpl for=".">',
					'<div class="x-boundlist-item">{NIK} - {NAMAKAR}</div>',
				'</tpl>'
			),
			displayTpl: Ext.create('Ext.XTemplate',
				'<tpl for=".">',
					'{NIK} - {NAMAKAR}',
				'</tpl>'
			)
		});
		var NIKHR_field = Ext.create('Ext.form.field.ComboBox', {
			name: 'NIKHR', /* column name of table */
			fieldLabel: 'NIKHR',
			store: nik_store,
			queryMode: 'local',
			//displayField: 'NAMAKAR',
			valueField: 'NIK',
			tpl: Ext.create('Ext.XTemplate',
				'<tpl for=".">',
					'<div class="x-boundlist-item">{NIK} - {NAMAKAR}</div>',
				'</tpl>'
			),
			displayTpl: Ext.create('Ext.XTemplate',
				'<tpl for=".">',
					'{NIK} - {NAMAKAR}',
				'</tpl>'
			)
		});
		var TGLATASAN1_field = Ext.create('Ext.form.field.Date', {
			name: 'TGLATASAN1', /* column name of table */
			format: 'Y-m-d',
			fieldLabel: 'TGLATASAN1'
		});
		var TGLATASAN2_field = Ext.create('Ext.form.field.Date', {
			name: 'TGLATASAN2', /* column name of table */
			format: 'Y-m-d',
			fieldLabel: 'TGLATASAN2'
		});
		var TGLATASAN3_field = Ext.create('Ext.form.field.Date', {
			name: 'TGLATASAN3', /* column name of table */
			format: 'Y-m-d',
			fieldLabel: 'TGLATASAN3'
		});
		var TGLHR_field = Ext.create('Ext.form.field.Date', {
			name: 'TGLHR', /* column name of table */
			format: 'Y-m-d',
			fieldLabel: 'TGLHR'
		});
		var USERNAME_field = Ext.create('Ext.form.field.Text', {
			name: 'USERNAME', /* column name of table */
			fieldLabel: 'USERNAME',
			maxLength: 12 /* length of column name */
		});		
        Ext.apply(this, {
            fieldDefaults: {
                labelAlign: 'right',
                labelWidth: 120,
                msgTarget: 'qtip',
				anchor: '100%'
            },
			defaultType: 'textfield',
            items: [NOCUTI_field,KODEUNIT_field,NIKATASAN1_field,NIKATASAN2_field,NIKATASAN3_field,NIKHR_field,TGLATASAN1_field,TGLATASAN2_field,TGLATASAN3_field,TGLHR_field,USERNAME_field],
			
	        buttons: [{
                iconCls: 'icon-save',
                itemId: 'save',
                text: 'Save',
                disabled: true,
                action: 'save'
            }, {
                iconCls: 'icon-add',
				itemId: 'create',
                text: 'Create',
                action: 'create'
            }, {
                iconCls: 'icon-reset',
                text: 'Cancel',
                action: 'cancel'
            }]
        });
        
        this.callParent();
    }
});