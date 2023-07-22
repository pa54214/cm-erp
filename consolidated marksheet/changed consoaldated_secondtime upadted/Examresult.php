<?php



if (!defined('BASEPATH')) {

    exit('No direct script access allowed');

}



class Examresult extends Admin_Controller

{



    public $exam_type = array();



    public function __construct()

    {

        parent::__construct();

        $this->exam_type          = $this->config->item('exam_type');

        $this->attendence_exam    = $this->config->item('attendence_exam');

        $this->sch_setting_detail = $this->setting_model->getSetting();

        $this->load->model(array('marksdivision_model', 'marksdivision_model'));

        $this->load->library('mailsmsconf');

         $this->load->library('media_storage');

    }



    public function printCard()

    {

        $this->form_validation->set_error_delimiters('', '');

        $this->form_validation->set_rules('admitcard_template', $this->lang->line('template'), 'required|trim|xss_clean');

        $this->form_validation->set_rules('post_exam_id', $this->lang->line('exam'), 'required|trim|xss_clean');

        $this->form_validation->set_rules('post_exam_group_id', $this->lang->line('exam_group'), 'required|trim|xss_clean');

        $this->form_validation->set_rules('exam_group_class_batch_exam_student_id[]', $this->lang->line('students'), 'required|trim|xss_clean');

        $data = array();



        if ($this->form_validation->run() == false) {

            $data = array(

                'admitcard_template'                     => form_error('admitcard_template'),

                'post_exam_id'                           => form_error('post_exam_id'),

                'post_exam_group_id'                     => form_error('post_exam_group_id'),

                'exam_group_class_batch_exam_student_id' => form_error('exam_group_class_batch_exam_student_id'),

            );

            $array = array('status' => 0, 'error' => $data);

            echo json_encode($array);

        } else {

            $post_exam_id            = $this->input->post('post_exam_id');

            $post_exam_group_id      = $this->input->post('post_exam_group_id');

            $students_array          = $this->input->post('exam_group_class_batch_exam_student_id');

            $exam                    = $this->examgroup_model->getExamByID($post_exam_id);

            $data['exam']            = $exam;

            $exam_grades             = $this->grade_model->getByExamType($exam->exam_group_type);

            $data['exam_grades']     = $exam_grades;

            $data['admitcard']       = $this->admitcard_model->get($this->input->post('admitcard_template'));

            $data['exam_subjects']   = $this->batchsubject_model->getExamSubjects($post_exam_id);

            $data['student_details'] = $this->examstudent_model->getStudentsAdmitCardByExamAndStudentID($students_array, $post_exam_id);

            $data['sch_setting']     = $this->sch_setting_detail;

            $student_admit_cards     = $this->load->view('admin/admitcard/_printadmitcard', $data, true);

            $array                   = array('status' => '1', 'error' => '', 'page' => $student_admit_cards);

            echo json_encode($array);

        }

    }



    public function admitcard()

    {

        if (!$this->rbac->hasPrivilege('print_admit_card', 'can_view')) {

            access_denied();

        }



        $this->session->set_userdata('top_menu', 'Examinations');

        $this->session->set_userdata('sub_menu', 'Examinations/examresult/admitcard');

        $examgroup_result      = $this->examgroup_model->get();

        $data['examgrouplist'] = $examgroup_result;

        $admitcard_result      = $this->admitcard_model->get();

        $data['admitcardlist'] = $admitcard_result;

        $class                 = $this->class_model->get();

        $data['title']         = 'Add Batch';

        $data['title_list']    = 'Recent Batch';

        $data['examType']      = $this->exam_type;

        $data['classlist']     = $class;

        $session               = $this->session_model->get();

        $data['sessionlist']   = $session;

        $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim|required|xss_clean');

        $this->form_validation->set_rules('section_id', $this->lang->line('section'), 'trim|required|xss_clean');

        $this->form_validation->set_rules('session_id', $this->lang->line('session'), 'trim|required|xss_clean');

        $this->form_validation->set_rules('exam_group_id', $this->lang->line('exam_group'), 'trim|required|xss_clean');

        $this->form_validation->set_rules('exam_id', $this->lang->line('exam'), 'trim|required|xss_clean');

        $this->form_validation->set_rules('admitcard', $this->lang->line('admit_card_template'), 'trim|required|xss_clean');



        if ($this->form_validation->run() == false) {



        } else {

            $exam_group_id              = $this->input->post('exam_group_id');

            $exam_id                    = $this->input->post('exam_id');

            $session_id                 = $this->input->post('session_id');

            $class_id                   = $this->input->post('class_id');

            $section_id                 = $this->input->post('section_id');

            $admitcard_template         = $this->input->post('admitcard');

            $data['admitcard_template'] = $admitcard_template;



            $data['studentList'] = $this->examgroupstudent_model->searchExamStudents($exam_group_id, $exam_id, $class_id, $section_id, $session_id);



            $data['examList'] = $this->examgroup_model->getExamByExamGroup($exam_group_id, true);



            $data['exam_id']       = $exam_id;

            $data['exam_group_id'] = $exam_group_id;

        }

        $data['sch_setting'] = $this->sch_setting_detail;

        $this->load->view('layout/header', $data);

        $this->load->view('admin/examresult/admitcard', $data);

        $this->load->view('layout/footer', $data);

    }



