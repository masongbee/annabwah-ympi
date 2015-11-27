Ext.define('YMPI.view.LAPORAN.v_lapjempkar_form', {
	extend	: 'Ext.form.Panel',
	
	alias	: 'widget.v_lapjempkar_form',
	
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
			fieldLabel: 'Bulan',
			format: 'F, Y',
			submitFormat: 'Y-m-d',
			value: new Date(),
			allowBlank: false
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

			// itemId: 'NIK_field',
			// queryMode: 'local',
			// displayField:'NAMAKAR',
			// valueField: 'NIK',
	  //       typeAhead: false,
	  //       loadingText: 'Searching...',
	  //       hideTrigger: false,
			// allowBlank: true,
	  //       tpl: Ext.create('Ext.XTemplate',
   //              '<tpl for=".">',
   //                  '<div class="x-boundlist-item">[<b>{NIK}</b>] - {NAMAKAR}</div>',
   //              '</tpl>'
   //          ),
   //          // template for the content inside text field
   //          displayTpl: Ext.create('Ext.XTemplate',
   //              '<tpl for=".">',
   //              	'[{NIK}] - {NAMAKAR}',
   //              '</tpl>'
   //          ),
	  //       itemSelector: 'div.search-item',
			// triggerAction: 'all',
			// lazyRender:true,
			// listClass: 'x-combo-list-small',
			// anchor:'100%',
			// forceSelection:true
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