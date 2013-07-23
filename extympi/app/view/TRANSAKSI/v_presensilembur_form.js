Ext.define('YMPI.view.TRANSAKSI.v_presensilembur_form', {
	extend	: 'Ext.form.Panel',
	
	alias	: 'widget.v_presensilembur_form',
	
	region:'east',
	id: 'east-region-container',
	
	title		: 'Create/Update presensilembur',
    bodyPadding	: 5,
    autoScroll	: true,
    
    initComponent: function(){
    	/*
		 * Deklarasi variable setiap field
		 */
		 
		var NIK_field = Ext.create('Ext.form.field.Text', {
			itemId: 'NIK_field',
			name: 'NIK', /* column name of table */
			fieldLabel: 'NIK',
			allowBlank: false /* jika primary_key */,
			maxLength: 10 /* length of column name */
		});
		var TJMASUK_field = Ext.create('Ext.form.field.Date', {
			itemId: 'TJMASUK_field',
			name: 'TJMASUK', /* column name of table */
			fieldLabel: 'TJMASUK',
			format: 'Y-m-d',
			allowBlank: false /* jika primary_key */
		});
		var NOLEMBUR_field = Ext.create('Ext.form.field.Text', {
			name: 'NOLEMBUR', /* column name of table */
			fieldLabel: 'NOLEMBUR',
			maxLength: 7 /* length of column name */
		});
		var NOURUT_field = Ext.create('Ext.form.field.Number', {
			name: 'NOURUT', /* column name of table */
			fieldLabel: 'NOURUT',
			maxLength: 11 /* length of column name */
		});
		var JENISLEMBUR_field = Ext.create('Ext.form.field.Text', {
			name: 'JENISLEMBUR', /* column name of table */
			fieldLabel: 'JENISLEMBUR',
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
            items: [NIK_field,TJMASUK_field,NOLEMBUR_field,NOURUT_field,JENISLEMBUR_field],
			
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