<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */



//$routes->setAutoRoute(true);

$routes->get('/', 'LoginController::index');
$routes->post('/', 'LoginController::index');
//$routes->get('/home', 'Home::index');
//
//
//$routes->get('/leaves', 'Leaves::index');

$routes->get('/login', 'LoginController::index');
$routes->post('/login', 'LoginController::index');
$routes->get('/logout', 'LoginController::logout');


$routes->get('/register', 'LoginController::register');
$routes->post('/register', 'LoginController::register');
$routes->get('/verify/(:any)', 'LoginController::verify_account/$1');

$routes->get('/forgot_password', 'LoginController::forgot_password');
$routes->post('/forgot_password', 'LoginController::forgot_password');

$routes->get('/resetPasword/(:any)', 'LoginController::reset_password/$1');
$routes->post('/resetPasword/(:any)', 'LoginController::reset_password/$1');

$routes->get('/data-privacy-policy', 'LoginController::privacy');


            $routes->get('/recommend_leave/(:any)', 'Attendance\Leaves2::approve_recommending/$1');
            $routes->get('/approve_leave/(:any)', 'Attendance\Leaves2::approve_approving/$1');
                
            $routes->get('/print_leave/(:any)', 'Attendance\Leaves2::download_ref/$1');
            
            
            
