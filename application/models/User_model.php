<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class User_model extends CI_Model {


	public function __construct() {
		
		parent::__construct();
		$this->load->database();
		
	}

	public function is_teacher_of_student($student_id, $teacher_id) {

		$this->db->select('courses.id');
		$this->db->from('courses');
		$this->db->where('teacher_courses.teacher_id', $teacher_id);
		$this->db->where('student_courses.student_id', $student_id);
		$this->db->join('student_courses', 'courses.id = student_courses.course_id');
		$this->db->join('teacher_courses', 'courses.id = teacher_courses.course_id');
		$relation = $this->db->get()->row();

		return (bool)$relation;

	}

	public function get_student_of_teacher($teacher_id) {

		$this->db->select('users.id, users.username');
		$this->db->from('users');
		$this->db->where('users.role', 'student');
		$this->db->join('student_courses', 'users.id = student_courses.student_id');
		$this->db->join('courses', 'student_courses.course_id = courses.id');
		$this->db->join('teacher_courses', 'courses.id = teacher_courses.course_id');
		$this->db->where('teacher_courses.teacher_id', $teacher_id);
		return $this->db->get()->result();

	}

	public function get_role($user_id) {
		$this->db->select('role');
		$this->db->where('id', $user_id);
		return $this->db->get('users')->row()->role;
	}

	public function get_by_role($role) {
		$this->db->where('role', $role);
		return $this->db->get('users')->result();
	}

	public function insert($data) {
		$this->db->insert('users', $data);
		return $this->db->insert_id();
	}

	public function update($id, $data) {
		$this->db->where('id', $id);
		$this->db->update('users', $data);
	}

	public function delete($id) {
		$this->db->where('id', $id);
		$this->db->delete('users');
	}

	public function create_user($username, $email, $password, $activation_key, $role) {
		
		$data = array(
			'username'   => $username,
			'email'      => $email,
			'password'   => $this->hash_password($password),
			'created_at' => date('Y-m-j H:i:s'),
			'activation_key' => $activation_key,
			'role' => $role
		);
		
		$this->db->insert('users', $data);
		return $this->db->insert_id(); 
		
	}

	public function activate_user($activation_key) {

		$data = array(
			'is_confirmed' => 1
		);

		$this->db->where('activation_key', $activation_key);
		$this->db->update('users', $data);

		$this->db->select('id');
		$this->db->from('users');
		$this->db->where('activation_key', $activation_key);
		$this->db->where('is_confirmed', 0);

		return $this->db->get()->row('id');
	}

	public function resolve_user_login($username, $password) {
		
		$this->db->select('password');
		$this->db->from('users');
		$this->db->where('username', $username);
		$hash = $this->db->get()->row('password');
		
		return $this->verify_password_hash($password, $hash);
		
	}

	public function get_user_id_from_username($username) {
		
		$this->db->select('id');
		$this->db->from('users');
		$this->db->where('username', $username);

		return $this->db->get()->row('id');
	}

	public function get_user($user_id) {
		
		$this->db->from('users');
		$this->db->where('id', $user_id);
		return $this->db->get()->row();
		
	}

	private function hash_password($password) {
		
		return password_hash($password, PASSWORD_BCRYPT);
		
	}

	private function verify_password_hash($password, $hash) {
		
		return password_verify($password, $hash);
		
	}
	
}
