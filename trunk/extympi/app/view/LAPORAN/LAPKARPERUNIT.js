Ext.define('YMPI.view.LAPORAN.LAPKARPERUNIT', {
	extend: 'Ext.grid.Panel',
	
	requires	: ['Ext.grid.*',
	        	    'Ext.data.*',
	        	    'Ext.form.field.Number',
	        	    'Ext.form.field.Date',
	        	    'Ext.tip.QuickTipManager'],
	
	itemId		: 'LAPKARPERUNIT',
    alias       : 'widget.LAPKARPERUNIT',
	//store 		: 'Task',
    
    title		: 'Daftar Karyawan Per Unit Kerja',
    columnLines : true,
    frame		: true,
    margins		: 0,
    
    initComponent: function(){
    	/*
    	 * Bisa menggunakan ==# var rowEditing #== atau ==# this.rowEditing #==
    	 */
    	/*var rowEditing = Ext.create('Ext.grid.plugin.RowEditing', {
			  clicksToEdit: 2
		});*/
    	var karstatus = Ext.create('Ext.data.Store', {
    	    fields: ['value', 'display'],
    	    data : [
    	        {"value":"S", "display":"Semua"},
    	        {"value":"K", "display":"Kontrak"},
    	        {"value":"C", "display":"Percobaan"},
    	        {"value":"M", "display":"Meninggal"},
    	        {"value":"H", "display":"PHK"}
    	    ]
    	});
    	var cb_karstatus = Ext.create('Ext.form.field.ComboBox', {
        	name: 'STATUS',
        	fieldLabel: '<b>Status Karyawan</b>',
        	labelWidth: 120,
            store: karstatus,
            queryMode: 'local',
            displayField: 'display',
            valueField: 'value',
            width: 250
    	});
    	
    	var store_unit_kerja = Ext.create('YMPI.store.UnitKerja');
    	var cb_unitkerja = Ext.create('Ext.form.field.ComboBox', {
        	name: 'STATUS',
        	fieldLabel: '<b>Unit Kerja</b>',
        	labelWidth: 120,
            store: store_unit_kerja,
            queryMode: 'remote',
            displayField: 'NAMAUNIT',
            valueField: 'KODEUNIT',
            flex: 2
    	});
    	
    	this.store = Ext.create('YMPI.store.Task');
    	this.features = [{
            id: 'group',
            ftype: 'groupingsummary',
            groupHeaderTpl: '{name}',
            hideGroupedHeader: true,
            enableGroupingMenu: false
        }];
        this.columns = [{
            header: 'Unit',
            width: 180,
            sortable: false,
            dataIndex: 'NAMAUNIT'
        }, {
            header: 'No. NIK',
            width: 180,
            sortable: false,
            dataIndex: 'NIK'
        }, {
            header: 'Nama',
            flex: 1,
            sortable: false,
            dataIndex: 'NAMA'
        }, {
            header: 'TL',
            width: 80,
            sortable: false,
            dataIndex: 'TL',
            renderer: Ext.util.Format.dateRenderer('m/d/Y')
        }, {
            header: 'Tgl Masuk',
            width: 80,
            sortable: false,
            dataIndex: 'TGLMASUK',
            renderer: Ext.util.Format.dateRenderer('m/d/Y')
        }, {
            header: 'Masa Kerja',
            width: 75,
            sortable: false,
            dataIndex: 'MASAKERJA'
        }, {
            header: 'Alamat',
            width: 75,
            sortable: false,
            dataIndex: 'ALAMAT'
        }];
        
        this.dockedItems = [Ext.create('Ext.form.Panel', {
            bodyPadding: 5,

            items: [{
                xtype: 'fieldcontainer',
                fieldLabel: '<b>Karyawan efektif s.d. Tgl</b>',
                labelWidth: 170,

                // The body area will contain three text fields, arranged
                // horizontally, separated by draggable splitters.
                layout: 'hbox',
                items: [{
                    xtype: 'datefield',
                    flex: 1
                }, {
                    xtype: 'splitter'
                }, cb_karstatus, {
                    xtype: 'splitter'
                }, cb_unitkerja]
            }],
            renderTo: Ext.getBody()
        })];
        
        this.callParent(arguments);
    }

});