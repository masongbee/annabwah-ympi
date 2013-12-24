Ext.define('YMPI.view.PROSES.v_importpres', {
	extend: 'Ext.grid.Panel',
	requires: ['YMPI.store.s_importpres',
			'Ext.ux.grid.FiltersFeature',
			'Ext.ux.ajax.JsonSimlet',
			'Ext.ux.ajax.SimManager'
			],
	
	title		: 'Import Presensi',
	itemId		: 'Listimportpres',
	alias       : 'widget.Listimportpres',
	store 		: 's_importpres',
	columnLines : true,
	frame		: true,
    emptyText: 'No Matching Records',
	selModel: {
		mode: 'MULTI'
	},
	
	margin		: 0,
	//selectedIndex: -1,
	selectedRecords: [],
	
	initComponent: function(){
		//Ext.Error.ignore = true;
		var me = this;
		var nshift,tgls,shiftLama;
		/* STORE start */	
		var nik_store = Ext.create('YMPI.store.s_karyawan');
		
		var shift_store = Ext.create('Ext.data.Store', {
			fields: [
                {name: 'NAMASHIFT', type: 'string', mapping: 'NAMASHIFT'},
                {name: 'SHIFTKE', type: 'string', mapping: 'SHIFTKE'},
				{name: 'JENISHARI', type: 'string',mapping: 'JENISHARI'},
				{name: 'JAMDARI', type: 'time',mapping: 'JAMDARI'},
				{name: 'JAMSAMPAI', type: 'time',mapping: 'JAMSAMPAI'}
            ],
			proxy: {
				type: 'ajax',
				actionMethods: {
					read    : 'POST',
				},
				url: 'c_importpres/getShift',
				reader: {
					type: 'json',
					root: 'data'
				}
			},
			autoLoad: false
		});
		/* STORE end */
		
		var filtersCfg = {
			ftype: 'filters',
			// encode and local configuration options defined previously for easier reuse
			encode: true, // json encode the filter query
			local: true   // defaults to false (remote filtering)
		};
		
    	/*
		 * Deklarasi variable setiap field
		 */
	
		var tglmulai_filterField = Ext.create('Ext.form.field.Date', {
			allowBlank : true,
			itemId: 'tglmulai',
			fieldLabel: 'Tgl Mulai',
			labelWidth: 55,
			name: 'TGLMULAI',
			format: 'd M, Y',
			altFormats: 'm,d,Y|Y-m-d',
			value: Ext.Date.subtract(new Date(), Ext.Date.DAY, 1),
			readOnly: false,
			width: 180
		});
		var tglsampai_filterField = Ext.create('Ext.form.field.Date', {
			allowBlank : true,
			itemId: 'tglsampai',
			fieldLabel: 'Tgl Sampai',
			labelWidth: 70,
			name: 'TGLSAMPAI',
			format: 'd M, Y',
			altFormats: 'm,d,Y|Y-m-d',
			value:new Date(),
			readOnly: false,
			width: 200,
			listeners: {
				'select': function(cb, records, e){
					var filter = "Range";
					
					var tglmulai_filter = tglmulai_filterField.getValue();
					var tglsampai_filter = cb.getValue();
					var tglm = tglmulai_filter.format("yyyy-mm-dd");
					var tgls = tglsampai_filter.format("yyyy-mm-dd");
					me.getStore().proxy.extraParams.tglmulai = tglm;
					me.getStore().proxy.extraParams.tglsampai = tgls;
					me.getStore().proxy.extraParams.saring = filter;
					me.getStore().load();
				}
			}
		});
		 
		/*var NIK_field = Ext.create('Ext.form.field.ComboBox', {
			itemId: 'NIK_field',
			name: 'NIK',
			//fieldLabel: 'NIK',
			store: nik_store,
			queryMode: 'local',
			valueField: 'NIK',
			tpl: Ext.create('Ext.XTemplate',
				'<tpl for=".">',
					'<div class="x-boundlist-item">{NIK} - {NAMAKAR}</div>',
				'</tpl>'
			),
			displayTpl: Ext.create('Ext.XTemplate',
				'<tpl for=".">',
					'{NIK} - {NAMAKAR}',
				'</tpl>'
			)
		});*/
		 
		var NAMA_field = Ext.create('Ext.form.field.ComboBox', {
			itemId: 'NAMA_field',
			name: 'NAMA',
			readOnly:true,
			//fieldLabel: 'NAMA',
			store: nik_store,
			queryMode: 'local',
			valueField: 'NAMAKAR',
			tpl: Ext.create('Ext.XTemplate',
				'<tpl for=".">',
					'<div class="x-boundlist-item">{NIK} - {NAMAKAR}</div>',
				'</tpl>'
			),
			displayTpl: Ext.create('Ext.XTemplate',
				'<tpl for=".">',
					'{NIK} - {NAMAKAR}',
				'</tpl>'
			)
		});
	
		var NIK_field = Ext.create('Ext.form.field.Text', {
			allowBlank : false,
			readOnly:true,
			maxLength: 10
		});
		
		var TJMASUK_field = Ext.create('Ext.form.field.Date', {
			allowBlank : true,
			format: 'Y-m-d H:i:s'
		});
		
		var TANGGAL_field = Ext.create('Ext.form.field.Date', {
			allowBlank : true,
			format: 'Y-m-d',
			readOnly: true,
			listeners: {
				select: function(field, value, e){
					tgls = Ext.Date.format(value,'Y-m-d');
					shift_store.proxy.extraParams.nshift = nshift;
					shift_store.proxy.extraParams.tgls = tgls;
					shift_store.load();
				}
			}
		});
		
		var NAMAUNIT_field = Ext.create('Ext.form.field.Text', {
			allowBlank : true,
			readOnly:true
		});
		
		var BAGIAN_field = Ext.create('Ext.form.field.Text', {
			allowBlank : true,
			readOnly:true
		});
		
		var NAMASHIFT_field = Ext.create('Ext.form.field.Text', {
			allowBlank : true,
			readOnly:true
		});
		
		var SHIFTKE_field = Ext.create('Ext.form.field.ComboBox', {
			allowBlank : false,
			store: shift_store,
			queryMode: 'local',
			valueField: 'SHIFTKE',
			displayField: 'SHIFTKE',
			emptyText: 'Shift Ke',
			//readOnly: true,
			listeners: {
				select: function(combo, records){
					JAMDARI_field.setValue(records[0].data.JAMDARI);
					JAMSAMPAI_field.setValue(records[0].data.JAMSAMPAI);
				}
			}
		});
		
		var JAMDARI_field = Ext.create('Ext.form.field.Text', {
			allowBlank : true,
			readOnly:true
		});
		
		var JAMSAMPAI_field = Ext.create('Ext.form.field.Text', {
			allowBlank : true,
			readOnly:true
		});
		
		var JAMSAMPAI_field = Ext.create('Ext.form.field.Text', {
			allowBlank : true,
			readOnly:true
		});
		
		var docktool = Ext.create('Ext.toolbar.Paging', {
			store: 's_importpres',
			dock: 'bottom',
			displayInfo: true
		});
		
		this.rowEditing = Ext.create('Ext.grid.plugin.RowEditing', {
			clicksToEdit: 2,
			clicksToMoveEditor: 1,
			listeners: {
				'beforeedit': function(editor, e){
					//console.info('before edit :');
					//console.info(e.record.data);
					
					shiftLama = e.record.data.SHIFTKE;
					nshift = e.record.data.NAMASHIFT;
					tgls = Ext.Date.format(e.record.data.TANGGAL,'Y-m-d');
					shift_store.proxy.extraParams.nshift = nshift;
					shift_store.proxy.extraParams.tgls = tgls;
					shift_store.load();
				},
				'canceledit': function(editor, e){
					if((/^\s*$/).test(e.record.data.NIK) || (/^\s*$/).test(e.record.data.TANGGAL) ){
						editor.cancelEdit();
						//var sm = e.grid.getSelectionModel();
						//e.store.remove(sm.getSelection());
					}
				},
				'validateedit': function(editor, e){
					//console.info('validate edit :');
					//console.info(e.record.data);
				},
				'afteredit': function(editor, e){
					//var me = this;
					console.log(e.record.data);
					if((/^\s*$/).test(e.record.data.NIK) || (/^\s*$/).test(e.record.data.TANGGAL) ){
						Ext.Msg.alert('Peringatan', 'Kolom "NIK","TANGGAL" tidak boleh kosong.');
						return false;
					}
					
					/**
					 * Get TJMASUK ==> otomatis update TANGGAL_field
					 */
					var tjmasuk = e.record.data.TJMASUK;
					var getyear = tjmasuk.getFullYear();
					var getmonth = tjmasuk.getMonth();
					var getdate = tjmasuk.getDate();
					var tgltjmasuk = new Date(getyear,getmonth,getdate);
					//console.log(new Date(getyear+'-'+getmonth+'-'+getdate));
					console.log(tgltjmasuk);
					e.record.data.TANGGAL = tgltjmasuk;
					//TANGGAL_field.setValue(tgltjmasuk);
					
					var jsonData = Ext.encode(e.record.data);
					Ext.Ajax.request({
						method: 'POST',
						url: 'c_importpres/save',
						params: {data: jsonData},
						success: function(response){
							e.record.set('TJMASUK',Ext.Date.format(e.record.data.TJMASUK, 'Y-m-d H:i:s'));
							e.record.set('TJKELUAR',Ext.Date.format(e.record.data.TJKELUAR, 'Y-m-d H:i:s'));
							e.record.commit();
							//me.getView().refresh();
							//console.log(e.record.getId());
							//e.store.load(e.record.getId());
							me.getView().refreshNode(e.record.index);
							/*e.store.reload({
								callback: function(){
									var newRecordIndex = e.store.findBy(
										function(record, id) {
											if (record.get('NIK') === e.record.data.NIK) {
												return true;
											}
											return false;
										}
									);
									//me.grid.getView().select(recordIndex); 
									me.grid.getSelectionModel().select(newRecordIndex);
								}
							});*/
						}
					});
					return true;
				}
			}
		});
		
		this.columns = [{header: 'NO. ID', dataIndex: 'ID', width: 100,
            filterable: true, sortable : true,hidden: true}
			,{
				header: 'TANGGAL',
				dataIndex: 'TANGGAL',
				field:TANGGAL_field,
				width: 120,
				filterable: true,
				sortable : true,
				hidden: false,
				renderer: Ext.util.Format.dateRenderer('Y-m-d')
			},{ header: 'NIK', dataIndex: 'NIK', width: 100,
            filterable: true, sortable : true,hidden: false},{ header: 'NAMA', dataIndex: 'NAMAKAR', width: 140,
            filterable: true, sortable : true,hidden: false},{ header: 'NAMA UNIT', dataIndex: 'NAMAUNIT', width: 200,
            filterable: true, hidden: true},{ header: 'BAGIAN', dataIndex: 'SINGKATAN', width: 80,
            filterable: true, hidden: false},{ header: 'NAMA SHIFT', dataIndex: 'NAMASHIFT', width: 100,
            filterable: true, hidden: true},{ header: 'SHIFT', dataIndex: 'SHIFTKE', field:SHIFTKE_field, width: 80,
            filterable: true, hidden: false},{ header: 'MASUK', dataIndex: 'JAMDARI', field:JAMDARI_field, width: 100,
            filterable: true, hidden: true},{ header: 'PULANG', dataIndex: 'JAMSAMPAI', field:JAMSAMPAI_field, width: 100,
            filterable: true, hidden: true},{ header: 'STATUS', dataIndex: 'STATUS', width: 100,
            filterable: true, hidden: true},{
				header: 'TJMASUK',
				dataIndex: 'TJMASUK',
				field: {
					xtype: 'datefield',
					format: 'Y-m-d H:i:s',
					//enableKeyEvents: true,
					listeners: {
						change: function(field, newValue, oldValue){
							if (field.isValid()) {
								/**
								 * Get TJMASUK ==> otomatis update TANGGAL_field
								 */
								var tjmasuk = field.getValue();
								var getyear = tjmasuk.getFullYear();
								var getmonth = tjmasuk.getMonth();
								var getdate = tjmasuk.getDate();
								var gethours = tjmasuk.getHours();
								var getminutes = tjmasuk.getMinutes();
								var getseconds = tjmasuk.getSeconds();
								var tgltjmasuk = new Date(getyear,getmonth,getdate);
								var datetimetjmasuk = new Date(getyear,getmonth,getdate,gethours,getminutes,getseconds);
								//console.log(new Date(getyear+'-'+getmonth+'-'+getdate));
								//console.log(datetimetjmasuk.isValid());
								//e.record.data.TANGGAL = tgltjmasuk;
								
								Ext.Ajax.request({
									method: 'POST',
									url: 'c_importpres/get_shift',
									params:{tjmasuk: tjmasuk},
									timeout: 10000,
									callback: function(options, success, response){
										var obj = Ext.JSON.decode(response.responseText);
										SHIFTKE_field.setValue(obj.data[0].SHIFTKE);
										JAMDARI_field.setValue(obj.data[0].JAMDARI);
										JAMSAMPAI_field.setValue(obj.data[0].JAMSAMPAI);
									},
									failure: function(response) {
										console.info(response);
									}
								});
								
								TANGGAL_field.setValue(tgltjmasuk);
							}
						}
					}
				},
				width: 180,
				sortable : true,
				filter: {
					type: 'datetime',
					dateFormat: 'Y-m-d H:i:s',
					date: {
						format: 'Y-m-d',
					},
					
					time: {
						format: 'H:i:s',
						increment: 1
					}
				},
				renderer: function(val,metadata,record){
					if (record.data.TJMASUK == null) {
						return 'null';
					}
					return Ext.Date.format(Ext.Date.parse(val, 'Y-m-d H:i:s', true), 'Y-m-d H:i:s');
				}
			},{
				header: 'TJKELUAR',
				dataIndex: 'TJKELUAR',
				field: {xtype: 'datefield',format: 'Y-m-d H:i:s'}, width: 180,sortable : true,
				filter: {
					type: 'datetime',
					dateFormat: 'Y-m-d H:i:s',
					date: {
						format: 'Y-m-d',
					},
	
					time: {
						format: 'H:i:s',
						increment: 1
					}
				},
				renderer: function(val,metadata,record){
					if (record.data.TJKELUAR == null) {
						return 'null';
					}
					return Ext.Date.format(Ext.Date.parse(val, 'Y-m-d H:i:s', true), 'Y-m-d H:i:s');
				}
			},{ header: 'ASALDATA', dataIndex: 'ASALDATA', field: {xtype: 'textfield'}, width: 200,
            filterable: true, hidden: true },{ header: 'POSTING', dataIndex: 'POSTING', field: {xtype: 'textfield'}, width: 200,
            filterable: true,hidden: true},{ header: 'USERNAME', dataIndex: 'USERNAME', width: 200,
            filterable: true,hidden: true}];
			
		this.plugins = [this.rowEditing, 'bufferedrenderer'];
		this.features = [filtersCfg];
		this.viewConfig = {
			getRowClass: function(record, rowIndex, rowParams, store) {
				var status = record.get('STATUS');
				var tjmasuk = record.get('TJMASUK');
				var tjkeluar = record.get('TJKELUAR');
				if (status == 'Y') {
					return 'font-green';
				}
				if (tjmasuk == null || tjkeluar == null) {
					return 'font-red';
				} else {
					return 'font-black';
				}
			}
		};
		this.dockedItems = [
			{
				xtype: 'toolbar',
				frame: true,
				items: [
					tglmulai_filterField, {
						xtype: 'splitter'
					}, tglsampai_filterField, {
						xtype: 'splitter'
					},{
					itemId	: 'btnimport',
					text	: 'Import',
					iconCls	: 'icon-add',
					action	: 'import'
				},{
					xtype	: 'button',
					itemId	: 'btn_option',
					text	: 'Option',
					disabled: false,
					iconCls	: 'icon-pencil',
					//action	: 'setmasuk',
					menu    : [
						{
							text: 'Set TJMASUK',
							handler:function(){
								console.info('Set TJMASUK');
								var tglmulai_filter = me.down('#tglmulai').getValue();
								var tglsampai_filter = me.down('#tglsampai').getValue();
								var tglm = tglmulai_filter.format("yyyy-mm-dd");
								var tgls = tglsampai_filter.format("yyyy-mm-dd");
								
								var selections = me.getSelectionModel().getSelection();
								var jsonData = [];
								for (var i=0; i<selections.length; i++) {
									var data = selections[i].data;
									jsonData.push(data);
								}
								jsonData = Ext.encode(jsonData);
								
								Ext.Ajax.request({
									method: 'POST',
									url: 'c_importpres/set_tjmasuk',
									//params:{tglmulai: tglm, tglsampai: tgls},
									params:{data: jsonData},
									timeout: 600000,
									success: function(response){
											var importpresStore = me.getStore();
											var objS = Ext.JSON.decode(response.responseText);
											//console.info(response.responseText);
											Ext.Msg.show({
												title: 'Generate TJMASUK',
												msg: objS.message,
												minWidth: 200,
												modal: true,
												icon: Ext.Msg.INFO,
												buttons: Ext.Msg.OK,
												fn:function(){
													importpresStore.load();
												}
											});
										}
										,
										failure: function(response) {
											console.info(response);
										}
								});
							}
						},
						{
							text: 'Set TJKELUAR',
							handler:function(){
								console.info('Set TJKELUAR');
								var tglmulai_filter = me.down('#tglmulai').getValue();
								var tglsampai_filter = me.down('#tglsampai').getValue();
								var tglm = tglmulai_filter.format("yyyy-mm-dd");
								var tgls = tglsampai_filter.format("yyyy-mm-dd");
								
								var selections = me.getSelectionModel().getSelection();
								var jsonData = [];
								for (var i=0; i<selections.length; i++) {
									var data = selections[i].data;
									jsonData.push(data);
								}
								jsonData = Ext.encode(jsonData);
								
								Ext.Ajax.request({
									method: 'POST',
									url: 'c_importpres/set_tjkeluar',
									//params:{tglmulai: tglm, tglsampai: tgls},
									params:{data: jsonData},
									timeout: 600000,
									success: function(response){
											var importpresStore = me.getStore();
											var objS = Ext.JSON.decode(response.responseText);
											//console.info(response.responseText);
											Ext.Msg.show({
												title: 'Generate TJKELUAR',
												msg: objS.message,
												minWidth: 200,
												modal: true,
												icon: Ext.Msg.INFO,
												buttons: Ext.Msg.OK,
												fn:function(){
													importpresStore.load();
												}
											});
										}
										,
										failure: function(response) {
											console.info(response);
										}
								});
							}
						}
					]
				},{
					text	: 'Add',
					iconCls	: 'icon-add',
					action	: 'create'
				}, {
					itemId	: 'btndelete',
					text	: 'Delete',
					iconCls	: 'icon-remove',
					action	: 'delete',
					disabled: true
				}, '-',{
					text	: 'Export Excel',
					iconCls	: 'icon-excel',
					action	: 'xexcel'
				}, {
					text	: 'Export PDF',
					iconCls	: 'icon-pdf',
					action	: 'xpdf'
				}, {
					text	: 'Cetak',
					iconCls	: 'icon-print',
					action	: 'print'
				}]
			},docktool
		];
		
		docktool.add([
			'->',{
				 xtype      : 'fieldcontainer',
				//fieldLabel : 'Filter',
				defaultType: 'radiofield',
				defaults: {
					flex: 2
				},
				layout: 'hbox',
				items: [
					{
						boxLabel  : 'Normal',
						name      : 'filter',
						checked	: true,
						inputValue: 'normal',
						id        : 'rad_normal',
						handler	: function(checkbox,checked){
							//console.info(checkbox.boxLabel);
							if(checked)
							{
								var importpresStore = me.getStore();
								var filter = "Range";
								me.down('#btn_option').setDisabled(false);
								
								var tglmulai_filter = me.down('#tglmulai').getValue();
								var tglsampai_filter = me.down('#tglsampai').getValue();
								var tglm = tglmulai_filter.format("yyyy-mm-dd");
								var tgls = tglsampai_filter.format("yyyy-mm-dd");
								importpresStore.proxy.extraParams.tglmulai = tglm;
								importpresStore.proxy.extraParams.tglsampai = tgls;
								
								importpresStore.proxy.extraParams.saring = filter;
								importpresStore.load();
							}
						}
					},{
						xtype: 'splitter'
					}, {
						boxLabel  : 'Log Kosong',
						name      : 'filter',
						inputValue: 'lkosong',
						id        : 'rad_lkosong',
						handler	: function(checkbox,checked){
							//console.info(checkbox.boxLabel);
							if(checked)
							{
								var importpresStore = me.getStore();
								me.down('#btn_option').setDisabled(true);								
								importpresStore.proxy.extraParams.saring = checkbox.boxLabel;
								importpresStore.load();
							}
						}
					},{
						xtype: 'splitter'
					}, {
						boxLabel  : 'Log Dobel',
						name      : 'filter',
						inputValue: 'ldobel',
						id        : 'rad_ldobel',
						handler	: function(checkbox,checked){
							//console.info(checkbox.boxLabel);
							if(checked)
							{
								var importpresStore = me.getStore();
								me.down('#btn_option').setDisabled(true);							
								importpresStore.proxy.extraParams.saring = checkbox.boxLabel;
								importpresStore.load();
							}
						}
					},{
						xtype: 'splitter'
					}, {
						boxLabel  : 'Salah Shift',
						name      : 'filter',
						inputValue: 'salah',
						id        : 'rad_salah',
						handler	: function(checkbox,checked){
							//console.info(checkbox.boxLabel);
							if(checked)
							{
								var importpresStore = me.getStore();
								me.down('#btn_option').setDisabled(true);							
								importpresStore.proxy.extraParams.saring = checkbox.boxLabel;
								importpresStore.load();
							}
						}
					}
				]
			},{
				text: 'Clear Filter Data',
				handler: function () {
					me.filters.clearFilters();
				} 
			}
		]);
		
		this.callParent(arguments);		
		//console.info(docktool);
		
		//this.on('itemclick', this.gridSelection);
		//this.getView().on('refresh', this.refreshSelection, this);
		
		//this.on('itemclick', this.gridSelection);
		this.getStore().on('beforeload', this.rememberSelection, this);
		this.getView().on('refresh', this.refreshSelection, this);
	},
	
	rememberSelection: function(sm, records) {
		this.selectedRecords = this.getSelectionModel().getSelection();
		this.getView().saveScrollState();
	},
	
	/*gridSelection: function(me, record, item, index, e, eOpts){
		this.selectedIndex = index;
		this.getView().saveScrollState();
	},*/
	
	/*refreshSelection: function() {
        this.getSelectionModel().select(this.selectedIndex);
    }*/
	refreshSelection: function() {
		if (0 >= this.selectedRecords.length) {
			return;
		}
		
		/*var newRecordsToSelect = [];
		for (var i = 0; i < this.selectedRecords.length; i++) {
			console.log(this.selectedRecords[i]);
			record = this.getStore().getById(this.selectedRecords[i].getId());
			if (!Ext.isEmpty(record)) {
				newRecordsToSelect.push(record);
			}
		}*/
		
		this.getSelectionModel().select(this.selectedRecords);
		this.getSelectionModel().clearSelections();
	}

});