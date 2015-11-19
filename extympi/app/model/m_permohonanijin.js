Ext.define('YMPI.model.m_permohonanijin', {
	extend: 'Ext.data.Model',
	alias		: 'widget.permohonanijinModel',
	fields		: ['NOIJIN','NIK','NAMAKAR'/*,'NAMAUNIT','NAMAKEL'*/,'JENISABSEN','JENISABSEN_ALIAS'
				   ,{
						name: 'TANGGAL',
						type: 'date',
						dateFormat: 'Y-m-d'
					}
				   ,'JAMDARI','JAMSAMPAI','KEMBALI','AMBILCUTI','AMBILCUTI_KETERANGAN'/*,'DIAGNOSA','TINDAKAN'*/
				   /*,'ANJURAN','PETUGASKLINIK'*/,'STATUSIJIN','STATUSIJIN_KETERANGAN'
				   /*,'NIKGA','NIKDRIVER','NIKSECURITY','USERNAME'*/
				   ,'NIKATASAN1','NAMAKARATASAN1'
				   ,'NIKPERSONALIA','NIKHR','NAMAKARHR','SISA'
				   ,'KETERANGAN'],
	idProperty	: 'NOIJIN'	
});