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
		var nik_store = Ext.create('YMPI.store.s_karyawan',{autoLoad:true,pageSize: 3000});
		
		var nikatasan_store = Ext.create('Ext.data.Store', {
			fields: [
                {name: 'NIK', type: 'string', mapping: 'NIK'},
                {name: 'NAMAKAR', type: 'string', mapping: 'NAMAKAR'}
            ],
			proxy: {
				type: 'ajax',
				url: 'c_public_function/get_atasan',
				reader: {
					type: 'json',
					root: 'data'
				}
			},
			autoLoad: true
		});
		
		/* STORE end */

    	/*
		 * Deklarasi variable setiap field
		 */
		 
		var NOLEMBUR_field = Ext.create('Ext.form.field.Text', {
			itemId: 'NOLEMBUR_field',
			name: 'NOLEMBUR', /* column name of table */
			fieldLabel: 'NO. LEMBUR',
			//allowBlank: false /* jika primary_key */,
			//maxLength: 7 /* length of column name */
			readOnly: true
		});
		var KODEUNIT_field = Ext.create('Ext.form.field.Hidden', {
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
		var TANGGAL_field = Ext.create('Ext.form.field.Date', {
			itemId: 'TANGGAL_field',
			name: 'TANGGAL', /* column name of table */
			format: 'Y-m-d',
			fieldLabel: 'TANGGAL'
		});
		var KEPERLUAN_field = Ext.create('Ext.form.field.Text', {
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
		
		var NIKUSUL_field = Ext.create('Ext.form.field.ComboBox', {
			itemId : 'NIKUSUL_field',
			name: 'NIKUSUL', 
			fieldLabel: 'PENGUSUL',
			store: nik_store,
			queryMode: 'local',
			tpl: Ext.create('Ext.XTemplate',
				'<tpl for=".">',
					'<div class="x-boundlist-item">{NIK} - {NAMAKAR}</div>',
				'</tpl>'
			),
			displayTpl: Ext.create('Ext.XTemplate',
				'<tpl for=".">',
					'{NIK} - {NAMAKAR}',
				'</tpl>'
			),
			valueField: 'NIK',
			readOnly: true
		});
		
		var NIKSETUJU_field = Ext.create('Ext.form.field.ComboBox', {
			itemId: 'NIKSETUJU_field',
			name: 'NIKSETUJU', /* column name of table */
			fieldLabel: 'ATASAN',
			typeAhead    : true,
			triggerAction: 'all',
			selectOnFocus: true,
            loadingText  : 'Searching...',
			displayField: 'NAMAKAR',
			store: nikatasan_store,
			queryMode: 'local',
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
			itemId: 'NIKDIKETAHUI_field',
			name: 'NIKDIKETAHUI', /* column name of table */
			fieldLabel: 'NIKDIKETAHUI',
			readOnly:true,
			typeAhead    : true,
			triggerAction: 'all',
			selectOnFocus: true,
            loadingText  : 'Searching...',
			displayField: 'NAMAKAR',
			store: nikatasan_store,
			queryMode: 'local',
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
			itemId: 'NIKPERSONALIA_field',
			name: 'NIKPERSONALIA', /* column name of table */
			fieldLabel: 'PERSONALIA',
			typeAhead    : true,
			triggerAction: 'all',
			selectOnFocus: true,
            loadingText  : 'Searching...',
			displayField: 'NAMAKAR',
			store: nik_store,
			queryMode: 'local',
			tpl: Ext.create('Ext.XTemplate',
				'<tpl for=".">',
					'<div class="x-boundlist-item">{NIK} - {NAMAKAR}</div>',
				'</tpl>'
			),
			displayTpl: Ext.create('Ext.XTemplate',
				'<tpl for=".">',
					'{NIK} - {NAMAKAR}',
				'</tpl>'
			),
			valueField: 'NIK',
			value : nik_hrd,
			readOnly : true
		});
		
		var TGLSETUJU_field = Ext.create('Ext.form.field.Date', {
			itemId: 'TGLSETUJU_field',
			name: 'TGLSETUJU', /* column name of table */
			format: 'Y-m-d',
			fieldLabel: 'TGL. ATASAN',
			readOnly:true,
			listeners:{
				'afterrender' : function(editor,e){
					if(NIKSETUJU_field.getValue() == user_nik)
					{
						editor.readOnly = false;
					}
				}
			}
		});
		var TGLPERSONALIA_field = Ext.create('Ext.form.field.Date', {
			itemId: 'TGLPERSONALIA_field',
			name: 'TGLPERSONALIA', /* column name of table */
			format: 'Y-m-d',
			readOnly:true,
			fieldLabel: 'TGL. PERSONALIA'
		});
		var USERNAME_field = Ext.create('Ext.form.field.Hidden', {
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
            items: [NOLEMBUR_field,KODEUNIT_field,TANGGAL_field,KEPERLUAN_field,NIKUSUL_field,NIKSETUJU_field,TGLSETUJU_field
            	,NIKPERSONALIA_field,TGLPERSONALIA_field,USERNAME_field],
			
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