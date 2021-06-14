<?php 

class Model_status extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	/* get the active store data */
	

	/* get the brand data */
	public function getStatusData($id = null)
	{
		if($id) {
			$sql = "SELECT * FROM status_paid where id = ?";
			$query = $this->db->query($sql, array($id));
			return $query->row_array();
		}

		$sql = "SELECT * FROM status_paid";
		$query = $this->db->query($sql);
		return $query->result_array();
	}

	public function create($data)
	{
		if($data) {
			$insert = $this->db->insert('status_paid', $data);
			return ($insert == true) ? true : false;
		}
	}

	public function update($data, $id)
	{
		if($data && $id) {
			$this->db->where('id', $id);
			$update = $this->db->update('status_paid', $data);
			return ($update == true) ? true : false;
		}
	}

	public function remove($id)
	{
		if($id) {
			$this->db->where('id', $id);
			$delete = $this->db->delete('status_paid');
			return ($delete == true) ? true : false;
		}
	}

}