    public function marksheet()

    {

        if (!$this->rbac->hasPrivilege('print_marksheet', 'can_view')) {

            access_denied();

        }

        $this->session->set_userdata('top_menu', 'Examinations');

        $this->session->set_userdata('sub_menu', 'Examinations/examresult/marksheetnew');//view name is updated on 18/07/2023

        $examgroup_result      = $this->examgroup_model->get();

        $data['examgrouplist'] = $examgroup_result;

        $marksheet_result      = $this->marksheet_model->get();

        $data['marksheetlist'] = $marksheet_result;

        $class                 = $this->class_model->get();

        $data['title']         = 'Add Batch';

        $data['title_list']    = 'Recent Batch';

        $data['examType']      = $this->exam_type;

        $data['classlist']     = $class;

        $session               = $this->session_model->get();

        $data['sessionlist']   = $session;

        $this->form_validation->set_rules('marksheet', $this->lang->line('marksheet_template'), 'trim|required|xss_clean');

        $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim|required|xss_clean');

        $this->form_validation->set_rules('section_id', $this->lang->line('section'), 'trim|required|xss_clean');

        $this->form_validation->set_rules('session_id', $this->lang->line('session'), 'trim|required|xss_clean');

        $this->form_validation->set_rules('exam_group_id', $this->lang->line('exam_group'), 'trim|required|xss_clean');

        $this->form_validation->set_rules('exam_id', $this->lang->line('exam'), 'trim|required|xss_clean');

        if ($this->form_validation->run() == false) {



        } else {

            $exam_group_id = $this->input->post('exam_group_id');

            $exam_id       = $this->input->post('exam_id');

            $session_id    = $this->input->post('session_id');

            $class_id      = $this->input->post('class_id');

            $section_id    = $this->input->post('section_id');

            $marksheet_template         = $this->input->post('marksheet');

            $data['marksheet_template'] = $marksheet_template;
            //agar exaxm id 2 hain toh hume exam id 1 tak search karna hain examgroupstudent_model mein exam id ka value change karke pass karna hain and datastudentexam ka value get karke view mein pass karna hain and then access karna hain
            $data['studentList']        = $this->examgroupstudent_model->searchExamStudents($exam_group_id, $exam_id, $class_id, $section_id, $session_id);

            $data['examList']           = $this->examgroup_model->getExamByExamGroup($exam_group_id, true);

            $data['exam_id']            = $exam_id;

            $data['exam_group_id']      = $exam_group_id;

        }

        $data['sch_setting'] = $this->sch_setting_detail;

        $this->load->view('layout/header', $data);

        $this->load->view('admin/examresult/marksheetnew', $data);//view name is updated on 18/07/2023

        $this->load->view('layout/footer', $data);

    }
    
    // public function demopdf()

    // {

    //     $data = array();

    //     $html = $this->load->view('admin/examresult/_printpdfmarksheet - Copy', $data, true);

    //     $this->load->library('m_pdf');

    //     $mpdf       = $this->m_pdf->load();

    //     $stylesheet = file_get_contents(base_url() . 'backend/pdf_style.css'); // external css

    //     $mpdf->SetDefaultBodyCSS('background', "url('./uploads/marksheet/e335ee85f71b9e2e29af3de3c6b588ec.jpg')");

    //     $mpdf->SetDefaultBodyCSS('background-image-resize', 6);

    //     $mpdf->SetDisplayMode('fullpage');

    //     $mpdf->WriteHTML($stylesheet, 1); // Writing style to pdf

    //     $mpdf->showWatermarkText = true;

    //     $mpdf->autoScriptToLang  = true;

    //     $mpdf->baseScript        = 1;

    //     $mpdf->autoLangToFont    = true;

    //     $mpdf->WriteHTML($html, \Mpdf\HTMLParserMode::HTML_BODY);

    //     $mpdf->Output();

    //     exit();



    // }

    public function pdftmarksheet()

