Ext.define('YMPI.view.TRANSAKSI.v_rinciancuti_form', {
	extend	: 'Ext.form.Panel',
	
	alias	: 'widget.v_rinciancuti_form',
	
	region:'east',
	id: 'east-region-container',
	
	title		: 'Create/Update rinciancuti',
    bodyPadding	: 5,
    autoScroll	: true,
    
    initComponent: function(){
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
		var NIK_field = Ext.create('Ext.form.field.Text', {
			name: 'NIK', /* column name of table */
			fieldLabel: 'NIK',
			maxLength: 10 /* length of column name */
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