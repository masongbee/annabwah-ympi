Ext.define('YMPI.view.MUTASI.v_monkar', {
	extend: 'Ext.grid.Panel',
	requires: ['YMPI.store.s_karyawan'],
	
	title		: 'Monitoring Karyawan',
	itemId		: 'Listmonkar',
	alias       : 'widget.Listmonkar',
	store 		: 's_karyawan',
	columnLines : true,
	frame		: false,
	
	margin		: 0,
	selectedIndex : -1,
	
	plugins: [{
        ptype: 'rowexpander',
        rowBodyTpl : new Ext.XTemplate(
			'<table>',
				'<tr>',
					'<td><div style="float: left; width: 400px;">',
						'<p><b>NIK:</b> {NIK}</p>',
						'<p><b>NAMA:</b> {NAMAKAR}</p><br>',
						'<p><b>ALAMAT:</b> {ALAMAT}, {DESA} RT/RW: {RT}/{RW}, {KECAMATAN}, {KOTA}</p>',
					'</div>',
					'<div style="float: left;">',
						'<img src="./photos/{FOTO}" height="60px" />',
					'</div>',
					'</td>',
				'</tr>',
			'</table>'
			)
    }],
	
	initComponent: function(){
		var me = this;
		
		/*STORE start*/
		var karstatus = Ext.create('Ext.data.Store', {
    	    fields: ['value', 'display'],
    	    data : [
    	        {"value":"T", "display":"Tetap"},
    	        {"value":"K", "display":"Kontrak"},
    	        {"value":"C", "display":"Masa Percobaan"},
    	        {"value":"P", "display":"Pensiun"},
    	        {"value":"H", "display":"di-PHK"},
    	        {"value":"M", "display":"Meninggal"}
    	    ]
    	});
    	var karsisamasakerja = Ext.create('Ext.data.Store', {
    	    fields: ['value', 'display'],
    	    data : [
    	        {"value":"1", "display":"Kurang dari 1 bulan"},
    	        {"value":"3", "display":"Kurang dari 3 bulan"},
    	        {"value":"6", "display":"Kurang dari 6 bulan"},
    	        {"value":"12", "display":"Kurang dari 1 tahun"},
    	        {"value":"0", "display":"Per Tanggal"}
    	    ]
    	});
    	var karmasakerja = Ext.create('Ext.data.Store', {
    	    fields: ['value', 'display'],
    	    data : [
    	        {"value":"1", "display":"Kurang dari 1 bulan"},
    	        {"value":"3", "display":"Kurang dari 3 bulan"},
    	        {"value":"6", "display":"Kurang dari 6 bulan"},
    	        {"value":"12", "display":"Kurang dari 1 tahun"},
    	        {"value":"13", "display":"Lebih dari 1 tahun"}
    	    ]
    	});
		/*STORE end*/
		
		var cb_status = Ext.create('Ext.form.field.ComboBox', {
        	name: 'STATUS',
        	fieldLabel: '<b>Status Karyawan</b>',
        	labelWidth: 110,
            store: karstatus,
            queryMode: 'local',
            displayField: 'display',
            valueField: 'value',
            width: 290,
            listeners: {
				select: function(combo, records, e){
					var status_value = records[0].data.value;

					date_tertentu.setVisible(false);
					if (records[0].data.value == 'K') {
						cb_sisa_masa_kerja.setVisible(true);
						
						cb_masa_kerja.setVisible(false);
					}else if(records[0].data.value == 'T') {
						cb_sisa_masa_kerja.setVisible(false);
						
						cb_masa_kerja.setVisible(true);
					}else{
						cb_sisa_masa_kerja.setVisible(false);
						
						cb_masa_kerja.setVisible(false);
					}

					cb_sisa_masa_kerja.reset();
					cb_masa_kerja.reset();
					date_tertentu.reset();

					me.getStore().getProxy().extraParams.query = '';
					me.getStore().getProxy().extraParams.statusval = status_value;
					me.getStore().getProxy().extraParams.masakerjaval = '';
					me.getStore().getProxy().extraParams.sisamasakerjaval = '';
					me.getStore().getProxy().extraParams.pertanggalval = '';
					me.getStore().load();
				}
            }
    	});
    	
    	var cb_sisa_masa_kerja = Ext.create('Ext.form.field.ComboBox', {
    		id: 'cb_sisa_masa_kerja',
        	name: 'SISA_MASA_KERJA',
        	fieldLabel: '<b>Sisa Masa Kerja</b>',
        	labelWidth: 100,
            store: karsisamasakerja,
            queryMode: 'local',
            displayField: 'display',
            valueField: 'value',
            width: 250,
            hidden: true,
            listeners: {
                select: function(combo, records, e){
					if (records[0].data.value == 0) {
						date_tertentu.setVisible(true);
					}else{
						date_tertentu.reset();
						date_tertentu.setVisible(false);

						me.getStore().getProxy().extraParams.query = '';
						me.getStore().getProxy().extraParams.statusval = cb_status.getValue();
						me.getStore().getProxy().extraParams.masakerjaval = '';
						me.getStore().getProxy().extraParams.sisamasakerjaval = records[0].data.value;
						me.getStore().getProxy().extraParams.pertanggalval = '';
						me.getStore().load();
					}
				}
            }
    	});
    	
    	var cb_masa_kerja = Ext.create('Ext.form.field.ComboBox', {
    		id: 'cb_masa_kerja',
        	name: 'MASA_KERJA',
        	fieldLabel: '<b>Masa Kerja</b>',
        	labelWidth: 80,
            store: karmasakerja,
            queryMode: 'local',
            displayField: 'display',
            valueField: 'value',
            width: 250,
            hidden: true,
            listeners: {
            	select: function(combo, records, e){
            		me.getStore().getProxy().extraParams.query = '';
					me.getStore().getProxy().extraParams.statusval = cb_status.getValue();
					me.getStore().getProxy().extraParams.masakerjaval = records[0].data.value;
					me.getStore().getProxy().extraParams.sisamasakerjaval = '';
					me.getStore().getProxy().extraParams.pertanggalval = '';
					me.getStore().load();
            	}
            }
    	});
    	
    	var date_tertentu = Ext.create('Ext.form.field.Date', {
    		id: 'date_tertentu',
    		fieldLabel: '<b>Tanggal</b>',
			labelWidth: 50,
            name: 'date',
            hidden: true,
			width: 170,
            // The value matches the format; will be parsed and displayed using that format.
            format: 'd M, Y',
            listeners: {
            	select: function(field, value, e){
            		me.getStore().getProxy().extraParams.query = '';
					me.getStore().getProxy().extraParams.statusval = cb_status.getValue();
					me.getStore().getProxy().extraParams.masakerjaval = '';
					me.getStore().getProxy().extraParams.sisamasakerjaval = '';
					me.getStore().getProxy().extraParams.pertanggalval = value;
					me.getStore().load();
            	}
            }
    	});
		
		this.columns = [
			{ header: 'NIK',  dataIndex: 'NIK', locked: true },
            { header: 'Nama', dataIndex: 'NAMAKAR', locked: true, width: 200 },
            { header: 'L/P', dataIndex: 'JENISKEL' },
            { header: 'Tgl Lahir', dataIndex: 'TGLLAHIR' },
            { header: 'Pendidikan', dataIndex: 'PENDIDIKAN' },
			{ header: 'Unit Kerja', dataIndex: 'KODEUNIT' },
			{ header: 'Jabatan', dataIndex: 'KODEJAB' },
            { header: 'Alamat', dataIndex: 'ALAMAT', width: 200 },
            { header: 'Tgl Kontrak', dataIndex: 'TGLKONTRAK' },
            { header: 'Lama Kontrak', dataIndex: 'LAMAKONTRAK' }];
		this.dockedItems = [
			Ext.create('Ext.toolbar.Toolbar', {
				items: [{
					xtype: 'fieldcontainer',
					layout: 'hbox',
					defaultType: 'button',
					items: [cb_status, {
						xtype: 'splitter'
					}, {
						xtype: 'splitter'
					}, cb_masa_kerja, {
						xtype: 'splitter'
					}, cb_sisa_masa_kerja, {
						xtype: 'splitter'
					}, date_tertentu]
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
					}, {
						xtype: 'splitter'
					}, {
						text	: 'Cetak',
						iconCls	: 'icon-print',
						action	: 'print'
					}]
				}]
			}),
			{
				xtype: 'pagingtoolbar',
				store: 's_karyawan',
				dock: 'bottom',
				displayInfo: true
			}
		];
		this.callParent(arguments);
		
		this.on('itemclick', this.gridSelection);
		//this.getView().on('refresh', this.refreshSelection, this);
	},	
	
	gridSelection: function(me, record, item, index, e, eOpts){
		//me.getSelectionModel().select(index);
		this.selectedIndex = index;
		//this.getView().saveState();
		//console.log(this.getView());
	},
	
	refreshSelection: function() {
        this.getSelectionModel().select(this.selectedIndex);   /*Ext.defer(this.setScrollTop, 30, this, [this.getView().scrollState.top]);*/
    }/*,
	
	onStatusChange: function(me, newValue, oldValue, eOpts){
    	if(newValue=='K'){
    		Ext.getCmp('cb_sisa_masa_kerja').setVisible(true);
    		Ext.getCmp('cb_masa_kerja').setVisible(false);
    	}else if(newValue=='T'){
    		Ext.getCmp('cb_sisa_masa_kerja').setVisible(false);
    		Ext.getCmp('cb_masa_kerja').setVisible(true);
    	}
    },
    
    onSisaMasaKerjaChange: function(field, newValue, oldValue, eOpts){
    	if(newValue==0){
    		Ext.getCmp('date_tertentu').setVisible(true);
    	}else{
			Ext.getCmp('date_tertentu').setVisible(false);
			this.getStore().reload({
				params: {
					query: '',
					filter_sisa_masa_kerja: newValue
				}
			});
		}
    }*/

});