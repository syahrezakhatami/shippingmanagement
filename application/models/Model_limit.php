<?php 

class Model_limit extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	 function get_user_group(){
		$query = $this->db->get('groups')->result();
		return $query;
	   }

	/* get the brand data */
	public function getLimitData($id = null)
	{
		if($id) {
			$sql = "SELECT * FROM limit_products WHERE id = ?";
			$query = $this->db->query($sql, array($id));
			return $query->row_array();
		}

		$sql = "SELECT limit_products.id,limit_products.limit_product,limit_products.group_id,groups.group_name from limit_products inner join groups on limit_products.group_id = groups.id";
		$query = $this->db->query($sql);
		return $query->result_array();
	}

	public function create($data)
	{
		if($data) {
			$insert = $this->db->insert('limit_products', $data);
			return ($insert == true) ? true : false;
		}
	}

	public function update($data, $id)
	{
		if($data && $id) {
			$this->db->where('id', $id);
			$update = $this->db->update('limit_products', $data);
			return ($update == true) ? true : false;
		}
	}

	public function remove($id)
	{
		if($id) {
			$this->db->where('id', $id);
			$delete = $this->db->delete('limit_products');
			return ($delete == true) ? true : false;
		}
	}

}