Ext.define('YMPI.model.m_permohonanijin', {
	extend: 'Ext.data.Model',
	alias		: 'widget.permohonanijinModel',
	fields		: ['NOIJIN','NIK','NAMAKAR','NAMAUNIT','NAMAKEL','JENISABSEN'
				   ,{
						name: 'TANGGAL',
						type: 'date',
						dateFormat: 'Y-m-d'
					}
				   ,'JAMDARI','JAMSAMPAI','KEMBALI','AMBILCUTI','DIAGNOSA','TINDAKAN'
				   ,'ANJURAN','PETUGASKLINIK','NIKATASAN1','NIKPERSONALIA','STATUSIJIN'
				   ,'NIKGA','NIKDRIVER','NIKSECURITY','USERNAME'
				   ,'NIKATASAN1','NAMAKARATASAN1'
				   ,'NIKHR','NAMAKARHR','SISA'],
	idProperty	: 'NOIJIN'	
});