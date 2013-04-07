Ext.define('YMPI.view.MUTASI.KaryawanList', {
	extend: 'Ext.grid.Panel',
    
	alias: 'widget.KaryawanList',
	
	title: 'Daftar Karyawan',
	//iconCls: 'icon-grid',
	frame		: true,
	columnLines : true,
	enableLocking: true,
	cls		: 'x-panel-default-framed-noradius',
	
    plugins: [{
        ptype: 'rowexpander',
        rowBodyTpl : new Ext.XTemplate(
        		'<tpl for="."><div class="search-item">',
                '<span><b>Grade: [{GRADE}]</b></span>',
                '<span>{KETERANGAN}</span>',
                '</div></tpl>')
    }],
    
    store: 'Grade',
    
    initComponent: function(){
        this.columns = [
            { header: 'Grade',  dataIndex: 'GRADE' },
            { header: 'Keterangan', dataIndex: 'KETERANGAN', width: 250 }
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
                                store: 'Grade',
                                dock: 'bottom',
                                displayInfo: false
                            }
                        ];
        
        this.callParent(arguments);
    }

});