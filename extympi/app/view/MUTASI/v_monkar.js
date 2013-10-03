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
    	        {"value":"T", "display":"Karyawan Tetap"},
    	        {"value":"K", "display":"Karyawan Kontrak"}
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
        	fieldLabel: 'Status',
        	labelWidth: 60,
            store: karstatus,
            queryMode: 'local',
            displayField: 'display',
            valueField: 'value',
            width: 250,
            listeners: {
                change: {
                    fn: this.onStatusChange,
                    scope: this,
                    buffer: 100
                }
            }
    	});
    	
    	var cb_sisa_masa_kerja = Ext.create('Ext.form.field.ComboBox', {
    		id: 'cb_sisa_masa_kerja',
        	name: 'SISA_MASA_KERJA',
        	fieldLabel: 'Sisa Masa Kerja',
        	labelWidth: 100,
            store: karsisamasakerja,
            queryMode: 'local',
            displayField: 'display',
            valueField: 'value',
            width: 250,
            hidden: true,
            listeners: {
                change: {
                    fn: this.onSisaMasaKerjaChange,
                    scope: this,
                    buffer: 100
                }
            }
    	});
    	
    	var cb_masa_kerja = Ext.create('Ext.form.field.ComboBox', {
    		id: 'cb_masa_kerja',
        	name: 'MASA_KERJA',
        	fieldLabel: 'Masa Kerja',
        	labelWidth: 100,
            store: karmasakerja,
            queryMode: 'local',
            displayField: 'display',
            valueField: 'value',
            width: 250,
            hidden: true
    	});
    	
    	var date_tertentu = Ext.create('Ext.form.field.Date', {
    		id: 'date_tertentu',
    		fieldLabel: 'Date',
            name: 'date',
            hidden: true,
            // The value matches the format; will be parsed and displayed using that format.
            format: 'd/m/Y'
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
    },
	
	onStatusChange: function(me, newValue, oldValue, eOpts){
    	if(newValue=='K'){
    		Ext.getCmp('cb_sisa_masa_kerja').setVisible(true);
    		Ext.getCmp('cb_masa_kerja').setVisible(false);
    	}else if(newValue=='T'){
    		Ext.getCmp('cb_sisa_masa_kerja').setVisible(false);
    		Ext.getCmp('cb_masa_kerja').setVisible(true);
    	}
    },
    
    onSisaMasaKerjaChange: function(mefunc, newValue, oldValue, eOpts){
    	if(newValue==0){
    		Ext.getCmp('date_tertentu').setVisible(true);
    	}else{
			this.getStore().reload({
				params: {
					query: '',
					filter_sisa_masa_kerja: newValue
				}
			});
		}
    }

});