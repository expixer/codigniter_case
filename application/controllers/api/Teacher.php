<?php
require APPPATH . '/libraries/REST_Controller.php';

use Restserver\Libraries\REST_Controller;

class Teacher extends REST_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('User_model');
		$this->load->model('Course_model');
		$this->load->model('Grade_model');
	}

	// Course Operations
	public function courses_get()
	{
		$teacher = extract_user_from_token();
		$courses = $this->Course_model->get_by_teacher($teacher->id);
		$this->response($courses, REST_Controller::HTTP_OK);
	}

	public function course_post()
	{
		$input = $this->input->post();
		$this->Course_model->insert($input);
		$this->response(['Course created successfully.'], REST_Controller::HTTP_CREATED);
	}

	public function course_get($id)
	{
		$courses = $this->Course_model->get_by_course_id_by_teacher($id, extract_user_from_token()->id);
		$this->response($courses, REST_Controller::HTTP_OK);
	}

	public function course_put($id)
	{
		if (!$this->Course_model->is_teacher_of_course($id, extract_user_from_token()->id)) {
			$this->response(['Bu dersin öğretmeni değilsiniz.'], REST_Controller::HTTP_FORBIDDEN);
		}
		$input = $this->put();
		$this->Course_model->update($id, $input);
		$this->response(['Course updated successfully.'], REST_Controller::HTTP_OK);
	}

	public function course_delete($id)
	{
		if (!$this->Course_model->is_teacher_of_course($id, extract_user_from_token()->id)) {
			$this->response(['Bu dersin öğretmeni değilsiniz.'], REST_Controller::HTTP_FORBIDDEN);
		}
		$this->Course_model->delete($id);
		$this->response(['Course deleted successfully.'], REST_Controller::HTTP_OK);
	}

	// Student Operations
	public function students_get()
	{
		$students = $this->User_model->get_student_of_teacher(extract_user_from_token()->id);
		$this->response($students, REST_Controller::HTTP_OK);
	}
	public function student_get($id)
	{
		if (!$this->User_model->is_teacher_of_student($id, extract_user_from_token()->id)) {
			$this->response(['Bu öğrencinin öğretmeni değilsiniz.'], REST_Controller::HTTP_FORBIDDEN);
		}
		$student = $this->User_model->get_user($id);
		$this->response($student, REST_Controller::HTTP_OK);
	}

	public function student_post()
	{
		$this->form_validation->set_rules('username', 'Username', 'required|alpha_numeric');
		$this->form_validation->set_rules('email', 'Email', 'required|valid_email');
		$this->form_validation->set_rules('password', 'Password', 'required');

		if (!$this->form_validation->run()) {
			$errors = $this->form_validation->error_array();
			$this->response($errors, REST_Controller::HTTP_BAD_REQUEST);
		}

		$username = $this->input->post('username');
		$email = $this->input->post('email');
		$password = $this->input->post('password');
		$activation_key = md5($email . time() . $username);
		$role = 'student';

		$this->User_model->create_user($username, $email, $password, $activation_key, $role);
		$this->response(['Student created successfully.'], REST_Controller::HTTP_OK);
	}

	public function student_put($id)
	{
		if (!$this->User_model->is_teacher_of_student($id, extract_user_from_token()->id)){
			$this->response(['Bu öğrencinin öğretmeni değilsiniz.'], REST_Controller::HTTP_FORBIDDEN);
		}
		$input = $this->put();
		$this->User_model->update($id, $input);
		$this->response(['Student updated successfully.'], REST_Controller::HTTP_OK);
	}

	public function student_delete($id)
	{
		if (!$this->User_model->is_teacher_of_student($id, extract_user_from_token()->id)){
			$this->response(['Bu öğrencinin öğretmeni değilsiniz.'], REST_Controller::HTTP_FORBIDDEN);
		}
		$this->User_model->delete($id);
		$this->response(['Student deleted successfully.'], REST_Controller::HTTP_OK);
	}

	// Grade Operations
	public function grade_post()
	{
		$input = $this->input->post();
		$this->Grade_model->insert($input);
		$this->response(['Grade assigned successfully.'], REST_Controller::HTTP_OK);
	}
}
