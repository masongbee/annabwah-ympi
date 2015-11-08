Ext.define('YMPI.view.TRANSAKSI.v_pelamar', {
	extend: 'Ext.grid.Panel',
	requires: ['YMPI.store.s_pelamar'],
	
	title		: 'pelamar',
	itemId		: 'Listpelamar',
	alias       : 'widget.Listpelamar',
	store 		: 's_pelamar',
	columnLines : true,
	frame		: true,
	multiSelect : true,
	
	margin		: 0,
	selectedIndex: -1,
	
	initComponent: function(){
		var me = this;

		/* STORE start */
		var agama_store = Ext.create('Ext.data.Store', {
    	    fields: ['value', 'display'],
    	    data : [
    	        {"value":"I", "display":"Islam"},
    	        {"value":"P", "display":"Kristen Protestan"},
    	        {"value":"K", "display":"Kristen Katholik"},
    	        {"value":"H", "display":"Hindu"},
    	        {"value":"B", "display":"Budha"},
    	        {"value":"C", "display":"Konghucu"}
    	    ]
    	});
    	var jeniskel_store = Ext.create('Ext.data.Store', {
    	    fields: ['value', 'display'],
    	    data : [
    	        {"value":"L", "display":"Laki-laki"},
    	        {"value":"P", "display":"Perempuan"}
    	    ]
    	});
    	var kawin_store = Ext.create('Ext.data.Store', {
    	    fields: ['value', 'display'],
    	    data : [
    	        {"value":"K", "display":"Sudah Kawin"},
    	        {"value":"B", "display":"Belum Kawin"},
    	        {"value":"D", "display":"Duda"},
    	        {"value":"J", "display":"Janda"}
    	    ]
    	});
    	var pendidikan_store = Ext.create('Ext.data.Store', {
    	    fields: ['value', 'display'],
    	    data : [
    	        {"value":"SD", "display":"Sekolah Dasar"},
    	        {"value":"SMP", "display":"Sekolah Menengah Pertama"},
    	        {"value":"SMA", "display":"Sekolah Menengah Atas / SMK"},
    	        {"value":"S1", "display":"Strata 1"},
    	        {"value":"S2", "display":"Strata 2"}
    	    ]
    	});

		var gellow_store = Ext.create('YMPI.store.s_posisilowongan', {
			autoLoad: true
		});
		/* STORE end */
		
		var KTP_field = Ext.create('Ext.form.field.Text', {
			allowBlank: false,
			maxLength: 16
		});
		var NAMAPELAMAR_field = Ext.create('Ext.form.field.Text', {
			allowBlank: false,
			maxLength: 30
		});
		var AGAMA_field = Ext.create('Ext.form.field.ComboBox', {
			store: agama_store,
			queryMode: 'local',
			displayField: 'display',
			valueField: 'value'
		});
		var ALAMAT_field = Ext.create('Ext.form.field.Text', {
			maxLength: 50
		});
		var JENISKEL_field = Ext.create('Ext.form.field.ComboBox', {
			store: jeniskel_store,
			queryMode: 'local',
			displayField: 'display',
			valueField: 'value'
		});
		var JURUSAN_field = Ext.create('Ext.form.field.Text', {
			maxLength: 20
		});
		var ALAMAT_field = Ext.create('Ext.form.field.Text', {
			maxLength: 50
		});
		var KAWIN_field = Ext.create('Ext.form.field.ComboBox', {
			store: kawin_store,
			queryMode: 'local',
			displayField: 'display',
			valueField: 'value'
		});
		var KOTA_field = Ext.create('Ext.form.field.Text', {
			maxLength: 50
		});
		var NAMASEKOLAH_field = Ext.create('Ext.form.field.Text', {
			maxLength: 20
		});
		var PENDIDIKAN_field = Ext.create('Ext.form.field.ComboBox', {
			store: pendidikan_store,
			queryMode: 'local',
			displayField: 'display',
			valueField: 'value'
		});
		var TELEPON_field = Ext.create('Ext.form.field.Text', {
			maxLength: 15
		});
		var TGLLAHIR_field = Ext.create('Ext.form.field.Date', {
			format: 'Y-m-d'
		});
		var TMPLAHIR_field = Ext.create('Ext.form.field.Text', {
			maxLength: 20
		});
		var STATUSPELAMAR_field = Ext.create('Ext.form.field.Text', {
			maxLength: 1,
			readOnly: true
		});
		var GELLOW_field = Ext.create('Ext.form.field.ComboBox', {
			store: gellow_store,
			queryMode: 'remote',
			displayField: 'GELLOW',
			valueField: 'GELLOW',
			allowBlank: false,
			tpl: Ext.create('Ext.XTemplate',
                '<tpl for=".">',
                    '<div class="x-boundlist-item">Gelombang: [<b>{GELLOW}</b>] - {GELLOW_KETERANGAN}<br/>',
                    'Level Jabatan: [<b>{KODEJAB}</b>] - {NAMAGRADE}<br/>',
                    'ID Jabatan: [<b>{IDJAB}</b>] - {NAMAUNIT}<br/></div>',
                '</tpl>'
            ),
            listeners: {
				select: function(combo, records){
					var idjab_value = records[0].data.IDJAB;
					var namaunit_value = records[0].data.NAMAUNIT;
					var kodejab_value = records[0].data.KODEJAB;
					var namagrade_value = records[0].data.NAMAGRADE;
					IDJAB_field.setValue(idjab_value);
					NAMAUNIT_field.setValue(namaunit_value);
					KODEJAB_field.setValue(kodejab_value);
					NAMAGRADE_field.setValue(namagrade_value);
				}
			}
		});
		var IDJAB_field = Ext.create('Ext.form.field.Text', {
			readOnly: true
		});
		var NAMAUNIT_field = Ext.create('Ext.form.field.Text', {
			readOnly: true
		});
		var KODEJAB_field = Ext.create('Ext.form.field.Text', {
			readOnly: true
		});
		var NAMAGRADE_field = Ext.create('Ext.form.field.Text', {
			readOnly: true
		});
		var JMLPOSISI_field = Ext.create('Ext.form.field.Number', {
			maxLength: 11
		});
		
		this.rowEditing = Ext.create('Ext.grid.plugin.RowEditing', {
			clicksToEdit: 2,
			clicksToMoveEditor: 1,
			listeners: {
				'beforeedit': function(editor, e){
					if(! (/^\s*$/).test(e.record.data.KTP) ){
						KTP_field.setReadOnly(true);
					}else{
						KTP_field.setReadOnly(false);
					}
					
				},
				'canceledit': function(editor, e){
					if((/^\s*$/).test(e.record.data.KTP)){
						editor.cancelEdit();
						// var sm = e.grid.getSelectionModel();
						// e.store.remove(sm.getSelection());
						var thiStore = e.store;
						var arrRec = [];
						thiStore.each(function(rec){
							if (rec.data.KTP == '') {
								arrRec.push(rec);
							};
						}, this);
						arrRec.forEach(function(data){
							thiStore.remove(data);
						});
					}
				},
				'validateedit': function(editor, e){
				},
				'afteredit': function(editor, e){
					var me = this;
					if((/^\s*$/).test(e.record.data.KTP) ){
						Ext.Msg.alert('Peringatan', 'Kolom "KTP" tidak boleh kosong.');
						return false;
					}
					
					var jsonData = Ext.encode(e.record.data);
					
					Ext.Ajax.request({
						method: 'POST',
						url: 'c_pelamar/save',
						params: {data: jsonData},
						success: function(response){
							e.store.reload({
								callback: function(){
									var newRecordIndex = e.store.findBy(
										function(record, id) {
											if (parseFloat(record.get('KTP')) === e.record.data.KTP) {
												return true;
											}
											return false;
										}
									);
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
			 	xtype: 'checkcolumn',
			 	columnHeaderCheckbox: true,
			 	dataIndex: 'PILIH',
			 	width: 50,
			 	editor: {
			       	xtype: 'checkbox',
			       	cls: 'x-grid-checkheader-editor'
			   	},
			   	listeners: {
			   		checkchange: function(thisfield, rowIndex, checked){
			   			var rec = me.getStore().getAt(rowIndex);
			   			var data = rec.data;
			   			if (data.STATUSPELAMAR != 'F') {
			   				me.getStore().getAt(rowIndex).set('PILIH', false);
			   			} else if(data.STATUSPELAMAR == 'F'){
			   				me.getStore().getAt(rowIndex).set('PILIH', true);
			   				me.getSelectionModel().select(rec, true);
			   			};
			   		}
			   	}
			},{
				header: 'STATUSPELAMAR',
				dataIndex: 'STATUSPELAMAR',
				width: 160,
				field: STATUSPELAMAR_field
			},{
				header: 'KTP',
				dataIndex: 'KTP',
				width: 120,
				field: KTP_field
			},{
				header: 'NAMAPELAMAR',
				dataIndex: 'NAMAPELAMAR',
				width: 120,
				field: NAMAPELAMAR_field
			},{
				header: 'AGAMA',
				dataIndex: 'AGAMA',
				width: 120,
				field: AGAMA_field
			},{
				header: 'ALAMAT',
				dataIndex: 'ALAMAT',
				width: 120,
				field: ALAMAT_field
			},{
				header: 'JENISKEL',
				dataIndex: 'JENISKEL',
				width: 120,
				field: JENISKEL_field
			},{
				header: 'JURUSAN',
				dataIndex: 'JURUSAN',
				width: 120,
				field: JURUSAN_field
			},{
				header: 'KAWIN',
				dataIndex: 'KAWIN',
				width: 120,
				field: KAWIN_field
			},{
				header: 'KOTA',
				dataIndex: 'KOTA',
				width: 120,
				field: KOTA_field
			},{
				header: 'NAMASEKOLAH',
				dataIndex: 'NAMASEKOLAH',
				width: 120,
				field: NAMASEKOLAH_field
			},{
				header: 'PENDIDIKAN',
				dataIndex: 'PENDIDIKAN',
				width: 120,
				field: PENDIDIKAN_field
			},{
				header: 'TELEPON',
				dataIndex: 'TELEPON',
				width: 120,
				field: TELEPON_field
			},{
				header: 'TGLLAHIR',
				dataIndex: 'TGLLAHIR',
				width: 120,
				field: TGLLAHIR_field
			},{
				header: 'TMPLAHIR',
				dataIndex: 'TMPLAHIR',
				width: 120,
				field: TMPLAHIR_field
			},{
				header: 'GELLOW',
				dataIndex: 'GELLOW',
				width: 120,
				field: GELLOW_field
			},{
				header: 'IDJAB',
				dataIndex: 'IDJAB',
				width: 160,
				field: IDJAB_field
			},{
				header: 'NAMAUNIT',
				dataIndex: 'NAMAUNIT',
				width: 160,
				field: NAMAUNIT_field
			},{
				header: 'KODEJAB',
				dataIndex: 'KODEJAB',
				width: 160,
				field: KODEJAB_field
			},{
				header: 'NAMAGRADE',
				dataIndex: 'NAMAGRADE',
				width: 160,
				field: NAMAGRADE_field
			}];
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
				}, '-',{
					xtype: 'fieldcontainer',
					layout: 'hbox',
					defaultType: 'button',
					items: [{
						itemId	: 'btnmutasi',
						text	: 'MUTASI',
						iconCls	: 'icon-save',
						action	: 'mutasi',
						disabled: true
					}]
				}]
			}),
			{
				xtype: 'pagingtoolbar',
				store: 's_pelamar',
				dock: 'bottom',
				displayInfo: true
			}
		];
		this.callParent(arguments);
		
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