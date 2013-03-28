Ext.define('YMPI.store.Examples', {
    extend: 'Ext.data.TreeStore',

    autoLoad	: true,
    autoSync	: false,
    
    proxy: {
        type: 'ajax',
        url	: 'c_menus/getMenus'
    },
    
    constructor: function(){
    	this.callParent(arguments);
    }
    
    
    
    /*root: {
        expanded: true,
        children: [
            {
			    text: 'File',
			    expanded: false,
			    children: [
			        { leaf: true, text: 'Info Setting' },
			        { leaf: true, text: 'User Manager' },
			        { leaf: true, text: 'Ganti Password' },
			        { leaf: true, text: 'Logout' }
			    ]
			},
			{
			    text: 'Data Master',
			    expanded: true,
			    children: [
			        { leaf: true, text: 'Unit Kerja dan Jabatan' },
			        { leaf: true, text: 'Status Karyawan' },
			        { leaf: true, text: 'Grade' },
			        { leaf: true, text: 'Tunjangan Keluarga' },
			        { leaf: true, text: 'Kalender Tahunan' },
			        { leaf: true, text: 'Daftar Cuti Karyawan' },
			        { leaf: true, text: 'Pembuatan Shift Kerja' },
			        { leaf: true, text: 'Kendaraan Jemputan' },
			        { leaf: true, text: 'Sopir Kendaraan' },
			        { leaf: true, text: 'Area Penjemputan' },
			        { leaf: true, text: 'RS Provider' },
			        { leaf: true, text: 'Jaminan Rawat Inap RS Provider' },
			        { leaf: true, text: 'Jaminan Rawat Inap RS Non Provider' },
			        { leaf: true, text: 'Batasan Manfaat Maternity' },
			        { leaf: true, text: 'Batasan Manfaat dan Plafon Rawat Jalan' },
			        { leaf: true, text: 'Upah Pokok' },
			        { leaf: true, text: 'Upah Lembur' },
			        { text: 'Tunjangan Tetap', expanded: false,
			        	children: [
		   			        { leaf: true, text: 'Tunjangan Jabatan' },
		   			        { leaf: true, text: 'Tunjangan Bahasa Jepang' },
		   			        { leaf: true, text: 'Tunjangan Keluarga' }
		   			    ]
			        },
			        { text: 'Tunjangan Tidak Tetap', expanded: false,
			        	children: [
		   			        { leaf: true, text: 'Tunjangan Transport' },
		   			        { leaf: true, text: 'Tunjangan Shift' },
		   			        { leaf: true, text: 'Tunjangan Pekerjaan' },
		   			        { leaf: true, text: 'Tunjangan Makan' },
		   			        { leaf: true, text: 'Tunjangan QCP' }
		   			    ]
			        },
			        { text: 'Insentif', expanded: false,
			        	children: [
		   			        { leaf: true, text: 'Insentif Disiplin' },
		   			        { leaf: true, text: 'Insentif Kehadiran' }
		   			    ]
			        },
			        { leaf: true, text: 'Uang Simpati' },
			        { leaf: true, text: 'Kota' },
			        { leaf: true, text: 'Pendidikan' },
			        { leaf: true, text: 'Agama' },
			        { leaf: true, text: 'Jenis Tes' },
			        { leaf: true, text: 'Jenis Training' },
			        { leaf: true, text: 'Periode Penilaian Kerja' },
			    ]
			},
			{
                text: 'Mutasi',
                expanded: false,
                children: [
                    { leaf: true, text: 'Data Karyawan' },
                    { leaf: true, text: 'Monitoring Karyawan Kontrak' },
                    { leaf: true, text: 'Monitoring Karyawan Tetap' },
                    { leaf: true, text: 'Tukar atau Pindah Shift' }
                    
                ]
            },
            {
                text: 'Laporan',
                expanded: false,
                children: [
                    { leaf: true, text: 'Daftar Karyawan Per Unit Kerja' },
                    { leaf: true, text: 'Daftar Karyawan Per Status' },
                    { leaf: true, text: 'Daftar Karyawan Per Jabatan' },
                    { leaf: true, text: 'Daftar Karyawan Per Grade' },
                    { leaf: true, text: 'Karyawan Individual' },
                    { leaf: true, text: 'Rekap Karyawan Per Unit Kerja' },
                    { leaf: true, text: 'Rekap Karyawan Per Status' },
                    { leaf: true, text: 'Rekap Karyawan Per Jabatan' },
                    { leaf: true, text: 'Rekap Karyawan Per Grade' },
                    { leaf: true, text: 'Mutasi Data Karyawan Per Periode' },
                    { leaf: true, text: 'Presensi Harian' },
                    { leaf: true, text: 'Presensi Mingguan' },
                    { leaf: true, text: 'Presensi Bulanan' },
                    { leaf: true, text: 'Presensi Tahunan' },
                    { leaf: true, text: 'Daftar Karyawan Disiplin' },
                    { leaf: true, text: 'Daftar Karyawan Hadir di Luar Shift' },
                    { leaf: true, text: 'Rekap Karyawan Lembur' },
                    { leaf: true, text: 'Daftar Cuti Karyawan s.d. Tanggal Tertentu' },
                    { leaf: true, text: 'Daftar Sakit Karyawan Per Periode' },
                    { leaf: true, text: 'Daftar Ijin Karyawan Per Periode' },
                    { leaf: true, text: 'Rekap Absensi Karyawan Per Periode' },
                    { leaf: true, text: 'Rekap Cuti Karyawan s.d. Tanggal Tertentu' },
                    { leaf: true, text: 'Daftar Karyawan Bolos Per Periode' },
                    { leaf: true, text: 'Daftar Keluar Kota Per Periode' },
                    { leaf: true, text: 'Daftar Keluar Kantor Per Periode' },
                    { leaf: true, text: 'Daftar Karyawan Lembur Per Periode' },
                    { leaf: true, text: 'Pembagian Shift per Periode' },
                    { leaf: true, text: 'Daftar Karyawan per Shift' },
                    { leaf: true, text: 'Daftar Tukar atau Pindah Shift per Periode' },
                    { leaf: true, text: 'Daftar Kendaraan Jemputan' },
                    { leaf: true, text: 'Daftar Sopir' },
                    { leaf: true, text: 'Daftar Area Penjemputan' },
                    { leaf: true, text: 'Pengaturan Kendaraan' },
                    { leaf: true, text: 'Pengaturan Penjemputan Karyawan' },
                    { leaf: true, text: 'Realisasi Penjemputan' },
                    { leaf: true, text: 'Daftar Karyawan Tak Dijemput' },
                    { leaf: true, text: 'Klaim Rawat Jalan Per Periode' },
                    { leaf: true, text: 'Klaim Rawat Inap Per Periode' },
                    { leaf: true, text: 'Klaim Per RS Provider atau Non' },
                    { leaf: true, text: 'Persentase Klaim vs Plafon' },
                    { leaf: true, text: 'Histori Klaim Karyawan' },
                    { leaf: true, text: 'Gaji Karyawan Per Bulan' },
                    { leaf: true, text: 'Rekap Gaji Karyawan Per Bulan' },
                    { leaf: true, text: 'Cetak Slip Gaji Karyawan' },
                    { leaf: true, text: 'Daftar Pelamar per Periode' },
                    { leaf: true, text: 'Daftar Pelamar Lolos Seleksi' },
                    { leaf: true, text: 'Rekap Hasil Rekrutmen' },
                    { leaf: true, text: 'Daftar Pelamar Diterima' },
                    { leaf: true, text: 'Daftar Karyawan Habis Kontrak' },
                    { leaf: true, text: 'Daftar Karyawan Habis Masa Percobaan' },
                    { leaf: true, text: 'Daftar Karyawan Belum Training' },
                    { leaf: true, text: 'Daftar Karyawan Sudah Training' },
                    { leaf: true, text: 'Histori Training Karyawan' },
                    { leaf: true, text: 'Daftar Hasil Penilaian Kinerja' },
                    { leaf: true, text: 'Daftar Kenaikan Gaji' },
                    { leaf: true, text: 'Histori Surat Perintah (SP)' }
                    
                ]
            },
            {
                text: 'Transaksi',
                expanded: false,
                children: [
                    { leaf: true, text: 'Import Data Presensi' },
                    { leaf: true, text: 'Input atau Edit Data Presensi' },
                    { leaf: true, text: 'Pengajuan Cuti' },
                    { leaf: true, text: 'Realisasi Cuti' },
                    { leaf: true, text: 'Karyawan Sakit' },
                    { leaf: true, text: 'Karyawan Ijin' },
                    { leaf: true, text: 'Penggantian Hak Cuti' },
                    { leaf: true, text: 'Keluar Kantor' },
                    { leaf: true, text: 'Realisasi Keluar Kantor' },
                    { leaf: true, text: 'Keluar Kota' },
                    { leaf: true, text: 'Realisasi Keluar Kota' },
                    { leaf: true, text: 'Perintah Lembur' },
                    { leaf: true, text: 'Pembagian Shift Kerja' },
                    { leaf: true, text: 'Atur Kendaraan Jemputan' },
                    { leaf: true, text: 'Atur Penjemputan Karyawan' },
                    { leaf: true, text: 'Realisasi Penjemputan Karyawan' },
                    { leaf: true, text: 'Cetak Surat Jaminan Pembayaran' },
                    { leaf: true, text: 'Klaim Rawat Jalan' },
                    { leaf: true, text: 'Klaim Rawat Inap' },
                    { leaf: true, text: 'Pembuatan Cicilan Excess Claim' },
                    { leaf: true, text: 'Pinjaman Karyawan' },
                    { leaf: true, text: 'Potongan Gaji' },
                    { leaf: true, text: 'Pendapatan Non Upah' },
                    { leaf: true, text: 'Pembukaan Lamaran Pekerjaan' },
                    { leaf: true, text: 'Entri Data Pelamar' },
                    { leaf: true, text: 'Update Status Pelamar' },
                    { leaf: true, text: 'Rencana Training' },
                    { leaf: true, text: 'Realisasi Rencana Training' },
                    { leaf: true, text: 'Entri Training Karyawan' },
                    { leaf: true, text: 'Jadwal Penilaian Kinerja' },
                    { leaf: true, text: 'Hasil Penilaian Kinerja' },
                    { leaf: true, text: 'Administrasi Surat Peringatan (SP)' }
                    
                ]
            },
            {
                text: 'Proses',
                expanded: false,
                children: [
                    { leaf: true, text: 'Rekap Presensi' },
                    { leaf: true, text: 'Cek Absensi vs Presensi' },
                    { leaf: true, text: 'Rekap Absensi' },
                    { leaf: true, text: 'Rekap Data Medical Claim ke Payroll' },
                    { leaf: true, text: 'Proses Perhitungan Gaji' },
                    { leaf: true, text: 'Penerimaan Karyawan Baru' },
                    { leaf: true, text: 'Administrasi Kontrak Karyawan' },
                    { leaf: true, text: 'Cetak ID Card' },
                    { leaf: true, text: 'Monitoring Kontrak Karyawan' },
                    { leaf: true, text: 'Monitoring Training Karyawan' },
                    { leaf: true, text: 'Rencana Kenaikan Gaji' }
                    
                ]
            }
        ]
    }*/
});
