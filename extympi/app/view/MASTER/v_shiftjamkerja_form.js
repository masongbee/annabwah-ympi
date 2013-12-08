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
		
		var shift_store = Ext.create('YMPI.store.s_shift',{autoLoad:true});
		var detilshift_store = Ext.create('YMPI.store.s_detilshift');
		var JENISHARI_store = Ext.create('Ext.data.Store', {
    	    fields: ['value', 'display'],
    	    data : [
    	        {"value":"N", "display":"Non Jum'at"},
    	        {"value":"J", "display":"Jum'at"}
    	    ]
    	});
		
		var SHIFTKE_field = Ext.create('Ext.form.field.ComboBox', {
			itemId: 'SHIFTKE_field',
			name: 'SHIFTKE', /* column name of table */
			fieldLabel: 'SHIFTKE',
			typeAhead    : true,
			triggerAction: 'all',
			selectOnFocus: true,
            loadingText  : 'Searching...',
			store: detilshift_store,
			queryMode: 'local',
			tpl: Ext.create('Ext.XTemplate',
				'<tpl for=".">',
					'<div class="x-boundlist-item">{NAMASHIFT} - {SHIFTKE}</div>',
				'</tpl>'
			),
			displayTpl: Ext.create('Ext.XTemplate',
				'<tpl for=".">',
					'{NAMASHIFT} - {SHIFTKE}',
				'</tpl>'
			),
			displayField: 'SHIFTKE',
			valueField: 'SHIFTKE'
		});
		var NAMASHIFT_field = Ext.create('Ext.form.field.ComboBox', {
			itemId: 'NAMASHIFT_field',
			name: 'NAMASHIFT', /* column name of table */
			fieldLabel: 'NAMASHIFT',
			typeAhead    : true,
			triggerAction: 'all',
			selectOnFocus: true,
            loadingText  : 'Searching...',
			store: shift_store,
			queryMode: 'local',
			tpl: Ext.create('Ext.XTemplate',
				'<tpl for=".">',
					'<div class="x-boundlist-item">{NAMASHIFT}</div>',
				'</tpl>'
			),
			displayTpl: Ext.create('Ext.XTemplate',
				'<tpl for=".">',
					'{NAMASHIFT}',
				'</tpl>'
			),
			displayField: 'NAMASHIFT',
			valueField: 'NAMASHIFT',
			enableKeyEvents: true,
			listeners: {
				'select': function(combo, records){
					console.info(records);
					detilshift_store.load({
						params: {
							NAMASHIFT: records[0].data.NAMASHIFT
						}
					});
					SHIFTKE_field.setReadOnly(false);
				}
			}
		});
		var SHIFTKE_field = Ext.create('Ext.form.field.ComboBox', {
			itemId: 'SHIFTKE_field',
			name: 'SHIFTKE', /* column name of table */
			fieldLabel: 'SHIFTKE',
			typeAhead    : true,
			triggerAction: 'all',
			selectOnFocus: true,
            loadingText  : 'Searching...',
			store: detilshift_store,
			queryMode: 'local',
			tpl: Ext.create('Ext.XTemplate',
				'<tpl for=".">',
					'<div class="x-boundlist-item">{NAMASHIFT} - {SHIFTKE}</div>',
				'</tpl>'
			),
			displayTpl: Ext.create('Ext.XTemplate',
				'<tpl for=".">',
					'{NAMASHIFT} - {SHIFTKE}',
				'</tpl>'
			),
			displayField: 'SHIFTKE',
			valueField: 'SHIFTKE'
		});
		var JENISHARI_field = Ext.create('Ext.form.field.ComboBox', {
			itemId: 'JENISHARI_field',
			name: 'JENISHARI', /* column name of table */
			fieldLabel: 'JENISHARI',
			store: JENISHARI_store,
			queryMode: 'local',
			tpl: Ext.create('Ext.XTemplate',
				'<tpl for=".">',
					'<div class="x-boundlist-item">{value} - {display}</div>',
				'</tpl>'
			),
			displayTpl: Ext.create('Ext.XTemplate',
				'<tpl for=".">',
					'{value} - {display}',
				'</tpl>'
			),
			value : 'N',
			valueField: 'value'
		});
		var JAMDARI_AWAL_field = Ext.create('Ext.form.field.Time', {
			name: 'JAMDARI_AWAL', /* column name of table */
			fieldLabel: 'JAMDARI_AWAL',
			format: 'H:i:s',
			increment: 5
		});
		var JAMDARI_field = Ext.create('Ext.form.field.Time', {
			name: 'JAMDARI', /* column name of table */
			fieldLabel: 'JAMDARI',
			format: 'H:i:s',
			increment: 5
		});
		var JAMDARI_AKHIR_field = Ext.create('Ext.form.field.Time', {
			name: 'JAMDARI_AKHIR', /* column name of table */
			fieldLabel: 'JAMDARI_AKHIR',
			format: 'H:i:s',
			increment: 5
		});
		var JAMSAMPAI_AWAL_field = Ext.create('Ext.form.field.Time', {
			name: 'JAMSAMPAI_AWAL', /* column name of table */
			fieldLabel: 'JAMSAMPAI_AWAL',
			format: 'H:i:s',
			increment: 5
		});
		var JAMSAMPAI_field = Ext.create('Ext.form.field.Time', {
			name: 'JAMSAMPAI', /* column name of table */
			fieldLabel: 'JAMSAMPAI',
			format: 'H:i:s',
			increment: 5
		});
		var JAMSAMPAI_AKHIR_field = Ext.create('Ext.form.field.Time', {
			name: 'JAMSAMPAI_AKHIR', /* column name of table */
			fieldLabel: 'JAMSAMPAI_AKHIR',
			format: 'H:i:s',
			increment: 5
		});
		var JAMREHAT1M_field = Ext.create('Ext.form.field.Time', {
			name: 'JAMREHAT1M', /* column name of table */
			fieldLabel: 'JAMREHAT1M',
			format: 'H:i:s',
			increment: 5
		});
		var JAMREHAT1S_field = Ext.create('Ext.form.field.Time', {
			name: 'JAMREHAT1S', /* column name of table */
			fieldLabel: 'JAMREHAT1S',
			format: 'H:i:s',
			increment: 5
		});
		var JAMREHAT2M_field = Ext.create('Ext.form.field.Time', {
			name: 'JAMREHAT2M', /* column name of table */
			fieldLabel: 'JAMREHAT2M',
			format: 'H:i:s',
			increment: 5
		});
		var JAMREHAT2S_field = Ext.create('Ext.form.field.Time', {
			name: 'JAMREHAT2S', /* column name of table */
			fieldLabel: 'JAMREHAT2S',
			format: 'H:i:s',
			increment: 5
		});
		var JAMREHAT3M_field = Ext.create('Ext.form.field.Time', {
			name: 'JAMREHAT3M', /* column name of table */
			fieldLabel: 'JAMREHAT3M',
			format: 'H:i:s',
			increment: 5
		});
		var JAMREHAT3S_field = Ext.create('Ext.form.field.Time', {
			name: 'JAMREHAT3S', /* column name of table */
			fieldLabel: 'JAMREHAT3S',
			format: 'H:i:s',
			increment: 5
		});
		var JAMREHAT4M_field = Ext.create('Ext.form.field.Time', {
			name: 'JAMREHAT4M', /* column name of table */
			fieldLabel: 'JAMREHAT4M',
			format: 'H:i:s',
			increment: 5
		});
		var JAMREHAT4S_field = Ext.create('Ext.form.field.Time', {
			name: 'JAMREHAT4S', /* column name of table */
			fieldLabel: 'JAMREHAT4S',
			format: 'H:i:s',
			increment: 5
		});		
        Ext.apply(this, {
            fieldDefaults: {
                labelAlign: 'right',
                labelWidth: 120,
                msgTarget: 'qtip',
				anchor: '100%'
            },
			defaultType: 'textfield',
            items: [NAMASHIFT_field,SHIFTKE_field,JENISHARI_field
					,JAMDARI_AWAL_field,JAMDARI_field,JAMDARI_AKHIR_field
					,JAMSAMPAI_AWAL_field,JAMSAMPAI_field,JAMSAMPAI_AKHIR_field
					,JAMREHAT1M_field,JAMREHAT1S_field,JAMREHAT2M_field,JAMREHAT2S_field
					,JAMREHAT3M_field,JAMREHAT3S_field,JAMREHAT4M_field,JAMREHAT4S_field],
			
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