Ext.define('YMPI.view.TRANSAKSI.v_td_evefektivitas_form', {
	extend	: 'Ext.form.Panel',
	
	alias	: 'widget.v_td_evefektivitas_form',
	
	region:'east',
	id: 'east-region-container',
	
	title		: 'Create/Update td_evefektivitas',
    bodyPadding	: 5,
    autoScroll	: true,
    
    initComponent: function(){
    	/*
		 * Deklarasi variable setiap field
		 */
		 
		var TDEVE_ID_field = Ext.create('Ext.form.field.Number', {
			itemId: 'TDEVE_ID_field',
			name: 'TDEVE_ID',
			fieldLabel: 'TDEVE_ID',
			allowBlank: false,
			maxLength: 10,
			hidden: true
		});
		var TDEVE_TDPELATIHAN_ID_field = Ext.create('Ext.form.field.Number', {
			name: 'TDEVE_TDPELATIHAN_ID', /* column name of table */
			fieldLabel: 'PELATIHAN',
			maxLength: 10
		});
		var TDEVE_NIK_field = Ext.create('Ext.form.field.Text', {
			name: 'TDEVE_NIK', /* column name of table */
			fieldLabel: 'PESERTA',
			maxLength: 10
		});
		var TDEVE_SARANEVALUATOR_field = Ext.create('Ext.form.field.TextArea', {
			name: 'TDEVE_SARANEVALUATOR', /* column name of table */
			fieldLabel: 'SARAN DARI EVALUATOR',
			maxLength: 255
		});
		var TDEVE001_field = Ext.create('Ext.form.field.Number', {
			name: 'TDEVE001', /* column name of table */
			fieldLabel: 'Knowledge',
			maxLength: 10
		});
		var TDEVE002_field = Ext.create('Ext.form.field.Number', {
			name: 'TDEVE002', /* column name of table */
			fieldLabel: 'Skill',
			maxLength: 10
		});
		var TDEVE003_field = Ext.create('Ext.form.field.Number', {
			name: 'TDEVE003', /* column name of table */
			fieldLabel: 'Attitude',
			maxLength: 10
		});
		var TDEVE004_field = Ext.create('Ext.form.field.Number', {
			name: 'TDEVE004', /* column name of table */
			fieldLabel: 'Apakah training ini sesuai dengan harapan?',
			maxLength: 10
		});
		var TDEVE005_field = Ext.create('Ext.form.field.Number', {
			name: 'TDEVE005', /* column name of table */
			fieldLabel: 'Implementasi di lapangan yang sudah dicapai',
			maxLength: 10
		});
		var TDEVE006_field = Ext.create('Ext.form.field.Number', {
			name: 'TDEVE006', /* column name of table */
			fieldLabel: 'Cost Down',
			maxLength: 10
		});
		var TDEVE007_field = Ext.create('Ext.form.field.Number', {
			name: 'TDEVE007', /* column name of table */
			fieldLabel: 'Perubahan Sistem',
			maxLength: 10
		});
		var TDEVE008_field = Ext.create('Ext.form.field.Number', {
			name: 'TDEVE008', /* column name of table */
			fieldLabel: 'Perubahan Sikap Kerja',
			maxLength: 10
		});
		var TDEVE009_field = Ext.create('Ext.form.field.Number', {
			name: 'TDEVE009', /* column name of table */
			fieldLabel: 'Belum Ada Kontribusi',
			maxLength: 10
		});
		var TDEVE_ASPEKPEL_fieldset = Ext.create('Ext.form.FieldSet',{
			title : 'ASPEK PELATIHAN',
			layout : 'column',
			frame : false,
			fieldDefaults: {
				labelWidth: 140,
				anchor: '100%'
			},
			items : [{
				columnWidth: 1,
				layout: 'form',
				items:[TDEVE001_field,TDEVE002_field,TDEVE003_field]
			}]
		});
		var TDEVE_KONTRIBUSI_fieldset = Ext.create('Ext.form.FieldSet',{
			title : 'Kontribusi Peserta dalam Mengimplementasikan Hasil Training di Lapangan',
			layout : 'anchor',
			frame : false,
			fieldDefaults: {
				labelWidth: 140,
				anchor: '100%'
			},
			maxWidth: 480,
			items : [{
				layout: 'form',
				items:[TDEVE006_field,TDEVE007_field,TDEVE008_field,TDEVE009_field]
			}]
		});
		var TDEVE_EVEFEK_fieldset = Ext.create('Ext.form.FieldSet',{
			title : 'EVALUASI EFEKTIVITAS (diisi oleh Evaluator)',
			layout : 'column',
			frame : false,
			fieldDefaults: {
				labelWidth: 280,
				anchor: '100%'
			},
			items : [{
				columnWidth: 1,
				layout: 'form',
				items:[TDEVE004_field,TDEVE005_field,TDEVE_KONTRIBUSI_fieldset]
			}]
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
	            xtype: 'container',
	            anchor: '100%',
	            layout: 'hbox',
	            items:[{
	                xtype: 'container',
	                flex: 1,
	                layout: 'anchor',
	                items: [TDEVE_ID_field,TDEVE_TDPELATIHAN_ID_field,TDEVE_NIK_field,TDEVE_ASPEKPEL_fieldset,TDEVE_SARANEVALUATOR_field]
	            }, {
					xtype: 'splitter'
				},{
	                xtype: 'container',
	                flex: 1,
	                layout: 'anchor',
	                items: [TDEVE_EVEFEK_fieldset]
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