$routes->group('',['filter' => 'AuthAdmin'], function($routes){
    
        
        $routes->get('/updateprofile', 'Profile\Profile::first_update');
        $routes->post('/updateprofile', 'Profile\Profile::first_update');
        
        $routes->post('/profile/update_password', 'Profile\Profile::update_reset_password');
        
        $routes->get('/update_esign', 'Attendance\Leaves::update_esignatory');
        $routes->post('/update_esign', 'Attendance\Leaves::update_esignatory');
        
        
        $routes->get('/profilepicture', 'Profile\Profile::picture_update');
        $routes->post('/profilepicture', 'Profile\Profile::picture_update');
        
        $routes->get('/dashboard', 'Dashboard::index');
        
        $routes->get('/get_memo', 'Dashboard::load_memo');
        $routes->post('/get_memo', 'Dashboard::load_memo');
        $routes->post('/get_my_credits', 'Dashboard::load_my_credits');
        $routes->get('/get_my_credits', 'Dashboard::load_my_credits');

        
        $routes->get('/executive', 'Executive::index');
        $routes->get('/research', 'Research\Research::index');
        $routes->get('/services', 'Services\Services::index');
        
        
        $routes->get('/myprofile', 'Profile\Profile::index');
        $routes->post('/myprofile/load_personal', 'Profile\Profile::tab_personal');
        $routes->post('/myprofile/load_family', 'Profile\Profile::tab_family');
        $routes->post('/myprofile/load_educational', 'Profile\Profile::tab_educational');
        $routes->post('/myprofile/load_eligibility', 'Profile\Profile::tab_eligibility');
        $routes->post('/myprofile/load_employment', 'Profile\Profile::tab_employment');
        $routes->post('/myprofile/load_voluntarywork', 'Profile\Profile::tab_voluntarywork');
        $routes->post('/myprofile/load_skills', 'Profile\Profile::tab_skills');
        $routes->post('/myprofile/load_recognitions', 'Profile\Profile::tab_recognitions');
        $routes->post('/myprofile/load_organizations', 'Profile\Profile::tab_organizations');
        $routes->post('/myprofile/load_others', 'Profile\Profile::tab_others');
        $routes->post('/myprofile/load_references', 'Profile\Profile::tab_references');
        $routes->post('/myprofile/load_pdsinfo', 'Profile\Profile::tab_pdsinfo');
        $routes->post('/myprofile/load_trainings', 'Profile\Profile::tab_trainings');
        
        

        $routes->get('/employees', 'Records\Employees::index');
        $routes->post('/employees/get_employees', 'Records\Employees::load_employees_table');
        $routes->get('/employees/edit/(:num)', 'Records\Employees::edit/$1');
        $routes->post('/employees/edit/(:num)', 'Records\Employees::edit/$1');
        $routes->get('/employees/view/(:num)', 'Records\Employees::view/$1');



        $routes->get('/attendance', 'Attendance\Attendance::index');

        $routes->get('/attendance/leaves', 'Attendance\Leaves::index');
        $routes->get('/leaves', 'Attendance\Leaves::index');
        $routes->post('/leaves/get_leaves', 'Attendance\Leaves::load_leave_table');

        $routes->get('/leaves/view/(:any)', 'Attendance\Leaves::view/$1');

        $routes->get('/leaves/download_form/(:any)', 'Attendance\Leaves::download_form/$1');
        $routes->get('/download_leave/(:any)', 'Attendance\Leaves::download_ref/$1');
        $routes->get('/download_leave', 'Attendance\Leaves::download_test');
        $routes->get('/leaves/filing', 'Attendance\Leaves::add');
        $routes->post('/leaves/filing', 'Attendance\Leaves::add');
        $routes->post('/leaves/filing', 'Attendance\Leaves::add');
        $routes->get('/leaves/cancel_draft', 'Attendance\Leaves::cancel_draft_leave');
        $routes->post('/leaves/add_details', 'Attendance\Leaves::add_details');
        $routes->get('/leaves/add_details', 'Attendance\Leaves::add_details');
        
        $routes->post('/leaves/add_files', 'Attendance\Leaves::add_files');
        $routes->get('/leaves/add_files', 'Attendance\Leaves::add_files');
        $routes->post('/leaves/upload_file', 'Attendance\Leaves::add_files');
        
        $routes->get('/leaves/add_signatories', 'Attendance\Leaves::add_signatories');
        $routes->post('/leaves/add_signatories', 'Attendance\Leaves::add_signatories');
        $routes->get('/leaves/add_confirm', 'Attendance\Leaves::add_confirm');
        $routes->get('/leaves/submit_leave', 'Attendance\Leaves::submit_leave');

        $routes->get('/leaves/receive/(:any)', 'Attendance\Leaves::receive/$1');
        $routes->post('/leaves/receive/(:any)', 'Attendance\Leaves::receive/$1');


        $routes->post('/attendance/leaves/load_employee_details', 'Attendance\Leaves::load_employee_details');
        $routes->post('/leaves/get_mindate', 'Attendance\Leaves::get_mindate');
        $routes->post('/leaves/add_date', 'Attendance\Leaves::add_date');
        $routes->post('/leaves/edit_date', 'Attendance\Leaves::edit_date');

        $routes->get('/leaves/delete_date/(:num)/(:num)', 'Attendance\Leaves::delete_date/$1/$2');

        $routes->post('/leaves/delete_detail/(:num)/(:num)', 'Attendance\Leaves::delete_detail/$1/$2');
        $routes->get('/leaves/delete_detail/(:num)/(:num)', 'Attendance\Leaves::delete_detail/$1/$2');

        
        
        $routes->get('/leaves/send_email_employee', 'Attendance\Leaves::send_email_employee');
        $routes->get('/leaves/send_email_recommending', 'Attendance\Leaves::send_email_recommending');





        $routes->get('/attendance/holidays/index', 'Attendance\Holidays::index');
        $routes->get('/attendance/holidays', 'Attendance\Holidays::index');

        $routes->get('/dtr', 'Attendance\Dtr::index');
        $routes->get('/travelorders', 'Attendance\Travelorders::index');

        $routes->get('/holidays', 'Attendance\Holidays::index');
        $routes->get('/holidays/add', 'Attendance\Holidays::add');
        $routes->post('/holidays/add', 'Attendance\Holidays::add');
        $routes->get('/holidays/delete/(:num)', 'Attendance\Holidays::delete/$1');
        $routes->post('/holidays/get_all_holidays', 'Attendance\Holidays::load_holidays_table');
        $routes->get('/holidays/edit/(:num)', 'Attendance\Holidays::edit/$1');
        $routes->post('/holidays/edit/(:num)', 'Attendance\Holidays::edit/$1');




        $routes->get('/credits', 'Attendance\Credits::index');
        $routes->get('/credits/view/(:num)/(:num)', 'Attendance\Credits::credits_view/$1/$2');
        $routes->get('/credits/view/(:num)', 'Attendance\Credits::credits_view/$1');

        $routes->post('/credits/load_logs', 'Attendance\Credits::load_credits_logs');
        $routes->get('/credits/load_details/(:num)/(:num)', 'Attendance\Credits::load_credits_details/$1/$2');
        $routes->get('/credits/award', 'Attendance\Credits::award');
        $routes->post('/credits/award', 'Attendance\Credits::award');
        $routes->post('/credits/load_earned', 'Attendance\Credits::load_credits_earned');

        $routes->get('/credits/add', 'Attendance\Credits::add');
        $routes->post('/credits/add', 'Attendance\Credits::add');
        $routes->get('/credits/delete/(:num)', 'Attendance\Credits::delete/$1');
        $routes->post('/credits/get_all_credits', 'Attendance\Credits::load_credits_table');
        $routes->get('/credits/edit/(:num)', 'Attendance\Credits::edit/$1');
        $routes->post('/credits/edit/(:num)', 'Attendance\Credits::edit/$1');

        $routes->get('/credits/add_log/(:num)', 'Attendance\Credits::add_log/$1');
        $routes->post('/credits/add_log/(:num)', 'Attendance\Credits::add_log/$1');

        $routes->get('/credits/edit_log/(:num)/(:num)', 'Attendance\Credits::edit_log/$1/$2');
        $routes->post('/credits/edit_log/(:num)/(:num)', 'Attendance\Credits::edit_log/$1/$2');

        $routes->get('/credits/delete_log/(:num)/(:num)', 'Attendance\Credits::delete_log/$1/$2');
        $routes->get('/credits/recompute/(:num)', 'Attendance\Credits::recompute/$1');
        
        $routes->get('services/requests/form', 'Services\Requests::form');
        $routes->get('services/requests/requests_form', 'Services\Requests::form');
        $routes->post('services/requests/submit', 'Services\Requests::submit');
        $routes->get('services/requests', 'Services\Requests::index');
        $routes->get('services/requests/check_pending_feedback', 'Services\Requests::check_pending_feedback');
        $routes->get('services/requests/requests_view/(:num)', 'Services\Requests::view/$1');
        $routes->get('services/requests/view/(:num)/(:num)', 'Services\Requests::view/$1/$2');
        $routes->post('services/requests/load_table_requests', 'Services\Requests::load_table_requests');
        $routes->get('services/requests/edit_document/(:num)/(:num)', 'Services\\Requests::edit_document/$1/$2');
        $routes->post('services/requests/edit_document/(:num)/(:num)', 'Services\\Requests::edit_document/$1/$2');
        $routes->get('services/requests/delete/(:num)', 'Services\\Requests::delete/$1');
        
        // File viewing routes - place at the end to avoid conflicts
        $routes->get('services/requests/view_file_test_segments/(:any)', 'Services\Requests::view_file_test_segments_single/$1');
        $routes->get('services/requests/view_file_test/(:segment)/(:segment)/(:segment)/(:segment)/(:any)', 'Services\Requests::view_file_test_segments/$1/$2/$3/$4/$5');
        $routes->get('services/requests/view_file_test/(.*)', 'Services\Requests::view_file_test/$1');
        $routes->get('services/requests/view_file/public/uploads/completed_documents/(:any)', 'Services\Requests::view_file/$1');
        $routes->get('services/requests/view_file/(.*)', 'Services\Requests::view_file/$1');
        $routes->get('services/requests/view_pdf/(:num)/(:num?)', 'Services\Requests::view_pdf/$1/$2');

        // Feedback routes
        $routes->get('feedback/submit/(:num)/(:num)', 'Feedback\Feedback::submit_feedback/$1/$2');
        $routes->post('feedback/save_feedback/(:num)/(:num)', 'Feedback\Feedback::save_feedback/$1/$2');
        $routes->get('feedback/view_feedback_pdf/(:num)/(:num)', 'Feedback\Feedback::view_feedback_pdf/$1/$2');
        $routes->get('feedback/download_feedback_pdf/(:num)/(:num)', 'Feedback\Feedback::download_feedback_pdf/$1/$2');
        
        // Training routes
        $routes->get('trainings', 'Trainings\Trainings::index');
        $routes->post('trainings/load_approved_trainings', 'Trainings\Trainings::load_approved_trainings');
        $routes->post('trainings/load_pending_trainings', 'Trainings\Trainings::load_pending_trainings');
        $routes->post('trainings/save_pending', 'Trainings\Trainings::save_pending');
        $routes->post('trainings/delete_pending/(:num)', 'Trainings\Trainings::delete_pending/$1');
        $routes->post('trainings/get_pending_details/(:num)', 'Trainings\Trainings::get_pending_details/$1');
        $routes->post('trainings/update_pending', 'Trainings\Trainings::update_pending');
        $routes->get('trainings/debug_pending', 'Trainings\Trainings::debug_pending_trainings');
        $routes->get('trainings/view/(:num)', 'Trainings\Trainings::view/$1');
        $routes->get('trainings/tabs', 'Trainings\Trainings::index');

        $routes->get('uploads/trainings/certificates/(:any)', 'Trainings\Trainings::serve_certificate/$1');


});
