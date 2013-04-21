Ext.define('YMPI.model.riwayattraining', {
    extend: 'Ext.data.Model',
    fields		: [
					'NIK',
					'NOURUT',
					'KETERANGAN',
					'NAMATRAINING',
					'TEMPAT',
					'PENYELENGGARA',
					'TGLMULAI',
					'TGLSAMPAI'
          		   ],
	idProperty	: 'NIK'
});
