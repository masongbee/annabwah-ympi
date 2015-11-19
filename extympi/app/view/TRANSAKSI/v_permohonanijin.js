Ext.define('YMPI.view.TRANSAKSI.v_permohonanijin', {
	extend: 'Ext.grid.Panel',
	requires: ['YMPI.store.s_permohonanijin'],
	
	title		: 'Absensi',
	itemId		: 'Listpermohonanijin',
	alias       : 'widget.Listpermohonanijin',
	store 		: 's_permohonanijin',
	columnLines : true,
	frame		: false,
	plugins		: 'bufferedrenderer',
	
	margin		: 0,
	selectedIndex : -1,
	
	initComponent: function(){
		var me = this;

		var filtersCfg = {
			ftype: 'filters',
			// encode and local configuration options defined previously for easier reuse
			encode: true, // json encode the filter query
			local: true   // defaults to false (remote filtering)
		};

		/* STORE start */	
		var nik_store = Ext.create('YMPI.store.s_karyawan',{autoLoad:true,pageSize: max_kar});
		
		var AMBILCUTI_store = Ext.create('Ext.data.Store', {
    	    fields: ['value', 'display'],
    	    data : [
    	        {"value":"3", "display":"N/A"},
    	        {"value":"1", "display":"POTONG CUTI"},
    	        {"value":"0", "display":"POTONG GAJI"}
    	    ]
    	});
		var KEMBALI_store = Ext.create('Ext.data.Store', {
    	    fields: ['value', 'display'],
    	    data : [
    	        {"value":"Y", "display":"YA"},
    	        {"value":"T", "display":"TIDAK"}
    	    ]
    	});
		
		var STATUSIJIN_store = Ext.create('Ext.data.Store', {
    	    fields: ['value', 'display'],
    	    data : [
    	        {"value":"A", "display":"DIAJUKAN"},
    	        {"value":"T", "display":"DITETAPKAN"},
    	        {"value":"C", "display":"DIBATALKAN"}
    	    ]
    	});
		
		var jenisabsen_store = Ext.create('Ext.data.Store', {
			fields: [
                {name: 'JENISABSEN', type: 'string', mapping: 'JENISABSEN'},
                {name: 'KELABSEN', type: 'string', mapping: 'KELABSEN'},
                {name: 'KETERANGAN', type: 'string', mapping: 'KETERANGAN'},
                {name: 'POTONG', type: 'string', mapping: 'POTONG'},
                {name: 'INSDISIPLIN', type: 'string', mapping: 'INSDISIPLIN'},
                {name: 'JENISABSEN_ALIAS', type: 'string', mapping: 'JENISABSEN_ALIAS'}
            ],
			proxy: {
				type: 'ajax',
				url: 'c_permohonanijin/get_jenisabsen',
				reader: {
					type: 'json',
					root: 'data'
				}
			},
			autoLoad: true
		});
		
		var personalia_store = Ext.create('Ext.data.Store', {
			fields: [
                {name: 'NIK', type: 'string', mapping: 'NIK'},
                {name: 'NAMAKAR', type: 'string', mapping: 'NAMAKAR'}
            ],
			proxy: {
				type: 'ajax',
				url: 'c_permohonanijin/get_personalia',
				reader: {
					type: 'json',
					root: 'data'
				}
			},
			autoLoad: true
		});
		/* STORE end */

		/*
		 * Deklarasi variable setiap field
		 */
		var NOIJIN_field = Ext.create('Ext.form.field.Text', {
			itemId: 'NOIJIN_field',
			name: 'NOIJIN', 
			maxLength: 7,
			emptyText: 'Auto',
			readOnly: true,
			//allowBlank: false,
			style : {textTransform: "uppercase"},
			enableKeyEvents: true,
			listeners: {
				'change': function(field, newValue, oldValue){
					field.setValue(newValue.toUpperCase());
				}
			}
		});
		var NIK_field = Ext.create('Ext.form.field.ComboBox', {
			itemId : 'NIK_field',
			allowBlank : false,
			store: 's_karyawan_byunitkerja',
			typeAhead    : true,
			triggerAction: 'all',
			selectOnFocus: true,
            loadingText  : 'Searching...',
			displayField: 'NAMAKAR',
			queryMode: 'local',
			valueField: 'NIK',
			tpl: Ext.create('Ext.XTemplate',
				'<tpl for=".">',
					'<div class="x-boundlist-item">[{NIK}] - {NAMAKAR}</div>',
				'</tpl>'
			),
			displayTpl: Ext.create('Ext.XTemplate',
				'<tpl for=".">',
					'[{NIK}] - {NAMAKAR}',
				'</tpl>'
			),
			listeners: {
				select: function(combo, records, e){
					Ext.Ajax.request({
						url: 'c_permohonanijin/getSisa',
						params: {
							nik: records[0].data.NIK
						},
						success: function(response){
							var rs = Ext.decode(response.responseText);
							
							SISACUTI_field.setValue(rs.sisacuti);
							if (rs.sisacuti > 0) {
								AMBILCUTI_field.setValue('1');
								AMBILCUTI_field.setReadOnly(true);
							}else{
								AMBILCUTI_field.setValue('0');
								AMBILCUTI_field.setReadOnly(true);
							}
						}
					});
				}
			}
		});
		/*var NIK_field = Ext.create('Ext.form.field.ComboBox', {
			itemId : 'NIK_field',
			name: 'NIK', 
			allowBlank : false,
			typeAhead    : true,
			triggerAction: 'all',
			selectOnFocus: true,
            loadingText  : 'Searching...',
			store: nik_store,
			queryMode: 'local',
			tpl: Ext.create('Ext.XTemplate',
				'<tpl for=".">',
					'<div class="x-boundlist-item">[{NIK}] - {NAMAKAR}</div>',
				'</tpl>'
			),
			displayTpl: Ext.create('Ext.XTemplate',
				'<tpl for=".">',
					'[{NIK}] - {NAMAKAR}',
				'</tpl>'
			),
			valueField: 'NIK',
			displayField: 'NAMAKAR',
			enableKeyEvents: true,
			listeners: {
				beforequery: function(qe){
					var kodeunit_filter = (user_kodeunit).replace(/0/gi, '');
					var re = new RegExp('^'+kodeunit_filter, 'i');
					qe.combo.getStore().filter('KODEUNIT', re);
	                qe.query = new RegExp(qe.query, 'i');
	                qe.forceAll = true;
	            },
				select: function(combo, records, e){
					Ext.Ajax.request({
						url: 'c_permohonanijin/getSisa',
						params: {
							nik: records[0].data.NIK
						},
						success: function(response){
							var rs = Ext.decode(response.responseText);
							
							SISACUTI_field.setValue(rs.sisacuti);
							if (rs.sisacuti > 0) {
								AMBILCUTI_field.setValue('1');
								AMBILCUTI_field.setReadOnly(true);
							}else{
								AMBILCUTI_field.setValue('0');
								AMBILCUTI_field.setReadOnly(true);
							}
						}
					});
				}
			}
		});*/
		var JENISABSEN_field = Ext.create('Ext.form.field.ComboBox', {
			itemId : 'JENISABSEN_field',
			name: 'JENISABSEN', 
			typeAhead    : true,
			triggerAction: 'all',
			selectOnFocus: true,
            loadingText  : 'Searching...',
			displayField: 'JENISABSEN_ALIAS',
			store: jenisabsen_store,
			queryMode: 'local',
			tpl: Ext.create('Ext.XTemplate',
				'<tpl for=".">',
					'<div class="x-boundlist-item">{JENISABSEN_ALIAS} - {KETERANGAN}</div>',
				'</tpl>'
			),
			displayTpl: Ext.create('Ext.XTemplate',
				'<tpl for=".">',
					'{JENISABSEN_ALIAS} - {KETERANGAN}',
				'</tpl>'
			),
			valueField: 'JENISABSEN',
			enableKeyEvents: true,
			listeners: {
				select: function(combo, records, e){
					if(records[0].data.KELABSEN != 'P')
					{
						JAMDARI_field.setDisabled(true);
						JAMSAMPAI_field.setDisabled(true);
						AMBILCUTI_field.setDisabled(false);
						KEMBALI_field.setDisabled(true);
					}
					else
					{
						JAMDARI_field.setDisabled(false);
						JAMSAMPAI_field.setDisabled(false);
						AMBILCUTI_field.setDisabled(true);
						KEMBALI_field.setDisabled(false);
					}
					
					if(records[0].data.POTONG == 'T')
					{
						console.info(records);
						AMBILCUTI_field.setValue('3');
						AMBILCUTI_field.setReadOnly(true);
					}
					else
					{
						Ext.Ajax.request({
							url: 'c_permohonanijin/getSisa',
							params: {
								nik: NIK_field.getValue()
							},
							success: function(response){
								var rs = Ext.decode(response.responseText);
								
								SISACUTI_field.setValue(rs.sisacuti);
								if (rs.sisacuti > 0) {
									AMBILCUTI_field.setValue('1');
									AMBILCUTI_field.setReadOnly(true);
								}else{
									AMBILCUTI_field.setValue('0');
									AMBILCUTI_field.setReadOnly(true);
								}
							}
						});
					}
				}
			}
		});
		var TANGGAL_field = Ext.create('Ext.form.field.Date', {
			itemId : 'TANGGAL_field',
			name: 'TANGGAL', 
			format: 'Y-m-d'
		});
		var JAMDARI_field = Ext.create('Ext.form.field.Time', {
			itemId : 'JAMDARI_field',
			name: 'JAMDARI', 
			format: 'H:i:s',
			increment:1
		});
		var JAMSAMPAI_field = Ext.create('Ext.form.field.Time', {
			itemId : 'JAMSAMPAI_field',
			name: 'JAMSAMPAI', 
			format: 'H:i:s',
			increment:1
		});
		var KEMBALI_field = Ext.create('Ext.form.field.ComboBox', {
			itemId : 'KEMBALI_field',
			name: 'KEMBALI', 
			store: KEMBALI_store,
			queryMode: 'local',
			tpl: Ext.create('Ext.XTemplate',
				'<tpl for=".">',
					'<div class="x-boundlist-item">{display}</div>',
				'</tpl>'
			),
			displayTpl: Ext.create('Ext.XTemplate',
				'<tpl for=".">',
					'{display}',
				'</tpl>'
			),
			valueNotFoundText : 'ga ada',
			valueField: 'value',
			//readOnly: true,
			allowBlank: true
		});
		var AMBILCUTI_field = Ext.create('Ext.form.field.ComboBox', {
			itemId: 'AMBILCUTI_field',
			name: 'AMBILCUTI',
			store: AMBILCUTI_store,
			queryMode: 'local',
			tpl: Ext.create('Ext.XTemplate',
				'<tpl for=".">',
					'<div class="x-boundlist-item">{display}</div>',
				'</tpl>'
			),
			displayTpl: Ext.create('Ext.XTemplate',
				'<tpl for=".">',
					'{display}',
				'</tpl>'
			),
			valueField: 'value',
			readOnly: true,
			allowBlank: true
		});
		var SISACUTI_field = Ext.create('Ext.form.field.Number', {
			itemId: 'SISACUTI_field',
			name: 'SISA',
			maxLength : 5,
			readOnly: true,
			allowBlank: true
		});
		var NIKATASAN1_field = Ext.create('Ext.form.field.ComboBox', {
			itemId: 'NIKATASAN1_field',
			name: 'NIKATASAN1', 
			allowBlank : false,
			store: nik_store,
			queryMode: 'local',
			tpl: Ext.create('Ext.XTemplate',
				'<tpl for=".">',
					'<div class="x-boundlist-item">{NIK} - {NAMAKAR}</div>',
				'</tpl>'
			),
			displayTpl: Ext.create('Ext.XTemplate',
				'<tpl for=".">',
					'{NIK} - {NAMAKAR}',
				'</tpl>'
			),
			valueField: 'NIK',
			readOnly: true
		});
		var STATUSIJIN_field = Ext.create('Ext.form.field.ComboBox', {
			itemId: 'STATUSIJIN_field',
			name: 'STATUSIJIN', 
			store: STATUSIJIN_store,
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
			value : 'A',
			valueField: 'value',
			readOnly: true
		});
		
		var NIKPERSONALIA_field = Ext.create('Ext.form.field.ComboBox', {
			itemId : 'NIKPERSONALIA_field',
			name: 'NIKPERSONALIA', 
			allowBlank : false,
			typeAhead    : true,
			triggerAction: 'all',
			selectOnFocus: true,
            loadingText  : 'Searching...',
			displayField: 'NAMAKAR',
			store: personalia_store,
			queryMode: 'local',
			tpl: Ext.create('Ext.XTemplate',
				'<tpl for=".">',
					'<div class="x-boundlist-item">{NIK} - {NAMAKAR}</div>',
				'</tpl>'
			),
			displayTpl: Ext.create('Ext.XTemplate',
				'<tpl for=".">',
					'{NIK} - {NAMAKAR}',
				'</tpl>'
			),
			valueField: 'NIK',
			readOnly : true
		});
		var USERNAME_field = Ext.create('Ext.form.field.Hidden', {
			name: 'USERNAME', 
			value: username,
			readOnly: true
		});

		var unitkerja_filterField = Ext.create('Ext.form.field.Checkbox', {
			itemId		: 'unitkerja_filterField',
			boxLabel	: 'All Unit, ',
			name		: 'ALLUNIT',
			inputValue	: 'y',
			hidden		: true,
			listeners	: {
				'change': function(thisfield, newValue, oldValue, e){
					if (newValue) {
						me.getStore().proxy.extraParams.allunit = 'y';
						me.getStore().load();
					} else{
						me.getStore().proxy.extraParams.allunit = '';
						me.getStore().load();
					};
				}
			}
		});

		var tglabsen_filterField = Ext.create('Ext.form.field.Date', {
			allowBlank : true,
			fieldLabel: 'Tgl Absen',
			labelWidth: 70,
			name: 'TGLABSEN',
			format: 'd M, Y',
			altFormats: 'm,d,Y|Y-m-d',
			value:new Date(),
			readOnly: false,
			width: 190,
			listeners: {
				'select': function(cb, records, e){
					var tanggal_absen_filter = cb.getValue();
					var tanggal_absen = tanggal_absen_filter.format("yyyy-mm-dd");
					me.getStore().proxy.extraParams.tglabsen = tanggal_absen;
					me.getStore().load();
				}
			}
		});

		this.rowEditing = Ext.create('Ext.grid.plugin.RowEditing', {
			clicksToEdit: 2,
			clicksToMoveEditor: 1,
			listeners: {
				'beforeedit': function(editor, e){
					if(! (/^\s*$/).test(e.record.data.NIK) ){
						NIK_field.setReadOnly(true);
					}else{
						NIK_field.setReadOnly(false);
					}
					
				},
				'canceledit': function(editor, e){
					if((/^\s*$/).test(e.record.data.NIK) ){
						editor.cancelEdit();
						var sm = e.grid.getSelectionModel();
						e.store.remove(sm.getSelection());
					}
				},
				'validateedit': function(editor, e){
				},
				'afteredit': function(editor, e){
					var me = this;
					if((/^\s*$/).test(e.record.data.NIK) ){
						Ext.Msg.alert('Peringatan', 'Kolom "NIK" tidak boleh kosong.');
						return false;
					}
					/* e.store.sync();
					return true; */
					var jsonData = Ext.encode(e.record.data);
					
					Ext.Ajax.request({
						method: 'POST',
						url: 'c_permohonanijin/save',
						params: {data: jsonData},
						success: function(response){
							e.store.reload({
								callback: function(){
									var newRecordIndex = e.store.findBy(
										function(record, id) {
											if ((new Date(record.get('TANGGAL'))).format('yyyy-mm-dd') === (new Date(e.record.data.TANGGAL)).format('yyyy-mm-dd')
												&& record.get('NIK') == e.record.data.NIK) {
												return true;
											}
											return false;
										}
									);
									/* me.grid.getView().select(recordIndex); */
									me.grid.getSelectionModel().select(newRecordIndex);
								}
							});
						}
					});
					return true;
				}
			}
		});

		this.columns = [
			{
				header: 'NOIJIN',
				dataIndex: 'NOIJIN',
				filterable: true,
				hidden: false,
				width: 70,
				field: NOIJIN_field
			},{
				header: 'NIK',
				dataIndex: 'NIK',
				filterable: true,
				hidden: false,
				width: 319,
				filterable: true,
				renderer: function(value, metaData, record){
					return '['+record.data.NIK+'] - '+record.data.NAMAKAR;
				},
				field: NIK_field
			},{
				header: 'JENISABSEN',
				dataIndex: 'JENISABSEN',
				filterable: true, 
				hidden: false,
				width: 150,
				renderer: function(value, metaData, record){
					return record.data.JENISABSEN_ALIAS+' - '+record.data.KETERANGAN;
				},
				field: JENISABSEN_field
			},{
				header: 'TANGGAL',
				dataIndex: 'TANGGAL',width: 140,
				renderer: Ext.util.Format.dateRenderer('D, d M Y'),
				filterable: true, 
				hidden: false,
				field: TANGGAL_field
			},{
				header: 'JAMDARI',
				dataIndex: 'JAMDARI',
				filterable: true, 
				hidden: false,
				field: JAMDARI_field
			},{
				header: 'JAMSAMPAI',
				dataIndex: 'JAMSAMPAI',
				filterable: true, 
				hidden: false,
				field: JAMSAMPAI_field
			},{
				header: 'KEMBALI',
				dataIndex: 'KEMBALI',
				filterable: true, 
				hidden: false,
				field: KEMBALI_field
			},{
				header: 'AMBILCUTI',
				dataIndex: 'AMBILCUTI',
				filterable: true, 
				width: 120,
				hidden: false,
				renderer: function(value, metaData, record){
					return record.data.AMBILCUTI_KETERANGAN;
				},
				field: AMBILCUTI_field
			},{
				header: 'SISACUTI',
				dataIndex: 'SISA',
				filterable: true, 
				hidden: false,
				field: SISACUTI_field
			},{
				header: 'PENGUSUL',
				dataIndex: 'NIKATASAN1',
				filterable: true,
				hidden: false,
				width: 200,
				renderer: function(value, metaData, record){
					return '['+record.data.NIKATASAN1+'] - '+record.data.NAMAKARATASAN1;
				},
				field: NIKATASAN1_field
			},{
				header: 'PERSONALIA',
				dataIndex: 'NIKPERSONALIA',
				filterable: true,
				hidden: false,
				width: 200,
				renderer: function(value, metaData, record){
					return '['+record.data.NIKHR+'] - '+record.data.NAMAKARHR;
				},
				field: NIKPERSONALIA_field
			},{
				header: 'STATUS IJIN',
				dataIndex: 'STATUSIJIN',
				filterable: true, 
				hidden: false,
				width: 150,
				renderer: function(value, metaData, record){
					return record.data.STATUSIJIN+' - '+record.data.STATUSIJIN_KETERANGAN;
				},
				field: STATUSIJIN_field
			}];
		this.features = [filtersCfg];
		this.plugins = [this.rowEditing];
		this.dockedItems = [
			Ext.create('Ext.toolbar.Toolbar', {
				items: [{
					xtype: 'fieldcontainer',
					layout: 'hbox',
					defaultType: 'button',
					items: [{
						text	: 'Add',
						iconCls	: 'icon-add',
						action	: 'create'
					}, {
						xtype: 'splitter'
					}, {
						itemId	: 'btndelete',
						text	: 'Delete',
						iconCls	: 'icon-remove',
						action	: 'delete',
						disabled: true
					}]
				}, '-', {
					xtype: 'fieldcontainer',
					layout: 'hbox',
					defaultType: 'button',
					items: [{
						text	: 'Export Excel',
						iconCls	: 'icon-excel',
						action	: 'xexcel'
					}, {
						xtype: 'splitter'
					}, {
						text	: 'Export PDF',
						iconCls	: 'icon-pdf',
						action	: 'xpdf'
					}]
				}, '-', unitkerja_filterField, tglabsen_filterField]
			}),
			{
				xtype: 'pagingtoolbar',
				store: 's_permohonanijin',
				dock: 'bottom',
				displayInfo: true
			}
		];
		this.callParent(arguments);
		
		this.on('itemclick', this.gridSelection);
		this.getView().on('refresh', this.refreshSelection, this);
	},	
	
	gridSelection: function(me, record, item, index, e, eOpts){
		//me.getSelectionModel().select(index);
		this.selectedIndex = index;
		this.getView().saveScrollState();
	},
	
	refreshSelection: function() {
        this.getSelectionModel().select(this.selectedIndex);   /*Ext.defer(this.setScrollTop, 30, this, [this.getView().scrollState.top]);*/
    }

});