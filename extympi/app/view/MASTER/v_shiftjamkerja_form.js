Ext.define('YMPI.view.MASTER.v_shiftjamkerja_form', {
	extend	: 'Ext.form.Panel',
	
	alias	: 'widget.v_shiftjamkerja_form',
	
	region:'east',
	id: 'east-region-container',
	
	title		: 'Create/Update shiftjamkerja',
    bodyPadding	: 5,
    autoScroll	: true,
    
    initComponent: function(){
    	/*
		 * Deklarasi variable setiap field
		 */
		 
		var NAMASHIFT_field = Ext.create('Ext.form.field.Text', {
			itemId: 'NAMASHIFT_field',
			name: 'NAMASHIFT', /* column name of table */
			fieldLabel: 'NAMASHIFT',
			allowBlank: false /* jika primary_key */,
			maxLength: 20 /* length of column name */
		});
		var SHIFTKE_field = Ext.create('Ext.form.field.Text', {
			itemId: 'SHIFTKE_field',
			name: 'SHIFTKE', /* column name of table */
			fieldLabel: 'SHIFTKE',
			allowBlank: false /* jika primary_key */,
			maxLength: 1 /* length of column name */
		});
		var JENISHARI_field = Ext.create('Ext.form.field.Text', {
			itemId: 'JENISHARI_field',
			name: 'JENISHARI', /* column name of table */
			fieldLabel: 'JENISHARI',
			allowBlank: false /* jika primary_key */,
			maxLength: 1 /* length of column name */
		});
		var JAMDARI_field = Ext.create('Ext.form.field.Text', {
			name: 'JAMDARI', /* column name of table */
			fieldLabel: 'JAMDARI'
		});
		var JAMSAMPAI_field = Ext.create('Ext.form.field.Text', {
			name: 'JAMSAMPAI', /* column name of table */
			fieldLabel: 'JAMSAMPAI'
		});
		var JAMREHAT1M_field = Ext.create('Ext.form.field.Text', {
			name: 'JAMREHAT1M', /* column name of table */
			fieldLabel: 'JAMREHAT1M'
		});
		var JAMREHAT1S_field = Ext.create('Ext.form.field.Text', {
			name: 'JAMREHAT1S', /* column name of table */
			fieldLabel: 'JAMREHAT1S'
		});
		var JAMREHAT2M_field = Ext.create('Ext.form.field.Text', {
			name: 'JAMREHAT2M', /* column name of table */
			fieldLabel: 'JAMREHAT2M'
		});
		var JAMREHAT2S_field = Ext.create('Ext.form.field.Text', {
			name: 'JAMREHAT2S', /* column name of table */
			fieldLabel: 'JAMREHAT2S'
		});
		var JAMREHAT3M_field = Ext.create('Ext.form.field.Text', {
			name: 'JAMREHAT3M', /* column name of table */
			fieldLabel: 'JAMREHAT3M'
		});
		var JAMREHAT3S_field = Ext.create('Ext.form.field.Text', {
			name: 'JAMREHAT3S', /* column name of table */
			fieldLabel: 'JAMREHAT3S'
		});
		var JAMREHAT4M_field = Ext.create('Ext.form.field.Text', {
			name: 'JAMREHAT4M', /* column name of table */
			fieldLabel: 'JAMREHAT4M'
		});
		var JAMREHAT4S_field = Ext.create('Ext.form.field.Text', {
			name: 'JAMREHAT4S', /* column name of table */
			fieldLabel: 'JAMREHAT4S'
		});		
        Ext.apply(this, {
            fieldDefaults: {
                labelAlign: 'right',
                labelWidth: 120,
                msgTarget: 'qtip',
				anchor: '100%'
            },
			defaultType: 'textfield',
            items: [NAMASHIFT_field,SHIFTKE_field,JENISHARI_field,JAMDARI_field,JAMSAMPAI_field,JAMREHAT1M_field,JAMREHAT1S_field,JAMREHAT2M_field,JAMREHAT2S_field,JAMREHAT3M_field,JAMREHAT3S_field,JAMREHAT4M_field,JAMREHAT4S_field],
			
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