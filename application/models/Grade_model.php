<?php
class Grade_model extends CI_Model {
	public function insert($data) {
		$this->db->insert('grades', $data);
		return $this->db->insert_id();
	}

	public function get_by_student($student_id) {
		$this->db->select('courses.name as course_name, grades.grade');
		$this->db->from('grades');
		$this->db->join('courses', 'courses.id = grades.course_id');
		$this->db->where('grades.student_id', $student_id);
		return $this->db->get()->result();
	}
}
