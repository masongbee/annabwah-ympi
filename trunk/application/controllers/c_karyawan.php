<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class C_karyawan extends CI_Controller {
	
	function __construct(){
		parent::__construct();		
		$this->load->model('m_karyawan', '', TRUE);
	}
	
	function getAll(){
		/*
		 * Collect Data
		 */
		$start  =   ($this->input->post('start', TRUE) ? $this->input->post('start', TRUE) : 0);
		$page   =   ($this->input->post('page', TRUE) ? $this->input->post('page', TRUE) : 1);
		$limit  =   ($this->input->post('limit', TRUE) ? $this->input->post('limit', TRUE) : 15);
		
		/*
		 * Processing Data
		 */
		$result = $this->m_karyawan->getAll($start, $page, $limit);
		echo json_encode($result);
	}
	
	function save(){
		/*
		 * Collect Data ==> diambil dari [model.karyawan]
		 */
		//$data   = json_decode($this->input->post('data',TRUE));
		$data = new stdClass();
		$data->NIK = $this->input->post('NIK',TRUE);
		$data->KODEUNIT = $this->input->post('KODEUNIT',TRUE);
		$data->KODEJAB = $this->input->post('KODEJAB',TRUE);
		$data->GRADE = $this->input->post('GRADE',TRUE);
		$data->NAMAKAR = $this->input->post('NAMAKAR',TRUE);
		$data->TGLMASUK = $this->input->post('TGLMASUK',TRUE);
		$data->JENISKEL = $this->input->post('JENISKEL',TRUE);
		$data->ALAMAT = $this->input->post('ALAMAT',TRUE);
		$data->DESA = $this->input->post('DESA',TRUE);
		$data->RT = $this->input->post('RT',TRUE);
		$data->RW = $this->input->post('RW',TRUE);
		$data->KECAMATAN = $this->input->post('KECAMATAN',TRUE);
		$data->KOTA = $this->input->post('KOTA',TRUE);
		$data->TELEPON = $this->input->post('TELEPON',TRUE);
		$data->TMPLAHIR = $this->input->post('TMPLAHIR',TRUE);
		$data->TGLLAHIR = $this->input->post('TGLLAHIR',TRUE);
		$data->ANAKKE = $this->input->post('ANAKKE',TRUE);
		$data->JMLSAUDARA = $this->input->post('JMLSAUDARA',TRUE);
		$data->PENDIDIKAN = $this->input->post('PENDIDIKAN',TRUE);
		$data->JURUSAN = $this->input->post('JURUSAN',TRUE);
		$data->NAMASEKOLAH = $this->input->post('NAMASEKOLAH',TRUE);
		$data->AGAMA = $this->input->post('AGAMA',TRUE);
		$data->NAMAAYAH = $this->input->post('NAMAAYAH',TRUE);
		$data->STATUSAYAH = $this->input->post('STATUSAYAH',TRUE);
		$data->ALAMATAYAH = $this->input->post('ALAMATAYAH',TRUE);
		$data->PENDDKAYAH = $this->input->post('PENDDKAYAH',TRUE);
		$data->PEKERJAYAH = $this->input->post('PEKERJAYAH',TRUE);
		$data->NAMAIBU = $this->input->post('NAMAIBU',TRUE);
		$data->STATUSIBU = $this->input->post('STATUSIBU',TRUE);
		$data->ALAMATIBU = $this->input->post('ALAMATIBU',TRUE);
		$data->PENDDKIBU = $this->input->post('PENDDKIBU',TRUE);
		$data->PEKERJIBU = $this->input->post('PEKERJIBU',TRUE);
		$data->KAWIN = $this->input->post('KAWIN',TRUE);
		$data->TGLKAWIN = $this->input->post('TGLKAWIN',TRUE);
		$data->NAMAPASANGAN = $this->input->post('NAMAPASANGAN',TRUE);
		$data->ALAMATPAS = $this->input->post('ALAMATPAS',TRUE);
		$data->TMPLAHIRPAS = $this->input->post('TMPLAHIRPAS',TRUE);
		$data->TGLLAHIRPAS = $this->input->post('TGLLAHIRPAS',TRUE);
		$data->AGAMAPAS = $this->input->post('AGAMAPAS',TRUE);
		$data->PEKERJPAS = $this->input->post('PEKERJPAS',TRUE);
		$data->KATPEKERJAAN = $this->input->post('KATPEKERJAAN',TRUE);
		$data->BHSJEPANG = $this->input->post('BHSJEPANG',TRUE);
		$data->JAMSOSTEK = $this->input->post('JAMSOSTEK',TRUE);
		$data->TGLJAMSOSTEK = $this->input->post('TGLJAMSOSTEK',TRUE);
		$data->STATUS = $this->input->post('STATUS',TRUE);
		$data->TGLSTATUS = $this->input->post('TGLSTATUS',TRUE);
		$data->TGLMUTASI = $this->input->post('TGLMUTASI',TRUE);
		$data->NOURUTKTRK = $this->input->post('NOURUTKTRK',TRUE);
		$data->TGLKONTRAK = $this->input->post('TGLKONTRAK',TRUE);
		$data->LAMAKONTRAK = $this->input->post('LAMAKONTRAK',TRUE);
		$data->NOACCKAR = $this->input->post('NOACCKAR',TRUE);
		$data->NAMABANK = $this->input->post('NAMABANK',TRUE);
		$data->FOTO = @$_FILES['FOTO']['name'];
		$data->FOTO_TMP = @$_FILES['FOTO']['tmp_name'];
		$data->USERNAME = $this->input->post('USERNAME',TRUE);
		$data->STATTUNKEL = $this->input->post('STATTUNKEL',TRUE);
		$data->ZONA = $this->input->post('ZONA',TRUE);
		$data->STATTUNTRAN = $this->input->post('STATTUNTRAN',TRUE);
		
		/*
		$data->NIK = $this->input->post('NIK',TRUE);
		$data->KODEUNIT = $this->input->post('KODEUNIT',TRUE);
		$data->KODEJAB = $this->input->post('KODEJAB',TRUE);
		$data->GRADE = $this->input->post('GRADE',TRUE);
		$data->NAMAKAR = $this->input->post('NAMAKAR',TRUE);
		$data->TGLMASUK = (strlen(trim($this->input->post('TGLMASUK',TRUE))) > 0 ? date('Y-m-d', strtotime($this->input->post('TGLMASUK',TRUE))) : NULL);
		$data->JENISKEL = $this->input->post('JENISKEL',TRUE);
		$data->ALAMAT = $this->input->post('ALAMAT',TRUE);
		$data->DESA = $this->input->post('DESA',TRUE);
		$data->RT = $this->input->post('RT',TRUE);
		$data->RW = $this->input->post('RW',TRUE);
		$data->KECAMATAN = $this->input->post('KECAMATAN',TRUE);
		$data->KOTA = $this->input->post('KOTA',TRUE);
		$data->TELEPON = $this->input->post('TELEPON',TRUE);
		$data->TMPLAHIR = $this->input->post('TMPLAHIR',TRUE);
		$data->TGLLAHIR = (strlen(trim($this->input->post('TGLLAHIR',TRUE))) > 0 ? date('Y-m-d', strtotime($this->input->post('TGLLAHIR',TRUE))) : NULL);
		$data->ANAKKE = ($this->input->post('ANAKKE',TRUE) > 0 ? $this->input->post('ANAKKE',TRUE) : NULL);
		$data->JMLSAUDARA = ($this->input->post('JMLSAUDARA',TRUE) > 0 ? $this->input->post('JMLSAUDARA',TRUE) : NULL);
		$data->PENDIDIKAN = $this->input->post('PENDIDIKAN',TRUE);
		$data->JURUSAN = $this->input->post('JURUSAN',TRUE);
		$data->NAMASEKOLAH = $this->input->post('NAMASEKOLAH',TRUE);
		$data->AGAMA = $this->input->post('AGAMA',TRUE);
		$data->NAMAAYAH = $this->input->post('NAMAAYAH',TRUE);
		$data->STATUSAYAH = $this->input->post('STATUSAYAH',TRUE);
		$data->ALAMATAYAH = $this->input->post('ALAMATAYAH',TRUE);
		$data->PENDDKAYAH = $this->input->post('PENDDKAYAH',TRUE);
		$data->PEKERJAYAH = $this->input->post('PEKERJAYAH',TRUE);
		$data->NAMAIBU = $this->input->post('NAMAIBU',TRUE);
		$data->STATUSIBU = $this->input->post('STATUSIBU',TRUE);
		$data->ALAMATIBU = $this->input->post('ALAMATIBU',TRUE);
		$data->PENDDKIBU = $this->input->post('PENDDKIBU',TRUE);
		$data->PEKERJIBU = $this->input->post('PEKERJIBU',TRUE);
		$data->KAWIN = $this->input->post('KAWIN',TRUE);
		$data->TGLKAWIN = (strlen(trim($this->input->post('TGLKAWIN',TRUE))) > 0 ? date('Y-m-d', strtotime($this->input->post('TGLKAWIN',TRUE))) : NULL);
		$data->NAMAPASANGAN = $this->input->post('NAMAPASANGAN',TRUE);
		$data->ALAMATPAS = $this->input->post('ALAMATPAS',TRUE);
		$data->TMPLAHIRPAS = $this->input->post('TMPLAHIRPAS',TRUE);
		$data->TGLLAHIRPAS = (strlen(trim($this->input->post('TGLLAHIRPAS',TRUE))) > 0 ? date('Y-m-d', strtotime($this->input->post('TGLLAHIRPAS',TRUE))) : NULL);
		$data->AGAMAPAS = $this->input->post('AGAMAPAS',TRUE);
		$data->PEKERJPAS = $this->input->post('PEKERJPAS',TRUE);
		$data->KATPEKERJAAN = $this->input->post('KATPEKERJAAN',TRUE);
		$data->BHSJEPANG = $this->input->post('BHSJEPANG',TRUE);
		$data->JAMSOSTEK = ($this->input->post('JAMSOSTEK',TRUE) == 'on' ? 1 : 0);
		$data->TGLJAMSOSTEK = (strlen(trim($this->input->post('TGLJAMSOSTEK',TRUE))) > 0 ? date('Y-m-d', strtotime($this->input->post('TGLJAMSOSTEK',TRUE))) : NULL);
		$data->STATUS = $this->input->post('STATUS',TRUE);
		$data->TGLSTATUS = (strlen(trim($this->input->post('TGLSTATUS',TRUE))) > 0 ? date('Y-m-d', strtotime($this->input->post('TGLSTATUS',TRUE))) : NULL);
		$data->TGLMUTASI = (strlen(trim($this->input->post('TGLMUTASI',TRUE))) > 0 ? date('Y-m-d', strtotime($this->input->post('TGLMUTASI',TRUE))) : NULL);
		$data->NOURUTKTRK = ($this->input->post('NOURUTKTRK',TRUE) > 0 ? $this->input->post('NOURUTKTRK',TRUE) : NULL);
		$data->TGLKONTRAK = (strlen(trim($this->input->post('TGLKONTRAK',TRUE))) > 0 ? date('Y-m-d', strtotime($this->input->post('TGLKONTRAK',TRUE))) : NULL);
		$data->LAMAKONTRAK = ($this->input->post('LAMAKONTRAK',TRUE) > 0 ? $this->input->post('LAMAKONTRAK',TRUE) : NULL);
		$data->NOACCKAR = $this->input->post('NOACCKAR',TRUE);
		$data->NAMABANK = $this->input->post('NAMABANK',TRUE);
		$data->FOTO = $this->input->post('FOTO',TRUE);
		$data->USERNAME = $this->input->post('USERNAME',TRUE);
		$data->STATTUNKEL = $this->input->post('STATTUNKEL',TRUE);
		$data->ZONA = $this->input->post('ZONA',TRUE);
		$data->STATTUNTRAN = ($this->input->post('STATTUNTRAN',TRUE) == 'on' ? 1 : 0);
		 */
		
		/*
		 * Processing Data
		 */
		$result = $this->m_karyawan->save($data);
		echo json_encode($result);
	}
	
	function delete(){
		/*
		 * Collect Data ==> diambil dari [model.karyawan]
		 */
		$data   = json_decode($this->input->post('data',TRUE));
		
		/*
		 * Processing Data
		 */
		$result = $this->m_karyawan->delete($data);
		echo json_encode($result);
	}
	
	/**
	 * Fungsi	: export2Excel
	 * 
	 * Untuk menyimpan data yang didapat dari Grid ExtJS ke dalam file Excel.
	 * Tidak lagi mengakses database untuk mendapatkan data.
	 */
	function export2Excel(){
		$data = json_decode($this->input->post('data',TRUE));
		
		//load our new PHPExcel library
		$this->load->library('excel');
		//activate worksheet number 1
		$this->excel->setActiveSheetIndex(0);
		//name the worksheet
		$this->excel->getActiveSheet()->setTitle('test worksheet');
		
		$col = 0;
		foreach ($data[0] as $key => $value){
			$this->excel->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $key);
			$this->excel->getActiveSheet()->getStyleByColumnAndRow($col, 1)->getFont()->setBold(true);
			$col++;
		}
		
		// Fetching the table data
		$row = 2;
		foreach($data as $record)
		{
			$col = ord("A");
			foreach ($data[0] as $key => $value)
			{
				$cellvalue = $record->$key;
				
				if($key == strtoupper('karyawan')){
					$this->excel->getActiveSheet()->getCell(chr($col).$row)->setValueExplicit($cellvalue, PHPExcel_Cell_DataType::TYPE_STRING);
				}else{
					$this->excel->getActiveSheet()->setCellValue(chr($col).$row, $cellvalue);
				}
				
				$col++;
			}
		
			$row++;
		}		
		
		$filename='karyawan.xlsx'; //save our workbook as this file name
		//header('Content-Type: application/vnd.ms-excel'); //mime type for Excel5
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'); //mime type for Excel2007
		header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
		header('Cache-Control: max-age=0'); //no cache
		
		//save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
		//if you want to save it as .XLSX Excel 2007 format
		$objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');
		//force user to download the Excel file without writing it to server's HD
		$objWriter->save(APPPATH.'../temp/'.$filename);
		echo $filename;
	}
	
	function export2PDF(){
		$getdata = json_decode($this->input->post('data',TRUE));
		$data["records"] = $getdata;
		$data["table"] = "karyawan";
		
		//html2pdf
		//Load the library
		$this->load->library('html2pdf');
		
		//Set folder to save PDF to
		$this->html2pdf->folder('./temp/');
		
		//Set the filename to save/download as
		$this->html2pdf->filename('karyawan.pdf');
		
		//Set the paper defaults
		$this->html2pdf->paper('a4', 'portrait');
		
		//Load html view
		$this->html2pdf->html($this->load->view('pdf_karyawan', $data, true));
		
		if($path = $this->html2pdf->create('save')) {
			//PDF was successfully saved or downloaded
			echo 'PDF saved to: ' . $path;
		}
	}
	
	function printRecords(){
		$getdata = json_decode($this->input->post('data',TRUE));
		$data["records"] = $getdata;
		$data["table"] = "karyawan";
		$print_view=$this->load->view("p_karyawan.php",$data,TRUE);
		if(!file_exists("temp")){
			mkdir("temp");
		}
		$print_file=fopen("temp/karyawan.html","w+");
		fwrite($print_file, $print_view);
		echo '1';
	}	
}