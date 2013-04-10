Ext.define('YMPI.model.Keluarga', {
    extend: 'Ext.data.Model',
    alias		: 'widget.KeluargaModel',
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
    /*fields		: [
          		   // Informasi Identitas /
          		   'NIK' ,'NAMAKAR' ,'JENISKEL' ,'TGLLAHIR' ,'TMPLAHIR' ,'TELEPON' ,'AGAMA'
          		   // Alamat /
          		   ,'ALAMAT' ,'DESA' ,'RT' ,'RW' ,'KECAMATAN' ,'KOTA'
          		   // Status Keluarga /
          		   ,'KODEUNIT' ,'KODEJAB' ,'GRADE' ,'TGLMASUK' ,'BHSJEPANG'],*/
	idProperty	: 'NIK'
});
