Ext.define('YMPI.model.m_rptpresensi', {
	extend: 'Ext.data.Model',
	alias		: 'widget.rptpresensiModel',
	fields		: ['ID',{
        name: 'TANGGAL',
        type: 'date',
        dateFormat: 'Y-m-d'
    },'NIK','NAMAKAR','NAMAUNIT','SINGKATAN','NAMAKEL','NAMASHIFT','SHIFTKE','ASALDATA','POSTING','USERNAME']	
});