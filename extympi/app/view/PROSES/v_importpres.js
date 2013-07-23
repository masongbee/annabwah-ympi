
// configure whether filter query is encoded or not (initially)
var encode = false;

// configure whether filtering is performed locally or remotely (initially)
var local = true;

var filtersCfg = {
    ftype: 'filters',
    autoReload: false, //don't reload automatically
	encode: encode, // json encode the filter query
	local: local,   // defaults to false (remote filtering)
    // filters may be configured through the plugin,
    // or in the column definition within the headers configuration
    filters: [{
        type: 'numeric',
        dataIndex: 'id'
    }, {
        type: 'string',
        dataIndex: 'NAMA'
    }, {
        type: 'numeric',
        dataIndex: 'price'
    }, {
        type: 'list',
        dataIndex: 'ASALDATA',
        options: ['Database', 'Manual'],
        phpMode: true
    }, {
        type: 'date',
        dataIndex: 'TJMASUK'
    }, {
        type: 'boolean',
        dataIndex: 'visible'
    }]
};

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
	
	margin		: 0,
	selectedIndex: -1,
	
	initComponent: function(){		
		/* STORE start */	
		var nik_store = Ext.create('YMPI.store.s_karyawan');
		
		Ext.ux.ajax.SimManager.init({
			delay: 300,
			defaultSimlet: null
		}).register({
			'myData': {
				data: [
					['D', 'Database'],
					['M', 'Manual']
				],
				stype: 'json'
			}
		});
		
		var optionsStore = Ext.create('Ext.data.Store', {
			fields: ['id', 'text'],
			proxy: {
				type: 'ajax',
				url: 'myData',
				reader: 'array'
			}
		});
		/* STORE end */
		
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
			readOnly: false,
			width: 180
		});
		 
		var NIK_field = Ext.create('Ext.form.field.ComboBox', {
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
		});
		 
		var NAMA_field = Ext.create('Ext.form.field.ComboBox', {
			itemId: 'NAMA_field',
			name: 'NAMA',
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
	
		/*var NIK_field = Ext.create('Ext.form.field.Text', {
			allowBlank : false,
			maxLength: 10
		});
	
		var NAMA_field = Ext.create('Ext.form.field.Text', {
			allowBlank : false,
			maxLength: 10
		});*/
		
		var TJMASUK_field = Ext.create('Ext.form.field.Date', {
			allowBlank : false,
			format: 'Y-m-d H:i:s'
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
				if(! (/^\s*$/).test(e.record.data.NIK) || ! (/^\s*$/).test(e.record.data.TJMASUK) ){
					NIK_field.setReadOnly(true);TJMASUK_field.setReadOnly(true);}
					else
					{
						NIK_field.setReadOnly(false);TJMASUK_field.setReadOnly(false);
					}
					
				},
				'canceledit': function(editor, e){
					if((/^\s*$/).test(e.record.data.NIK) || (/^\s*$/).test(e.record.data.TJMASUK) ){
						editor.cancelEdit();
						var sm = e.grid.getSelectionModel();
						e.store.remove(sm.getSelection());
					}
				},
				'validateedit': function(editor, e){
				},
				'afteredit': function(editor, e){
					var me = this;
					if((/^\s*$/).test(e.record.data.NIK) || (/^\s*$/).test(e.record.data.TJMASUK) ){
						Ext.Msg.alert('Peringatan', 'Kolom "NIK","TJMASUK" tidak boleh kosong.');
						return false;
					}
					/* e.store.sync();
					return true; */
					var jsonData = Ext.encode(e.record.data);
					
					Ext.Ajax.request({
						method: 'POST',
						url: 'c_importpres/save',
						params: {data: jsonData},
						success: function(response){
							e.store.reload({
								callback: function(){
									var newRecordIndex = e.store.findBy(
										function(record, id) {
											if (record.get('NIK') === e.record.data.NIK && (new Date(record.get('TJMASUK'))).format('yyyy-mm-dd hh:nn:ss') === (new Date(e.record.data.TJMASUK)).format('yyyy-mm-dd hh:nn:ss')) {
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
			{ header: 'NIK', dataIndex: 'NIK', field: NIK_field, width: 200,
            filterable: true,
			renderer : function(val,metadata,record) {
                    if (record.data.TJMASUK == record.data.TJKELUAR || record.data.TJKELUAR == null ) {
                        return '<span style="color:red;">' + val + '</span>';
                    }
                    return val;
                }},
			{ header: 'NAMA', dataIndex: 'NAMA', field: NAMA_field, width: 200,
            filter: true,
			renderer : function(val,metadata,record) {
                    if (record.data.TJMASUK == record.data.TJKELUAR || record.data.TJKELUAR == null) {
                        return '<span style="color:red;">' + val + '</span>';
                    }
                    return val;
                }},
			{ header: 'TJMASUK', dataIndex: 'TJMASUK', field: TJMASUK_field, width: 200,
            filter: {
                type: 'date',
                // specify disabled to disable the filter menu
                // disabled: true
            },
			renderer : function(val,metadata,record) {
                    if (record.data.TJMASUK == record.data.TJKELUAR || record.data.TJKELUAR == null) {
                        return '<span style="color:red;">' + val + '</span>';
                    }
                    return val;
                }},
			{ header: 'TJKELUAR', dataIndex: 'TJKELUAR', field: {xtype: 'datefield',format: 'Y-m-d H:i:s'}, width: 200,
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
                // specify disabled to disable the filter menu
                // disabled: true
            },
			renderer : function(val,metadata,record) {
                    if (record.data.TJMASUK == record.data.TJKELUAR || record.data.TJKELUAR == null) {
                        return '<span style="color:red;">' + val + '</span>';
                    }
                    return val;
                }},
			{ header: 'ASALDATA', dataIndex: 'ASALDATA', field: {xtype: 'textfield'}, width: 200,
            filter: {
                type: 'list',
				store: optionsStore,
                // specify disabled to disable the filter menu
                // disabled: true
            },
			renderer : function(val,metadata,record) {
                    if (record.data.TJMASUK == record.data.TJKELUAR || record.data.TJKELUAR == null) {
                        return '<span style="color:red;">' + val + '</span>';
                    }
                    return val;
                } }
			];
		this.plugins = [this.rowEditing];
		this.features = [filtersCfg];
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
					text	: 'Import',
					iconCls	: 'icon-add',
					action	: 'import'
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
				}, {
					text	: 'Filter',
					action	: 'filter'
				}]
			},docktool
		];
		
		docktool.add([
			'->',
			{
				text: 'Clear Filter Data',
				handler: function () {
					//this.filters.clearFilters();
					console.info("Clear Filter data");
					filtersCfg.clearFilters();
				} 
			}  
		]);
		
		this.callParent(arguments);		
		//console.info(docktool);
		
		this.on('itemclick', this.gridSelection);
		this.getView().on('refresh', this.refreshSelection, this);
	},
	
	gridSelection: function(me, record, item, index, e, eOpts){
		this.selectedIndex = index;
		this.getView().saveScrollState();
	},
	
	refreshSelection: function() {
        this.getSelectionModel().select(this.selectedIndex);
    }

});