    {



       
        $template         = $this->marksheet_model->get($this->input->post('marksheet_template'));

        $data['template'] = $template;



        $student_id                             = $this->input->post('student_id');

        $post_exam_id                           = $this->input->post('post_exam_id');

        $post_exam_group_id                     = $this->input->post('post_exam_group_id');

        $exam_group_class_batch_exam_student_id = $this->input->post('exam_group_class_batch_exam_student_id');
        
                $exam                                   = $this->examgroup_model->getExamByID($post_exam_id);

                $data['exam']                           = $exam;

                $marks_division                         = $this->marksdivision_model->get();

                $data['marks_division']                 = $marks_division;

                $student_data                           = $this->student_model->get($student_id);

                $exam_grades                            = $this->grade_model->getByExamType($exam->exam_group_type);

                $data['exam_grades']                    = $exam_grades;

                $data['marksheet']                      = $this->examresult_model->getStudentExamResults($post_exam_id, $post_exam_group_id, $exam_group_class_batch_exam_student_id, $student_id);

                $data['sch_setting']                    = $this->sch_setting_detail;

                $html= $this->load->view('admin/examresult/_printpdfmarksheet', $data, true);


                $type = $this->input->post('type');

                $this->load->library('m_pdf');

                $mpdf       = $this->m_pdf->load();

                $stylesheet = file_get_contents(base_url() . 'backend/pdf_style.css'); // external css

                if ($template->background_img != "") {



                    $mpdf->SetDefaultBodyCSS('background', "url('".$this->customlib->getFolderPath()."./uploads/marksheet/" . $template->background_img . "')");

                    $mpdf->SetDefaultBodyCSS('background-image-resize', 6);

                }

                $mpdf->WriteHTML($stylesheet, 1); // Writing style to pdf

                $mpdf->SetWatermarkText($this->sch_setting_detail->name, .2);

                $mpdf->SetDisplayMode('fullpage');

                $mpdf->showWatermarkText = true;

                $mpdf->autoScriptToLang  = true;

                $mpdf->baseScript        = 1;

                $mpdf->autoLangToFont    = true;


                $mpdf->WriteHTML($html, \Mpdf\HTMLParserMode::HTML_BODY);

        
        $response = true;

        if ($type == "email") {

            $content = $mpdf->Output(random_string() . '.pdf', 'S');

            $this->load->library('mailer');



            $student_value=$data['marksheet']['student'];



            $exam_roll_no=($exam->use_exam_roll_no) ? $student_value['exam_roll_no']:$student_value['student_roll_no'];







            $student_name = $this->customlib->getFullName($student_data['firstname'], $student_data['middlename'], $student_data['lastname'], $data['sch_setting']->middlename, $data['sch_setting']->lastname);



            $sender_details = array('email' => $student_data['email'], 'student_name' => $student_name, 'class' => $student_data['class'], 'section' => $student_data['section'], 'admission_no' => $student_data['admission_no'], 'roll_no' => $student_data['roll_no'], 'admit_card_roll_no'=>$exam_roll_no,'dob' => $student_data['dob'], 'guardian_name' => $student_data['guardian_name'], 'guardian_relation' => $student_data['guardian_relation'], 'guardian_phone' => $student_data['guardian_phone'], 'father_name' => $student_data['father_name'], 'father_phone' => $student_data['father_phone'], 'mother_name' => $student_data['mother_name'], 'gender' => $student_data['gender'], 'guardian_email' => $student_data['guardian_email'], 'exam' => $exam->exam);





            $this->mailsmsconf->mailsms('email_pdf_exam_marksheet', $sender_details, '', '', $content);



        } elseif ($type == "download") {



            $content = $mpdf->Output(random_string() . '.pdf', 'I');

            return $content;



        }

        if ($response) {

            $array = array('status' => 1, 'message' => $this->lang->line('mail_sent_successfully'));



        } else {

            $array = array('status' => 0, 'message' => $this->lang->line('something_went_wrong'));



        }

        echo json_encode($array);



    }

    public function printmarksheet()

