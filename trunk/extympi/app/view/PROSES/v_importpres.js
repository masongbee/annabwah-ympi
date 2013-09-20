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
			format: 'Y-m-d'
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
		
		var SHIFTKE_field = Ext.create('Ext.form.field.Text', {
			allowBlank : true,
			readOnly:true
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
				},
				'canceledit': function(editor, e){
					if((/^\s*$/).test(e.record.data.NIK) || (/^\s*$/).test(e.record.data.TANGGAL) ){
						editor.cancelEdit();
						//var sm = e.grid.getSelectionModel();
						//e.store.remove(sm.getSelection());
					}
				},
				'validateedit': function(editor, e){
					/*console.info('validate edit :');
					delete e.record.data['JAMDARI'];
					delete e.record.data['JAMSAMPAI'];
					delete e.record.data['NAMAKAR'];
					delete e.record.data['NAMAKEL'];
					delete e.record.data['NAMASHIFT'];
					delete e.record.data['NAMAUNIT'];
					delete e.record.data['SHIFTKE'];
					delete e.record.data['SINGKATAN'];
					console.info(e.record.data);*/
				},
				'afteredit': function(editor, e){
					//console.info('after edit :');
					//console.info(e.record.data);
					var me = this;
					if((/^\s*$/).test(e.record.data.NIK) || (/^\s*$/).test(e.record.data.TANGGAL) ){
						Ext.Msg.alert('Peringatan', 'Kolom "NIK","TANGGAL" tidak boleh kosong.');
						return false;
					}
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
									//me.grid.getView().select(recordIndex); 
									me.grid.getSelectionModel().select(newRecordIndex);
								}
							});
						}
					});
					return true;
				}
			}
		});
		
		this.columns = [{header: 'NO. ID', dataIndex: 'ID', width: 100,
            filterable: true, sortable : true,hidden: true,
			renderer : function(val,metadata,record) {
				if(record.data.TJMASUK != null)
				{
					var t = Ext.util.Format.substr(record.data.TJMASUK,0,10);
					var j = Ext.util.Format.substr(record.data.TJMASUK,11,8);
					var tgl1 = new Date(t+'T'+j);
					var tgl2 = Ext.Date.format(record.data.TANGGAL,'Y-m-d')+'T'+record.data.JAMDARI;
					var t1 = new Date(Ext.Date.format(tgl1,'m/d/Y H:i:s'));
					var t2 = new Date(tgl2);
					var dt = (t1 - t2);
					
					if ((dt/60000) >= 300) {
						return '<span style="color:green;">' + val + '</span>';
					}
				}
				
				if (record.data.TJMASUK == null || record.data.TJKELUAR == null) {
					return '<span style="color:red;">' + val + '</span>';
				}
				else
					return '<span style="color:black;">' + val + '</span>';
			}},{ header: 'TANGGAL', dataIndex: 'TANGGAL', field:{xtype:'datefield',format: 'Y-m-d'}, width: 120,
            filterable: true, sortable : true,hidden: false,
			renderer : function(val,metadata,record) {
				var tgl = new Date(val);

				if(record.data.TJMASUK != null)
				{
					var t = Ext.util.Format.substr(record.data.TJMASUK,0,10);
					var j = Ext.util.Format.substr(record.data.TJMASUK,11,8);
					var tgl1 = new Date(t+'T'+j);
					var tgl2 = Ext.Date.format(record.data.TANGGAL,'Y-m-d')+'T'+record.data.JAMDARI;
					var t1 = new Date(Ext.Date.format(tgl1,'m/d/Y H:i:s'));
					var t2 = new Date(tgl2);
					var dt = (t1 - t2);
					
					if ((dt/60000) >= 300) {
						return '<span style="color:green;">' + Ext.Date.format(tgl,'D, d M Y') + '</span>';
					}
				}
				
				if (record.data.TJMASUK == null || record.data.TJKELUAR == null) {
					return '<span style="color:red;">' + Ext.Date.format(tgl,'D, d M Y') + '</span>';
				}
				else
					return '<span style="color:black;">' + Ext.Date.format(tgl,'D, d M Y') + '</span>';
			}},{ header: 'NIK', dataIndex: 'NIK', width: 100,
            filterable: true, sortable : true,hidden: false,
			renderer : function(val,metadata,record) {
				if(record.data.TJMASUK != null)
				{
					var t = Ext.util.Format.substr(record.data.TJMASUK,0,10);
					var j = Ext.util.Format.substr(record.data.TJMASUK,11,8);
					var tgl1 = new Date(t+'T'+j);
					var tgl2 = Ext.Date.format(record.data.TANGGAL,'Y-m-d')+'T'+record.data.JAMDARI;
					var t1 = new Date(Ext.Date.format(tgl1,'m/d/Y H:i:s'));
					var t2 = new Date(tgl2);
					var dt = (t1 - t2);
					
					if ((dt/60000) >= 300) {
						return '<span style="color:green;">' + val + '</span>';
					}
				}
				
				if (record.data.TJMASUK == null || record.data.TJKELUAR == null) {
					return '<span style="color:red;">' + val + '</span>';
				}
				else
					return '<span style="color:black;">' + val + '</span>';
			}},{ header: 'NAMA', dataIndex: 'NAMAKAR', width: 140,
            filterable: true, sortable : true,hidden: false,
			renderer : function(val,metadata,record) {
				if(record.data.TJMASUK != null)
				{
					var t = Ext.util.Format.substr(record.data.TJMASUK,0,10);
					var j = Ext.util.Format.substr(record.data.TJMASUK,11,8);
					var tgl1 = new Date(t+'T'+j);
					var tgl2 = Ext.Date.format(record.data.TANGGAL,'Y-m-d')+'T'+record.data.JAMDARI;
					var t1 = new Date(Ext.Date.format(tgl1,'m/d/Y H:i:s'));
					var t2 = new Date(tgl2);
					var dt = (t1 - t2);
					
					if ((dt/60000) >= 300) {
						return '<span style="color:green;">' + val + '</span>';
					}
				}
				
				if (record.data.TJMASUK == null || record.data.TJKELUAR == null) {
					return '<span style="color:red;">' + val + '</span>';
				}
				else
					return '<span style="color:black;">' + val + '</span>';
			}},{ header: 'NAMA UNIT', dataIndex: 'NAMAUNIT', width: 200,
            filterable: true, hidden: true,
			renderer : function(val,metadata,record) {
				if(record.data.TJMASUK != null)
				{
					var t = Ext.util.Format.substr(record.data.TJMASUK,0,10);
					var j = Ext.util.Format.substr(record.data.TJMASUK,11,8);
					var tgl1 = new Date(t+'T'+j);
					var tgl2 = Ext.Date.format(record.data.TANGGAL,'Y-m-d')+'T'+record.data.JAMDARI;
					var t1 = new Date(Ext.Date.format(tgl1,'m/d/Y H:i:s'));
					var t2 = new Date(tgl2);
					var dt = (t1 - t2);
					
					if ((dt/60000) >= 300) {
						return '<span style="color:green;">' + val + '</span>';
					}
				}
				
				if (record.data.TJMASUK == null || record.data.TJKELUAR == null) {
					return '<span style="color:red;">' + val + '</span>';
				}
				else
					return '<span style="color:black;">' + val + '</span>';
			}},{ header: 'BAGIAN', dataIndex: 'SINGKATAN', width: 80,
            filterable: true, hidden: false,
			renderer : function(val,metadata,record) {
				if(record.data.TJMASUK != null)
				{
					var t = Ext.util.Format.substr(record.data.TJMASUK,0,10);
					var j = Ext.util.Format.substr(record.data.TJMASUK,11,8);
					var tgl1 = new Date(t+'T'+j);
					var tgl2 = Ext.Date.format(record.data.TANGGAL,'Y-m-d')+'T'+record.data.JAMDARI;
					var t1 = new Date(Ext.Date.format(tgl1,'m/d/Y H:i:s'));
					var t2 = new Date(tgl2);
					var dt = (t1 - t2);
					
					if ((dt/60000) >= 300) {
						return '<span style="color:green;">' + val + '</span>';
					}
				}
				
				if (record.data.TJMASUK == null || record.data.TJKELUAR == null) {
					return '<span style="color:red;">' + val + '</span>';
				}
				else
					return '<span style="color:black;">' + val + '</span>';
			}},{ header: 'NAMA SHIFT', dataIndex: 'NAMASHIFT', width: 100,
            filterable: true, hidden: true,
			renderer : function(val,metadata,record) {
				if(record.data.TJMASUK != null)
				{
					var t = Ext.util.Format.substr(record.data.TJMASUK,0,10);
					var j = Ext.util.Format.substr(record.data.TJMASUK,11,8);
					var tgl1 = new Date(t+'T'+j);
					var tgl2 = Ext.Date.format(record.data.TANGGAL,'Y-m-d')+'T'+record.data.JAMDARI;
					var t1 = new Date(Ext.Date.format(tgl1,'m/d/Y H:i:s'));
					var t2 = new Date(tgl2);
					var dt = (t1 - t2);
					
					if ((dt/60000) >= 300) {
						return '<span style="color:green;">' + val + '</span>';
					}
				}
				
				if (record.data.TJMASUK == null || record.data.TJKELUAR == null) {
					return '<span style="color:red;">' + val + '</span>';
				}
				else
					return '<span style="color:black;">' + val + '</span>';
			}},{ header: 'SHIFT', dataIndex: 'SHIFTKE', width: 80,
            filterable: true, hidden: false,
			renderer : function(val,metadata,record) {
				if(record.data.TJMASUK != null)
				{
					var t = Ext.util.Format.substr(record.data.TJMASUK,0,10);
					var j = Ext.util.Format.substr(record.data.TJMASUK,11,8);
					var tgl1 = new Date(t+'T'+j);
					var tgl2 = Ext.Date.format(record.data.TANGGAL,'Y-m-d')+'T'+record.data.JAMDARI;
					var t1 = new Date(Ext.Date.format(tgl1,'m/d/Y H:i:s'));
					var t2 = new Date(tgl2);
					var dt = (t1 - t2);
					
					if ((dt/60000) >= 300) {
						return '<span style="color:green;">' + val + '</span>';
					}
				}
				
				if (record.data.TJMASUK == null || record.data.TJKELUAR == null) {
					return '<span style="color:red;">' + val + '</span>';
				}
				else
					return '<span style="color:black;">' + val + '</span>';
			}},{ header: 'MASUK', dataIndex: 'JAMDARI', width: 100,
            filterable: true, hidden: false,
			renderer : function(val,metadata,record) {
				if(record.data.TJMASUK != null)
				{
					var t = Ext.util.Format.substr(record.data.TJMASUK,0,10);
					var j = Ext.util.Format.substr(record.data.TJMASUK,11,8);
					var tgl1 = new Date(t+'T'+j);
					var tgl2 = Ext.Date.format(record.data.TANGGAL,'Y-m-d')+'T'+record.data.JAMDARI;
					var t1 = new Date(Ext.Date.format(tgl1,'m/d/Y H:i:s'));
					var t2 = new Date(tgl2);
					var dt = (t1 - t2);
					
					if ((dt/60000) >= 300) {
						return '<span style="color:green;">' + val + '</span>';
					}
				}
				
				if (record.data.TJMASUK == null || record.data.TJKELUAR == null) {
					return '<span style="color:red;">' + val + '</span>';
				}
				else
					return '<span style="color:black;">' + val + '</span>';
			}},{ header: 'PULANG', dataIndex: 'JAMSAMPAI', width: 100,
            filterable: true, hidden: false,
			renderer : function(val,metadata,record) {
				if(record.data.TJMASUK != null)
				{
					var t = Ext.util.Format.substr(record.data.TJMASUK,0,10);
					var j = Ext.util.Format.substr(record.data.TJMASUK,11,8);
					var tgl1 = new Date(t+'T'+j);
					var tgl2 = Ext.Date.format(record.data.TANGGAL,'Y-m-d')+'T'+record.data.JAMDARI;
					var t1 = new Date(Ext.Date.format(tgl1,'m/d/Y H:i:s'));
					var t2 = new Date(tgl2);
					var dt = (t1 - t2);
					
					if ((dt/60000) >= 300) {
						return '<span style="color:green;">' + val + '</span>';
					}
				}
				
				if (record.data.TJMASUK == null || record.data.TJKELUAR == null) {
					return '<span style="color:red;">' + val + '</span>';
				}
				else
					return '<span style="color:black;">' + val + '</span>';
			}},{ header: 'TJMASUK', dataIndex: 'TJMASUK', field: {xtype: 'datefield',format: 'Y-m-d H:i:s'}, width: 180,sortable : true,
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
				if(record.data.TJMASUK != null)
				{
					var t = Ext.util.Format.substr(record.data.TJMASUK,0,10);
					var j = Ext.util.Format.substr(record.data.TJMASUK,11,8);
					var tgl1 = new Date(t+'T'+j);
					var tgl2 = Ext.Date.format(record.data.TANGGAL,'Y-m-d')+'T'+record.data.JAMDARI;
					var t1 = new Date(Ext.Date.format(tgl1,'m/d/Y H:i:s'));
					var t2 = new Date(tgl2);
					var dt = (t1 - t2);
					
					if ((dt/60000) >= 300) {
						return '<span style="color:green;">' + val + '</span>';
					}
				}
				
				if (record.data.TJMASUK == null || record.data.TJKELUAR == null) {
					return '<span style="color:red;">' + val + '</span>';
				}
				else
					return '<span style="color:black;">' + val + '</span>';
			}},{ header: 'TJKELUAR', dataIndex: 'TJKELUAR', field: {xtype: 'datefield',format: 'Y-m-d H:i:s'}, width: 180,sortable : true,
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
				if(record.data.TJMASUK != null)
				{
					var t = Ext.util.Format.substr(record.data.TJMASUK,0,10);
					var j = Ext.util.Format.substr(record.data.TJMASUK,11,8);
					var tgl1 = new Date(t+'T'+j);
					var tgl2 = Ext.Date.format(record.data.TANGGAL,'Y-m-d')+'T'+record.data.JAMDARI;
					var t1 = new Date(Ext.Date.format(tgl1,'m/d/Y H:i:s'));
					var t2 = new Date(tgl2);
					var dt = (t1 - t2);
					
					if ((dt/60000) >= 300) {
						return '<span style="color:green;">' + val + '</span>';
					}
				}
				
				if (record.data.TJMASUK == null || record.data.TJKELUAR == null) {
					return '<span style="color:red;">' + val + '</span>';
				}
				else
					return '<span style="color:black;">' + val + '</span>';
			}},{ header: 'ASALDATA', dataIndex: 'ASALDATA', field: {xtype: 'textfield'}, width: 200,
            filterable: true, hidden: true,
			renderer : function(val,metadata,record) {
				if(record.data.TJMASUK != null)
				{
					var t = Ext.util.Format.substr(record.data.TJMASUK,0,10);
					var j = Ext.util.Format.substr(record.data.TJMASUK,11,8);
					var tgl1 = new Date(t+'T'+j);
					var tgl2 = Ext.Date.format(record.data.TANGGAL,'Y-m-d')+'T'+record.data.JAMDARI;
					var t1 = new Date(Ext.Date.format(tgl1,'m/d/Y H:i:s'));
					var t2 = new Date(tgl2);
					var dt = (t1 - t2);
					
					if ((dt/60000) >= 300) {
						return '<span style="color:green;">' + val + '</span>';
					}
				}
				
				if (record.data.TJMASUK == null || record.data.TJKELUAR == null) {
					return '<span style="color:red;">' + val + '</span>';
				}
				else
					return '<span style="color:black;">' + val + '</span>';
			} },{ header: 'POSTING', dataIndex: 'POSTING', field: {xtype: 'textfield'}, width: 200,
            filterable: true,hidden: true,
			renderer : function(val,metadata,record) {
				if(record.data.TJMASUK != null)
				{
					var t = Ext.util.Format.substr(record.data.TJMASUK,0,10);
					var j = Ext.util.Format.substr(record.data.TJMASUK,11,8);
					var tgl1 = new Date(t+'T'+j);
					var tgl2 = Ext.Date.format(record.data.TANGGAL,'Y-m-d')+'T'+record.data.JAMDARI;
					var t1 = new Date(Ext.Date.format(tgl1,'m/d/Y H:i:s'));
					var t2 = new Date(tgl2);
					var dt = (t1 - t2);
					
					if ((dt/60000) >= 300) {
						return '<span style="color:green;">' + val + '</span>';
					}
				}
				
				if (record.data.TJMASUK == null || record.data.TJKELUAR == null) {
					return '<span style="color:red;">' + val + '</span>';
				}
				else
					return '<span style="color:black;">' + val + '</span>';
			}},{ header: 'USERNAME', dataIndex: 'USERNAME', width: 200,
            filterable: true,hidden: true,
			renderer : function(val,metadata,record) {
				if(record.data.TJMASUK != null)
				{
					var t = Ext.util.Format.substr(record.data.TJMASUK,0,10);
					var j = Ext.util.Format.substr(record.data.TJMASUK,11,8);
					var tgl1 = new Date(t+'T'+j);
					var tgl2 = Ext.Date.format(record.data.TANGGAL,'Y-m-d')+'T'+record.data.JAMDARI;
					var t1 = new Date(Ext.Date.format(tgl1,'m/d/Y H:i:s'));
					var t2 = new Date(tgl2);
					var dt = (t1 - t2);
					
					if ((dt/60000) >= 300) {
						return '<span style="color:green;">' + val + '</span>';
					}
				}
				
				if (record.data.TJMASUK == null || record.data.TJKELUAR == null) {
					return '<span style="color:red;">' + val + '</span>';
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