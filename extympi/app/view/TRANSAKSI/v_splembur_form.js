Ext.define('YMPI.view.TRANSAKSI.v_splembur_form', {
	extend	: 'Ext.form.Panel',
	
	alias	: 'widget.v_splembur_form',
	
	region:'east',
	id: 'east-region-container',
	
	title		: 'Create/Update SPL',
    bodyPadding	: 5,
    autoScroll	: true,
    
    initComponent: function(){
		/* STORE start */	
		var unit_store = Ext.create('YMPI.store.s_unitkerja',{autoLoad:true});	
		var nik_store = Ext.create('YMPI.store.s_karyawan');
		
		/* STORE end */

    	/*
		 * Deklarasi variable setiap field
		 */
		 
		var NOLEMBUR_field = Ext.create('Ext.form.field.Text', {
			itemId: 'NOLEMBUR_field',
			name: 'NOLEMBUR', /* column name of table */
			fieldLabel: 'NOLEMBUR',
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
		var TANGGAL_field = Ext.create('Ext.ux.form.DateTimeField', {
			name: 'TANGGAL', /* column name of table */
			format: 'Y-m-d',
			fieldLabel: 'TANGGAL'
		});
		var KEPERLUAN_field = Ext.create('Ext.form.field.TextArea', {
			name: 'KEPERLUAN', /* column name of table */
			fieldLabel: 'KEPERLUAN',
			maxLength: 30 /* length of column name */
		});
		
		/*var NIKUSUL_field = Ext.create('Ext.form.field.ComboBox', {
			name: 'NIKUSUL',
			fieldLabel: 'NIKUSUL',
			store: nik_store,
			allowBlank: false,
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
		});*/
		
		var NIKUSUL_field = Ext.create('Ext.form.field.Text', {
			itemId: 'NIKUSUL_field',
			name: 'NIKUSUL',
			fieldLabel: 'NIKUSUL',
			allowBlank: false,
			value: user_nik,
			readOnly:true
		});
		
		var NIKSETUJU_field = Ext.create('Ext.form.field.ComboBox', {
			itemId: 'NIKSETUJU_field',
			name: 'NIKSETUJU', /* column name of table */
			fieldLabel: 'NIKSETUJU',
			store: nik_store,
			allowBlank: false,
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
		var NIKDIKETAHUI_field = Ext.create('Ext.form.field.ComboBox', {
			name: 'NIKDIKETAHUI', /* column name of table */
			fieldLabel: 'NIKDIKETAHUI',
			readOnly:true,
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
		var NIKPERSONALIA_field = Ext.create('Ext.form.field.ComboBox', {
			name: 'NIKPERSONALIA', /* column name of table */
			fieldLabel: 'NIKPERSONALIA',
			store: nik_store,
			queryMode: 'local',
			readOnly:true,
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
		
		var TGLSETUJU_field = Ext.create('Ext.form.field.Date', {
			itemId: 'TGLSETUJU_field',
			name: 'TGLSETUJU', /* column name of table */
			format: 'Y-m-d',
			fieldLabel: 'TGLSETUJU',
			readOnly:true,
			listeners:{
				'afterrender' : function(editor,e){
					if(NIKSETUJU_field.getValue() == username)
					{
						editor.readOnly = false;
					}
				}
			}
		});
		var TGLPERSONALIA_field = Ext.create('Ext.form.field.Date', {
			name: 'TGLPERSONALIA', /* column name of table */
			format: 'Y-m-d',
			readOnly:true,
			fieldLabel: 'TGLPERSONALIA'
		});
		var USERNAME_field = Ext.create('Ext.form.field.Text', {
			name: 'USERNAME', /* column name of table */
			fieldLabel: 'USERNAME',
			value: username,
			readOnly:true,
			maxLength: 18 /* length of column name */
		});		
        Ext.apply(this, {
            fieldDefaults: {
                labelAlign: 'right',
                labelWidth: 120,
                msgTarget: 'qtip',
				anchor: '100%'
            },
			defaultType: 'textfield',
            items: [NOLEMBUR_field,KODEUNIT_field,TANGGAL_field,KEPERLUAN_field,NIKUSUL_field,NIKSETUJU_field,NIKDIKETAHUI_field,NIKPERSONALIA_field,TGLSETUJU_field,TGLPERSONALIA_field,USERNAME_field],
			
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