    {

        $this->form_validation->set_error_delimiters('', '');

        $this->form_validation->set_rules('post_exam_id', $this->lang->line('exam'), 'required|trim|xss_clean');

        $this->form_validation->set_rules('post_exam_group_id', $this->lang->line('exam_group'), 'required|trim|xss_clean');

        $this->form_validation->set_rules('exam_group_class_batch_exam_student_id[]', $this->lang->line('students'), 'required|trim|xss_clean');

        $data = array();



        if ($this->form_validation->run() == false) {

            $data = array(

                'post_exam_id'                           => form_error('post_exam_id'),

                'post_exam_group_id'                     => form_error('post_exam_group_id'),

                'exam_group_class_batch_exam_student_id' => form_error('exam_group_class_batch_exam_student_id'),

            );

            $array = array('status' => 0, 'error' => $data);

            echo json_encode($array);

        } else {

            $template = $this->marksheet_model->get($this->input->post('marksheet_template'));



            $data['template'] = $template;



            $post_exam_id           = $this->input->post('post_exam_id'); 

            $post_exam_group_id     = $this->input->post('post_exam_group_id');

            $students_array         = $this->input->post('exam_group_class_batch_exam_student_id');


            
                    $marks_division         = $this->marksdivision_model->get();

                    $data['marks_division'] = $marks_division;

                    $exam                   = $this->examgroup_model->getExamByID($post_exam_id);

                    $data['exam']           = $exam;

                    $exam_grades            = $this->grade_model->getByExamType($exam->exam_group_type);

                    $data['exam_grades']    = $exam_grades;

                    $data['marksheet']      = $this->examresult_model->getExamResults($post_exam_id, $post_exam_group_id, $students_array);

                    $data['sch_setting']    = $this->sch_setting_detail;

                    $html= $this->load->view('admin/examresult/_printmarksheet', $data, true);
                    
               
                    $this->load->library('m_pdf');

                    $mpdf       = $this->m_pdf->load();

                    $stylesheet = file_get_contents(base_url() . 'backend/pdf_style.css'); // external css

                    if ($template->background_img != "") {



                        $mpdf->SetDefaultBodyCSS('background', "url('".$this->customlib->getFolderPath()."./uploads/marksheet/" . $template->background_img . "')");

                        $mpdf->SetDefaultBodyCSS('background-image-resize', 6);

                    }

                    $mpdf->WriteHTML($stylesheet, 1); // Writing style to pdf

                    $mpdf->SetWatermarkText($this->sch_setting_detail->name, .2);

                    $mpdf->SetDisplayMode('fullpage');

                    $mpdf->showWatermarkText = true;

                    $mpdf->autoScriptToLang  = true;

                    $mpdf->baseScript        = 1;

                    $mpdf->autoLangToFont    = true;


                    $mpdf->WriteHTML($html, \Mpdf\HTMLParserMode::HTML_BODY);

            $response = true;

            $content = $mpdf->Output(random_string() . '.pdf', 'I');

            return $content;



        }

    }

    public function index()

    {

        if (!$this->rbac->hasPrivilege('exam_result', 'can_view')) {

            access_denied();

        }

        $this->session->set_userdata('top_menu', 'Examinations');

        $this->session->set_userdata('sub_menu', 'Examinations/Examresult');

        $examgroup_result      = $this->examgroup_model->get();

        $data['examgrouplist'] = $examgroup_result;

        $marksheet_result      = $this->marksheet_model->get();

        $data['marksheetlist'] = $marksheet_result;

        $class                 = $this->class_model->get();

        $data['title']         = 'Add Batch';

        $data['title_list']    = 'Recent Batch';

        $data['examType']      = $this->exam_type;

        $data['classlist']     = $class;

        $session               = $this->session_model->get();

        $data['sessionlist']   = $session;

        $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim|required|xss_clean');

        $this->form_validation->set_rules('section_id', $this->lang->line('section'), 'trim|required|xss_clean');

        $this->form_validation->set_rules('session_id', $this->lang->line('session'), 'trim|required|xss_clean');

        $this->form_validation->set_rules('exam_group_id', $this->lang->line('exam_group'), 'trim|required|xss_clean');

        $this->form_validation->set_rules('exam_id', $this->lang->line('exam'), 'trim|required|xss_clean');



        if ($this->form_validation->run() == true) {

            $exam_group_id = $this->input->post('exam_group_id');

            $exam_id       = $this->input->post('exam_id');

            $session_id    = $this->input->post('session_id');

            $class_id      = $this->input->post('class_id');

            $section_id    = $this->input->post('section_id');



            $marksheet_template         = $this->input->post('marksheet');

            $data['marksheet_template'] = $marksheet_template;

            $exam_details               = $this->examgroup_model->getExamByID($exam_id);

            $studentList                = $this->examgroupstudent_model->searchExamStudents($exam_group_id, $exam_id, $class_id, $section_id, $session_id);



            $exam_subjects       = $this->batchsubject_model->getExamSubjects($exam_id);

            $data['subjectList'] = $exam_subjects;



            if (!empty($studentList)) {

                foreach ($studentList as $student_key => $student_value) {

                    $studentList[$student_key]->subject_results = $this->examresult_model->getStudentResultByExam($exam_id, $student_value->exam_group_class_batch_exam_student_id);

                }

            }



            $data['studentList'] = $studentList;



            $exam_grades           = $this->grade_model->getByExamType($exam_details->exam_group_type);

            $data['exam_grades']   = $exam_grades;

            $data['exam_details']  = $exam_details;

            $data['exam_id']       = $exam_id;

            $data['exam_group_id'] = $exam_group_id;

        }



        $data['sch_setting'] = $this->sch_setting_detail;

        $this->load->view('layout/header', $data);

        $this->load->view('admin/examresult/index', $data);

        $this->load->view('layout/footer', $data);

    }

