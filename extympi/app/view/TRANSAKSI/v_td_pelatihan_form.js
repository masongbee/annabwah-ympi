Ext.define('YMPI.view.TRANSAKSI.v_td_pelatihan_form', {
	extend	: 'Ext.form.Panel',
	
	alias	: 'widget.v_td_pelatihan_form',
	
	region:'east',
	id: 'east-region-container',
	
	title		: 'Create/Update td_pelatihan',
    bodyPadding	: 5,
    autoScroll	: true,
    
    initComponent: function(){
    	/*
		 * Deklarasi variable setiap field
		 */
		 
		var TDPELATIHAN_ID_field = Ext.create('Ext.form.field.Number', {
			itemId: 'TDPELATIHAN_ID_field',
			name: 'TDPELATIHAN_ID',
			fieldLabel: 'TDPELATIHAN_ID',
			allowBlank: true,
			maxLength: 10,
			hidden: true
		});
		var TDPELATIHAN_NO_field = Ext.create('Ext.form.field.Text', {
			name: 'TDPELATIHAN_NO', /* column name of table */
			fieldLabel: 'Dok No',
			maxLength: 25 /* length of column name */
		});
		var TDPELATIHAN_TANGGAL_field = Ext.create('Ext.form.field.Date', {
			name: 'TDPELATIHAN_TANGGAL', /* column name of table */
			format: 'Y-m-d',
			fieldLabel: 'Tanggal'
		});
		/*var TDPELATIHAN_DIBUAT_field = Ext.create('Ext.form.field.Number', {
			name: 'TDPELATIHAN_DIBUAT',
			fieldLabel: 'TDPELATIHAN_DIBUAT',
			maxLength: 10,
			hidden: true
		});*/
		var TDPELATIHAN_DIBUAT_field = Ext.create('Ext.form.ComboBox', {
			name: 'TDPELATIHAN_DIBUAT',
			store: 'YMPI.store.s_karyawan',
			queryMode: 'remote',
			fieldLabel: 'Dibuat',
			displayField:'NAMAKAR',
			valueField: 'NIK',
	        typeAhead: false,
	        loadingText: 'Searching...',
			pageSize:10,
	        hideTrigger: false,
			allowBlank: true,
	        tpl: Ext.create('Ext.XTemplate',
                '<tpl for=".">',
                    '<div class="x-boundlist-item">[<b>{NIK}</b>] - {NAMAKAR}</div>',
                '</tpl>'
            ),
            // template for the content inside text field
            displayTpl: Ext.create('Ext.XTemplate',
                '<tpl for=".">',
                	'[{NIK}] - {NAMAKAR}',
                '</tpl>'
            ),
	        itemSelector: 'div.search-item',
			triggerAction: 'all',
			lazyRender:true,
			listClass: 'x-combo-list-small',
			anchor:'100%',
			forceSelection:true,
			listeners: {
				beforequery: function(queryEvent, e){
					this.getStore().clearFilter();
				},
				select: function(combo, records, e){
					if (records.length == 1) {
						var data = records[0].data;
						TDPELATIHAN_DIBUAT_NAMA_field.setValue(data.NAMAKAR);
					};
				}
			}
		});
		var TDPELATIHAN_DIBUAT_NAMA_field = Ext.create('Ext.form.field.Text', {
			name: 'TDPELATIHAN_DIBUAT_NAMA', /* column name of table */
			fieldLabel: 'Dibuat',
			maxLength: 50,
			hidden: true
		});
		/*var TDPELATIHAN_DIPERIKSA_field = Ext.create('Ext.form.field.Number', {
			name: 'TDPELATIHAN_DIPERIKSA',
			fieldLabel: 'TDPELATIHAN_DIPERIKSA',
			maxLength: 10,
			hidden: true
		});*/
		var TDPELATIHAN_DIPERIKSA_field = Ext.create('Ext.form.ComboBox', {
			name: 'TDPELATIHAN_DIPERIKSA',
			store: 'YMPI.store.s_karyawan',
			queryMode: 'remote',
			fieldLabel: 'Diperiksa',
			displayField:'NAMAKAR',
			valueField: 'NIK',
	        typeAhead: false,
	        loadingText: 'Searching...',
			pageSize:10,
	        hideTrigger: false,
			allowBlank: true,
	        tpl: Ext.create('Ext.XTemplate',
                '<tpl for=".">',
                    '<div class="x-boundlist-item">[<b>{NIK}</b>] - {NAMAKAR}</div>',
                '</tpl>'
            ),
            // template for the content inside text field
            displayTpl: Ext.create('Ext.XTemplate',
                '<tpl for=".">',
                	'[{NIK}] - {NAMAKAR}',
                '</tpl>'
            ),
	        itemSelector: 'div.search-item',
			triggerAction: 'all',
			lazyRender:true,
			listClass: 'x-combo-list-small',
			anchor:'100%',
			forceSelection:true,
			listeners: {
				beforequery: function(queryEvent, e){
					this.getStore().clearFilter();
				},
				select: function(combo, records, e){
					if (records.length == 1) {
						var data = records[0].data;
						TDPELATIHAN_DIPERIKSA_NAMA_field.setValue(data.NAMAKAR);
					};
				}
			}
		});
		var TDPELATIHAN_DIPERIKSA_NAMA_field = Ext.create('Ext.form.field.Text', {
			name: 'TDPELATIHAN_DIPERIKSA_NAMA', /* column name of table */
			fieldLabel: 'Diperiksa',
			maxLength: 50,
			hidden: true
		});
		/*var TDPELATIHAN_DIKETAHUI_field = Ext.create('Ext.form.field.Number', {
			name: 'TDPELATIHAN_DIKETAHUI',
			fieldLabel: 'TDPELATIHAN_DIKETAHUI',
			maxLength: 10,
			hidden: true
		});*/
		var TDPELATIHAN_DIKETAHUI_field = Ext.create('Ext.form.ComboBox', {
			name: 'TDPELATIHAN_DIKETAHUI',
			store: 'YMPI.store.s_karyawan',
			queryMode: 'remote',
			fieldLabel: 'Diketahui',
			displayField:'NAMAKAR',
			valueField: 'NIK',
	        typeAhead: false,
	        loadingText: 'Searching...',
			pageSize:10,
	        hideTrigger: false,
			allowBlank: true,
	        tpl: Ext.create('Ext.XTemplate',
                '<tpl for=".">',
                    '<div class="x-boundlist-item">[<b>{NIK}</b>] - {NAMAKAR}</div>',
                '</tpl>'
            ),
            // template for the content inside text field
            displayTpl: Ext.create('Ext.XTemplate',
                '<tpl for=".">',
                	'[{NIK}] - {NAMAKAR}',
                '</tpl>'
            ),
	        itemSelector: 'div.search-item',
			triggerAction: 'all',
			lazyRender:true,
			listClass: 'x-combo-list-small',
			anchor:'100%',
			forceSelection:true,
			listeners: {
				beforequery: function(queryEvent, e){
					this.getStore().clearFilter();
				},
				select: function(combo, records, e){
					if (records.length == 1) {
						var data = records[0].data;
						TDPELATIHAN_DIKETAHUI_NAMA_field.setValue(data.NAMAKAR);
					};
				}
			}
		});
		var TDPELATIHAN_DIKETAHUI_NAMA_field = Ext.create('Ext.form.field.Text', {
			name: 'TDPELATIHAN_DIKETAHUI_NAMA', /* column name of table */
			fieldLabel: 'Diketahui',
			maxLength: 50,
			hidden: true
		});
		/*var TDPELATIHAN_DISETUJUI01_field = Ext.create('Ext.form.field.Number', {
			name: 'TDPELATIHAN_DISETUJUI01',
			fieldLabel: 'TDPELATIHAN_DISETUJUI01',
			maxLength: 10,
			hidden: true
		});*/
		var TDPELATIHAN_DISETUJUI01_field = Ext.create('Ext.form.ComboBox', {
			name: 'TDPELATIHAN_DISETUJUI01',
			store: 'YMPI.store.s_karyawan',
			queryMode: 'remote',
			fieldLabel: 'Disetujui-01',
			displayField:'NAMAKAR',
			valueField: 'NIK',
	        typeAhead: false,
	        loadingText: 'Searching...',
			pageSize:10,
	        hideTrigger: false,
			allowBlank: true,
	        tpl: Ext.create('Ext.XTemplate',
                '<tpl for=".">',
                    '<div class="x-boundlist-item">[<b>{NIK}</b>] - {NAMAKAR}</div>',
                '</tpl>'
            ),
            // template for the content inside text field
            displayTpl: Ext.create('Ext.XTemplate',
                '<tpl for=".">',
                	'[{NIK}] - {NAMAKAR}',
                '</tpl>'
            ),
	        itemSelector: 'div.search-item',
			triggerAction: 'all',
			lazyRender:true,
			listClass: 'x-combo-list-small',
			anchor:'100%',
			forceSelection:true,
			listeners: {
				beforequery: function(queryEvent, e){
					this.getStore().clearFilter();
				},
				select: function(combo, records, e){
					if (records.length == 1) {
						var data = records[0].data;
						TDPELATIHAN_DISETUJUI01_NAMA_field.setValue(data.NAMAKAR);
					};
				}
			}
		});
		var TDPELATIHAN_DISETUJUI01_NAMA_field = Ext.create('Ext.form.field.Text', {
			name: 'TDPELATIHAN_DISETUJUI01_NAMA', /* column name of table */
			fieldLabel: 'Disetujui-01',
			maxLength: 50,
			hidden: true
		});
		/*var TDPELATIHAN_DISETUJUI02_field = Ext.create('Ext.form.field.Number', {
			name: 'TDPELATIHAN_DISETUJUI02',
			fieldLabel: 'TDPELATIHAN_DISETUJUI02',
			maxLength: 10,
			hidden: true
		});*/
		var TDPELATIHAN_DISETUJUI02_field = Ext.create('Ext.form.ComboBox', {
			name: 'TDPELATIHAN_DISETUJUI02',
			store: 'YMPI.store.s_karyawan',
			queryMode: 'remote',
			fieldLabel: 'Disetujui-02',
			displayField:'NAMAKAR',
			valueField: 'NIK',
	        typeAhead: false,
	        loadingText: 'Searching...',
			pageSize:10,
	        hideTrigger: false,
			allowBlank: true,
	        tpl: Ext.create('Ext.XTemplate',
                '<tpl for=".">',
                    '<div class="x-boundlist-item">[<b>{NIK}</b>] - {NAMAKAR}</div>',
                '</tpl>'
            ),
            // template for the content inside text field
            displayTpl: Ext.create('Ext.XTemplate',
                '<tpl for=".">',
                	'[{NIK}] - {NAMAKAR}',
                '</tpl>'
            ),
	        itemSelector: 'div.search-item',
			triggerAction: 'all',
			lazyRender:true,
			listClass: 'x-combo-list-small',
			anchor:'100%',
			forceSelection:true,
			listeners: {
				beforequery: function(queryEvent, e){
					this.getStore().clearFilter();
				},
				select: function(combo, records, e){
					if (records.length == 1) {
						var data = records[0].data;
						TDPELATIHAN_DISETUJUI02_NAMA_field.setValue(data.NAMAKAR);
					};
				}
			}
		});
		var TDPELATIHAN_DISETUJUI02_NAMA_field = Ext.create('Ext.form.field.Text', {
			name: 'TDPELATIHAN_DISETUJUI02_NAMA', /* column name of table */
			fieldLabel: 'Disetujui-02',
			maxLength: 50,
			hidden: true
		});
		/*var TDPELATIHAN_DISETUJUI03_field = Ext.create('Ext.form.field.Number', {
			name: 'TDPELATIHAN_DISETUJUI03',
			fieldLabel: 'TDPELATIHAN_DISETUJUI03',
			maxLength: 10,
			hidden: true
		});*/
		var TDPELATIHAN_DISETUJUI03_field = Ext.create('Ext.form.ComboBox', {
			name: 'TDPELATIHAN_DISETUJUI03',
			store: 'YMPI.store.s_karyawan',
			queryMode: 'remote',
			fieldLabel: 'Disetujui-03',
			displayField:'NAMAKAR',
			valueField: 'NIK',
	        typeAhead: false,
	        loadingText: 'Searching...',
			pageSize:10,
	        hideTrigger: false,
			allowBlank: true,
	        tpl: Ext.create('Ext.XTemplate',
                '<tpl for=".">',
                    '<div class="x-boundlist-item">[<b>{NIK}</b>] - {NAMAKAR}</div>',
                '</tpl>'
            ),
            // template for the content inside text field
            displayTpl: Ext.create('Ext.XTemplate',
                '<tpl for=".">',
                	'[{NIK}] - {NAMAKAR}',
                '</tpl>'
            ),
	        itemSelector: 'div.search-item',
			triggerAction: 'all',
			lazyRender:true,
			listClass: 'x-combo-list-small',
			anchor:'100%',
			forceSelection:true,
			listeners: {
				beforequery: function(queryEvent, e){
					this.getStore().clearFilter();
				},
				select: function(combo, records, e){
					if (records.length == 1) {
						var data = records[0].data;
						TDPELATIHAN_DISETUJUI03_NAMA_field.setValue(data.NAMAKAR);
					};
				}
			}
		});
		var TDPELATIHAN_DISETUJUI03_NAMA_field = Ext.create('Ext.form.field.Text', {
			name: 'TDPELATIHAN_DISETUJUI03_NAMA', /* column name of table */
			fieldLabel: 'Disetujui-03',
			maxLength: 50,
			hidden: true
		});
		var TDPELATIHAN_TDTRAINING_ID_field = Ext.create('Ext.form.field.Number', {
			name: 'TDPELATIHAN_TDTRAINING_ID', /* column name of table */
			fieldLabel: 'TDPELATIHAN_TDTRAINING_ID',
			maxLength: 10,
			hidden: true
		});
		var TDPELATIHAN_TDTRAINING_ID_field = Ext.create('Ext.form.ComboBox', {
			name: 'TDPELATIHAN_TDTRAINING_ID',
			store: 'YMPI.store.s_td_training',
			queryMode: 'remote',
			fieldLabel: 'Training',
			displayField:'TDTRAINING_NAMA',
			valueField: 'TDTRAINING_ID',
	        typeAhead: false,
	        loadingText: 'Searching...',
			pageSize:10,
	        hideTrigger: false,
			allowBlank: true,
	        tpl: Ext.create('Ext.XTemplate',
                '<tpl for=".">',
                    '<div class="x-boundlist-item">[<b>{TDTRAINING_KODE}</b>] - {TDTRAINING_NAMA}</div>',
                '</tpl>'
            ),
            // template for the content inside text field
            displayTpl: Ext.create('Ext.XTemplate',
                '<tpl for=".">',
                	'[{TDTRAINING_KODE}] - {TDTRAINING_NAMA}',
                '</tpl>'
            ),
	        itemSelector: 'div.search-item',
			triggerAction: 'all',
			lazyRender:true,
			listClass: 'x-combo-list-small',
			anchor:'100%',
			forceSelection:true,
			listeners: {
				beforequery: function(queryEvent, e){
					this.getStore().clearFilter();
				},
				select: function(combo, records, e){
					if (records.length == 1) {
						var data = records[0].data;
						TDPELATIHAN_TDTRAINING_NAMA_field.setValue(data.TDTRAINING_NAMA);
						TDPELATIHAN_TDKELOMPOK_ID_field.setValue(data.TDTRAINING_TDKELOMPOK_ID);
						TDPELATIHAN_TDKELOMPOK_NAMA_field.setValue(data.TDTRAINING_TDKELOMPOK_NAMA);
						TDPELATIHAN_TDTRAINING_TUJUAN_field.setValue(data.TDTRAINING_TUJUAN);
						TDPELATIHAN_TDTRAINING_JENIS_field.setValue(data.TDTRAINING_JENIS);
						TDPELATIHAN_TDTRAINING_SIFAT_field.setValue(data.TDTRAINING_SIFAT);
					};
				}
			}
		});
		var TDPELATIHAN_TDTRAINING_NAMA_field = Ext.create('Ext.form.field.Text', {
			name: 'TDPELATIHAN_TDTRAINING_NAMA', /* column name of table */
			fieldLabel: 'Nama Training',
			maxLength: 255,
			hidden: true
		});
		var TDPELATIHAN_TDKELOMPOK_ID_field = Ext.create('Ext.form.field.Number', {
			name: 'TDPELATIHAN_TDKELOMPOK_ID', /* column name of table */
			fieldLabel: 'TDPELATIHAN_TDKELOMPOK_ID',
			maxLength: 10,
			hidden: true
		});
		var TDPELATIHAN_TDKELOMPOK_NAMA_field = Ext.create('Ext.form.field.Text', {
			name: 'TDPELATIHAN_TDKELOMPOK_NAMA', /* column name of table */
			fieldLabel: 'Kelompok',
			maxLength: 255 /* length of column name */
		});
		var TDPELATIHAN_TDTRAINING_TUJUAN_field = Ext.create('Ext.form.field.Text', {
			name: 'TDPELATIHAN_TDTRAINING_TUJUAN', /* column name of table */
			fieldLabel: 'Tujuan Training',
			maxLength: 255 /* length of column name */
		});
		var TDPELATIHAN_TDTRAINING_JENIS_field = Ext.create('Ext.form.field.Text', {
			name: 'TDPELATIHAN_TDTRAINING_JENIS', /* column name of table */
			fieldLabel: 'Jenis Training'
		});
		var TDPELATIHAN_TDTRAINING_SIFAT_field = Ext.create('Ext.form.field.Text', {
			name: 'TDPELATIHAN_TDTRAINING_SIFAT', /* column name of table */
			fieldLabel: 'Sifat Training'
		});
		var TDPELATIHAN_PESERTA_field = Ext.create('Ext.form.field.Text', {
			name: 'TDPELATIHAN_PESERTA', /* column name of table */
			fieldLabel: 'Peserta',
			maxLength: 255 /* length of column name */
		});
		var TDPELATIHAN_PESERTA_JUMLAH_field = Ext.create('Ext.form.field.Number', {
			name: 'TDPELATIHAN_PESERTA_JUMLAH', /* column name of table */
			fieldLabel: 'Jumlah Peserta',
			maxLength: 11 /* length of column name */
		});
		var TDPELATIHAN_DURASI_field = Ext.create('Ext.form.field.Number', {
			name: 'TDPELATIHAN_DURASI', /* column name of table */
			fieldLabel: 'Durasi Training (Jam)',
			maxLength: 11 /* length of column name */
		});
		var TDPELATIHAN_BIAYA_PLAN_field = Ext.create('Ext.ux.form.NumericField', {
			name: 'TDPELATIHAN_BIAYA_PLAN', /* column name of table */
			fieldLabel: 'Biaya (Plan)',
			useThousandSeparator: true,
			decimalPrecision: 2,
			alwaysDisplayDecimals: true,
			currencySymbol: 'Rp',
			thousandSeparator: '.',
			decimalSeparator: ','
		});
		var TDPELATIHAN_BIAYA_AKTUAL_field = Ext.create('Ext.ux.form.NumericField', {
			name: 'TDPELATIHAN_BIAYA_AKTUAL', /* column name of table */
			fieldLabel: 'Biaya (Aktual)',
			useThousandSeparator: true,
			decimalPrecision: 2,
			alwaysDisplayDecimals: true,
			currencySymbol: 'Rp',
			thousandSeparator: '.',
			decimalSeparator: ','
		});
		var TDPELATIHAN_BIAYA_BALANCE_field = Ext.create('Ext.ux.form.NumericField', {
			name: 'TDPELATIHAN_BIAYA_BALANCE', /* column name of table */
			fieldLabel: 'Balance',
			useThousandSeparator: true,
			decimalPrecision: 2,
			alwaysDisplayDecimals: true,
			currencySymbol: 'Rp',
			thousandSeparator: '.',
			decimalSeparator: ',',
			readOnly: true
		});
		var TDPELATIHAN_TDTRAINER_ID_field = Ext.create('Ext.form.field.Number', {
			name: 'TDPELATIHAN_TDTRAINER_ID', /* column name of table */
			fieldLabel: 'TDPELATIHAN_TDTRAINER_ID',
			maxLength: 10,
			hidden: true
		});
		var TDPELATIHAN_TDTRAINER_NAMA_field = Ext.create('Ext.form.field.Text', {
			name: 'TDPELATIHAN_TDTRAINER_NAMA', /* column name of table */
			fieldLabel: 'Trainer',
			maxLength: 255 /* length of column name */
		});
		var TDPELATIHAN_EVREAKSI_field = Ext.create('Ext.form.field.Number', {
			name: 'TDPELATIHAN_EVREAKSI', /* column name of table */
			fieldLabel: 'Ev. Reaksi',
			maxLength: 11 /* length of column name */
		});
		var TDPELATIHAN_EVEFFECTIVITAS_field = Ext.create('Ext.form.field.Number', {
			name: 'TDPELATIHAN_EVEFFECTIVITAS', /* column name of table */
			fieldLabel: 'Ev. Effectivitas',
			maxLength: 11 /* length of column name */
		});
		var TDPELATIHAN_DATE_PLAN_field = Ext.create('Ext.ux.form.HighlightableDatePicker', {
			itemId: 'TDPELATIHAN_DATE_PLAN_field',
			name: 'TDPELATIHAN_DATE_PLAN'
		});
		var TDPELATIHAN_DATE_AKTUAL_field = Ext.create('Ext.ux.form.HighlightableDatePicker', {
			itemId: 'TDPELATIHAN_DATE_AKTUAL_field',
			name: 'TDPELATIHAN_DATE_AKTUAL'
		});
		/*var TDPELATIHAN_DATE_AKTUAL_field = Ext.create('Ext.ux.form.field.MultiDate', {
			itemId: 'TDPELATIHAN_DATE_AKTUAL_field',
			name: 'TDPELATIHAN_DATE_AKTUAL',
			allowBlank: false,
            multiValue: true,
            submitFormat: 'Y-m-d',
            submitRangeSeparator: '/'
		});*/

		var TDPELATIHAN_RENCANA_fieldset = Ext.create('Ext.form.FieldSet',{
			title : 'Rencana',
			layout : 'column',
			frame : false,
			fieldDefaults: {
				labelWidth: 140,
				anchor: '100%'
			},
			items : [{
				columnWidth: 1,
				layout: 'form',
				items:[TDPELATIHAN_BIAYA_PLAN_field
					,{
						xtype: 'fieldcontainer',
						fieldLabel: 'Rencana Pelaksanaan',
						layout: 'hbox',
						/*defaults: {
							hideLabel: true
						},*/
						items: [TDPELATIHAN_DATE_PLAN_field]
					}
				]
			}]
		});
		var TDPELATIHAN_AKTUAL_fieldset = Ext.create('Ext.form.FieldSet',{
			title : 'Realisasi',
			layout : 'column',
			frame : false,
			fieldDefaults: {
				labelWidth: 140,
				anchor: '100%'
			},
			items : [{
				columnWidth: 1,
				layout: 'form',
				items:[TDPELATIHAN_BIAYA_AKTUAL_field
					,{
						xtype: 'fieldcontainer',
						fieldLabel: 'Realisasi Pelaksanaan',
						layout: 'hbox',
						/*defaults: {
							hideLabel: true
						},*/
						items: [TDPELATIHAN_DATE_AKTUAL_field]
					}]
			}]
		});
        Ext.apply(this, {
            fieldDefaults: {
                labelAlign: 'right',
                labelWidth: 155,
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
	                items: [TDPELATIHAN_ID_field,TDPELATIHAN_NO_field,TDPELATIHAN_TANGGAL_field,TDPELATIHAN_DIBUAT_field
		            	,TDPELATIHAN_DIBUAT_NAMA_field,TDPELATIHAN_DIPERIKSA_field,TDPELATIHAN_DIPERIKSA_NAMA_field
		            	,TDPELATIHAN_DIKETAHUI_field,TDPELATIHAN_DIKETAHUI_NAMA_field,TDPELATIHAN_DISETUJUI01_field
		            	,TDPELATIHAN_DISETUJUI01_NAMA_field,TDPELATIHAN_DISETUJUI02_field,TDPELATIHAN_DISETUJUI02_NAMA_field
		            	,TDPELATIHAN_DISETUJUI03_field,TDPELATIHAN_DISETUJUI03_NAMA_field,TDPELATIHAN_TDTRAINING_ID_field
		            	,TDPELATIHAN_TDTRAINING_NAMA_field,TDPELATIHAN_TDKELOMPOK_ID_field,TDPELATIHAN_TDKELOMPOK_NAMA_field]
	            },{
	                xtype: 'container',
	                flex: 1,
	                layout: 'anchor',
	                items: [TDPELATIHAN_TDTRAINING_TUJUAN_field,TDPELATIHAN_TDTRAINING_JENIS_field,TDPELATIHAN_TDTRAINING_SIFAT_field
		            	,TDPELATIHAN_PESERTA_field,TDPELATIHAN_PESERTA_JUMLAH_field,TDPELATIHAN_DURASI_field
		            	,TDPELATIHAN_TDTRAINER_ID_field,TDPELATIHAN_TDTRAINER_NAMA_field,TDPELATIHAN_EVREAKSI_field
		            	,TDPELATIHAN_EVEFFECTIVITAS_field,TDPELATIHAN_BIAYA_BALANCE_field]
	            }]
	        }, {
	            xtype: 'container',
	            anchor: '100%',
	            layout: 'hbox',
	            items:[{
	            	xtype: 'container',
	                flex: 1,
	                layout: 'anchor',
	                items: [TDPELATIHAN_RENCANA_fieldset]
	            },{
	            	xtype: 'container',
	                flex: 1,
	                layout: 'anchor',
	                items: [TDPELATIHAN_AKTUAL_fieldset]
	            }]
	        }/*{
				xtype: 'form',
				bodyStyle: 'border-width: 0px;',
				layout: 'column',
				items: [{
					//left column
					xtype: 'form',
					bodyStyle: 'border-width: 0px;',
					columnWidth:0.49,
					items: [
						TDPELATIHAN_ID_field,TDPELATIHAN_NO_field,TDPELATIHAN_TANGGAL_field,TDPELATIHAN_DIBUAT_field
		            	,TDPELATIHAN_DIBUAT_NAMA_field,TDPELATIHAN_DIPERIKSA_field,TDPELATIHAN_DIPERIKSA_NAMA_field
		            	,TDPELATIHAN_DIKETAHUI_field,TDPELATIHAN_DIKETAHUI_NAMA_field,TDPELATIHAN_DISETUJUI01_field
		            	,TDPELATIHAN_DISETUJUI01_NAMA_field,TDPELATIHAN_DISETUJUI02_field,TDPELATIHAN_DISETUJUI02_NAMA_field
		            	,TDPELATIHAN_DISETUJUI03_field,TDPELATIHAN_DISETUJUI03_NAMA_field,TDPELATIHAN_TDTRAINING_ID_field
		            	,TDPELATIHAN_TDTRAINING_NAMA_field,TDPELATIHAN_TDKELOMPOK_ID_field,TDPELATIHAN_TDKELOMPOK_NAMA_field
		            	,TDPELATIHAN_RENCANA_fieldset
					]
				} ,{
					xtype: 'splitter',
					columnWidth:0.02
				} ,{
					//right column
					xtype: 'form',
					bodyStyle: 'border-width: 0px;',
					columnWidth:0.49,
					buttonAlign: 'right',
					items: [
						TDPELATIHAN_TDTRAINING_TUJUAN_field,TDPELATIHAN_TDTRAINING_JENIS_field,TDPELATIHAN_TDTRAINING_SIFAT_field
		            	,TDPELATIHAN_PESERTA_field,TDPELATIHAN_PESERTA_JUMLAH_field,TDPELATIHAN_DURASI_field
		            	,TDPELATIHAN_TDTRAINER_ID_field,TDPELATIHAN_TDTRAINER_NAMA_field,TDPELATIHAN_EVREAKSI_field
		            	,TDPELATIHAN_EVEFFECTIVITAS_field,TDPELATIHAN_BIAYA_BALANCE_field
		            	,TDPELATIHAN_AKTUAL_fieldset
					]
				}]
			}*/],
			
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