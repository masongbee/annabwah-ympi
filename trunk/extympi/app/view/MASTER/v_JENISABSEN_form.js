Ext.define('YMPI.view.MASTER.v_jenisabsen_form', {
	extend	: 'Ext.form.Panel',
	
	alias	: 'widget.v_jenisabsen_form',
	
	region:'east',
	id: 'east-region-container',
	
	title		: 'Create/Update Jenis Absen',
    bodyPadding	: 5,
    autoScroll	: true,
    
    initComponent: function(){
    	/*
		 * Deklarasi variable setiap field
		 */
		 
		var JENISABSEN_field = Ext.create('Ext.form.field.Text', {
			itemId: 'JENISABSEN_field',
			name: 'JENISABSEN', /* column name of table */
			fieldLabel: 'JENISABSEN',
			allowBlank: false /* jika primary_key */,
			maxLength: 2 /* length of column name */
		});
		var KELABSEN_field = Ext.create('Ext.form.field.Text', {
			name: 'KELABSEN', /* column name of table */
			fieldLabel: 'KELABSEN',
			maxLength: 1 /* length of column name */
		});		
		var KETERANGAN_field = Ext.create('Ext.form.field.Text', {
			name: 'KETERANGAN', /* column name of table */
			fieldLabel: 'KETERANGAN',
			maxLength: 20 /* length of column name */
		});			
		var POTONG_field = Ext.create('Ext.form.field.Text', {
			name: 'POTONG', /* column name of table */
			fieldLabel: 'POTONG',
			maxLength: 1 /* length of column name */
		});			
		var INSDISIPLIN_field = Ext.create('Ext.form.field.Text', {
			name: 'INSDISIPLIN', /* column name of table */
			fieldLabel: 'INSDISIPLIN',
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
            items: [JENISABSEN_field,KELABSEN_field,KETERANGAN_field,POTONG_field,INSDISIPLIN_field],
			
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