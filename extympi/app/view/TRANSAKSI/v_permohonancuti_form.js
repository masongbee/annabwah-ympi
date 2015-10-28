Ext.define('YMPI.view.TRANSAKSI.v_permohonancuti_form', {
	extend	: 'Ext.form.Panel',
	
	alias	: 'widget.v_permohonancuti_form',
	
	region:'east',
	id: 'east-region-container',
	
	title		: 'Create/Update permohonancuti',
    bodyPadding	: 5,
    autoScroll	: true,
    
    initComponent: function(){
    	/* STORE start */	
		var unit_store = Ext.create('YMPI.store.s_unitkerja');	
		var nik_store = Ext.create('YMPI.store.s_karyawan',{autoLoad:true,pageSize: max_kar});
		
		var personalia_store = Ext.create('Ext.data.Store', {
			fields: [
                {name: 'NIK', type: 'string', mapping: 'NIK'},
                {name: 'NAMAKAR', type: 'string', mapping: 'NAMAKAR'}
            ],
			proxy: {
				type: 'ajax',
				url: 'c_permohonancuti/get_personalia',
				reader: {
					type: 'json',
					root: 'data'
				}
			},
			autoLoad: true
		});

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
		
		var STATUSCUTI_store = Ext.create('Ext.data.Store', {
    	    fields: ['value', 'display'],
    	    data : [
    	        {"value":"A", "display":"DIAJUKAN"},
    	        {"value":"S", "display":"DISETUJUI"},
    	        {"value":"T", "display":"DITETAPKAN"},
    	        {"value":"C", "display":"DIBATALKAN"}
    	    ]
    	});
		/* STORE end */
		 
		var NOCUTI_field = Ext.create('Ext.form.field.Text', {
			itemId: 'NOCUTI_field',
			name: 'NOCUTI', 
			fieldLabel: 'NOCUTI',
			//allowBlank: false,
			//maxLength: 7,
			emptyText: 'Auto',
			readOnly: true
		});
		var KODEUNIT_field = Ext.create('Ext.form.field.Hidden', {
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
		var NIKATASANC1_field = Ext.create('Ext.form.field.ComboBox', {
			itemId : 'NIKATASANC1_field',
			name: 'NIKATASAN1', 
			fieldLabel: 'PEMOHON',
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
		var NIKATASANC2_field = Ext.create('Ext.form.field.ComboBox', {
			itemId : 'NIKATASANC2_field',
			name: 'NIKATASAN2', 
			fieldLabel: 'DISETUJUI',
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
		var NIKATASANC3_field = Ext.create('Ext.form.field.ComboBox', {
			name: 'NIKATASAN3', 
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
			itemId : 'NIKHR_field',
			name: 'NIKHR', 
			fieldLabel: 'DITETAPKAN',
			allowBlank : false,
			typeAhead    : true,
			triggerAction: 'all',
			selectOnFocus: true,
            loadingText  : 'Searching...',
			displayField: 'NAMAKAR',
			store: personalia_store,
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
		var TGLATASANC1_field = Ext.create('Ext.form.field.Date', {
			name: 'TGLATASAN1', 
			format: 'Y-m-d H:i:s',
			fieldLabel: 'TGL MOHON',
			readOnly: true
		});
		var TGLATASANC2_field = Ext.create('Ext.form.field.Date', {
			name: 'TGLATASAN2', 
			format: 'Y-m-d H:i:s',
			fieldLabel: 'TGL SETUJU',
			readOnly: true
		});
		var TGLATASANC3_field = Ext.create('Ext.form.field.Date', {
			name: 'TGLATASAN3', 
			format: 'Y-m-d',
			fieldLabel: 'TGLATASAN3',
			readOnly: true
		});
		var TGLHR_field = Ext.create('Ext.form.field.Date', {
			name: 'TGLHR', 
			format: 'Y-m-d H:i:s',
			fieldLabel: 'TGL TETAP/BATAL',
			readOnly: true
		});
		
		var STATUSCUTI_field = Ext.create('Ext.form.field.ComboBox', {
			itemId: 'STATUSCUTI_field',
			name: 'STATUSCUTI', 
			fieldLabel: 'STATUS CUTI',
			store: STATUSCUTI_store,
			queryMode: 'local',
			tpl: Ext.create('Ext.XTemplate',
				'<tpl for=".">',
					'<div class="x-boundlist-item">{value} - {display}</div>',
				'</tpl>'
			),
			displayTpl: Ext.create('Ext.XTemplate',
				'<tpl for=".">',
					'{value} - {display}',
				'</tpl>'
			),
			value : 'A',
			valueField: 'value',
			readOnly : true,
			listeners: {
				'select': function(combo, records, eOpts){
					/*Ext.Ajax.request({
						url: 'c_permohonancuti/setStatusCuti',
						params: {
							NOCUTI: NOCUTI_field.getValue(),
							STATUSCUTI: records[0].data.value
						}
					});*/
				}
			}
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
            items: [NOCUTI_field,KODEUNIT_field,NIKATASANC1_field,TGLATASANC1_field,NIKATASANC2_field,TGLATASANC2_field,NIKHR_field,TGLHR_field,STATUSCUTI_field,USERNAME_field],
			
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