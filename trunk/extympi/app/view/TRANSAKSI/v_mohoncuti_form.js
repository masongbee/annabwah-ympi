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
			name: 'NOCUTI', 
			fieldLabel: 'NOCUTI',
			allowBlank: false,
			maxLength: 7 
		});
		var KODEUNIT_field = Ext.create('Ext.form.field.ComboBox', {
			name: 'KODEUNIT', 
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
			allowBlank: true
		});
		var NIKATASANC1_field = Ext.create('Ext.form.field.Text', {
			itemId : 'NIKATASANC1_field',
			name: 'NIKATASANC1', 
			fieldLabel: 'NIKATASAN1'
			//store: nik_store,
			//queryMode: 'local',
			//displayField: 'NAMAKAR',
			//valueField: 'NIK',
			/*tpl: Ext.create('Ext.XTemplate',
				'<tpl for=".">',
					'<div class="x-boundlist-item">{NIK} - {NAMAKAR}</div>',
				'</tpl>'
			),
			displayTpl: Ext.create('Ext.XTemplate',
				'<tpl for=".">',
					'{NIK} - {NAMAKAR}',
				'</tpl>'
			)*/
		});
		var NIKATASANC2_field = Ext.create('Ext.form.field.ComboBox', {
			name: 'NIKATASANC2', 
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
		var NIKATASANC3_field = Ext.create('Ext.form.field.ComboBox', {
			name: 'NIKATASANC3', 
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
			name: 'NIKHR', 
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
		var TGLATASANC1_field = Ext.create('Ext.form.field.Date', {
			name: 'TGLATASANC1', 
			format: 'Y-m-d',
			fieldLabel: 'TGLATASAN1'
		});
		var TGLATASANC2_field = Ext.create('Ext.form.field.Date', {
			name: 'TGLATASANC2', 
			format: 'Y-m-d',
			fieldLabel: 'TGLATASAN2'
		});
		var TGLATASANC3_field = Ext.create('Ext.form.field.Date', {
			name: 'TGLATASANC3', 
			format: 'Y-m-d',
			fieldLabel: 'TGLATASAN3'
		});
		var TGLHR_field = Ext.create('Ext.form.field.Date', {
			name: 'TGLHR', 
			format: 'Y-m-d',
			fieldLabel: 'TGLHR'
		});
		var USERNAME_field = Ext.create('Ext.form.field.Hidden', {
			name: 'USERNAME', 
			fieldLabel: 'USERNAME',
			value: username,
			readOnly: true
		});		
        Ext.apply(this, {
            fieldDefaults: {
                labelAlign: 'right',
                labelWidth: 120,
                msgTarget: 'qtip',
				anchor: '100%'
            },
			defaultType: 'textfield',
            items: [NOCUTI_field,KODEUNIT_field,NIKATASANC1_field,NIKATASANC2_field,NIKHR_field,TGLATASANC1_field,TGLATASANC2_field,TGLHR_field,USERNAME_field],
			
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