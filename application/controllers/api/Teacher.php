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
		$this->response(['Ders oluşturuldu.'], REST_Controller::HTTP_CREATED);
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
		$this->response(['Ders güncellendi.'], REST_Controller::HTTP_OK);
	}

	public function course_delete($id)
	{
		if (!$this->Course_model->is_teacher_of_course($id, extract_user_from_token()->id)) {
			$this->response(['Bu dersin öğretmeni değilsiniz.'], REST_Controller::HTTP_FORBIDDEN);
		}
		$this->Course_model->delete($id);
		$this->response(['Ders silindi.'], REST_Controller::HTTP_OK);
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
		$this->form_validation->set_rules('username', 'Username', 'required');
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

		// Aktivasyon maili
		$subject = 'Hesap Aktivasyonu';
		$message = 'Merhaba ' . $username . ',<br><br>Hesabınızı aktive etmek için aşağıdaki linke tıklayın:<br><br><a href="' . base_url() . 'activate/' . $activation_key . '">Hesabı Aktifleştir</a>';
		$this->smtp->send($email, $subject, $message);

		$this->response(['Öğrenci oluşturuldu.'], REST_Controller::HTTP_CREATED);
	}

	public function student_put($id)
	{
		if (!$this->User_model->is_teacher_of_student($id, extract_user_from_token()->id)) {
			$this->response(['Bu öğrencinin öğretmeni değilsiniz.'], REST_Controller::HTTP_FORBIDDEN);
		}
		$input = $this->put();
		$this->User_model->update($id, $input);
		$this->response(['Öğrenci güncellendi.'], REST_Controller::HTTP_OK);
	}

	public function student_delete($id)
	{
		if (!$this->User_model->is_teacher_of_student($id, extract_user_from_token()->id)) {
			$this->response(['Bu öğrencinin öğretmeni değilsiniz.'], REST_Controller::HTTP_FORBIDDEN);
		}
		$this->User_model->delete($id);
		$this->response(['Öğrenci kaydı silindi.'], REST_Controller::HTTP_OK);
	}

	// Grade Operations
	public function grade_get($id)
	{
		$grade = $this->Grade_model->get_by_id($id);
		if (!$this->Course_model->is_teacher_of_course($grade->course_id, extract_user_from_token()->id)) {
			$this->response(['Bu dersin öğretmeni değilsiniz.'], REST_Controller::HTTP_FORBIDDEN);
		}
		$this->response($grade, REST_Controller::HTTP_OK);
	}

	public function grades_get()
	{
		$grades = $this->Grade_model->get_by_teacher(extract_user_from_token()->id);
		$this->response($grades, REST_Controller::HTTP_OK);
	}

	public function grade_post()
	{

		if (!$this->Course_model->is_teacher_of_course($this->input->post('course_id'), extract_user_from_token()->id)) {
			$this->response(['Bu dersin öğretmeni değilsiniz.'], REST_Controller::HTTP_FORBIDDEN);
		}
		$input = $this->input->post();
		$this->Grade_model->insert($input);
		$this->Grade_model->update_average_and_letter($input['student_id'], $input['course_id']);
		$this->response(['Öğrenciye not verildi.'], REST_Controller::HTTP_OK);
	}

	public function grade_put($id)
	{
		$grade = $this->Grade_model->get_by_id($id);
		if (!$this->Course_model->is_teacher_of_course($grade->course_id, extract_user_from_token()->id)) {
			$this->response(['Bu dersin öğretmeni değilsiniz.'], REST_Controller::HTTP_FORBIDDEN);
		}
		$input = $this->put();
		$this->Grade_model->update($id, $input);
		$this->Grade_model->update_average_and_letter($input['student_id'], $input['course_id']);
		$this->response(['Öğrenci notu güncellendi.'], REST_Controller::HTTP_OK);
	}

}
