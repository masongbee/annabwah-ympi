Ext.define('YMPI.model.m_importpres', {
	extend: 'Ext.data.Model',
	alias		: 'widget.importpresModel',
	fields		: ['ID',{
        name: 'TANGGAL',
        type: 'date',
        dateFormat: 'Y-m-d'
    },'NIK','NAMAKAR','NAMAUNIT','SINGKATAN','NAMAKEL','NAMASHIFT','SHIFTKE','JAMDARI','JAMSAMPAI','STATUS',{
        name: 'TJMASUK',
        type: 'datetime',
        dateFormat: 'Y-m-d H:i:s'
    },{
        name: 'TJKELUAR',
        type: 'datetime',
        dateFormat: 'Y-m-d H:i:s'
    },'ASALDATA','POSTING','USERNAME','NAMAHARI','JENISLIBUR'],
	idProperty	: 'ID'
});