    public function updaterank()

    {

        $exam_group_class_batch_exam_id         = $this->input->post('exam_group_class_batch_exam_id');

        $exam_group_class_batch_exam_student_id = $this->input->post('exam_group_class_batch_exam_student_id');

        if (!empty($exam_group_class_batch_exam_student_id)) {

            $exam_group_class_batch_exam_students = array();

            foreach ($exam_group_class_batch_exam_student_id as $exam_student_id_key => $exam_student_id_value) {

                $exam_group_class_batch_exam_students[] = array(

                    'id'   => $exam_student_id_value,

                    'rank' => $this->input->post('exam_group_class_batch_exam_student_id_' . $exam_student_id_value),

                );



            }

            $this->examresult_model->updaterank($exam_group_class_batch_exam_students, $exam_group_class_batch_exam_id);

        }



        $array = array('status' => '1', 'message' => $this->lang->line('update_message'));

        echo json_encode($array);

    }

    public function examrank()

    {

        $exam_id       = $this->input->post('exam_id');

        $studentList   = $this->examgroupstudent_model->searchExamStudentsByExam($exam_id);

         

        $exam_details  = $this->examgroup_model->getExamByID($exam_id);

        $exam_subjects = $this->batchsubject_model->getExamSubjects($exam_id);

        

        $subjectList   = $exam_subjects;



        if (!empty($studentList)) {

            foreach ($studentList as $student_key => $student_value) {

                $studentList[$student_key]->subject_results = $this->examresult_model->getStudentResultByExam($exam_id, $student_value->exam_group_class_batch_exam_student_id);

            }

        }

        $data['subjectList']  = $exam_subjects;

        $data['studentList']  = $studentList;

        $exam_grades          = $this->grade_model->getByExamType($exam_details->exam_group_type);

        $data['exam_grades']  = $exam_grades;

        $data['exam_details'] = $exam_details;

        $data['exam_id']      = $exam_id;

        $data['sch_setting']  = $this->sch_setting_detail;

        $page                 = $this->load->view('admin/examresult/_partialexamrank', $data, true);



        $array = array('status' => '1', 'page' => $page, 'exam_details' => $exam_details, 'message' => $this->lang->line('success_message'));

        echo json_encode($array);



    }

    public function getStudentByClassBatch()

    {

        $class_id            = $this->input->post('class_id');

        $section_id          = $this->input->post('section_id');

        $session_id          = $this->input->post('session_id');

        $data['studentList'] = $this->examgroupstudent_model->searchStudentByClassSectionSession($class_id, $section_id, $session_id);

        echo json_encode($data);

    }

    public function getExamGroupByStudent()

    {

        $student_id = $this->input->post('student_id');

        $data['examgrouplist'] = $this->examgroup_model->getExamGroupByStudent($student_id);

        echo json_encode($data);

    }

    public function studentresult()

    {

        $this->form_validation->set_error_delimiters('', '');

        $this->form_validation->set_rules('exam_group_id', $this->lang->line('exam_group_id'), 'required|trim|xss_clean');

        $this->form_validation->set_rules('student_id', $this->lang->line('student_id'), 'required|trim|xss_clean');



        if ($this->form_validation->run() == false) {

            $data = array(

                'exam_group_id' => form_error('exam_group_id'),

                'student_id'    => form_error('student_id'),

            );

            $array = array('status' => 0, 'error' => $data);

            echo json_encode($array);

        } else {



            $student_id         = $this->input->post('student_id');

            $exam_group_id      = $this->input->post('exam_group_id');

            $exam_group_exam_id = $this->input->post('exam_id');



            $examresult  = array();

            $exam_grades = array();

            if ($exam_group_exam_id != "") {

                $examresult = $this->examgroup_model->getExamResultDetailStudent($exam_group_exam_id, $exam_group_id, $student_id);



                $data['examresult']  = $examresult;

                $exam_grades         = $this->grade_model->getByExamType($examresult->exam_type);

                $data['exam_grades'] = $exam_grades;

                $examresult          = $this->load->view('admin/examresult/_getExam', $data, true);

            } else {

                $exam_group         = $this->examgroup_model->get($exam_group_id);

                $data['exam_group'] = $exam_group;

                $exam_grades         = $this->grade_model->getByExamType($exam_group->exam_type);

                $data['exam_grades'] = $exam_grades;

                $exam_result              = $this->examgroup_model->getExamGroupExamsResultByStudentID($exam_group_id, $student_id);

                $data['examresult']       = $exam_result;

                $exam_connections         = $this->examgroup_model->getExamGroupConnection($exam_group_id);

                $data['exam_connections'] = $exam_connections;

                $examresult               = $this->load->view('admin/examresult/_getExamGroupResult', $data, true);

            }



            $data['exam_grades'] = $exam_grades;



            $array = array('status' => '1', 'result' => $examresult, 'message' => $this->lang->line('success_message'));

            echo json_encode($array);

        }

    }

