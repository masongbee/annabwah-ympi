Ext.define('YMPI.model.m_lapgaji', {
	extend: 'Ext.data.Model',
	alias		: 'widget.lapgajiModel',
	fields		: ['SERIAL_NUMBER','NIK','NAMAKAR','SINGKATAN','TGLMASUK','STATUS','NAMALEVEL','GRADE','STATTUNKEL'
		,'RPUPAHPOKOK','RPTLEMBUR','RPTUNJTETAP','RPTUNJTDKTTP','RPNONUPAH','RPTHR','TOTALPENDAPATAN','RPPUPAHPOKOK'
		,'IURAN','PINJAMAN','TOTALPOTONGAN'
		,'PENDAPATANBERSIH']
});