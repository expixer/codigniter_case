<?php
require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class Teacher extends REST_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->model('Course_model');
		$this->load->model('Grade_model');
	}

	public function add_course_post() {
		$input = $this->input->post();
		$input['teacher_id'] = $this->session->userdata('user_id'); // Assuming session has user_id set by middleware
		$this->Course_model->insert($input);
		$this->response(['Course created successfully.'], REST_Controller::HTTP_OK);
	}

	public function grade_post() {
		$input = $this->input->post();
		$this->Grade_model->insert($input);
		$this->response(['Grade assigned successfully.'], REST_Controller::HTTP_OK);
	}
}
