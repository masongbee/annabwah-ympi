Ext.define('YMPI.view.MUTASI.v_karyawan_form', {
	extend	: 'Ext.form.Panel',
	
	alias	: 'widget.v_karyawan_form',
	
	region:'east',
	id: 'east-region-container',
	
	title		: 'Create/Update karyawan',
    bodyPadding	: 5,
    autoScroll	: true,
    
    initComponent: function(){
		/* STORE start */
		var grade_store = Ext.create('YMPI.store.s_grade', {
			autoLoad: true
		});
		
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
		
		var unit_store = Ext.create('YMPI.store.s_unitkerja', {
			autoLoad: true
		});
		
		var leveljabatan_store = Ext.create('YMPI.store.s_leveljabatan', {
			autoLoad: true
		});
		var jabatan_pure_store = Ext.create('YMPI.store.s_jabatan_pure', {
			autoLoad: true
		});
		
		var status_store = Ext.create('Ext.data.Store', {
    	    fields: ['value', 'display'],
    	    data : [
    	        {"value":"T", "display":"TETAP"},
    	        {"value":"K", "display":"KONTRAK"},
    	        {"value":"C", "display":"PERCOBAAN"},
    	        {"value":"P", "display":"PENSIUN"},
    	        {"value":"H", "display":"PHK"},
    	        {"value":"M", "display":"MENINGGAL"}
    	    ]
    	});
		/* STORE end */
		
    	/*
		 * Deklarasi variable setiap field
		 */
		 
		var NIK_field = Ext.create('Ext.form.field.Text', {
			itemId: 'NIK_field',
			name: 'NIK', /* column name of table */
			fieldLabel: 'NIK',
			allowBlank: false, /* jika primary_key */
			maxLength: 10 /* length of column name */
		});
		/*var KODEUNIT_field = Ext.create('Ext.form.field.ComboBox', {
			name: 'KODEUNIT', // column name of table 
			fieldLabel: 'Kode Unit <font color=red>(*)</font>',
			store: unit_store,
			queryMode: 'local',
			displayField: 'NAMAUNIT',
			valueField: 'KODEUNIT',
			tpl: Ext.create('Ext.XTemplate',
                '<tpl for=".">',
                    '<div class="x-boundlist-item">{NAMAUNIT_TREE}</div>',
                '</tpl>'
            ),
			allowBlank: false,
			listeners: {
				select: function(combo, records){
					var kodeunit_value = records[0].data.KODEUNIT;
					KODEJAB_field.reset();
					KODEJAB_field.getStore().load({
						params: {KODEUNIT: kodeunit_value}
					});
				}
			}
		});*/
		var KODEUNIT_field = Ext.create('Ext.form.field.Text', {
			name: 'KODEUNIT', /* column name of table */
			fieldLabel: 'Kode Unit <font color=red>(*)</font>',
			hidden: true
		});
		var NAMAUNIT_field = Ext.create('Ext.form.field.Text', {
			name: 'NAMAUNIT', /* column name of table */
			fieldLabel: 'Unit Kerja <font color=red>(*)</font>',
			readOnly: true
		});
		var KODEKEL_field = Ext.create('Ext.form.field.Text', {
			name: 'KODEKEL', /* column name of table */
			fieldLabel: 'Kode Kelompok',
			hidden: true
		});
		var NAMAKEL_field = Ext.create('Ext.form.field.Text', {
			name: 'NAMAKEL', /* column name of table */
			fieldLabel: 'Kelompok',
			readOnly: true
		});
		/*var GRADE_field = Ext.create('Ext.form.field.ComboBox', {
			name: 'GRADE', // column name of table 
			fieldLabel: 'Grade <font color=red>(*)</font>',
			store: grade_store,
			queryMode: 'local',
			displayField: 'KETERANGAN',
			valueField: 'GRADE',
			allowBlank: false
		});*/
		var KODEGRADE_field = Ext.create('Ext.form.field.Text', {
			name: 'GRADE', /* column name of table */
			fieldLabel: 'Grade',
			hidden: true
		});
		var NAMAGRADE_field = Ext.create('Ext.form.field.Text', {
			name: 'KETERANGAN', /* column name of table */
			fieldLabel: 'Grade <font color=red>(*)</font>',
			readOnly: true
		});
		var KODEJAB_field = Ext.create('Ext.form.field.ComboBox', {
			name: 'KODEJAB', /* column name of table */
			fieldLabel: 'Level Jabatan <font color=red>(*)</font>',
			store: leveljabatan_store,
			queryMode: 'local',
			displayField: 'NAMALEVEL',
			valueField: 'KODEJAB',
			allowBlank: false,
			listeners: {
				select: function(combo, records){
					var grade_value = records[0].data.GRADE;
					var keterangan_value = records[0].data.KETERANGAN;
					KODEGRADE_field.setValue(grade_value);
					NAMAGRADE_field.setValue(keterangan_value);
				}
			}
		});
		var IDJAB_field = Ext.create('Ext.form.field.ComboBox', {
			name: 'IDJAB', /* column name of table */
			fieldLabel: 'ID Jabatan <font color=red>(*)</font>',
			store: jabatan_pure_store,
			queryMode: 'local',
			displayField: 'IDJAB',
			valueField: 'IDJAB',
			allowBlank: false,
			listeners: {
				select: function(combo, records){
					console.log(records[0]);
					var kodeunit_value = records[0].data.KODEUNIT;
					var namaunit_value = records[0].data.NAMAUNIT;
					var kodekel_value = records[0].data.KODEKEL;
					var namakel_value = records[0].data.NAMAKEL;
					KODEUNIT_field.setValue(kodeunit_value);
					NAMAUNIT_field.setValue(namaunit_value);
					KODEKEL_field.setValue(kodekel_value);
					NAMAKEL_field.setValue(namakel_value);
				}
			}
		});
		var NAMAKAR_field = Ext.create('Ext.form.field.Text', {
			name: 'NAMAKAR', /* column name of table */
			fieldLabel: 'Nama',
			maxLength: 50 /* length of column name */
		});
		var TGLMASUK_field = Ext.create('Ext.form.field.Date', {
			name: 'TGLMASUK', /* column name of table */
			format: 'Y-m-d',
			fieldLabel: 'Tgl Masuk <font color=red>(*)</font>'
		});
		var JENISKEL_field = Ext.create('Ext.form.RadioGroup', {
			flex: 1,
			layout: {
				autoFlex: false
			},
			defaults: {
				name: 'JENISKEL',
				margin: '0 15 0 0'
			},
			items: [{
				inputValue: 'L',
				boxLabel: 'Laki-laki',
				checked: true
			}, {
				inputValue: 'P',
				boxLabel: 'Perempuan'
			}]
		});
		var ALAMAT_field = Ext.create('Ext.form.field.TextArea', {
			name: 'ALAMAT', /* column name of table */
			fieldLabel: 'Alamat',
			maxLength: 40 /* length of column name */
		});
		var DESA_field = Ext.create('Ext.form.field.Text', {
			name: 'DESA' /* column name of table */
		});
		var RT_field = Ext.create('Ext.form.field.Text', {
			name: 'RT', /* column name of table */
			maxLength: 3,/* length of column name */
			width: 50
		});
		var RW_field = Ext.create('Ext.form.field.Text', {
			name: 'RW', /* column name of table */
			maxLength: 3, /* length of column name */
			width: 50
		});
		var KECAMATAN_field = Ext.create('Ext.form.field.Text', {
			name: 'KECAMATAN', /* column name of table */
			maxLength: 20 /* length of column name */
		});
		var KOTA_field = Ext.create('Ext.form.field.Text', {
			name: 'KOTA', /* column name of table */
			maxLength: 20 /* length of column name */
		});
		var TELEPON_field = Ext.create('Ext.form.field.Text', {
			name: 'TELEPON', /* column name of table */
			fieldLabel: 'Telepon',
			maxLength: 15, /* length of column name */
			flex: 2/*,
			//emptyText: 'xxx-xxx-xxxx',
			//maskRe: /[\d\-]/,
			//regex: /^\d{3}-\d{3}-\d{4}$/,
			//regexText: 'Must be in the format xxx-xxx-xxxx'*/
		});
		var TMPLAHIR_field = Ext.create('Ext.form.field.Text', {
			name: 'TMPLAHIR', /* column name of table */
			maxLength: 20 /* length of column name */
		});
		var TGLLAHIR_field = Ext.create('Ext.form.field.Date', {
			name: 'TGLLAHIR', /* column name of table */
			format: 'Y-m-d'
		});
		var ANAKKE_field = Ext.create('Ext.form.field.Number', {
			name: 'ANAKKE', /* column name of table */
			fieldLabel: 'ANAKKE',
			maxLength: 11 /* length of column name */
		});
		var JMLSAUDARA_field = Ext.create('Ext.form.field.Number', {
			name: 'JMLSAUDARA', /* column name of table */
			fieldLabel: 'JMLSAUDARA',
			maxLength: 11 /* length of column name */
		});
		var PENDIDIKAN_field = Ext.create('Ext.form.field.Text', {
			name: 'PENDIDIKAN', /* column name of table */
			fieldLabel: 'PENDIDIKAN',
			maxLength: 3 /* length of column name */
		});
		var JURUSAN_field = Ext.create('Ext.form.field.Text', {
			name: 'JURUSAN', /* column name of table */
			fieldLabel: 'JURUSAN',
			maxLength: 20 /* length of column name */
		});
		var NAMASEKOLAH_field = Ext.create('Ext.form.field.Text', {
			name: 'NAMASEKOLAH', /* column name of table */
			fieldLabel: 'NAMASEKOLAH',
			maxLength: 20 /* length of column name */
		});
		var AGAMA_field = Ext.create('Ext.form.field.ComboBox', {
			name: 'AGAMA', /* column name of table */
			store: agama_store,
			queryMode: 'local',
			displayField: 'display',
			valueField: 'value'
		});
		var NAMAAYAH_field = Ext.create('Ext.form.field.Text', {
			name: 'NAMAAYAH', /* column name of table */
			fieldLabel: 'NAMAAYAH',
			maxLength: 20 /* length of column name */
		});
		var STATUSAYAH_field = Ext.create('Ext.form.field.Text', {
			name: 'STATUSAYAH', /* column name of table */
			fieldLabel: 'STATUSAYAH',
			maxLength: 1 /* length of column name */
		});
		var ALAMATAYAH_field = Ext.create('Ext.form.field.TextArea', {
			name: 'ALAMATAYAH', /* column name of table */
			fieldLabel: 'ALAMATAYAH',
			maxLength: 40 /* length of column name */
		});
		var PENDDKAYAH_field = Ext.create('Ext.form.field.Text', {
			name: 'PENDDKAYAH', /* column name of table */
			fieldLabel: 'PENDDKAYAH',
			maxLength: 3 /* length of column name */
		});
		var PEKERJAYAH_field = Ext.create('Ext.form.field.Text', {
			name: 'PEKERJAYAH', /* column name of table */
			fieldLabel: 'PEKERJAYAH',
			maxLength: 20 /* length of column name */
		});
		var NAMAIBU_field = Ext.create('Ext.form.field.Text', {
			name: 'NAMAIBU', /* column name of table */
			fieldLabel: 'NAMAIBU',
			maxLength: 20 /* length of column name */
		});
		var STATUSIBU_field = Ext.create('Ext.form.field.Text', {
			name: 'STATUSIBU', /* column name of table */
			fieldLabel: 'STATUSIBU',
			maxLength: 1 /* length of column name */
		});
		var ALAMATIBU_field = Ext.create('Ext.form.field.TextArea', {
			name: 'ALAMATIBU', /* column name of table */
			fieldLabel: 'ALAMATIBU',
			maxLength: 40 /* length of column name */
		});
		var PENDDKIBU_field = Ext.create('Ext.form.field.Text', {
			name: 'PENDDKIBU', /* column name of table */
			fieldLabel: 'PENDDKIBU',
			maxLength: 3 /* length of column name */
		});
		var PEKERJIBU_field = Ext.create('Ext.form.field.Text', {
			name: 'PEKERJIBU', /* column name of table */
			fieldLabel: 'PEKERJIBU',
			maxLength: 20 /* length of column name */
		});
		var KAWIN_field = Ext.create('Ext.form.RadioGroup', {
			layout: {
				autoFlex: false
			},
			defaults: {
				name: 'KAWIN',
				margin: '0 15 0 0'
			},
			width: 360,
			items: [{
				inputValue: 'K',
				boxLabel: 'Sudah Kawin',
				checked: true
			}, {
				inputValue: 'B',
				boxLabel: 'Belum Kawin'
			}, {
				inputValue: 'D',
				boxLabel: 'Duda'
			}, {
				inputValue: 'J',
				boxLabel: 'Janda'
			}],
			listeners: {
				change: function(me, newValue, oldValue){
					//console.log(oldValue.KAWIN);
					//console.log(newValue.KAWIN);
				}
			}
		});
		var TGLKAWIN_field = Ext.create('Ext.form.field.Date', {
			name: 'TGLKAWIN', /* column name of table */
			format: 'Y-m-d',
			fieldLabel: 'Tgl Kawin <font color=red>(*)</font>',
			labelWidth: 80,
			width: 200
		});
		var NAMAPASANGAN_field = Ext.create('Ext.form.field.Text', {
			name: 'NAMAPASANGAN', /* column name of table */
			fieldLabel: 'NAMAPASANGAN',
			maxLength: 20 /* length of column name */
		});
		var ALAMATPAS_field = Ext.create('Ext.form.field.TextArea', {
			name: 'ALAMATPAS', /* column name of table */
			fieldLabel: 'ALAMATPAS',
			maxLength: 40 /* length of column name */
		});
		var TMPLAHIRPAS_field = Ext.create('Ext.form.field.Text', {
			name: 'TMPLAHIRPAS', /* column name of table */
			fieldLabel: 'TMPLAHIRPAS',
			maxLength: 20 /* length of column name */
		});
		var TGLLAHIRPAS_field = Ext.create('Ext.form.field.Date', {
			name: 'TGLLAHIRPAS', /* column name of table */
			format: 'Y-m-d',
			fieldLabel: 'TGLLAHIRPAS'
		});
		var AGAMAPAS_field = Ext.create('Ext.form.field.Text', {
			name: 'AGAMAPAS', /* column name of table */
			fieldLabel: 'AGAMAPAS',
			maxLength: 1 /* length of column name */
		});
		var PEKERJPAS_field = Ext.create('Ext.form.field.Text', {
			name: 'PEKERJPAS', /* column name of table */
			fieldLabel: 'PEKERJPAS',
			maxLength: 20 /* length of column name */
		});
		var KATPEKERJAAN_field = Ext.create('Ext.form.field.Text', {
			name: 'KATPEKERJAAN', /* column name of table */
			fieldLabel: 'KATPEKERJAAN',
			maxLength: 1 /* length of column name */
		});
		var BHSJEPANG_field = Ext.create('Ext.form.RadioGroup', {
			flex: 1,
			layout: {
				autoFlex: false
			},
			defaults: {
				name: 'BHSJEPANG',
				margin: '0 15 0 0'
			},
			items: [{
				inputValue: 0,
				boxLabel: 'Tidak Bisa',
				checked: true
			}, {
				inputValue: 1,
				boxLabel: 'Bisa'
			}, {
				inputValue: 2,
				boxLabel: 'Intermediate'
			}, {
				inputValue: 3,
				boxLabel: 'Mahir'
			}]
		});
		var JAMSOSTEK_field = Ext.create('Ext.form.field.Checkbox', {
			name: 'JAMSOSTEK', /* column name of table */
			boxLabel: 'JAMSOSTEK <font color=red>(*)</font>'
		});
		var TGLJAMSOSTEK_field = Ext.create('Ext.form.field.Date', {
			name: 'TGLJAMSOSTEK', /* column name of table */
			format: 'Y-m-d',
			fieldLabel: 'Tgl <font color=red>(*)</font>',
			labelWidth: 50
		});
		var STATUS_field = Ext.create('Ext.form.field.ComboBox', {
			name: 'STATUS', /* column name of table */
			store: status_store,
			queryMode: 'local',
			displayField: 'display',
			valueField: 'value',
			width: 120
		});
		var TGLSTATUS_field = Ext.create('Ext.form.field.Date', {
			name: 'TGLSTATUS', /* column name of table */
			format: 'Y-m-d',
			fieldLabel: 'Tgl',
			labelWidth: 20,
			width: 140
		});
		var TGLMUTASI_field = Ext.create('Ext.form.field.Date', {
			name: 'TGLMUTASI', /* column name of table */
			format: 'Y-m-d',
			fieldLabel: 'TGLMUTASI'
		});
		var NOURUTKTRK_field = Ext.create('Ext.form.field.Number', {
			name: 'NOURUTKTRK', /* column name of table */
			fieldLabel: 'No. Urut Kontrak',
			maxLength: 11, /* length of column name */
			labelWidth: 105,
			width: 162
		});
		var TGLKONTRAK_field = Ext.create('Ext.form.field.Date', {
			name: 'TGLKONTRAK', /* column name of table */
			format: 'Y-m-d',
			fieldLabel: 'Tgl Kontrak <font color=red>(*)</font>',
			labelWidth: 90,
			width: 210
		});
		var LAMAKONTRAK_field = Ext.create('Ext.form.field.Number', {
			name: 'LAMAKONTRAK', /* column name of table */
			fieldLabel: 'Lama Kontrak',
			maxLength: 11, /* length of column name */
			labelWidth: 90,
			width: 147
		});
		var NOACCKAR_field = Ext.create('Ext.form.field.Text', {
			name: 'NOACCKAR', /* column name of table */
			fieldLabel: 'NOACCKAR',
			maxLength: 12 /* length of column name */
		});
		var NAMABANK_field = Ext.create('Ext.form.field.Text', {
			name: 'NAMABANK', /* column name of table */
			fieldLabel: 'NAMABANK',
			maxLength: 20 /* length of column name */
		});
		var FOTO_field = Ext.create('Ext.form.field.File', {
			itemId: 'foto_field',
			name: 'FOTO', /* column name of table */
			fieldLabel: 'FOTO',
			buttonText: 'Select Photo...'
		});
		var USERNAME_field = Ext.create('Ext.form.field.Text', {
			name: 'USERNAME', /* column name of table */
			fieldLabel: 'USERNAME',
			maxLength: 12 /* length of column name */
		});
		var STATTUNKEL_field = Ext.create('Ext.form.RadioGroup', {
			flex: 1,
			layout: {
				autoFlex: false
			},
			defaults: {
				name: 'STATTUNKEL',
				margin: '0 15 0 0'
			},
			items: [{
				inputValue: 'F',
				boxLabel: 'Full, Suami/Istri dan Anak'
			}, {
				inputValue: 'A',
				boxLabel: 'Anak saja'
			}, {
				inputValue: 'P',
				boxLabel: 'Pasangan saja, Suami atau Istri'
			}]
		});
		var ZONA_field = Ext.create('Ext.form.field.Text', {
			name: 'ZONA', /* column name of table */
			fieldLabel: 'ZONA',
			maxLength: 1 /* length of column name */
		});
		var STATTUNTRAN_field = Ext.create('Ext.form.field.Checkbox', {
			name: 'STATTUNTRAN', /* column name of table */
			fieldLabel: 'Tunjangan Transport',
			labelWidth: 150
		});
        Ext.apply(this, {
            fieldDefaults: {
                labelAlign: 'right',
                labelWidth: 120,
                msgTarget: 'qtip',
				anchor: '100%'
            },
			//defaultType: 'textfield',
            items: [{
                xtype: 'fieldset',
                title: 'Data Pribadi',
                //defaultType: 'textfield',
                layout: 'anchor',
                defaults: {
                    anchor: '100%'
                },
                items: [NIK_field, NAMAKAR_field, {
                	xtype: 'fieldcontainer',
                	fieldLabel: 'Laki-laki/Perempuan',
                	layout: 'hbox',
                	defaultType: 'textfield',
                	defaults: {
                		hideLabel: true
                	},
                	items: [JENISKEL_field]
                }, {
                	xtype: 'fieldcontainer',
                	fieldLabel: 'Tempat, Tgl Lahir <font color=red>(*)</font>',
                	layout: 'hbox',
                	defaultType: 'textfield',
                	defaults: {
                		hideLabel: true
                	},
                	items: [TMPLAHIR_field, {
                		xtype: 'label',
                		text: ',',
                		margin: '10 5 0 5'
                	}, TGLLAHIR_field]
                }, {
                	xtype: 'fieldcontainer',
                	fieldLabel: 'Agama <font color=red>(*)</font>',
                	layout: 'hbox',
                	defaultType: 'textfield',
                	defaults: {
                		hideLabel: true
                	},
                	items: [AGAMA_field]
                }, ALAMAT_field, {
                	xtype: 'fieldcontainer',
                	fieldLabel: 'Desa',
                	layout: 'hbox',
                	defaultType: 'textfield',
                	defaults: {
                		hideLabel: true
                	},
                	items: [DESA_field, {
                		xtype: 'label',
                		text: 'RT/RW',
                		margin: '5 5 0 5'
                	}, RT_field, {
                		xtype: 'label',
                		text: '/',
                		margin: '5 5 0 5'
                	}, RW_field]
                }, {
					xtype: 'fieldcontainer',
                	fieldLabel: 'Kecamatan, Kota',
                	layout: 'hbox',
                	defaultType: 'textfield',
                	defaults: {
                		hideLabel: true
                	},
					items: [KECAMATAN_field, {
                		xtype: 'label',
                		text: ', ',
                		margin: '5 5 0 5'
                	}, KOTA_field]
				}
				,TELEPON_field
				,ANAKKE_field
				,JMLSAUDARA_field
				,PENDIDIKAN_field
				,JURUSAN_field
				,NAMASEKOLAH_field
				,FOTO_field]
            }, {
                xtype: 'fieldset',
                title: 'Data Kekaryawanan',
                defaultType: 'textfield',
                layout: 'anchor',
                defaults: {
                    anchor: '100%'
                },
				items: [IDJAB_field,KODEUNIT_field,NAMAUNIT_field,KODEKEL_field,NAMAKEL_field,KODEJAB_field,KODEGRADE_field,NAMAGRADE_field,TGLMASUK_field
				,KATPEKERJAAN_field
				,{
                	xtype: 'fieldcontainer',
                	fieldLabel: 'Bhs Jepang <font color=red>(*)</font>',
                	layout: 'hbox',
                	defaults: {
                		hideLabel: true
                	},
                	items: [BHSJEPANG_field]
                }
				,{
                	xtype: 'fieldcontainer',
                	fieldLabel: 'Asuransi',
                	layout: 'hbox',
                	defaults: {
                		hideLabel: true
                	},
                	items: [JAMSOSTEK_field,{
                		xtype: 'label',
                		text: ', ',
                		margin: '5 5 0 5'
                	},TGLJAMSOSTEK_field]
                }
				,{
                	xtype: 'fieldcontainer',
                	fieldLabel: 'Status <font color=red>(*)</font>',
                	layout: 'hbox',
					defaults: {
                		hideLabel: true
                	},
                	items: [STATUS_field, {
                		xtype: 'label',
                		text: ', ',
                		margin: '5 5 0 5'
                	},TGLSTATUS_field, {
                		xtype: 'label',
                		text: ', ',
                		margin: '5 5 0 5'
                	}, NOURUTKTRK_field, {
                		xtype: 'label',
                		text: ', ',
                		margin: '5 5 0 5'
                	},TGLKONTRAK_field, {
                		xtype: 'label',
                		text: ', ',
                		margin: '5 5 0 5'
                	},LAMAKONTRAK_field]
                }
				,TGLMUTASI_field
				,NOACCKAR_field,NAMABANK_field,USERNAME_field
				,ZONA_field
				,{
                	xtype: 'fieldcontainer',
                	fieldLabel: 'Tunjangan Keluarga <font color=red>(*)</font>',
					labelWidth: 150,
                	layout: 'hbox',
					defaults: {
                		hideLabel: true,
						margin: '0 15 0 0'
                	},
                	items: [STATTUNKEL_field]
                }
				,STATTUNTRAN_field]
			}, {
                xtype: 'fieldset',
                title: 'Data Perkawinan',
                defaultType: 'textfield',
                layout: 'anchor',
                defaults: {
                    anchor: '100%'
                },
				items: [{
                	xtype: 'fieldcontainer',
                	fieldLabel: 'Kawin <font color=red>(*)</font>',
                	layout: 'hbox',
                	defaults: {
                		hideLabel: true
                	},
                	items: [KAWIN_field, {
						xtype: 'label',
						text: ', ',
						margin: '5 5 0 5',
						width: 10
					}, TGLKAWIN_field]
                },NAMAPASANGAN_field,ALAMATPAS_field,TMPLAHIRPAS_field,TGLLAHIRPAS_field,AGAMAPAS_field,PEKERJPAS_field]
			}, {
                xtype: 'fieldset',
                title: 'Data Orang Tua',
                defaultType: 'textfield',
                layout: 'anchor',
                defaults: {
                    anchor: '100%'
                },
				items: [NAMAAYAH_field,STATUSAYAH_field,ALAMATAYAH_field,PENDDKAYAH_field,PEKERJAYAH_field,NAMAIBU_field,STATUSIBU_field,ALAMATIBU_field,PENDDKIBU_field,PEKERJIBU_field]
			}],
			
	        buttons: [{
                iconCls: 'icon-save',
                itemId: 'save',
                text: 'Save',
                disabled: true,
				formBind: true,
                action: 'save'
            }, {
                iconCls: 'icon-add',
				itemId: 'create',
                text: 'Create',
				formBind: true,
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