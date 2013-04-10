Ext.define('YMPI.model.Karyawan', {
    extend: 'Ext.data.Model',
    alias		: 'widget.KaryawanModel',
    fields		: [
          		   	/* Informasi Identitas */
					'NIK',
					'KODEUNIT',
					'KODEJAB',
					'GRADE',
					'NAMAKAR',
					'TGLMASUK',
					'JENISKEL',
					'ALAMAT',
					'DESA',
					'RT',
					'RW',
					'KECAMATAN',
					'KOTA',
					'TELEPON',
					'TMPLAHIR',
					'TGLLAHIR',
					'ANAKKE',
					'JMLSAUDARA',
					'PENDIDIKAN',
					'JURUSAN',
					'NAMASEKOLAH',
					'AGAMA',
					'NAMAAYAH',
					'STATUSAYAH',
					'ALAMATAYAH',
					'PENDDKAYAH',
					'PEKERJAYAH',
					'NAMAIBU',
					'STATUSIBU',
					'ALAMATIBU',
					'PENDDKIBU',
					'PEKERJIBU',
					'KAWIN',
					'TGLKAWIN',
					'NAMAPASANGAN',
					'ALAMATPAS',
					'TMPLAHIRPAS',
					'TGLLAHIRPAS',
					'AGAMAPAS',
					'PEKERJPAS',
					'KATPEKERJAAN',
					'BHSJEPANG',
					'JAMSOSTEK',
					'TGLJAMSOSTEK',
					'STATUS',
					'TGLSTATUS',
					'TGLMUTASI',
					'NOURUTKTRK',
					'TGLKONTRAK',
					'LAMAKONTRAK',
					'NOACCKAR',
					'NAMABANK',
					'FOTO',
					'USERNAME'
          		   ],
    /*fields		: [
          		   // Informasi Identitas /
          		   'NIK' ,'NAMAKAR' ,'JENISKEL' ,'TGLLAHIR' ,'TMPLAHIR' ,'TELEPON' ,'AGAMA'
          		   // Alamat /
          		   ,'ALAMAT' ,'DESA' ,'RT' ,'RW' ,'KECAMATAN' ,'KOTA'
          		   // Status Karyawan /
          		   ,'KODEUNIT' ,'KODEJAB' ,'GRADE' ,'TGLMASUK' ,'BHSJEPANG'],*/
	idProperty	: 'NIK'
});
