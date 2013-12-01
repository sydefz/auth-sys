<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class LibAuth {
	public function __construct() {
    	$this->CI =& get_instance();
	}

    public function login($firstName, $lastName, $password) {
    	//check database for user authentication
    	$this->CI->load->model('Employee_model');
        try {
            $result = $this->CI->Employee_model->getUserAndType($password, $firstName, $lastName);
            if (count($result)) {
                $user = $result[0];
                //this user exists, log him/her in
                $this->CI->session->set_userdata('loggedIn', true);
                $this->CI->session->set_userdata('firstName', $user->first_name);
                $this->CI->session->set_userdata('lastName', $user->last_name);
                $this->CI->session->set_userdata('userId', $user->emp_no);
                $this->CI->session->set_userdata('isManager', $user->is_manager);
                $this->CI->session->set_userdata('deptNo', $user->dept_no);
                $this->CI->session->set_userdata('deptName', $user->dept_name);
                return true;
            } else {
                return false;
            }
        } catch (InvalidArgumentException $ex) {
            return false;
        } catch (DBException $ex) {
            trigger_error('AUTH/DB: ' . $ex->getMessage());
            return false;
        } catch (Exception $ex) {
            trigger_error('AUTH/ERROR: ' . $ex->getMessage());
            return false;
        }
    	
    }

    public function logout() {
    	session_destroy();
    	return true;
    }

    public function hasLoggedIn() {
    	if ($this->CI->session->userdata('loggedIn') === true) {
		    return true;
		} else {
		    return false;
		}
    }

    public function getUserId() {
    	return $this->CI->session->userdata('userId');
    }

    public function getUserName() {
    	return $this->CI->session->userdata('firstName') . ' ' . $this->CI->session->userdata('lastName');
    }
}