<?php

defined('MOODLE_INTERNAL') || die();

class assign_feedback_contact extends assign_feedback_plugin {
	public function get_name() {
		return get_string('pluginname', 'assignfeedback_contact');
	}

	public function is_enabled() {
		return true;
	}

	public function has_summary() {
		return false;
	}
	public function can_contact() {
		return true;
	}
	public function is_configurable() {
		return false;
	}
	/** 
   	 * Returns a "contact selected" option as a batch operation.
	 */
	public function get_grading_batch_operations() {
		global $CFG;

		if ($this->can_contact()) {
			return [
				'contactselected' => get_string('contactselected', 'assignfeedback_contact')
			];
		}
	}
	/**
	 * Handles Batch operation(non-PHPdoc)
	 */
	function grading_batch_operation($action, $users) {

		switch(strtolower($action)) {
			case 'contactselected':
			    if (
			            $this->can_contact()
	            ) {
	                $this->contact_selected_userids($users);
	            } else {
	                print_error('contactnotpermitted', 'assignfeedback_strathnotices');
	            }
				break;
		}
		return '';
	}

	/**
	 * Use Moodle Messaging to contact the users.
	 */
	public function contact_selected_userids($users) {
		$properusers = $DB->get_records_list('user', 'id', $users,'id ASC');
		$courseid = $this->assignment->get_instance()->course;
		if (empty($SESSION->emailto)) {
			$SESSION->emailto = array();
		}
		$SESSION->emailto[$courseid]= array();
		$urlparams = array(
			'id' => $this->assignment->get_instance()->course,
			'sesskey' => sesskey(),
			'formaction' => 'messageselect.php'
		);
		foreach($properusers as $uid=>$u) {
			$SESSION->emailto[$courseid][] = $u;
			//$urlparams['user'.$uid] = 'on';
		}

		$url = new moodle_url('/user/action_redir.php', $urlparams);
		redirect($url);
		exit();
	}

}
