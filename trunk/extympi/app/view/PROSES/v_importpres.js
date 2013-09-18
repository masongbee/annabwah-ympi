// configure whether filter query is encoded or not (initially)
var encode = true;
// configure whether filtering is performed locally or remotely (initially)
var local = false;

var filtersCfg = {
    ftype: 'filters',
    autoReload: true, //don't reload automatically
	encode: encode, // json encode the filter query
	local: local
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
    emptyText: 'No Matching Records',
	
	margin		: 0,
	selectedIndex: -1,
	
	initComponent: function(){
		var me = this;
		/* STORE start */	
		var nik_store = Ext.create('YMPI.store.s_karyawan');
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
				if(! (/^\s*$/).test(e.record.data.NIK) || (/^\s*$/).test(e.record.data.TJMASUK)){
					NIK_field.setReadOnly(true);}
					else
					{
						NIK_field.setReadOnly(false);;
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
											if (record.get('NIK') === e.record.data.NIK) {
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
		
		this.columns = [{ header: 'TANGGAL', dataIndex: 'TANGGAL', editor:{xtype:'datefield',format: 'Y-m-d'}, width: 120,
            filterable: true, sortable : true,hidden: false,
			renderer : function(val,metadata,record) {
				var tgl = new Date(val);				
				var re = /(\w+)\s(\w+)/;
				var newval = record.data.TJMASUK.replace(re, "$1T$2");
				var tgl1 = new Date(newval);
				var tgl2 = Ext.Date.format(record.data.TANGGAL,'Y-m-d')+'T'+record.data.JAMDARI;
				var t1 = new Date(Ext.Date.format(tgl1,'m/d/Y H:i:s'));
				var t2 = new Date(tgl2);
				var dt = (t1 - t2);
				
				if (record.data.TJMASUK == null || record.data.TJKELUAR == null) {
					return '<span style="color:red;">' + Ext.Date.format(tgl,'D, d M Y') + '</span>';
				}
				else if ((dt/60000) >= 300) {
					return '<span style="color:green;">' + Ext.Date.format(tgl,'D, d M Y') + '</span>';
				}
				else
					return '<span style="color:black;">' + Ext.Date.format(tgl,'D, d M Y') + '</span>';
			}},{ header: 'NIK', dataIndex: 'NIK', field: NIK_field, width: 100,
            filterable: true, sortable : true,hidden: false,
			renderer : function(val,metadata,record) {
				var re = /(\w+)\s(\w+)/;
				var newval = record.data.TJMASUK.replace(re, "$1T$2");
				var tgl1 = new Date(newval);
				var tgl2 = Ext.Date.format(record.data.TANGGAL,'Y-m-d')+'T'+record.data.JAMDARI;
				var t1 = new Date(Ext.Date.format(tgl1,'m/d/Y H:i:s'));
				var t2 = new Date(tgl2);
				var dt = (t1 - t2);
				
				if (record.data.TJMASUK == null || record.data.TJKELUAR == null) {
					return '<span style="color:red;">' + val + '</span>';
				}
				else if ((dt/60000) >= 300) {
					return '<span style="color:green;">' + val + '</span>';
				}
				else
					return '<span style="color:black;">' + val + '</span>';
			}},{ header: 'NAMA', dataIndex: 'NAMAKAR', field: NAMA_field, width: 140,
            filterable: true, sortable : true,hidden: false,
			renderer : function(val,metadata,record) {
				var re = /(\w+)\s(\w+)/;
				var newval = record.data.TJMASUK.replace(re, "$1T$2");
				var tgl1 = new Date(newval);
				var tgl2 = Ext.Date.format(record.data.TANGGAL,'Y-m-d')+'T'+record.data.JAMDARI;
				var t1 = new Date(Ext.Date.format(tgl1,'m/d/Y H:i:s'));
				var t2 = new Date(tgl2);
				var dt = (t1 - t2);
				
				if (record.data.TJMASUK == null || record.data.TJKELUAR == null) {
					return '<span style="color:red;">' + val + '</span>';
				}
				else if ((dt/60000) >= 300) {
					return '<span style="color:green;">' + val + '</span>';
				}
				else
					return '<span style="color:black;">' + val + '</span>';
			}},{ header: 'NAMA UNIT', dataIndex: 'NAMAUNIT', editor:{xtype:'textfield'}, width: 200,
            filterable: true, hidden: true,
			renderer : function(val,metadata,record) {
				var re = /(\w+)\s(\w+)/;
				var newval = record.data.TJMASUK.replace(re, "$1T$2");
				var tgl1 = new Date(newval);
				var tgl2 = Ext.Date.format(record.data.TANGGAL,'Y-m-d')+'T'+record.data.JAMDARI;
				var t1 = new Date(Ext.Date.format(tgl1,'m/d/Y H:i:s'));
				var t2 = new Date(tgl2);
				var dt = (t1 - t2);
				
				if (record.data.TJMASUK == null || record.data.TJKELUAR == null) {
					return '<span style="color:red;">' + val + '</span>';
				}
				else if ((dt/60000) >= 300) {
					return '<span style="color:green;">' + val + '</span>';
				}
				else
					return '<span style="color:black;">' + val + '</span>';
			}},{ header: 'NAMA SHIFT', dataIndex: 'NAMASHIFT', editor:{xtype:'textfield'}, width: 100,
            filterable: true, hidden: true,
			renderer : function(val,metadata,record) {
				var re = /(\w+)\s(\w+)/;
				var newval = record.data.TJMASUK.replace(re, "$1T$2");
				var tgl1 = new Date(newval);
				var tgl2 = Ext.Date.format(record.data.TANGGAL,'Y-m-d')+'T'+record.data.JAMDARI;
				var t1 = new Date(Ext.Date.format(tgl1,'m/d/Y H:i:s'));
				var t2 = new Date(tgl2);
				var dt = (t1 - t2);
				
				if (record.data.TJMASUK == null || record.data.TJKELUAR == null) {
					return '<span style="color:red;">' + val + '</span>';
				}
				else if ((dt/60000) >= 300) {
					return '<span style="color:green;">' + val + '</span>';
				}
				else
					return '<span style="color:black;">' + val + '</span>';
			}},{ header: 'SHIFT KE', dataIndex: 'SHIFTKE', editor:{xtype:'textfield'}, width: 100,
            filterable: true, hidden: false,
			renderer : function(val,metadata,record) {
				var re = /(\w+)\s(\w+)/;
				var newval = record.data.TJMASUK.replace(re, "$1T$2");
				var tgl1 = new Date(newval);
				var tgl2 = Ext.Date.format(record.data.TANGGAL,'Y-m-d')+'T'+record.data.JAMDARI;
				var t1 = new Date(Ext.Date.format(tgl1,'m/d/Y H:i:s'));
				var t2 = new Date(tgl2);
				var dt = (t1 - t2);
				
				if (record.data.TJMASUK == null || record.data.TJKELUAR == null) {
					return '<span style="color:red;">' + val + '</span>';
				}
				else if ((dt/60000) >= 300) {
					return '<span style="color:green;">' + val + '</span>';
				}
				else
					return '<span style="color:black;">' + val + '</span>';
			}},{ header: 'MASUK', dataIndex: 'JAMDARI', editor:{xtype:'textfield'}, width: 100,
            filterable: true, hidden: false,
			renderer : function(val,metadata,record) {
				var re = /(\w+)\s(\w+)/;
				var newval = record.data.TJMASUK.replace(re, "$1T$2");
				var tgl1 = new Date(newval);
				var tgl2 = Ext.Date.format(record.data.TANGGAL,'Y-m-d')+'T'+record.data.JAMDARI;
				var t1 = new Date(Ext.Date.format(tgl1,'m/d/Y H:i:s'));
				var t2 = new Date(tgl2);
				var dt = (t1 - t2);
				
				if (record.data.TJMASUK == null || record.data.TJKELUAR == null) {
					return '<span style="color:red;">' + val + '</span>';
				}
				else if ((dt/60000) >= 300) {
					return '<span style="color:green;">' + val + '</span>';
				}
				else
					return '<span style="color:black;">' + val + '</span>';
			}},{ header: 'PULANG', dataIndex: 'JAMSAMPAI', editor:{xtype:'textfield'}, width: 100,
            filterable: true, hidden: true,
			renderer : function(val,metadata,record) {
				var re = /(\w+)\s(\w+)/;
				var newval = record.data.TJMASUK.replace(re, "$1T$2");
				var tgl1 = new Date(newval);
				var tgl2 = Ext.Date.format(record.data.TANGGAL,'Y-m-d')+'T'+record.data.JAMDARI;
				var t1 = new Date(Ext.Date.format(tgl1,'m/d/Y H:i:s'));
				var t2 = new Date(tgl2);
				var dt = (t1 - t2);
				
				if (record.data.TJMASUK == null || record.data.TJKELUAR == null) {
					return '<span style="color:red;">' + val + '</span>';
				}
				else if ((dt/60000) >= 300) {
					return '<span style="color:green;">' + val + '</span>';
				}
				else
					return '<span style="color:black;">' + val + '</span>';
			}},{ header: 'TJMASUK', dataIndex: 'TJMASUK', field: {xtype: 'datefield',format: 'Y-m-d H:i:s'}, width: 200,sortable : true,
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
			renderer : function(val,metadata,record) {
				var re = /(\w+)\s(\w+)/;
				var newval = record.data.TJMASUK.replace(re, "$1T$2");
				var tgl1 = new Date(newval);
				var tgl2 = Ext.Date.format(record.data.TANGGAL,'Y-m-d')+'T'+record.data.JAMDARI;
				var t1 = new Date(Ext.Date.format(tgl1,'m/d/Y H:i:s'));
				var t2 = new Date(tgl2);
				var dt = (t1 - t2);
				
				if (record.data.TJMASUK == null || record.data.TJKELUAR == null) {
					return '<span style="color:red;">' + val + '</span>';
				}
				else if ((dt/60000) >= 300) {
					return '<span style="color:green;">' + val + '</span>';
				}
				else
					return '<span style="color:black;">' + val + '</span>';
			}},{ header: 'TJKELUAR', dataIndex: 'TJKELUAR', field: {xtype: 'datefield',format: 'Y-m-d H:i:s'}, width: 200,sortable : true,
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
			renderer : function(val,metadata,record) {
				var re = /(\w+)\s(\w+)/;
				var newval = record.data.TJMASUK.replace(re, "$1T$2");
				var tgl1 = new Date(newval);
				var tgl2 = Ext.Date.format(record.data.TANGGAL,'Y-m-d')+'T'+record.data.JAMDARI;
				var t1 = new Date(Ext.Date.format(tgl1,'m/d/Y H:i:s'));
				var t2 = new Date(tgl2);
				var dt = (t1 - t2);
				
				if (record.data.TJMASUK == null || record.data.TJKELUAR == null) {
					return '<span style="color:red;">' + val + '</span>';
				}
				else if ((dt/60000) >= 300) {
					return '<span style="color:green;">' + val + '</span>';
				}
				else
					return '<span style="color:black;">' + val + '</span>';
			}},{ header: 'ASALDATA', dataIndex: 'ASALDATA', field: {xtype: 'textfield'}, width: 200,
            filterable: true, hidden: true,
			renderer : function(val,metadata,record) {
				var re = /(\w+)\s(\w+)/;
				var newval = record.data.TJMASUK.replace(re, "$1T$2");
				var tgl1 = new Date(newval);
				var tgl2 = Ext.Date.format(record.data.TANGGAL,'Y-m-d')+'T'+record.data.JAMDARI;
				var t1 = new Date(Ext.Date.format(tgl1,'m/d/Y H:i:s'));
				var t2 = new Date(tgl2);
				var dt = (t1 - t2);
				
				if (record.data.TJMASUK == null || record.data.TJKELUAR == null) {
					return '<span style="color:red;">' + val + '</span>';
				}
				else if ((dt/60000) >= 300) {
					return '<span style="color:green;">' + val + '</span>';
				}
				else
					return '<span style="color:black;">' + val + '</span>';
			} },{ header: 'POSTING', dataIndex: 'POSTING', field: {xtype: 'textfield'}, width: 200,
            filterable: true,hidden: true,
			renderer : function(val,metadata,record) {
				var re = /(\w+)\s(\w+)/;
				var newval = record.data.TJMASUK.replace(re, "$1T$2");
				var tgl1 = new Date(newval);
				var tgl2 = Ext.Date.format(record.data.TANGGAL,'Y-m-d')+'T'+record.data.JAMDARI;
				var t1 = new Date(Ext.Date.format(tgl1,'m/d/Y H:i:s'));
				var t2 = new Date(tgl2);
				var dt = (t1 - t2);
				
				if (record.data.TJMASUK == null || record.data.TJKELUAR == null) {
					return '<span style="color:red;">' + val + '</span>';
				}
				else if ((dt/60000) >= 300) {
					return '<span style="color:green;">' + val + '</span>';
				}
				else
					return '<span style="color:black;">' + val + '</span>';
			}},{ header: 'USERNAME', dataIndex: 'USERNAME', width: 200,
            filterable: true,hidden: true,
			renderer : function(val,metadata,record) {
				var re = /(\w+)\s(\w+)/;
				var newval = record.data.TJMASUK.replace(re, "$1T$2");
				var tgl1 = new Date(newval);
				var tgl2 = Ext.Date.format(record.data.TANGGAL,'Y-m-d')+'T'+record.data.JAMDARI;
				var t1 = new Date(Ext.Date.format(tgl1,'m/d/Y H:i:s'));
				var t2 = new Date(tgl2);
				var dt = (t1 - t2);
				
				if (record.data.TJMASUK == null || record.data.TJKELUAR == null) {
					return '<span style="color:red;">' + val + '</span>';
				}
				else if ((dt/60000) >= 300) {
					return '<span style="color:green;">' + val + '</span>';
				}
				else
					return '<span style="color:black;">' + val + '</span>';
			}}];
			
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
				}]
			},docktool
		];
		
		docktool.add([
			'->',{
				text	: 'Salah Cek Log',
				action	: 'filter'
			},{
				text	: 'Salah Shift',
				action	: 'shift'
			},{
				itemId	: 'ubahshift',
				text	: 'Ubah Shift',
				action	: 'ubah',
				disabled: true
			},{
				text: 'Clear Filter Data',
				handler: function () {
					me.filters.clearFilters();
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