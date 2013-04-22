Ext.define('YMPI.view.MUTASI.MONKAR', {
	extend: 'Ext.grid.Panel',
    requires: [],
    
    itemId		: 'MONKAR',
    alias       : 'widget.MONKAR',
	store 		: 'Karyawan',
    
    title		: 'Monitoring Karyawan',
	//iconCls: 'icon-grid',
	frame		: true,
	columnLines : true,
	frame		: true,
    margins		: 0,
	enableLocking: true,
	
    plugins: [{
        ptype: 'rowexpander',
        rowBodyTpl : new Ext.XTemplate(
        		'<p><b>NIK:</b> {NIK}</p>',
                '<p><b>NAMA:</b> {NAMAKAR}</p><br>',
                '<p><b>ALAMAT:</b> {ALAMAT}, {DESA} RT/RW: {RT}/{RW}, {KECAMATAN}, {KOTA}</p>'
                )
    }],
    
    initComponent: function(){
    	var karstatus = Ext.create('Ext.data.Store', {
    	    fields: ['value', 'display'],
    	    data : [
    	        {"value":"T", "display":"Karyawan Tetap"},
    	        {"value":"K", "display":"Karyawan Kontrak"}
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
            hidden: true,
            listeners: {
                change: {
                    fn: this.onStatusChange,
                    scope: this,
                    buffer: 100
                }
            }
    	});
    	
        this.columns = [
            { header: 'NIK',  dataIndex: 'NIK' },
            { header: 'Nama', dataIndex: 'NAMAKAR', flex: 1 },
            { header: 'L/P', dataIndex: 'JENISKEL' },
            { header: 'Tgl Lahir', dataIndex: 'TGLLAHIR' },
            { header: 'Tmpt Lahir', dataIndex: 'TMPLAHIR' },
            { header: 'Telepon', dataIndex: 'TELEPON' },
            { header: 'Agama', dataIndex: 'AGAMA' }
        ];
        
        this.dockedItems = [
            {
            	xtype: 'toolbar',
            	frame: true,
                items: [cb_status, cb_masa_kerja]
            },
            {
                xtype: 'pagingtoolbar',
                store: 'Karyawan',
                dock: 'bottom',
                displayInfo: false
            }
        ];
        
        this.callParent(arguments);
    },
    
    onStatusChange: function(me, newValue, oldValue, eOpts){
    	console.log('value = '+newValue);
    	Ext.getCmp('cb_masa_kerja').setVisible(true);
    }

});