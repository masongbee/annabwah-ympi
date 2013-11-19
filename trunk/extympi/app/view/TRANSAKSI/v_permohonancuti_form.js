Ext.define('YMPI.view.TRANSAKSI.v_permohonancuti_form', {
	extend	: 'Ext.form.Panel',
	
	alias	: 'widget.v_permohonancuti_form',
	
	region:'east',
	id: 'east-region-container',
	
	title		: 'Create/Update permohonancuti',
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
		var KODEUNIT_field = Ext.create('Ext.form.field.Text', {
			name: 'KODEUNIT', /* column name of table */
			fieldLabel: 'KODEUNIT',
			maxLength: 5 /* length of column name */
		});
		var CUTIMASAL_field = Ext.create('Ext.form.field.Text', {
			name: 'CUTIMASAL', /* column name of table */
			fieldLabel: 'CUTIMASAL',
			maxLength: 1 /* length of column name */
		});
		var NIKATASAN1_field = Ext.create('Ext.form.field.Text', {
			name: 'NIKATASAN1', /* column name of table */
			fieldLabel: 'NIKATASAN1',
			maxLength: 10 /* length of column name */
		});
		var NIKATASAN2_field = Ext.create('Ext.form.field.Text', {
			name: 'NIKATASAN2', /* column name of table */
			fieldLabel: 'NIKATASAN2',
			maxLength: 10 /* length of column name */
		});
		var NIKATASAN3_field = Ext.create('Ext.form.field.Text', {
			name: 'NIKATASAN3', /* column name of table */
			fieldLabel: 'NIKATASAN3',
			maxLength: 10 /* length of column name */
		});
		var NIKHR_field = Ext.create('Ext.form.field.Text', {
			name: 'NIKHR', /* column name of table */
			fieldLabel: 'NIKHR',
			maxLength: 10 /* length of column name */
		});
		var TGLATASAN1_field = Ext.create('Ext.form.field.Date', {
			name: 'TGLATASAN1', /* column name of table */
			format: 'Y-m-d',
			fieldLabel: 'TGLATASAN1'
		});
		var TGLATASAN2_field = Ext.create('Ext.form.field.Date', {
			name: 'TGLATASAN2', /* column name of table */
			format: 'Y-m-d',
			fieldLabel: 'TGLATASAN2'
		});
		var TGLATASAN3_field = Ext.create('Ext.form.field.Date', {
			name: 'TGLATASAN3', /* column name of table */
			format: 'Y-m-d',
			fieldLabel: 'TGLATASAN3'
		});
		var TGLHR_field = Ext.create('Ext.form.field.Date', {
			name: 'TGLHR', /* column name of table */
			format: 'Y-m-d',
			fieldLabel: 'TGLHR'
		});
		var STATUSCUTI_field = Ext.create('Ext.form.field.Text', {
			name: 'STATUSCUTI', /* column name of table */
			fieldLabel: 'STATUSCUTI',
			maxLength: 1 /* length of column name */
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
            items: [NOCUTI_field,KODEUNIT_field,CUTIMASAL_field,NIKATASAN1_field,NIKATASAN2_field,NIKATASAN3_field,NIKHR_field,TGLATASAN1_field,TGLATASAN2_field,TGLATASAN3_field,TGLHR_field,STATUSCUTI_field,USERNAME_field],
			
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