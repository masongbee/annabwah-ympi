Ext.define('YMPI.view.LAPORAN.v_lapkrjkar_form', {
	extend	: 'Ext.form.Panel',
	
	alias	: 'widget.v_lapkrjkar_form',
	
	// title		: 'Filter',
    bodyPadding	: 5,
    autoScroll	: true,
	//comboFilter	: [],
    
    initComponent: function(){
		var me = this;

		/*
		 * Deklarasi variable setiap field
		 */
		var TAHUN1_field = Ext.create('Ext.form.field.Number', {
			name: 'TAHUN1',
			allowBlank : false,
			minLength: 4,
			maxLength: 4
		});
		var TAHUN2_field = Ext.create('Ext.form.field.Number', {
			name: 'TAHUN2',
			allowBlank : true,
			minLength: 4,
			maxLength: 4
		});
		var NIK_field = Ext.create('Ext.form.ComboBox', {
			itemId: 'NIK_field',
			name : 'NIK',
			fieldLabel : 'Karyawan',
			// store : 'MASTER.store.s_m_depo',
			queryMode : 'local',
			displayField : 'NAMAKAR',
			valueField : 'NIK',
			emptyText: 'Kosong = All',
			forceSelection : true,
			allowBlank: false,
			listeners : {
				beforequery: function(queryEvent, e){
					queryEvent.query = new RegExp(queryEvent.query, 'i');
	                queryEvent.forceAll = true;
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
					items: [{
	                	xtype: 'fieldcontainer',
	                	fieldLabel: 'Dari Tahun',
	                	layout: 'hbox',
	                	defaultType: 'textfield',
	                	defaults: {
	                		hideLabel: true
	                	},
	                	items: [TAHUN1_field, {
	                		xtype: 'label',
	                		text: ' s/d Tahun:',
	                		// margin: '10 5 0 5',
	                		padding: '4 5 4 5'
	                	}, TAHUN2_field]
	                }]
				} ,{
					xtype: 'splitter',
					columnWidth:0.02
				} ,{
					//right column
					xtype: 'form',
					bodyStyle: 'border-width: 0px;',
					columnWidth:0.49,
					items: [
						NIK_field
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