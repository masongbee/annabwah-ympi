Ext.define('YMPI.view.TRANSAKSI.v_permohonanijin_form', {
	extend	: 'Ext.form.Panel',
	
	alias	: 'widget.v_permohonanijin_form',
	
	region:'east',
	id: 'east-region-container',
	
	title		: 'Create/Update permohonanijin',
    bodyPadding	: 5,
    autoScroll	: true,
    
    initComponent: function(){
		var me = this;
		/* STORE start */	
		var nik_store = Ext.create('YMPI.store.s_karyawan',{autoLoad:true});
		
		var AMBILCUTI_store = Ext.create('Ext.data.Store', {
    	    fields: ['value', 'display'],
    	    data : [
    	        {"value":"Y", "display":"YA"},
    	        {"value":"T", "display":"TIDAK"}
    	    ]
    	});
		var KEMBALI_store = Ext.create('Ext.data.Store', {
    	    fields: ['value', 'display'],
    	    data : [
    	        {"value":"Y", "display":"YA"},
    	        {"value":"T", "display":"TIDAK"}
    	    ]
    	});
		
		var STATUSIJIN_store = Ext.create('Ext.data.Store', {
    	    fields: ['value', 'display'],
    	    data : [
    	        {"value":"A", "display":"DIAJUKAN"},
    	        {"value":"T", "display":"DITETAPKAN"},
    	        {"value":"C", "display":"DIBATALKAN"}
    	    ]
    	});
		
		var jenisabsen_store = Ext.create('Ext.data.Store', {
			fields: [
                {name: 'JENISABSEN', type: 'string', mapping: 'JENISABSEN'},
                {name: 'KETERANGAN', type: 'string', mapping: 'KETERANGAN'}
            ],
			proxy: {
				type: 'ajax',
				url: 'c_permohonanijin/get_jenisabsen',
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
		 
		var NOIJIN_field = Ext.create('Ext.form.field.Text', {
			itemId: 'NOIJIN_field',
			name: 'NOIJIN', 
			fieldLabel: 'NOIJIN',
			maxLength: 7,
			allowBlank: false,
			style : {textTransform: "uppercase"},
			enableKeyEvents: true,
			listeners: {
				'change': function(field, newValue, oldValue){
					field.setValue(newValue.toUpperCase());
				}
			}
		});
		var NIK_field = Ext.create('Ext.form.field.ComboBox', {
			itemId : 'NIK_field',
			inputId : 'NIK',
			name: 'NIK', 
			fieldLabel: 'NIK',
			allowBlank : false,
			store: nik_store,
			queryMode: 'local',
			tpl: Ext.create('Ext.XTemplate',
				'<tpl for=".">',
					'<div class="x-boundlist-item">{NIK} - {NAMAKAR}</div>',
				'</tpl>'
			),
			displayTpl: Ext.create('Ext.XTemplate',
				'<tpl for=".">',
					'{NIK}',
				'</tpl>'
			),
			valueField: 'NIK',
			enableKeyEvents: true,
			listeners: {
				'change': function(editor, e){
					if(editor.value != '')
					{
						if(Ext.get('NIK') != null)
						{
							var sisa=0;
							Ext.Ajax.request({
								url: 'c_permohonanijin/getSisa',
								params: {
									JENIS: 'SISACUTI',
									KOLOM: '',
									KEY: Ext.get('NIK').dom.value
								},
								success: function(response){
									var msg = Ext.decode(response.responseText);
									//console.info(msg);
									if(msg.data != '')
									{
										Ext.get('SISA').dom.value = msg.data[0].SISACUTI;
										me.down('#AMBILCUTI_field').setReadOnly(false);
										//panelDetail.getForm().findField('QUANTITY').setMaxValue(msg.data[0].TERIMAQ);
									}
									else
									{
										Ext.get('SISA').dom.value = sisa;
										me.down('#AMBILCUTI_field').setReadOnly(true);
										//panelDetail.getForm().findField('QUANTITY').setMaxValue(sisa);
									}
								}
							});
						}
					}
				}
			}
		});
		var JENISABSEN_field = Ext.create('Ext.form.field.ComboBox', {
			itemId : 'JENISABSEN_field',
			name: 'JENISABSEN', 
			fieldLabel: 'JENISABSEN',
			maxLength: 2,
			store: jenisabsen_store,
			queryMode: 'local',
			tpl: Ext.create('Ext.XTemplate',
				'<tpl for=".">',
					'<div class="x-boundlist-item">{JENISABSEN} - {KETERANGAN}</div>',
				'</tpl>'
			),
			displayTpl: Ext.create('Ext.XTemplate',
				'<tpl for=".">',
					'{JENISABSEN}',
				'</tpl>'
			),
			valueField: 'JENISABSEN',
			enableKeyEvents: true,
			listeners: {
				'change': function(editor, e){
					if(editor.value != 'IP')
					{
						me.down('#JAMDARI_field').setReadOnly(true);
						me.down('#JAMSAMPAI_field').setReadOnly(true);
						me.down('#AMBILCUTI_field').setReadOnly(false);
					}
					else
					{
						me.down('#JAMDARI_field').setReadOnly(false);
						me.down('#JAMSAMPAI_field').setReadOnly(false);
						me.down('#AMBILCUTI_field').setReadOnly(true);
					}
				}
			}
		});
		var TANGGAL_field = Ext.create('Ext.form.field.Date', {
			itemId : 'TANGGAL_field',
			name: 'TANGGAL', 
			format: 'Y-m-d',
			fieldLabel: 'TANGGAL'
		});
		var JAMDARI_field = Ext.create('Ext.form.field.Time', {
			itemId : 'JAMDARI_field',
			name: 'JAMDARI', 
			fieldLabel: 'JAMDARI',
			format: 'H:i:s',
			increment:1
		});
		var JAMSAMPAI_field = Ext.create('Ext.form.field.Time', {
			itemId : 'JAMSAMPAI_field',
			name: 'JAMSAMPAI', 
			fieldLabel: 'JAMSAMPAI',
			format: 'H:i:s',
			increment:1
		});
		var KEMBALI_field = Ext.create('Ext.form.field.ComboBox', {
			name: 'KEMBALI', 
			fieldLabel: 'KEMBALI',
			store: KEMBALI_store,
			queryMode: 'local',
			tpl: Ext.create('Ext.XTemplate',
				'<tpl for=".">',
					'<div class="x-boundlist-item">{display}</div>',
				'</tpl>'
			),
			displayTpl: Ext.create('Ext.XTemplate',
				'<tpl for=".">',
					'{display}',
				'</tpl>'
			),
			valueField: 'value',
			flex: 1,
			//readOnly: true,
			allowBlank: true
		});
		/*var AMBILCUTI_field = Ext.create('Ext.form.field.Number', {
			name: 'AMBILCUTI',
			fieldLabel: 'AMBILCUTI',
			maxLength: 1
		});*/
		
		var AMBILCUTI_field = Ext.create('Ext.form.FieldContainer',{
			layout: 'hbox',
			defaultType: 'textfield',
			items: [{
				xtype: 'combobox',
				itemId : 'AMBILCUTI_field',
				fieldLabel: 'AMBILCUTI',
				//inputId : 'QUANTITY',
				name: 'AMBILCUTI',
				//labelWidth: 150,
				store: AMBILCUTI_store,
				queryMode: 'local',
				tpl: Ext.create('Ext.XTemplate',
					'<tpl for=".">',
						'<div class="x-boundlist-item">{display}</div>',
					'</tpl>'
				),
				displayTpl: Ext.create('Ext.XTemplate',
					'<tpl for=".">',
						'{display}',
					'</tpl>'
				),
				valueField: 'value',
				flex: 1,
				//readOnly: true,
				allowBlank: true
			},{
				xtype: 'splitter'
			},{
				xtype: 'splitter'
			},{
				fieldLabel: 'SISA CUTI',
				inputId : 'SISA',
				name: 'SISA',
				//labelWidth: 50,
				flex: 1,
				maxLength : 5,
				readOnly: true,
				allowBlank: true
			}]
		});
		
		/*var DIAGNOSA_field = Ext.create('Ext.form.field.Text', {
			name: 'DIAGNOSA', 
			fieldLabel: 'DIAGNOSA',
			maxLength: 20 
		});
		var TINDAKAN_field = Ext.create('Ext.form.field.Text', {
			name: 'TINDAKAN', 
			fieldLabel: 'TINDAKAN',
			maxLength: 20 
		});
		var ANJURAN_field = Ext.create('Ext.form.field.Text', {
			name: 'ANJURAN', 
			fieldLabel: 'ANJURAN',
			maxLength: 20 
		});
		var PETUGASKLINIK_field = Ext.create('Ext.form.field.Text', {
			name: 'PETUGASKLINIK', 
			fieldLabel: 'PETUGASKLINIK',
			maxLength: 20 
		});
		var NIKATASAN1_field = Ext.create('Ext.form.field.ComboBox', {
			name: 'NIKATASAN1', 
			fieldLabel: 'NIKATASAN1',
			allowBlank : false,
			store: nik_store,
			queryMode: 'local',
			tpl: Ext.create('Ext.XTemplate',
				'<tpl for=".">',
					'<div class="x-boundlist-item">{NIK} - {NAMAKAR}</div>',
				'</tpl>'
			),
			displayTpl: Ext.create('Ext.XTemplate',
				'<tpl for=".">',
					'{NIK}',
				'</tpl>'
			),
			valueField: 'NIK'
		});*/
		
		var NIKATASAN1_field = Ext.create('Ext.form.field.Text', {
			itemId: 'NIKATASAN1_field',
			name: 'NIKATASAN1', 
			fieldLabel: 'NIKATASAN1',
			allowBlank : false,
			//valueField : user_nik,
			readOnly: true
		});
		
		var STATUSIJIN_field = Ext.create('Ext.form.field.ComboBox', {
			itemId: 'STATUSIJIN_field',
			name: 'STATUSIJIN', 
			fieldLabel: 'STATUS IJIN',
			store: STATUSIJIN_store,
			queryMode: 'local',
			tpl: Ext.create('Ext.XTemplate',
				'<tpl for=".">',
					'<div class="x-boundlist-item">{value} - {display}</div>',
				'</tpl>'
			),
			displayTpl: Ext.create('Ext.XTemplate',
				'<tpl for=".">',
					'{value}',
				'</tpl>'
			),
			valueField: 'value',
			readOnly: true
		});
		
		var NIKPERSONALIA_field = Ext.create('Ext.form.field.ComboBox', {
			itemId : 'NIKPERSONALIA_field',
			name: 'NIKPERSONALIA', 
			fieldLabel: 'NIKPERSONALIA',
			allowBlank : false,
			store: nik_store,
			queryMode: 'local',
			tpl: Ext.create('Ext.XTemplate',
				'<tpl for=".">',
					'<div class="x-boundlist-item">{NIK} - {NAMAKAR}</div>',
				'</tpl>'
			),
			displayTpl: Ext.create('Ext.XTemplate',
				'<tpl for=".">',
					'{NIK}',
				'</tpl>'
			),
			valueField: 'NIK'
		});
		/*var NIKGA_field = Ext.create('Ext.form.field.ComboBox', {
			name: 'NIKGA', 
			fieldLabel: 'NIKGA',
			allowBlank : false,
			store: nik_store,
			queryMode: 'local',
			tpl: Ext.create('Ext.XTemplate',
				'<tpl for=".">',
					'<div class="x-boundlist-item">{NIK} - {NAMAKAR}</div>',
				'</tpl>'
			),
			displayTpl: Ext.create('Ext.XTemplate',
				'<tpl for=".">',
					'{NIK}',
				'</tpl>'
			),
			valueField: 'NIK'
		});
		var NIKDRIVER_field = Ext.create('Ext.form.field.ComboBox', {
			name: 'NIKDRIVER', 
			fieldLabel: 'NIKDRIVER',
			allowBlank : false,
			store: nik_store,
			queryMode: 'local',
			tpl: Ext.create('Ext.XTemplate',
				'<tpl for=".">',
					'<div class="x-boundlist-item">{NIK} - {NAMAKAR}</div>',
				'</tpl>'
			),
			displayTpl: Ext.create('Ext.XTemplate',
				'<tpl for=".">',
					'{NIK}',
				'</tpl>'
			),
			valueField: 'NIK'
		});
		var NIKSECURITY_field = Ext.create('Ext.form.field.ComboBox', {
			name: 'NIKSECURITY', 
			fieldLabel: 'NIKSECURITY',
			allowBlank : false,
			store: nik_store,
			queryMode: 'local',
			tpl: Ext.create('Ext.XTemplate',
				'<tpl for=".">',
					'<div class="x-boundlist-item">{NIK} - {NAMAKAR}</div>',
				'</tpl>'
			),
			displayTpl: Ext.create('Ext.XTemplate',
				'<tpl for=".">',
					'{NIK}',
				'</tpl>'
			),
			valueField: 'NIK'
		});*/
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
            items: [NOIJIN_field,NIK_field,JENISABSEN_field,TANGGAL_field,JAMDARI_field,JAMSAMPAI_field,KEMBALI_field,AMBILCUTI_field,NIKATASAN1_field,NIKPERSONALIA_field,STATUSIJIN_field,USERNAME_field],
			
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