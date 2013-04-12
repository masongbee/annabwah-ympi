Ext.define('YMPI.model.Keluarga', {
    extend: 'Ext.data.Model',
    fields		: [
					'NOURUT',
					'STATUSKEL',
					'NIK',
					'NAMAKEL',
					'JENISKEL',
					'ALAMAT',
					'TMPLAHIR',
					'TGLLAHIR',
					'PENDIDIKAN',
					'PEKERJAAN',
					'TANGGUNGSPKK',
					'TGLMENINGGAL'
          		   ],
	idProperty	: 'NIK'
});
