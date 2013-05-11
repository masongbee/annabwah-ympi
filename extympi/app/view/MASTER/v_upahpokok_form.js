Ext.define('YMPI.view.MASTER.v_upahpokok_form', {
	extend	: 'Ext.form.Panel',
	
	alias	: 'widget.v_upahpokok_form',
	
	region:'east',
	id: 'east-region-container',
	
	title		: 'Create/Update upahpokok',
    bodyPadding	: 5,
    autoScroll	: true,
    
    initComponent: function(){
    	/*
		 * Deklarasi variable setiap field
		 */
		 var VALIDFROM_field = Ext.create('Ext.form.field.Date', {
			itemId: 'VALIDFROM_field',
			name: 'VALIDFROM', /* column name of table */
			fieldLabel: 'VALIDFROM',
			allowBlank: false /* jika primary_key */
		});var NOURUT_field = Ext.create('Ext.form.field.Number', {
			itemId: 'NOURUT_field',
			name: 'NOURUT', /* column name of table */
			fieldLabel: 'NOURUT',
			allowBlank: false /* jika primary_key */
		});var GRADE_field = Ext.create('Ext.form.field.Text', {
			name: 'GRADE', /* column name of table */
			fieldLabel: 'GRADE',
			maxLength: 150 /* length of column name */
		});var KODEJAB_field = Ext.create('Ext.form.field.Text', {
			name: 'KODEJAB', /* column name of table */
			fieldLabel: 'KODEJAB',
			maxLength: 150 /* length of column name */
		});var NIK_field = Ext.create('Ext.form.field.Text', {
			name: 'NIK', /* column name of table */
			fieldLabel: 'NIK',
			maxLength: 150 /* length of column name */
		});var RPUPAHPOKOK_field = Ext.create('Ext.ux.form.NumericField', {
			itemId: 'RPUPAHPOKOK_field',
			name: 'RPUPAHPOKOK', /* column name of table */
			fieldLabel: 'RPUPAHPOKOK',
			useThousandSeparator: true,
			decimalPrecision: 2,
			alwaysDisplayDecimals: true,
			currencySymbol: 'Rp',
			thousandSeparator: '.',
			decimalSeparator: ','
		});var USERNAME_field = Ext.create('Ext.form.field.Text', {
			name: 'USERNAME', /* column name of table */
			fieldLabel: 'USERNAME',
			value: username,
			maxLength: 150 /* length of column name */
		});		
        Ext.apply(this, {
            fieldDefaults: {
                labelAlign: 'right',
                labelWidth: 120,
                msgTarget: 'qtip',
				anchor: '100%'
            },
			defaultType: 'textfield',
            items: [VALIDFROM_field,NOURUT_field,GRADE_field,KODEJAB_field,NIK_field,RPUPAHPOKOK_field,USERNAME_field],
			
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