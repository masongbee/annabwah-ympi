Ext.define('YMPI.view.MASTER.v_td_training_form', {
	extend	: 'Ext.form.Panel',
	
	alias	: 'widget.v_td_training_form',
	
	region:'east',
	id: 'east-region-container',
	
	title		: 'Create/Update td_training',
    bodyPadding	: 5,
    autoScroll	: true,
    
    initComponent: function(){
    	/* DATA STORE start */
    	var kelompoktraining_store = Ext.create('YMPI.store.s_td_kelompok',{autoLoad:true});
    	/* DATA STORE end */
    	/*
		 * Deklarasi variable setiap field
		 */
		var TDTRAINING_ID_field = Ext.create('Ext.form.field.Number', {
			itemId: 'TDTRAINING_ID_field',
			name: 'TDTRAINING_ID', /* column name of table */
			fieldLabel: 'TDTRAINING_ID',
			allowBlank: true,
			maxLength: 10,
			hidden: true
		});
		var TDTRAINING_KODE_field = Ext.create('Ext.form.field.Text', {
			name: 'TDTRAINING_KODE', /* column name of table */
			fieldLabel: 'KODE',
			maxLength: 5 /* length of column name */
		});
		var TDTRAINING_NAMA_field = Ext.create('Ext.form.field.Text', {
			name: 'TDTRAINING_NAMA', /* column name of table */
			fieldLabel: 'NAMA',
			maxLength: 255 /* length of column name */
		});
		var TDTRAINING_KETERANGAN_field = Ext.create('Ext.form.field.Text', {
			name: 'TDTRAINING_KETERANGAN', /* column name of table */
			fieldLabel: 'KETERANGAN',
			maxLength: 255 /* length of column name */
		});
		var TDTRAINING_TDKELOMPOK_ID_field = Ext.create('Ext.form.field.ComboBox', {
			itemId: 'TDTRAINING_TDKELOMPOK_ID_field',
			name: 'TDTRAINING_TDKELOMPOK_ID', /* column name of table */
			fieldLabel: 'Kelompok',
			typeAhead    : true,
			triggerAction: 'all',
			selectOnFocus: true,
            loadingText  : 'Searching...',
			store: kelompoktraining_store,
			queryMode: 'local',
			tpl: Ext.create('Ext.XTemplate',
				'<tpl for=".">',
					'<div class="x-boundlist-item">{TDKELOMPOK_KODE} - {TDKELOMPOK_NAMA}</div>',
				'</tpl>'
			),
			displayTpl: Ext.create('Ext.XTemplate',
				'<tpl for=".">',
					'{TDKELOMPOK_KODE} - {TDKELOMPOK_NAMA}',
				'</tpl>'
			),
			displayField: 'TDKELOMPOK_NAMA',
			valueField: 'TDKELOMPOK_ID',
			listeners: {
				beforequery: function(queryEvent, e){
					this.getStore().clearFilter();
				},
				select: function(combo, records, e){
					if (records.length == 1) {
						var data = records[0].data;
						TDTRAINING_TDKELOMPOK_NAMA_field.setValue(data.TDKELOMPOK_NAMA);
					};
				}
			}
		});
		var TDTRAINING_TDKELOMPOK_NAMA_field = Ext.create('Ext.form.field.Text', {
			name: 'TDTRAINING_TDKELOMPOK_NAMA', /* column name of table */
			fieldLabel: 'TDTRAINING_TDKELOMPOK_NAMA',
			maxLength: 255,
			readOnly: true,
			hidden: true
		});
		var TDTRAINING_TUJUAN_field = Ext.create('Ext.form.field.Text', {
			name: 'TDTRAINING_TUJUAN', /* column name of table */
			fieldLabel: 'TUJUAN',
			maxLength: 255 /* length of column name */
		});
		var TDTRAINING_JENIS_field = Ext.create('Ext.form.field.ComboBox', {
			name: 'TDTRAINING_JENIS', /* column name of table */
			fieldLabel: 'JENIS',
			typeAhead    : true,
			triggerAction: 'all',
			selectOnFocus: true,
            loadingText  : 'Searching...',
			store: Ext.create('Ext.data.Store', {
			    fields: ['value', 'display'],
			    data : [
			        {"value":"ex", "display":"External"},
			        {"value":"id", "display":"In-House Intra Dept"},
			        {"value":"cd", "display":"In-House Cross Dept"}
			    ]
			}),
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
			displayField: 'display',
			valueField: 'value'
		});
		var TDTRAINING_SIFAT_field = Ext.create('Ext.form.field.ComboBox', {
			name: 'TDTRAINING_SIFAT', /* column name of table */
			fieldLabel: 'SIFAT',
			typeAhead    : true,
			triggerAction: 'all',
			selectOnFocus: true,
            loadingText  : 'Searching...',
			store: Ext.create('Ext.data.Store', {
			    fields: ['value', 'display'],
			    data : [
			        {"value":"wajib", "display":"Wajib"},
			        {"value":"rekomendasi", "display":"Rekomendasi"}
			    ]
			}),
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
			displayField: 'display',
			valueField: 'value'
		});
        Ext.apply(this, {
            fieldDefaults: {
                labelAlign: 'right',
                labelWidth: 120,
                msgTarget: 'qtip',
				anchor: '100%'
            },
			defaultType: 'textfield',
            items: [TDTRAINING_ID_field,TDTRAINING_KODE_field,TDTRAINING_NAMA_field,TDTRAINING_KETERANGAN_field
            	,TDTRAINING_TDKELOMPOK_ID_field,TDTRAINING_TDKELOMPOK_NAMA_field,TDTRAINING_TUJUAN_field,TDTRAINING_JENIS_field
            	,TDTRAINING_SIFAT_field],
			
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