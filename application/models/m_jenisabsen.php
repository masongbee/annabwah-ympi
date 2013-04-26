<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
		/**
		 * Class	: M_jenisabsen
		 * 
		 * Table	: jenisabsen
		 *  
		 * @author masongbee
		 *
		 */
		class M_jenisabsen extends CI_Model{

			function __construct(){
				parent::__construct();
			}
			
			/**
			 * Fungsi	: getAll
			 * 
			 * Untuk mengambil all-data
			 * 
			 * @param number $start
			 * @param number $page
			 * @param number $limit
			 * @return json
			 */
			function getAll($start, $page, $limit){$query  = $this->db->limit($limit, $start)->order_by('JENISABSEN', 'ASC')->get('jenisabsen')->result();
				$total  = $this->db->get('jenisabsen')->num_rows();
				
				$data   = array();
				foreach($query as $result){
					$data[] = $result;
				}
				
				$json	= array(
								'success'   => TRUE,
								'message'   => "Loaded data",
								'total'     => $total,
								'data'      => $data
				);
				
				return $json;
			}
			
			/**
			 * Fungsi	: save
			 * 
			 * Untuk menambah data baru atau mengubah data lama
			 * 
			 * @param array $data
			 * @return json
			 */
			function save($data){
				$last   = NULL;
				
				$par = array('JENISABSEN'=>$data->JENISABSEN);
				
				if($this->db->get_where('jenisabsen', $par)->num_rows() > 0){
					/*
					 * Data Exist
					 */
					$this->db->where($par)->update('jenisabsen', $data);
					$last   = $data;
					
				}else{
					/*
					 * Data Not Exist
					 * 
					 * Process Insert
					 */
					$this->db->insert('jenisabsen', $data);
					$last   = $this->db->order_by('JENISABSEN', 'ASC')->get('jenisabsen')->row();
					
				}
				
				$total  = $this->db->get('jenisabsen')->num_rows();
				
				$json   = array(
								"success"   => TRUE,
								"message"   => 'Data berhasil disimpan',
								'total'     => $total,
								"data"      => $last
				);
				
				return $json;
			}
			
			/**
			 * Fungsi	: delete
			 * 
			 * Untuk menghapus satu data
			 * 
			 * @param array $data
			 * @return json
			 */
			function delete($data){
				$par = array('JENISABSEN'=>$data->JENISABSEN);
				
				$this->db->where($par)->delete('jenisabsen');
				
				$total  = $this->db->get('jenisabsen')->num_rows();
				$last = $this->db->get('jenisabsen')->result();
				
				$json   = array(
								"success"   => TRUE,
								"message"   => 'Data berhasil dihapus',
								'total'     => $total,
								"data"      => $last
				);				
				return $json;
			}
		}
		?>