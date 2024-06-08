<?php
require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class Student extends REST_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->model('Grade_model');
	}

	public function grades_get() {
		$student_id = extract_user_from_token()->id;
		$grades = $this->Grade_model->get_by_student($student_id);
		$this->response($grades, REST_Controller::HTTP_OK);
	}

	public function grade_by_course_get($course_id) {
		$student_id = extract_user_from_token()->id;
		$grades = $this->Grade_model->get_by_student_and_course($student_id, $course_id);

		if (empty($grades)) {
			$this->response(['message' => 'Bu ders için not bulunamadı.'], REST_Controller::HTTP_NOT_FOUND);
		}

		if ($grades[0]->student_id != $student_id) {
			$this->response(['message' => 'Bu not size ait değil.'], REST_Controller::HTTP_FORBIDDEN);
		}

		$this->response($grades, REST_Controller::HTTP_OK);
	}
}
