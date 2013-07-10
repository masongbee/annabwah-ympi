Ext.define('YMPI.view.MASTER.v_cutitahunan_form', {
	extend	: 'Ext.form.Panel',
	
	alias	: 'widget.v_cutitahunan_form',
	
	region:'east',
	id: 'east-region-container',
	
	title		: 'Create/Update Cuti Tahunan',
    bodyPadding	: 5,
    autoScroll	: true,
    
    initComponent: function(){
		/* STORE start */	
		var nik_store = Ext.create('YMPI.store.s_karyawan');
		
		
		var kompensasi_store = Ext.create('Ext.data.Store', {
    	    fields: ['value', 'display'],
    	    data : [
    	        {"value":"Y", "display":"Ya"},
    	        {"value":"T", "display":"Tidak"}
    	    ]
    	});
		/* STORE end */
		
    	/*
		 * Deklarasi variable setiap field
		 */
		 
		var NIK_field = Ext.create('Ext.form.field.ComboBox', {
			itemId: 'NIK_field',
			name: 'NIK',
			fieldLabel: 'NIK',
			store: nik_store,
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
		var TAHUN_field = Ext.create('Ext.form.field.Number', {
			itemId: 'TAHUN_field',
			name: 'TAHUN', /* column name of table */
			fieldLabel: 'TAHUN',
			allowBlank: false /* jika primary_key */,
			maxLength: 11 /* length of column name */});
		var TANGGAL_field = Ext.create('Ext.form.field.Date', {
			itemId: 'TANGGAL_field',
			name: 'TANGGAL', /* column name of table */
			fieldLabel: 'TANGGAL',
			format: 'Y-m-d',
			allowBlank: false /* jika primary_key */
		});
		var JENISCUTI_field = Ext.create('Ext.form.field.Text', {
			name: 'JENISCUTI', /* column name of table */
			fieldLabel: 'JENISCUTI',
			maxLength: 1 /* length of column name */
		});
		var JMLCUTI_field = Ext.create('Ext.form.field.Number', {
			name: 'JMLCUTI', /* column name of table */
			fieldLabel: 'JMLCUTI',
			maxLength: 11 /* length of column name */
		});
		var SISACUTI_field = Ext.create('Ext.form.field.Number', {
			name: 'SISACUTI', /* column name of table */
			fieldLabel: 'SISACUTI',
			maxLength: 11 /* length of column name */
		});
		var DIKOMPENSASI_field = Ext.create('Ext.form.field.ComboBox', {
			name: 'DIKOMPENSASI', /* column name of table */
			fieldLabel: 'DIKOMPENSASI',
			store: kompensasi_store,
			queryMode: 'local',
			valueField: 'value',
			tpl: Ext.create('Ext.XTemplate',
				'<tpl for=".">',
					'<div class="x-boundlist-item">{value} - {display}</div>',
				'</tpl>'
			),
			displayTpl: Ext.create('Ext.XTemplate',
				'<tpl for=".">',
					'{value} - {display}',
				'</tpl>'
			)
		});
		var USERNAME_field = Ext.create('Ext.form.field.Text', {
			name: 'USERNAME',
			fieldLabel: 'USERNAME',
			readOnly: true,
			valueField: username
		});		
        Ext.apply(this, {
            fieldDefaults: {
                labelAlign: 'right',
                labelWidth: 120,
                msgTarget: 'qtip',
				anchor: '100%'
            },
			defaultType: 'textfield',
            items: [NIK_field,TAHUN_field,TANGGAL_field,JENISCUTI_field,JMLCUTI_field,SISACUTI_field,DIKOMPENSASI_field,USERNAME_field],
			
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