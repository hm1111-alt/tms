<?php

namespace App\Controllers\Attendance;

use App\Controllers\BaseController;
use App\Libraries\PdfService;

class Leaves extends BaseController
{
        public function __construct()
        {
            // Load the model in the constructor
            $this->mdl_leave = new \App\Models\attendance\mdl_leave();
                
            $this->mdl_setting = new \App\Models\preferences\mdl_setting();
            $this->class_name = basename(str_replace('\\', '/', get_class($this)));
            
        }

        public function index()
        {
                $data['status'] = 1;
                $data['page'] = $this->mdl_setting->get_page_details($this->class_name);
                $data['statuses'] = $this->mdl_leave->get_statuses_tab();
                
                return view('attendance/leaves/leaves',$data);

        }
        
        function send_email_employee2($leave_refno='')
        {
                $leave_refno = 'LV-rtXzl3YBnd';
                $leave_id = $this->mdl_leave->get_leaveid($leave_refno);
                //$leave_id = @$leaveid[0]->id_leave;
                
                $leave = $this->mdl_leave->get_leave($leave_id);
                $data['leave'] = $leave;
                $details = $this->mdl_leave->get_leave_details($leave_id);
                $data['details'] = $details;
                
                $data['leave_dates'] = $this->mdl_leave->get_leave_dates($leave_id);
                $data['signatories'] = $this->mdl_leave->get_leave_signatories($leave_id);
                $data['approved'] = $this->mdl_leave->get_leave_approved($leave_id);
                
                $data['email_title'] = 'HRMIS | Application for Leave';

                $email_body = "Hi ".@$leave[0]->emp_fname.",";
                $email_body .= "<br><br>Your Application for leave has been submitted on ".date('F j, Y',strtotime(@$leave[0]->updated_date)).".";
                $email_body .= "<br><br>";
                $email_body .= "<a href='".site_url('print_leave/'.$leave_refno)."' style='font-weight: bold; ' target='_blank'>";
                    $email_body .= "Click here to print or download your leave application.";
                $email_body .= "</a>";
                $email_body .= "<br><br>";

                $email_body .= view('attendance/leaves/email_details', $data);

                $email_body .= '<br><hr><br>This is an auto-generated email from CLSU E-portal. Please do not reply.';

                $data['email_body'] = $email_body;

                $body = view('login/email_body', $data);
                
                                    
                                    $subject = 'CLSU E-portal | '.$data['email_title'];
                                    $cc = '';
                                    $bcc = '';

                                    $user = $this->mdl_leave->get_employee_email(@$leave[0]->emp_idno);
                                    
                                    $to_email[0] = @$user[0]->emp_email_official; // 'sottobenjamin@gmail.com'; // for testing //
                                    $to_name[0] = @$user[0]->emp_fname.' '.@$user[0]->emp_lname.' '.@$user[0]->emp_extname; 
                                    
                                    if(@$user[0]->emp_email_personal){
                                        $to_email[1] = @$user[0]->emp_email_personal; // 'sottobenjamin@gmail.com'; // for testing //
                                        $to_name[1] = @$user[0]->emp_fname.' '.@$user[0]->emp_lname.' '.@$user[0]->emp_extname; 
                                    }

                                    helper('mailer'); // Load URL helper
                                    $mail_res = phpmail('CLSU-MISO-mailer',$to_email,$to_name,$subject,$body, $cc,$bcc);

                                    echo $mail_res ? 'Email sent' : 'something went wrong';

                                    
            
        }


}