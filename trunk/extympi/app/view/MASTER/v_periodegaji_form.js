Ext.define('YMPI.view.MASTER.v_periodegaji_form', {
	extend	: 'Ext.form.Panel',
	
	alias	: 'widget.v_periodegaji_form',
	
	region:'east',
	id: 'east-region-container',
	
	title		: 'Create/Update periodegaji',
    bodyPadding	: 5,
    autoScroll	: true,
    
    initComponent: function(){
    	/*
		 * Deklarasi variable setiap field
		 */
		 
		var BULAN_field = Ext.create('Ext.form.field.Text', {
			itemId: 'BULAN_field',
			name: 'BULAN', /* column name of table */
			fieldLabel: 'BULAN',
			allowBlank: false /* jika primary_key */,
			maxLength: 6 /* length of column name */
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
		var POSTING_field = Ext.create('Ext.form.field.Text', {
			name: 'POSTING', /* column name of table */
			fieldLabel: 'POSTING',
			maxLength: 1 /* length of column name */
		});
		var TGLPOSTING_field = Ext.create('Ext.form.field.Date', {
			name: 'TGLPOSTING', /* column name of table */
			format: 'Y-m-d',
			fieldLabel: 'TGLPOSTING'
		});
		var USERNAME_field = Ext.create('Ext.form.field.Text', {
			name: 'USERNAME', /* column name of table */
			fieldLabel: 'USERNAME',
			maxLength: 12 /* length of column name */
		});		
        Ext.apply(this, {
            fieldDefaults: {
                labelAlign: 'right',
                labelWidth: 120,
                msgTarget: 'qtip',
				anchor: '100%'
            },
			defaultType: 'textfield',
            items: [BULAN_field,TGLMULAI_field,TGLSAMPAI_field,POSTING_field,TGLPOSTING_field,USERNAME_field],
			
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