<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Products extends Admin_Controller 
{
	public function __construct()
	{
		parent::__construct();

		$this->not_logged_in();

		$this->data['page_title'] = 'Products';

		$this->load->model('model_products');
		$this->load->model('model_category');
		$this->load->model('model_stores');
		$this->load->model('model_groups');
	}

    /* 
    * It only redirects to the manage product page
    */
	public function index()
	{
        if(!in_array('viewProduct', $this->permission)) {
            redirect('dashboard', 'refresh');
        }
        $this->data['total_w1'] = $this->model_products->countTotalWareHouse1();
		$this->data['total_w2'] = $this->model_products->countTotalWareHouse2();
		$this->data['total_w3'] = $this->model_products->countTotalWareHouse3();
		$this->data['total_w4'] = $this->model_products->countTotalWareHouse4();
        $this->data['limit1'] = $this->model_products->LimitWarehouse1();
		$this->data['limit2'] = $this->model_products->LimitWarehouse2();
		$this->data['limit3'] = $this->model_products->LimitWarehouse3();
		$this->data['limit4'] = $this->model_products->LimitWarehouse4();
        
        $w1 = $this->data['total_w1'];

        $w2 = $this->data['total_w2'];

        $w3 = $this->data['total_w3'];

        $w4 =$this->data['total_w4'];

        $l1 = $this->data['limit1'];

        $l2 =$this->data['limit2'];

        $l3 = $this->data['limit3'];

        $l4 =$this->data['limit4'];


        if($w1 == $l1)
        {
            $pesan= 'alert("Warehouse 1 Sudah Penuh")';
            $this->session->set_flashdata('message', $pesan);
            $user_id = $this->session->userdata('id');
            $is_admin = ($user_id == 1) ? true :false;
            $this->data['is_admin'] = $is_admin;
            $this->render_template('products/index', $this->data);	
        } elseif ($w2 == $l2)
        {
            $pesan= 'alert("Warehouse 2 Sudah Penuh")';
            $this->session->set_flashdata('message', $pesan);
            $user_id = $this->session->userdata('id');
            $is_admin = ($user_id == 1) ? true :false;
            $this->data['is_admin'] = $is_admin;
            $this->render_template('products/index', $this->data);	
        } elseif ($w3 == $l3)
        {
            $pesan= 'alert("Warehouse 3 Sudah Penuh")';
            $this->session->set_flashdata('message', $pesan);
            $user_id = $this->session->userdata('id');
            $is_admin = ($user_id == 1) ? true :false;
            $this->data['is_admin'] = $is_admin;
            $this->render_template('products/index', $this->data);	
        } elseif($w4 == $l4)
        {
            $pesan= 'alert("Warehouse 4 Sudah Penuh")';
            $this->session->set_flashdata('message', $pesan);
            $user_id = $this->session->userdata('id');
            $is_admin = ($user_id == 1) ? true :false;
            $this->data['is_admin'] = $is_admin;
            $this->render_template('products/index', $this->data);	
        } else {

		$user_id = $this->session->userdata('id');
		$is_admin = ($user_id == 1) ? true :false;

		$this->data['is_admin'] = $is_admin;

		$this->render_template('products/index', $this->data);
        }	
	}

    /*
    * It Fetches the products data from the product table 
    * this function is called from the datatable ajax function
    */
	public function fetchProductData()
	{
		$result = array('data' => array());

		$data = $this->model_products->getProductData();

		foreach ($data as $key => $value) {

            $store_data = $this->model_stores->getStoresData($value['store_id']);
            $group = $this->model_groups->getGroupData($value['group_id']);
			// button
            $buttons = '';
            if(in_array('updateProduct', $this->permission)) {
    			$buttons .= '<a href="'.base_url('products/update/'.$value['id']).'" class="btn btn-default"><i class="fa fa-pencil"></i></a>';
            }

            if(in_array('deleteProduct', $this->permission)) { 
    			$buttons .= ' <button type="button" class="btn btn-default" onclick="removeFunc('.$value['id'].')" data-toggle="modal" data-target="#removeModal"><i class="fa fa-trash"></i></button>';
            }
			

			$img = '<img src="'.base_url($value['image']).'" alt="'.$value['name'].'" class="img-circle" width="50" height="50" />';

            $availability = ($value['availability'] == 1) ? '<span class="label label-success">Active</span>' : '<span class="label label-warning">Inactive</span>';

            $qty_status = '';
            if($value['qty'] <= 10) {
                $qty_status = '<span class="label label-warning">Low !</span>';
            } else if($value['qty'] <= 0) {
                $qty_status = '<span class="label label-danger">Out of stock !</span>';
            }


			$result['data'][$key] = array(
				$img,
				$value['sku'],
				$value['name'],
				$value['price'],
                $value['qty'] . ' ' . $qty_status,
                $store_data['name'],
                $group['group_name'],
				$availability,
				$buttons
			);
		} // /foreach

		echo json_encode($result);
	}	

    /*
    * If the validation is not valid, then it redirects to the create page.
    * If the validation for each input field is valid then it inserts the data into the database 
    * and it stores the operation message into the session flashdata and display on the manage product page
    */
	public function create()
	{
		if(!in_array('createProduct', $this->permission)) {
            redirect('dashboard', 'refresh');
        }

		$this->form_validation->set_rules('product_name', 'Product name', 'trim|required');
		$this->form_validation->set_rules('sku', 'SKU', 'trim|required');
		$this->form_validation->set_rules('price', 'Price', 'trim|required');
		$this->form_validation->set_rules('qty', 'Qty', 'trim|required');
        $this->form_validation->set_rules('store', 'Store', 'trim|required');
		$this->form_validation->set_rules('availability', 'Availability', 'trim|required');
		
       
        // { 
        // }else {
        if ($this->form_validation->run() == TRUE) {
            // true case
        	$upload_image = $this->upload_image();

        	$data = array(
        		'name' => $this->input->post('product_name'),
        		'sku' => $this->input->post('sku'),
        		'price' => $this->input->post('price'),
        		'qty' => $this->input->post('qty'),
        		'image' => $upload_image,
        		'description' => $this->input->post('description'),
        		'group_id' =>$this->input->post('groups'),
        		'category_id' => json_encode($this->input->post('category')),
                'store_id' => $this->input->post('store'),
        		'availability' => $this->input->post('availability'),
        	);
            $group_user = $this->input->post('groups');
            $cek_warehouse = $this->model_products->Warehouse($group_user);
            $cek_limit = $this->model_products->Limit($group_user);
            if($cek_warehouse == $cek_limit )
            {
                $nama_warehouse = $this->model_products->Nama($group_user);
                $this->session->set_flashdata('message', ''.$nama_warehouse.' Penuh');
                redirect('products/create', 'refresh');
            } else {
                $create = $this->model_products->create($data);
                if($create == true) {
                    $this->session->set_flashdata('success', 'Successfully created');
                    redirect('products/', 'refresh');
                }
                else {
                    $this->session->set_flashdata('errors', 'Error occurred!!');
                    redirect('products/create', 'refresh');
                }
            }
           
        
        }
        else {
            // false case

        	// attributes 
        
			$this->data['groups'] = $this->model_groups->getActiveGroup();        	
			$this->data['category'] = $this->model_category->getActiveCategroy();        	
			$this->data['stores'] = $this->model_stores->getActiveStore();        	

            $this->render_template('products/create', $this->data);
        }	
	}
// }

    /*
    * This function is invoked from another function to upload the image into the assets folder
    * and returns the image path
    */
	public function upload_image()
    {
    	// assets/images/product_image
        $config['upload_path'] = 'assets/images/product_image';
        $config['file_name'] =  uniqid();
        $config['allowed_types'] = 'gif|jpg|png';
        $config['max_size'] = '1000';

        // $config['max_width']  = '1024';s
        // $config['max_height']  = '768';

        $this->load->library('upload', $config);
        if ( ! $this->upload->do_upload('product_image'))
        {
            $error = $this->upload->display_errors();
            return $error;
        }
        else
        {
            $data = array('upload_data' => $this->upload->data());
            $type = explode('.', $_FILES['product_image']['name']);
            $type = $type[count($type) - 1];
            
            $path = $config['upload_path'].'/'.$config['file_name'].'.'.$type;
            return ($data == true) ? $path : false;            
        }
    }

    /*
    * If the validation is not valid, then it redirects to the edit product page 
    * If the validation is successfully then it updates the data into the database 
    * and it stores the operation message into the session flashdata and display on the manage product page
    */
	public function update($product_id)
	{      
        if(!in_array('updateProduct', $this->permission)) {
            redirect('dashboard', 'refresh');
        }

        if(!$product_id) {
            redirect('dashboard', 'refresh');
        }

        $this->form_validation->set_rules('product_name', 'Product name', 'trim|required');
        $this->form_validation->set_rules('sku', 'SKU', 'trim|required');
        $this->form_validation->set_rules('price', 'Price', 'trim|required');
        $this->form_validation->set_rules('qty', 'Qty', 'trim|required');
        $this->form_validation->set_rules('store', 'Store', 'trim|required');
        $this->form_validation->set_rules('availability', 'Availability', 'trim|required');

        if ($this->form_validation->run() == TRUE) {
            // true case
            
            $data = array(
                'name' => $this->input->post('product_name'),
                'sku' => $this->input->post('sku'),
                'price' => $this->input->post('price'),
                'qty' => $this->input->post('qty'),
                'description' => $this->input->post('description'),
                'group_id' =>$this->input->post('groups'),
                'category_id' => json_encode($this->input->post('category')),
                'store_id' => $this->input->post('store'),
                'availability' => $this->input->post('availability'),
            );

            
            if($_FILES['product_image']['size'] > 0) {
                $upload_image = $this->upload_image();
                $upload_image = array('image' => $upload_image);
                
                $this->model_products->update($upload_image, $product_id);
            }

            $update = $this->model_products->update($data, $product_id);
            if($update == true) {
                $this->session->set_flashdata('success', 'Successfully updated');
                redirect('products/', 'refresh');
            }
            else {
                $this->session->set_flashdata('errors', 'Error occurred!!');
                redirect('products/update/'.$product_id, 'refresh');
            }
        }
        else {
            // attributes 
        
            
            // false case
			$this->data['groups'] = $this->model_groups->getActiveGroup();        	
            $this->data['category'] = $this->model_category->getActiveCategroy();           
            $this->data['stores'] = $this->model_stores->getActiveStore();          

            $product_data = $this->model_products->getProductData($product_id);
            $this->data['product_data'] = $product_data;
            $this->render_template('products/edit', $this->data); 
        }   
	}

    /*
    * It removes the data from the database
    * and it returns the response into the json format
    */
	public function remove()
	{
        if(!in_array('deleteProduct', $this->permission)) {
            redirect('dashboard', 'refresh');
        }
        
        $product_id = $this->input->post('product_id');

        $response = array();
        if($product_id) {
            $delete = $this->model_products->remove($product_id);
            if($delete == true) {
                $response['success'] = true;
                $response['messages'] = "Successfully removed";
                redirect('products/', 'refresh');
            }
            else {
                $response['success'] = false;
                $response['messages'] = "Error in the database while removing the product information";
            }
        }
        else {
            $response['success'] = false;
            $response['messages'] = "Refersh the page again!!";
        }

        echo json_encode($response);
	}

}