    public function getStudentCurrentResult()

    {

        $this->form_validation->set_rules('student_session_id', $this->lang->line('student_id'), 'trim|required|xss_clean');



        if ($this->form_validation->run() == false) {



            $msg = array(

                'student_session_id' => form_error('student_session_id'),

            );



            $array = array('status' => 0, 'error' => $msg);

        } else {

            $student_session_id  = $this->input->post('student_session_id');

            $data['exam_grades'] = $this->grade_model->get();

            $exam_groups_attempt = $this->examgroup_model->getExamGroupByStudentSession($student_session_id);



            $data['exam_groups_attempt'] = $exam_groups_attempt;

            $examresult                  = $this->load->view('admin/examresult/_getExamGroupResult', $data, true);

            $array                       = array('status' => 1, 'error' => '', 'result' => $examresult);

        }

        echo json_encode($array);

    }

    public function generatemarksheet()

    {

        $this->form_validation->set_rules('exam_id', $this->lang->line('exam_id'), 'trim|required|xss_clean');

        $this->form_validation->set_rules('check[]', $this->lang->line('students'), 'trim|required|xss_clean');



        if ($this->form_validation->run() == false) {



            $msg = array(

                'exam_id' => form_error('exam_id'),

                'check'   => form_error('check'),

            );



            $array = array('status' => 0, 'error' => $msg);

        } else {

            echo "<pre/>";

            $exam_id         = $this->input->post('exam_id');

            $students        = $this->input->post('check');

            $exam            = $this->examgroup_model->getExamByID($exam_id);

            $exam_id         = $exam->id;

            $students_result = array();

            if (!empty($students)) {

                foreach ($students as $student_key => $student_value) {

                    print_r($student_value);

                    exit();



                    $students_result[] = $this->examresult_model->getStudentExamResult($exam_id, $student_value);

                }

            }

            print_r($students_result);

            exit();

        }

        echo json_encode($array);

    }

    public function rankreport()

    {

        if (!$this->rbac->hasPrivilege('rank_report', 'can_view')) {

            access_denied();

        }



        $this->session->set_userdata('top_menu', 'Reports');

        $this->session->set_userdata('sub_menu', 'Reports/examinations');

        $this->session->set_userdata('subsub_menu', 'Reports/examinations/rankreport');

        $examgroup_result      = $this->examgroup_model->get();

        $data['examgrouplist'] = $examgroup_result;



        $marksheet_result      = $this->marksheet_model->get();

        $data['marksheetlist'] = $marksheet_result;



        $class               = $this->class_model->get();

        $data['title']       = 'Add Batch';

        $data['title_list']  = 'Recent Batch';

        $data['examType']    = $this->exam_type;

        $data['classlist']   = $class;

        $session             = $this->session_model->get();

        $data['sessionlist'] = $session;

        $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim|required|xss_clean');

        $this->form_validation->set_rules('section_id', $this->lang->line('section'), 'trim|required|xss_clean');

        $this->form_validation->set_rules('session_id', $this->lang->line('session'), 'trim|required|xss_clean');

        $this->form_validation->set_rules('exam_group_id', $this->lang->line('exam_group'), 'trim|required|xss_clean');

        $this->form_validation->set_rules('exam_id', $this->lang->line('exam'), 'trim|required|xss_clean');



        if ($this->form_validation->run() == true) {



   

            $exam_group_id = $this->input->post('exam_group_id');

            $exam_id       = $this->input->post('exam_id');

            $session_id    = $this->input->post('session_id');

            $class_id      = $this->input->post('class_id');

            $section_id    = $this->input->post('section_id');



            $marksheet_template         = $this->input->post('marksheet');

            $data['marksheet_template'] = $marksheet_template;

            $exam_details               = $this->examgroup_model->getExamByID($exam_id);



            $studentList = $this->examgroupstudent_model->searchExamStudents($exam_group_id, $exam_id, $class_id, $section_id, $session_id);



            $exam_subjects       = $this->batchsubject_model->getExamSubjects($exam_id);

            $data['subjectList'] = $exam_subjects;



            if (!empty($studentList)) {

                foreach ($studentList as $student_key => $student_value) {

                    $studentList[$student_key]->subject_results = $this->examresult_model->getStudentResultByExam($exam_id, $student_value->exam_group_class_batch_exam_student_id);

                }

            }



            $data['studentList'] = $studentList;



            $exam_grades           = $this->grade_model->getByExamType($exam_details->exam_group_type);

            $data['exam_grades']   = $exam_grades;

            $data['exam_details']  = $exam_details;

            $data['exam_id']       = $exam_id;

            $data['exam_group_id'] = $exam_group_id;

        }

        $data['sch_setting'] = $this->sch_setting_detail;

        $this->load->view('layout/header', $data);

        $this->load->view('admin/examresult/rankreport', $data);

        $this->load->view('layout/footer', $data);

    }

