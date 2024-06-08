<?php
class Course_model extends CI_Model {
	public function __construct() {
		parent::__construct();
		$this->load->database();
	}

	public function insert($data) {
		$this->db->insert('courses', $data);
		return $this->db->insert_id();
	}

	public function is_teacher_of_course($course_id, $teacher_id) {
		$subquery = $this->db->select('course_id')
			->from('teacher_courses')
			->where('teacher_id', $teacher_id)
			->get_compiled_select();

		$this->db->where('id', $course_id);
		$this->db->where_in('id', $subquery, false);
		$query = $this->db->get('courses');

		return $query->num_rows() > 0;
	}

	public function update($id, $data) {
		$this->db->where('id', $id);
		$this->db->update('courses', $data);
	}

	public function delete($id) {
		$this->db->where('id', $id);
		$this->db->delete('courses');

		$this->db->where('course_id', $id);
		$this->db->delete('teacher_courses');
	}

	public function get_by_teacher($teacher_id) {
		$this->db->select('courses.id, courses.name');
		$this->db->from('courses');
		$this->db->join('teacher_courses', 'courses.id = teacher_courses.course_id');
		$this->db->where('teacher_courses.teacher_id', $teacher_id);
		$query = $this->db->get();
		return $query->result();
	}

	public function get_by_course_id_by_teacher($course_id, $teacher_id) {
		$this->db->select('courses.id, courses.name');
		$this->db->from('courses');
		$this->db->join('teacher_courses', 'courses.id = teacher_courses.course_id');
		$this->db->where('teacher_courses.teacher_id', $teacher_id);
		$this->db->where('courses.id', $course_id);
		$query = $this->db->get();
		return $query->row();
	}
}
