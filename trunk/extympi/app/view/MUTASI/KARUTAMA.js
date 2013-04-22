Ext.define('YMPI.view.MUTASI.KARUTAMA', {
	extend: 'Ext.grid.Panel',
    
	alias: 'widget.KARUTAMA',
	
	//title: 'Daftar Karyawan',
	//iconCls: 'icon-grid',
	frame		: true,
	columnLines : true,
	enableLocking: true,
	//cls		: 'x-panel-default-framed-noradius',
	
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
	        				'<img src="./assets/upload/3554.jpg" height="60px" />',
	        			'</div>',
	        			'</td>',
	        		'</tr>',
        		'</table>'
                )
    }],
    
    store: 'Karyawan',
    
    initComponent: function(){
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
                                items: [{
                                    text	: 'Add',
                                    iconCls	: 'icon-add',
                                    action	: 'create'
                                }, '-', {
                                    itemId	: 'btndelete',
                                    text	: 'Delete',
                                    iconCls	: 'icon-remove',
                                    action	: 'delete',
                                    disabled: true
                                }, '-', '-',{
                                	text	: 'Export Excel',
                                    iconCls	: 'icon-excel',
                                    action	: 'xexcel'
                                }, '-',{
                                	text	: 'Cetak',
                                    iconCls	: 'icon-print',
                                    action	: 'print'
                                }]
                            },
                            {
                                xtype: 'pagingtoolbar',
                                store: 'Karyawan',
                                dock: 'bottom',
                                displayInfo: false
                            }
                        ];
        
        this.callParent(arguments);
    }

});