    public function examinations()

    {

        if (!$this->rbac->hasPrivilege('rank_report', 'can_view')) {

            access_denied();

        }

        $this->session->set_userdata('top_menu', 'Reports');

        $this->session->set_userdata('sub_menu', 'Reports/examinations');

        $this->session->set_userdata('subsub_menu', '');

        $this->load->view('layout/header');

        $this->load->view('admin/examresult/examinations');

        $this->load->view('layout/footer');

    }
    
    public function pdftestmarksheets()
    {   
        $template = $this->marksheet_model->get($this->input->post('marksheet_template'));
        $post_exam_group_id = $this->input->post('exam_group_id');
        $exam_option = $this->input->post('examoptionposition');
        $studentIds = $this->input->post('studentIds');
        $session_id = $this->input->post('session_id');
        $session = $this->session_model->get($session_id);
        $session_name = $session['session'];
        $class_name = $this->input->post('class_name');
        $section_name = $this->input->post('section_name');
        $info = array(
          'session_name' => $session_name,
          'class_name' => $class_name,
          'section_name' => $section_name
        );
        $class_id = $this->input->post('class_id');
        $section_id = $this->input->post('section_id');
        $data = array();
        $exams = $this->examgroup_model->getExamByExamGroup($post_exam_group_id, true);
        $examIds = array();
        foreach ($exams as $row) {
            $examIds[] = $row->id;
        }
        sort($examIds);
        //update
        $exam_id = end($examIds);
        $studentRanks = $this->ranksystem($post_exam_group_id, $exam_id, $class_id, $session_id, $exam_option);
        // 15-07-2023
        foreach ($studentIds as $studentId) {
            $counter = 1;
            foreach ($examIds as $examId) {
                if ($counter <= $exam_option) {
                    $exam_subjects = $this->batchsubject_model->getExamSubjects($examId);
                    $result = $this->examgroupstudent_model->searchExamStudentByExamNew($examId, $studentId, $session_id);
                    if ($result) {
                        $subject_results = $this->examresult_model->getStudentResultByExam($examId, $result->exam_group_class_batch_exam_student_id);
                        $data[$studentId]['exam_subjects'][$examId] = $exam_subjects;
                        $data[$studentId]['subject_results'][$examId] = $subject_results;
                    }
                }
                $counter++;
            }
            $studentdetails = $this->student_model->get($studentId);
            $data[$studentId]['studentdetails'] = $studentdetails;
            $data[$studentId]['info'] = $info;
            $data[$studentId]['template'] = $template;
            $data[$studentId]['exams'] = $exams;
            $data[$studentId]['rank'] = $studentRanks[$studentId];
        }
        //$data['sch_setting'] = $this->sch_setting_detail;
        $data['storedArray'] = $data;
        $html= $this->load->view('admin/examresult/marksheet_view', $data, true);
        $this->load->library('m_pdf');
        $mpdf = $this->m_pdf->load();
        $stylesheet = file_get_contents(base_url() . 'backend/bootstrap/css/bootstrap.min.css'); // external css
        $mpdf->WriteHTML($stylesheet, 1); // Writing style to pdf
        $stylesheet1 = file_get_contents(base_url() . 'backend/new_style.css'); // external css
        $mpdf->WriteHTML($stylesheet1, 1); // Writing style to pdf
        $mpdf->SetDisplayMode('fullpage');
        $mpdf->showWatermarkText = true;
        $mpdf->autoScriptToLang  = true;
        $mpdf->baseScript        = 1;
        $mpdf->autoLangToFont    = true;
        $mpdf->WriteHTML($html, \Mpdf\HTMLParserMode::HTML_BODY);
        $response = true;
        $content = $mpdf->Output(random_string() . '.pdf', 'I');
        return $content;
    }

