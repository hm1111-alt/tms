<?php
// Add test pending training record for current employee

require_once 'system/bootstrap.php';

try {
    $db = \Config\Database::connect('training');
    
    // Get current employee from session
    $emp_idno = session()->get('emp_idno');
    $empid = session()->get('empid');
    $emp_fullname = session()->get('emp_fullname');
    
    echo "<h2>Add Test Pending Training</h2>";
    echo "<p><strong>Adding for Employee:</strong> " . esc($emp_idno) . " - " . esc($emp_fullname ?? 'Unknown') . "</p>";
    
    // Check if employee exists in db_employee
    $db_employee = \Config\Database::connect('default');
    $employee = $db_employee->table('employees')
        ->where('emp_idno', $emp_idno)
        ->get()
        ->getRowArray();
    
    if (!$employee) {
        echo "<p style='color:red'><strong>ERROR:</strong> Employee not found in db_employee!</p>";
        exit;
    }
    
    $emp_fullname = $employee['emp_fullname'];
    $emp_lname = $employee['emp_lname'];
    
    // Insert test pending training
    $testData = [
        'emp_idno' => $emp_idno,
        'employee_id' => $employee['id_employee'] ?? null,
        'emp_fullname' => $emp_fullname,
        'emp_lname' => $emp_lname,
        'station_id' => null,
        'training_hours' => 8,
        'training_remarks' => 'Test pending training',
        'training_certificate_file' => null,
        'training_name' => 'Test Pending Training - Web Development Basics',
        'training_category_id' => 4, // General
        'training_datefrom' => date('Y-m-d'),
        'training_dateto' => date('Y-m-d', strtotime('+2 days')),
        'training_facilitator' => 'Test Facilitator',
        'training_venue' => 'Online',
        'employee_training_id' => null,
        'training_id' => null,
        'is_approved' => 0,  // PENDING
        'is_disapproved' => 0,
        'approve_remarks' => null,
        'added_by' => $empid,
        'added_date' => date('Y-m-d H:i:s'),
        'updated_by' => null,
        'updated_date' => null
    ];
    
    $insert = $db->table('pending_trainings')->insert($testData);
    
    if ($insert) {
        $insertId = $db->insertID();
        echo "<p style='color:green; font-size:18px;'><strong>✓ SUCCESS!</strong></p>";
        echo "<p><strong>Test training added with ID:</strong> {$insertId}</p>";
        echo "<h3>Details:</h3>";
        echo "<ul>";
        echo "<li><strong>Employee:</strong> {$emp_fullname} ({$emp_idno})</li>";
        echo "<li><strong>Training Name:</strong> Test Pending Training - Web Development Basics</li>";
        echo "<li><strong>Date From:</strong> " . date('M j, Y') . "</li>";
        echo "<li><strong>Date To:</strong> " . date('M j, Y', strtotime('+2 days')) . "</li>";
        echo "<li><strong>Hours:</strong> 8</li>";
        echo "<li><strong>Status:</strong> <span style='color:orange;'><strong>PENDING</strong></span> (is_approved = 0)</li>";
        echo "</ul>";
        
        echo "<p style='margin-top:20px;'><a href='trainings' style='background:#007bff;color:white;padding:10px 20px;text-decoration:none;border-radius:5px;'>Go to Trainings Page</a></p>";
        echo "<p><em>You should now see this training in the PENDING TAB!</em></p>";
    } else {
        echo "<p style='color:red'><strong>✗ FAILED to insert record!</strong></p>";
        echo "<pre>" . print_r($db->error(), true) . "</pre>";
    }
    
    // Show all pending trainings for this employee
    echo "<hr><h3>All Pending Trainings for {$emp_idno}:</h3>";
    $pendingTrainings = $db->table('pending_trainings')
        ->where('emp_idno', $emp_idno)
        ->where('is_approved', 0)
        ->where('is_disapproved', 0)
        ->orderBy('id_pending_training', 'DESC')
        ->get()
        ->getResultArray();
    
    if (!empty($pendingTrainings)) {
        echo "<table border='1' cellpadding='5'>";
        echo "<tr><th>ID</th><th>Training Name</th><th>Date From</th><th>Date To</th><th>Hours</th><th>Status</th></tr>";
        foreach ($pendingTrainings as $pt) {
            echo "<tr>";
            echo "<td>" . $pt['id_pending_training'] . "</td>";
            echo "<td>" . $pt['training_name'] . "</td>";
            echo "<td>" . $pt['training_datefrom'] . "</td>";
            echo "<td>" . $pt['training_dateto'] . "</td>";
            echo "<td>" . $pt['training_hours'] . "</td>";
            echo "<td style='color:orange;'><strong>PENDING</strong></td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No pending trainings found.</p>";
    }
    
} catch (\Exception $e) {
    echo "<p style='color:red'><strong>Error:</strong> " . esc($e->getMessage()) . "</p>";
    echo "<pre>" . esc($e->getTraceAsString()) . "</pre>";
}
