<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->setAutoRoute(false);

// Public routes (no authentication required)
$routes->get('/', 'Trainings\Trainings::public_landing');
// Remove duplicate /trainings public route - let it use the authenticated one

// Login/Authentication routes
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

// Protected routes (require authentication)
$routes->group('', ['filter' => 'AuthAdmin'], function($routes){
    
    // Profile update routes
    $routes->get('/updateprofile', 'Profile\Profile::first_update');
    $routes->post('/updateprofile', 'Profile\Profile::first_update');
    $routes->post('/profile/update_password', 'Profile\Profile::update_reset_password');
    $routes->get('/profilepicture', 'Profile\Profile::picture_update');
    $routes->post('/profilepicture', 'Profile\Profile::picture_update');
    
    // Dashboard
    $routes->get('/dashboard', 'Dashboard::index');
    $routes->get('/get_memo', 'Dashboard::load_memo');
    $routes->post('/get_memo', 'Dashboard::load_memo');
    $routes->post('/get_my_credits', 'Dashboard::load_my_credits');
    $routes->get('/get_my_credits', 'Dashboard::load_my_credits');

    // My Profile
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
    
    // Training Management System Routes
    $routes->get('trainings', 'Trainings\Trainings::index');
    $routes->get('mytrainings', 'Trainings\Trainings::my_trainings'); // Employee/Guest personal trainings
    $routes->get('trainings/my_trainings', 'Trainings\Trainings::my_trainings'); // Alternative route with prefix
    $routes->get('trainings/pending', 'Trainings\Trainings::pending'); // View pending trainings
    $routes->get('trainings/add', 'Trainings\Trainings::add');
    $routes->post('trainings/add', 'Trainings\Trainings::add');
    $routes->get('trainings/edit/(:num)', 'Trainings\Trainings::edit/$1');
    $routes->post('trainings/update', 'Trainings\Trainings::update');
    $routes->post('trainings/load_approved_trainings', 'Trainings\Trainings::load_approved_trainings');
    $routes->post('trainings/load_pending_trainings', 'Trainings\Trainings::load_pending_trainings');
    $routes->post('trainings/save_pending', 'Trainings\Trainings::save_pending');
    $routes->post('trainings/delete_pending/(:num)', 'Trainings\Trainings::delete_pending/$1');
    $routes->post('trainings/get_pending_details/(:num)', 'Trainings\Trainings::get_pending_details/$1');
    $routes->post('trainings/update_pending', 'Trainings\Trainings::update_pending');
    $routes->get('trainings/debug_pending', 'Trainings\Trainings::debug_pending_trainings');
    $routes->post('trainings/delete/(:num)', 'Trainings\Trainings::delete/$1');
    $routes->get('trainings/tabs', 'Trainings\Trainings::index');
    $routes->get('uploads/trainings/certificates/(:any)', 'Trainings\Trainings::serve_certificate/$1');
});

// Public training view route (no authentication required)
$routes->get('trainings/view/(:num)', 'Trainings\Trainings::view/$1');

// Enrollment route (requires authentication - will redirect to login if not logged in)
$routes->get('trainings/enroll/(:num)', 'Trainings\Trainings::enroll/$1');