    public function ranksystem($post_exam_group_id, $exam_id, $class_id, $session_id, $exam_option)
    {
        $studentList = $this->examgroupstudent_model->searchExamStudentsNew($post_exam_group_id, $exam_id, $class_id, $session_id);
        $data1['students'] = $studentList;
        $exam_option = $exam_option;
        $studentIds = array(); // Array to store student IDs
        foreach ($data1['students'] as $student) {
            $studentId = $student->student_id;
            $studentIds[] = $studentId; // Add student ID to the array
        }
        $exams = $this->examgroup_model->getExamByExamGroup($post_exam_group_id, true);
        $examIds = array();
        foreach ($exams as $row) {
            $examIds[] = $row->id;
        }
        sort($examIds);
        $data = array(); // Array to store data
        foreach ($studentIds as $studentId) {
            $counter = 1;
            foreach ($examIds as $examId) {
                if ($counter <= $exam_option) {
                    $result = $this->examgroupstudent_model->searchExamStudentByExamNew($examId, $studentId, $session_id);
                    $exam_subjects = $this->batchsubject_model->getExamSubjects($examId);
                    if ($result) {
                        $subject_results = $this->examresult_model->getStudentResultByExam($examId, $result->exam_group_class_batch_exam_student_id);
                        $data[$studentId]['subject_results'][$examId] = $subject_results;
                        $data[$studentId]['exam_subjects'][$examId] = $exam_subjects;
                    }
                }
                $counter++;
            }
        }
        $studentRanks = array(); // Array to store student ranks
        $totalPercentages = array(); // Array to store total percentages
        foreach ($data as $studentId => $studentData) {
            $tmarks = 0;
            $tmaxMarks = 0;
            $subjectGetMarks = array();
            foreach ($studentData['subject_results'] as $examId => $subject_results) {
                foreach ($subject_results as $subject_result) {
                    //$maxMarks = $subject_result->max_marks;
                    if ($subject_result->attendence === "present") {
                        if (isset($subject_result->get_marks)) {
                            $marks = $subject_result->get_marks;
                        } else {
                            $marks = 0;
                        }
                    } else {
                        $marks = 0;
                    }
                    $subjectGetMarks[$subject_result->subject_id][$examId] = $marks;
                    $tmarks += $marks;
                }
            }
            $subjectIds = array();
            $subjectMaxMarks = array();
            foreach ($studentData['exam_subjects'] as $examId => $exam_subjects) {
                foreach ($exam_subjects as $exam_subject) {
                    $maxMarks = $exam_subject->max_marks;
                    $subjectMaxMarks[$exam_subject->subject_id][$examId] = $maxMarks;
                    $tmaxMarks += $maxMarks;
                    $subjectId = $exam_subject->subject_id;
                    if (!in_array($subjectId, $subjectIds)) {
                        $subjectIds[] = $subjectId;
                    }
                }
            }
            $subjectTotalMarks = array();
            $subjectTotalMaxMarks = array();
            $subjectTotalPercentage = array();
            $subjectPercentageLessThan40 = false;
            foreach ($subjectIds as $subjectId) {
                $subjecttMarks = 0;
                $subjectmMarks = 0;
                foreach ($examIds as $examId) {
                    $subjecttMarks += $subjectGetMarks[$subjectId][$examId];
                    $subjectmMarks += $subjectMaxMarks[$subjectId][$examId];
                }
                $subjectTotalMarks[$subjectId] = $subjecttMarks;
                $subjectTotalMaxMarks[$subjectId] = $subjectmMarks;
                $subjectTotalPercentage[$subjectId] = number_format((($subjectTotalMarks[$subjectId] / $subjectTotalMaxMarks[$subjectId]) * 100), 2);
                if ($subjectTotalPercentage[$subjectId] < 40.00) {
                    $subjectPercentageLessThan40 = true;
                    break;
                }
            }
            if (!$subjectPercentageLessThan40) {
                $totalPercentage = number_format((($tmarks / $tmaxMarks) * 100), 2);
                if ($totalPercentage > 40.00) {
                    $totalPercentages[$studentId] = $totalPercentage;
                }
            }
        }
        $rank = 1;
        arsort($totalPercentages); // Sort total percentages in descending order
        foreach ($totalPercentages as $studentId => $totalPercentage) {
            $studentRanks[$studentId] = $rank;
            $rank++;
        }
        return $studentRanks;
    }

}
