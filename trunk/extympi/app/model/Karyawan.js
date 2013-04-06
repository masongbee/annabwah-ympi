Ext.define('YMPI.model.Karyawan', {
    extend: 'Ext.data.Model',
    alias		: 'widget.KaryawanModel',
    fields		: [
          		   /* Informasi Identitas */
          		   'NIK' ,'NAMAKAR' ,'JENISKEL' ,'TGLLAHIR' ,'TMPLAHIR' ,'TELEPON' ,'AGAMA'
          		   /* Alamat */
          		   ,'ALAMAT' ,'DESA' ,'RT' ,'RW' ,'KECAMATAN' ,'KOTA'
          		   /* Status Karyawan */
          		   ,'KODEUNIT' ,'KODEJAB' ,'GRADE' ,'TGLMASUK' ,'BHSJEPANG'],
	idProperty	: 'NIK'
});
