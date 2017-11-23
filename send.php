<?php
// Implements a message form to send to multiple users.
require_once('../../../../config.php');
require_once($CFG->dirroot . '/mod/assign/locallib.php');
require_login();



if (isguestuser()) {
    redirect($CFG->wwwroot);
}
if (empty($CFG->messaging)) {
    print_error('disabled', 'message');
}

//var_dump($SESSION->emailto);

// Assign we're sending student messages to
$id = required_param('id', PARAM_INT);

list($course, $cm) = get_course_and_cm_from_cmid($id, 'assign');

$context = context_module::instance($cm->id);

$assign = new assign($context, $cm, $course);
$PAGE->set_cm($cm);
$PAGE->set_context($context);
$PAGE->set_url('/mod/assign/feedback/contact/send.php', ['id' => $id, 'sesskey'=>sesskey()]);
$PAGE->set_title("Contact students");
$t = [];
$t['blindmarking'] = $assign->is_blind_marking();

$f = new assignfeedback_contact\send_form();
//$f = new assignfeedback_send_form();
echo $OUTPUT->header();
echo $OUTPUT->heading("Contact students");
echo $f->display();
//echo "Message form to go here";
echo $OUTPUT->footer();
