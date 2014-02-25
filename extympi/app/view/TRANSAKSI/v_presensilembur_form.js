Ext.define('YMPI.view.TRANSAKSI.v_presensilembur_form', {
	extend	: 'Ext.form.Panel',
	
	alias	: 'widget.v_presensilembur_form',
	
	region:'east',
	id: 'east-region-container',
	
	title		: 'Create/Update presensilembur',
    bodyPadding	: 5,
    autoScroll	: true,
    
    initComponent: function(){
		var me = this;
    	/*
		 * Deklarasi variable setiap field
		 */
		 
		var NIK_field = Ext.create('Ext.form.field.Text', {
			itemId: 'NIK_field',
			name: 'NIK',
			fieldLabel: 'NIK',
			allowBlank: false,
			enableKeyEvents: true,
			listeners: {
				blur: function(){
					TJMASUK_field.setValue(new Date());
				},
				keypress: function(){
					TJMASUK_field.setValue(new Date());
				},
				specialkey: function(field, e){
					if (e.getKey() == e.ENTER) {
						//this.up().up().down('button[action=save]').fireEvent('click');
						me.down('#save').fireEvent('click');
					}
				}
			},
			maxLength: 10
		});
		
		
		var TJMASUK_field = Ext.create('Ext.form.field.Date', {
			itemId: 'TJMASUK_field',
			name: 'TJMASUK', /* column name of table */
			fieldLabel: 'TJMASUK',
			format: 'Y-m-d H:i:s',
			allowBlank: true,
			value: new Date()
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
            items: [{
				xtype: 'form',
				bodyStyle: 'border-width: 0px;',
				layout: 'column',
				items: [{
					//left column
					xtype: 'form',
					bodyStyle: 'border-width: 0px;',
					columnWidth:0.49,
					items: [
						NIK_field,TJMASUK_field,NOLEMBUR_field
					]
				} ,{
					xtype: 'splitter',
					columnWidth:0.02
				} ,{
					//right column
					xtype: 'form',
					bodyStyle: 'border-width: 0px;',
					columnWidth:0.49,
					items: [
						NOURUT_field,JENISLEMBUR_field
					]
				}]
			}],
			
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