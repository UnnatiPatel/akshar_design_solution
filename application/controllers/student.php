<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');
/**
 *
 */
class Student extends CI_Controller {

	function __construct() {
		parent::__construct();
		$users = array(5);
		parent::authenticate($users);
	}

	//Dashboard
	public function index() {
		$this -> data['title'] = "ADS | Dashboard";
		$this -> load -> view('backend/master_page/top', $this -> data);
		$this -> load -> view('backend/css/dashboard_css');
		$this -> load -> view('backend/master_page/header');
		$this -> load -> view('backend/branch_manager/dashboard');
		$this -> load -> view('backend/master_page/footer');
		$this -> load -> view('backend/js/dashboard_js');
		$this -> load -> view('backend/master_page/bottom');
	}

	//Show marks
	public function test_result() {
		$this -> data['title'] = "ADS | Target Type";
		$this -> load -> view('backend/master_page/top', $this -> data);
		$this -> load -> view('backend/css/test_result_css');
		$this -> load -> view('backend/master_page/header');
		$this -> load -> view('backend/branch_manager/test_result');
		$this -> load -> view('backend/master_page/footer');
		$this -> load -> view('backend/js/test_result_js');
		$this -> load -> view('backend/master_page/bottom');
	}

	//Show Attendance
	public function show_attendance() {
		$this -> data['title'] = "ADS | Time Table";
		$this -> load -> view('backend/master_page/top', $this -> data);
		$this -> load -> view('backend/css/show_attendance_css');
		$this -> load -> view('backend/master_page/header');
		$this -> load -> view('backend/branch_manager/show_attendance');
		$this -> load -> view('backend/master_page/footer');
		$this -> load -> view('backend/js/show_attendance_js');
		$this -> load -> view('backend/master_page/bottom');
	}

	//Profile
	public function profile() {
		$this -> data['title'] = "ADS | Profile";
		$this -> load -> model('user_model');
		$this -> load -> view('backend/master_page/top', $this -> data);
		$this -> load -> view('backend/css/student_profile_css');
		$data['profile'] = $this -> user_model -> getDetailsByStudent($this -> userId);
		$this -> load -> view('backend/master_page/header', $data);
		if (isset($_POST['edit_profile'])) {
			$studentData = array('userFirstName' => $_POST['first_name'], 'userMiddleName' => $_POST['middle_name'], 'userLastName' => $_POST['last_name'], 'userDOB' => $_POST['date_of_birth'], 'userContactNumber' => $_POST['mobile_no'], 'userEmailAddress' => $_POST['email'], 'userQualification' => $_POST['qualification'], 'userStreet1' => $_POST['street_1'], 'userStreet2' => $_POST['street_2'], 'userPostalCode' => $_POST['pin_code'], 'userState' => $_POST['state'], 'userCity' => $_POST['city']);
			$otherData = array('studentInstituteName' => $_POST['name_of_institute'], 'studentGuardianName' => $_POST['guardian_name'], 'studentGuardianOccupation' => $_POST['occupation_of_guardian'], 'studentReferenceName' => $_POST['reference']);
			$this -> user_model -> updateUser($studentData, $this -> userId);
			$this -> user_model -> updateStudetDetails($otherData, $this -> userId);
			redirect(base_url() . "student/profile");
		}elseif (isset($_POST['change_avatar'])) {
			$config['upload_path'] = './images/avatar';
			$config['allowed_types'] = 'gif|jpg|png';
			$config['max_size'] = '10000';
			$config['file_name'] = $this -> userId;
			$this -> load -> library('upload', $config);
			$filed_name = "student_avtar";
			$this -> upload -> do_upload($filed_name);
			$fileData = $this -> upload -> data();
			$studentData = array('avtar' => $fileData['file_name']);
			$this -> user_model -> updateUser($studentData, $this -> userId);
			redirect(base_url() . "student/profile");
		}elseif (isset($_POST['change_resume'])) {
			$config['upload_path'] = './resumes';
			$config['allowed_types'] = 'pdf';
			$config['max_size'] = '10000';
			$config['file_name'] = $this -> userId;
			$this -> load -> library('upload', $config);
			$filed_name = "student_resume";
			$this -> upload -> do_upload($filed_name);
			$fileData = $this -> upload -> data();
			$otherData = array('resume' => $fileData['file_name']);
			$this -> user_model -> updateStudetDetails($otherData, $this -> userId);
			redirect(base_url() . "student/profile");
		} elseif (isset($_POST['change_password'])) {
			$this -> load -> library("form_validation");
			$this -> form_validation -> set_rules('current_password', 'Current Password', 'required|trim');
			$this -> form_validation -> set_rules('new_password', 'New Password', 'required|trim');
			$this -> form_validation -> set_rules('re_new_password', 'Enter Same Password', 'required|trim|matches[new_password]');
			if ($this -> form_validation -> run() == FALSE) {
				$this -> data['validate'] = true;
			} else {
				$currPass = $this -> user_model -> getDetailsbyUser($this -> userId,"userPassword");
				if ($currPass != $_POST['current_password']) {
					$this -> data['error'] = "Password is not Correct";
				} else {
					$staffData = array('userPassword' => $_POST['new_password']);
					$this -> user_model -> updateUser($staffData, $this -> userId);
				}
			}
		}
		$this -> load -> view('backend/branch_manager/student_profile');
		$this -> load -> view('backend/master_page/footer');
		$this -> load -> view('backend/js/student_profile_js');
		$this -> load -> view('backend/master_page/bottom');
	}
}
