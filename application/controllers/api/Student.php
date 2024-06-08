<?php
require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class Student extends REST_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->model('Grade_model');
	}

	public function grades_get() {
		$student_id = $this->session->userdata('user_id'); // Assuming session has user_id set by middleware
		$grades = $this->Grade_model->get_by_student($student_id);
		$this->response($grades, REST_Controller::HTTP_OK);
	}
}
