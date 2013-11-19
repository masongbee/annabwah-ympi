Ext.define('YMPI.view.TRANSAKSI.v_rinciancuti_form', {
	extend	: 'Ext.form.Panel',
	
	alias	: 'widget.v_rinciancuti_form',
	
	region:'east',
	id: 'east-region-container',
	
	title		: 'Create/Update rinciancuti',
    bodyPadding	: 5,
    autoScroll	: true,
    
    initComponent: function(){
		/* STORE start */
		var nik_store = Ext.create('YMPI.store.s_karyawan', {
			autoLoad: true
		});
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
		var NOURUT_field = Ext.create('Ext.form.field.Number', {
			itemId: 'NOURUT_field',
			name: 'NOURUT', /* column name of table */
			fieldLabel: 'NOURUT',
			allowBlank: false /* jika primary_key */,
			maxLength: 11 /* length of column name */});
		var NIK_field = Ext.create('Ext.form.ComboBox', {
			name: 'NIK',
			fieldLabel: 'NIK',
			store: nik_store,
			queryMode: 'remote',
			displayField:'NAMAKAR',
			valueField: 'NIK',
	        typeAhead: false,
	        loadingText: 'Searching...',
			pageSize:15,
	        hideTrigger: false,
			allowBlank: false,
	        tpl: Ext.create('Ext.XTemplate',
                '<tpl for=".">',
                    '<div class="x-boundlist-item">[<b>{NIK}</b>] - {NAMAKAR}</div>',
                '</tpl>'
            ),
            // template for the content inside text field
            displayTpl: Ext.create('Ext.XTemplate',
                '<tpl for=".">',
                	'[{NIK}] - {NAMAKAR}',
                '</tpl>'
            ),
	        itemSelector: 'div.search-item',
			triggerAction: 'all',
			lazyRender:true,
			listClass: 'x-combo-list-small',
			anchor:'100%',
			forceSelection:true
		});
		var JENISABSEN_field = Ext.create('Ext.form.field.Text', {
			name: 'JENISABSEN', /* column name of table */
			fieldLabel: 'JENISABSEN',
			maxLength: 2 /* length of column name */
		});
		var LAMA_field = Ext.create('Ext.form.field.Number', {
			name: 'LAMA', /* column name of table */
			fieldLabel: 'LAMA',
			maxLength: 11 /* length of column name */
		});
		var TGLMULAI_field = Ext.create('Ext.form.field.Date', {
			name: 'TGLMULAI', /* column name of table */
			format: 'Y-m-d',
			fieldLabel: 'TGLMULAI'
		});
		var TGLSAMPAI_field = Ext.create('Ext.form.field.Date', {
			name: 'TGLSAMPAI', /* column name of table */
			format: 'Y-m-d',
			fieldLabel: 'TGLSAMPAI'
		});
		var SISACUTI_field = Ext.create('Ext.form.field.Number', {
			name: 'SISACUTI', /* column name of table */
			fieldLabel: 'SISACUTI',
			maxLength: 11 /* length of column name */
		});
		var STATUSCUTI_field = Ext.create('Ext.form.field.Text', {
			name: 'STATUSCUTI', /* column name of table */
			fieldLabel: 'STATUSCUTI',
			maxLength: 1 /* length of column name */
		});		
        Ext.apply(this, {
            fieldDefaults: {
                labelAlign: 'right',
                labelWidth: 120,
                msgTarget: 'qtip',
				anchor: '100%'
            },
			defaultType: 'textfield',
            items: [NOCUTI_field,NOURUT_field,NIK_field,JENISABSEN_field,LAMA_field,TGLMULAI_field,TGLSAMPAI_field,SISACUTI_field,STATUSCUTI_field],
			
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