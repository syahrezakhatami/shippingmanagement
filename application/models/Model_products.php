<?php 

class Model_products extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	/* get the brand data */
	public function getProductData($id = null)
	{
		if($id) {
			$sql = "SELECT * FROM products where id = ?";
			$query = $this->db->query($sql, array($id));
			return $query->row_array();
		}

		$sql = "SELECT * FROM products ORDER BY id DESC";
		$query = $this->db->query($sql);
		return $query->result_array();
	}

	public function getActiveProductData()
	{
		$sql = "SELECT * FROM products WHERE availability = ? ORDER BY id DESC";
		$query = $this->db->query($sql, array(1));
		return $query->result_array();
	}

	public function create($data)
	{
		if($data) {
			$insert = $this->db->insert('products', $data);
			return ($insert == true) ? true : false;
		}
	}

	public function update($data, $id)
	{
		if($data && $id) {
			$this->db->where('id', $id);
			$update = $this->db->update('products', $data);
			return ($update == true) ? true : false;
		}
	}

	public function remove($id)
	{
		if($id) {
			$this->db->where('id', $id);
			$delete = $this->db->delete('products');
			return ($delete == true) ? true : false;
		}
	}

	public function countTotalProducts()
	{
		$sql = "SELECT * FROM products";
		$query = $this->db->query($sql);
		return $query->num_rows();
	}

	public function countTotalWareHouse1()
	{
		return $this->db->from('products')
		->select('COUNT(products.group_id) as total_warehouse1')
          ->join('groups', 'products.group_id = groups.id')
          ->join('limit_products', 'groups.id = limit_products.group_id')
		  ->where('products.group_id = 4')
          ->get()
          ->result();
	}
	public function countTotalWareHouse2()
	{
		return $this->db->from('products')
		->select('COUNT(products.group_id) as total_warehouse2')
          ->join('groups', 'products.group_id = groups.id')
          ->join('limit_products', 'groups.id = limit_products.group_id')
		  ->where('products.group_id = 5')
          ->get()
          ->result();
	}
	public function countTotalWareHouse3()
	{
		return $this->db->from('products')
		->select('COUNT(products.group_id) as total_warehouse3')
          ->join('groups', 'products.group_id = groups.id')
          ->join('limit_products', 'groups.id = limit_products.group_id')
		  ->where('products.group_id = 6')
          ->get()
          ->result();
	}
	public function countTotalWareHouse4()
	{
		return $this->db->from('products')
		->select('COUNT(products.group_id) as total_warehouse4')
          ->join('groups', 'products.group_id = groups.id')
          ->join('limit_products', 'groups.id = limit_products.group_id')
		  ->where('products.group_id = 7')
          ->get()
          ->result();
	}
	public function LimitWarehouse1()
	{
		$this->db->distinct();
        return $this->db->from('limit_products')
		->select('limit_products.limit_product')
          ->join('groups', 'limit_products.group_id = groups.id')
          ->join('products', 'groups.id = products.group_id ')
		  ->where('limit_products.group_id = 4')
          ->get()
          ->result();
	}
	public function LimitWarehouse2()
	{
		$this->db->distinct();
        return $this->db->from('limit_products')
		->select('limit_products.limit_product')
          ->join('groups', 'limit_products.group_id = groups.id')
          ->join('products', 'groups.id = products.group_id ')
		  ->where('limit_products.group_id = 5')
          ->get()
          ->result();
	}
	public function LimitWarehouse3()
	{
		$this->db->distinct();
        return $this->db->from('limit_products')
		->select('limit_products.limit_product')
          ->join('groups', 'limit_products.group_id = groups.id')
          ->join('products', 'groups.id = products.group_id ')
		  ->where('limit_products.group_id = 6')
          ->get()
          ->result();
	}
	public function LimitWarehouse4()
	{
		$this->db->distinct();
        return $this->db->from('limit_products')
		->select('limit_products.limit_product')
          ->join('groups', 'limit_products.group_id = groups.id')
          ->join('products', 'groups.id = products.group_id ')
		  ->where('limit_products.group_id = 7')
          ->get()
          ->result();
	}
	public function Warehouse($group_user)
	{
		$this->db->select('count(group_id) as total_warehouse')->from('products')->where('group_id',$group_user);
		$query = $this->db->get();

		if ($query->num_rows() > 0) {
			return $query->row()->total_warehouse;
		}
		return false;
	}
	public function Limit($group_user)
	{
		$this->db->select('limit_product')->from('limit_products')->where('group_id',$group_user);
		$query = $this->db->get();

		if ($query->num_rows() > 0) {
			return $query->row()->limit_product;
		}
		return false;
	}
	public function Nama($group_user)
	{
		$this->db->select('group_name')->from('groups')->where('id',$group_user);

     $query = $this->db->get();

     if ($query->num_rows() > 0) {
         return $query->row()->group_name;
     }
     return false;
		
	}

}