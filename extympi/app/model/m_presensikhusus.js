Ext.define('YMPI.model.m_presensikhusus', {
	extend: 'Ext.data.Model',
	alias		: 'widget.presensikhususModel',
	fields		: ['ID','NIK','NAMASHIFT','SHIFTKE','TANGGAL'
		,{
			name: 'TJMASUK',
			type: 'datetime',
			dateFormat: 'Y-m-d H:i:s'
		},{
			name: 'TJKELUAR',
			type: 'datetime',
			dateFormat: 'Y-m-d H:i:s'
		}
		,'ASALDATA','JENISABSEN','JENISLEMBUR','EXTRADAY']	
});