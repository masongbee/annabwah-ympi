Ext.define('YMPI.model.m_importpres', {
	extend: 'Ext.data.Model',
	alias		: 'widget.importpresModel',
	fields		: [{
        name: 'TANGGAL',
        type: 'date',
        dateFormat: 'Y-m-d'
    },'NIK','NAMAKAR','NAMAUNIT','NAMAKEL','NAMASHIFT','SHIFTKE','JAMDARI','JAMSAMPAI',{
        name: 'TJMASUK',
        type: 'datetime',
        dateFormat: 'Y-m-d H:i:s'
    },{
        name: 'TJKELUAR',
        type: 'datetime',
        dateFormat: 'Y-m-d H:i:s'
    },'ASALDATA','POSTING','USERNAME']	
});