Ext.define('YMPI.view.LAPORAN.v_lappenugasankar_form', {
	extend	: 'Ext.form.Panel',
	
	alias	: 'widget.v_lappenugasankar_form',
	
	// title		: 'Filter',
    bodyPadding	: 5,
    autoScroll	: true,
	//comboFilter	: [],
    
    initComponent: function(){
		var me = this;

		/*
		 * Deklarasi variable setiap field
		 */
		var BULAN_field = Ext.create('Ext.form.field.Month', {
			name: 'BULAN',
			fieldLabel: 'Per Bulan',
			format: 'F, Y',
			submitFormat: 'Y-m-d',
			value: new Date(),
			allowBlank: false,
			listeners: {
				select: function(field, value, e){
					TGLMULAI_field.allowBlank = true;
					TGLSAMPAI_field.allowBlank = true;
					TGLMULAI_field.reset();
					TGLSAMPAI_field.reset();
					TGLMULAI_field.setValue(null);
					TGLSAMPAI_field.setValue(null);
				}
			}
		});
		var TGLMULAI_field = Ext.create('Ext.form.field.Date', {
			itemId: 'TGLMULAI_field',
			name: 'TGLMULAI',
			allowBlank : true,
			format: 'Y-m-d',
			vtype: 'daterange',
			endDateField: 'TGLSAMPAI_field',
			listeners: {
				select: function(field, value, e){
					field.allowBlank = false;
					TGLSAMPAI_field.allowBlank = false;
					BULAN_field.allowBlank = true;
					BULAN_field.reset();
					BULAN_field.setValue(null);
				}
			}
		});
		var TGLSAMPAI_field = Ext.create('Ext.form.field.Date', {
			itemId: 'TGLSAMPAI_field',
			name: 'TGLSAMPAI',
			allowBlank : true,
			format: 'Y-m-d',
			vtype: 'daterange',
			startDateField: 'TGLMULAI_field',
			listeners: {
				select: function(field, value, e){
					field.allowBlank = false;
					TGLMULAI_field.allowBlank = false;
					BULAN_field.allowBlank = true;
					BULAN_field.reset();
					BULAN_field.setValue(null);
				}
			}
		});
		
        Ext.apply(this, {
            fieldDefaults: {
                labelAlign: 'right',
                labelWidth: 120,
                msgTarget: 'qtip',
				anchor: '100%'
            },
			defaultType: 'textfield',
			listeners: {
				afterrender: function(){
					//me.down(detail_tabs).setDisabled(true);
				}
			},
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
						BULAN_field
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
						{
		                	xtype: 'fieldcontainer',
		                	fieldLabel: 'Per Periode',
		                	layout: 'hbox',
							defaults: {
		                		hideLabel: true
		                	},
		                	items: [TGLMULAI_field, {
		                		xtype: 'label',
		                		text: ' s.d ',
		                		margin: '5 5 0 5'
		                	},TGLSAMPAI_field]
		                }
					]
				}]
			}],
			
	        buttons: [{
                iconCls: 'icon-reset',
                text: 'Search',
                action: 'searchall'
            }]
        });
        
        this.callParent();
    }
});