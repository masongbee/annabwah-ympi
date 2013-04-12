Ext.define('YMPI.model.RiwayatKerja', {
    extend: 'Ext.data.Model',
    fields		: [
					'NIK',
					'NOURUT',
					'TAHUN',
					'POSISI',
					'NAMAPERUSH',
					'ALAMAT',
					'LAMABEKERJA',
					'ALASANBERHENTI'
          		   ],
	idProperty	: 'NIK'
});
