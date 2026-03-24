<?php
// Quick diagnostic to check pending trainings for current employee

require_once 'system/bootstrap.php';

try {
    $db = \Config\Database::connect('training');
    $db_employee = \Config\Database::connect('default'); // db_employee
    
    // Get current employee from session
    $emp_idno = session()->get('emp_idno');
    $empid = session()->get('empid');
    
    echo "<h2>Pending Trainings Diagnostic</h2>";
    echo "<p><strong>Current Employee ID (emp_idno):</strong> " . esc($emp_idno ?? 'NULL') . "</p>";
    echo "<p><strong>Current Employee ID (empid):</strong> " . esc($empid ?? 'NULL') . "</p>";
    
    // Check if employee exists in db_employee
    echo "<h3>Employee Information (from db_employee):</h3>";
    $employee = $db_employee->table('employees')
        ->where('emp_idno', $emp_idno)
        ->get()
        ->getRowArray();
    
    if ($employee) {
        echo "<p><strong>✓ Employee found:</strong> " . esc($employee['emp_fullname']) . "</p>";
        echo "<p><strong>Email:</strong> " . esc($employee['emp_email_official']) . "</p>";
        echo "<p><strong>Position:</strong> " . esc($employee['emp_position_name'] ?? 'N/A') . "</p>";
    } else {
        echo "<p style='color:red'><strong>✗ Employee NOT found in db_employee!</strong></p>";
    }
    
    // Check total records
    $total = $db->table('pending_trainings')->countAllResults();
    echo "<h3>Total Records in pending_trainings: {$total}</h3>";
    
    // Check records for this employee
    $employeeRecords = $db->table('pending_trainings')
        ->where('emp_idno', $emp_idno)
        ->countAllResults();
    echo "<h3>Records for emp_idno '{$emp_idno}': {$employeeRecords}</h3>";
    
    // Breakdown by status
    echo "<h3>Breakdown by Status:</h3>";
    
    $pending = $db->table('pending_trainings')
        ->where('emp_idno', $emp_idno)
        ->groupStart()
            ->where('is_approved', 0)
            ->orWhere('is_approved', null)
        ->groupEnd()
        ->where('is_disapproved', 0)
        ->countAllResults();
    
    $approved = $db->table('pending_trainings')
        ->where('emp_idno', $emp_idno)
        ->where('is_approved', 1)
        ->countAllResults();
    
    $disapproved = $db->table('pending_trainings')
        ->where('emp_idno', $emp_idno)
        ->where('is_disapproved', 1)
        ->countAllResults();
    
    echo "<ul>";
    echo "<li><strong>Pending (should show in Pending tab):</strong> {$pending}</li>";
    echo "<li><strong>Approved (should show in Approved tab):</strong> {$approved}</li>";
    echo "<li><strong>Disapproved:</strong> {$disapproved}</li>";
    echo "</ul>";
    
    // Show pending records
    if ($pending > 0) {
        echo "<h3>Pending Records (Should appear in Pending Tab):</h3>";
        $pendingRecords = $db->table('pending_trainings')
            ->where('emp_idno', $emp_idno)
            ->groupStart()
                ->where('is_approved', 0)
                ->orWhere('is_approved', null)
            ->groupEnd()
            ->where('is_disapproved', 0)
            ->orderBy('id_pending_training', 'DESC')
            ->get()
            ->getResultArray();
        
        echo "<table border='1' cellpadding='5'>";
        echo "<tr><th>ID</th><th>Training Name</th><th>is_approved</th><th>is_disapproved</th><th>Date From</th><th>Date To</th><th>Hours</th></tr>";
        foreach ($pendingRecords as $r) {
            echo "<tr>";
            echo "<td>" . ($r['id_pending_training'] ?? 'N/A') . "</td>";
            echo "<td>" . ($r['training_name'] ?? 'N/A') . "</td>";
            echo "<td>" . ($r['is_approved'] ?? 'NULL') . "</td>";
            echo "<td>" . ($r['is_disapproved'] ?? 'NULL') . "</td>";
            echo "<td>" . ($r['training_datefrom'] ?? 'N/A') . "</td>";
            echo "<td>" . ($r['training_dateto'] ?? 'N/A') . "</td>";
            echo "<td>" . ($r['training_hours'] ?? 'N/A') . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
    
    // Show approved records
    if ($approved > 0) {
        echo "<h3>Approved Records (Should appear in Approved Tab):</h3>";
        $approvedRecords = $db->table('pending_trainings')
            ->where('emp_idno', $emp_idno)
            ->where('is_approved', 1)
            ->orderBy('id_pending_training', 'DESC')
            ->get()
            ->getResultArray();
        
        echo "<table border='1' cellpadding='5'>";
        echo "<tr><th>ID</th><th>Training Name</th><th>is_approved</th><th>Date From</th><th>Date To</th><th>Hours</th></tr>";
        foreach ($approvedRecords as $r) {
            echo "<tr>";
            echo "<td>" . ($r['id_pending_training'] ?? 'N/A') . "</td>";
            echo "<td>" . ($r['training_name'] ?? 'N/A') . "</td>";
            echo "<td style='background-color:lightgreen'>" . ($r['is_approved'] ?? 'NULL') . "</td>";
            echo "<td>" . ($r['training_datefrom'] ?? 'N/A') . "</td>";
            echo "<td>" . ($r['training_dateto'] ?? 'N/A') . "</td>";
            echo "<td>" . ($r['training_hours'] ?? 'N/A') . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
    
    // Show all records for this employee
    echo "<h3>All Records for This Employee:</h3>";
    $allRecords = $db->table('pending_trainings')
        ->where('emp_idno', $emp_idno)
        ->orderBy('id_pending_training', 'DESC')
        ->limit(20)
        ->get()
        ->getResultArray();
    
    if (!empty($allRecords)) {
        echo "<table border='1' cellpadding='5'>";
        echo "<tr><th>ID</th><th>Training Name</th><th>is_approved</th><th>is_disapproved</th><th>Date From</th><th>Date To</th></tr>";
        foreach ($allRecords as $r) {
            $bg = '';
            if ($r['is_approved'] == 1) $bg = 'style="background-color:lightgreen"';
            elseif ($r['is_disapproved'] == 1) $bg = 'style="background-color:lightcoral"';
            elseif ($r['is_approved'] == 0 || $r['is_approved'] === null) $bg = 'style="background-color:lightyellow"';
            
            echo "<tr {$bg}>";
            echo "<td>" . ($r['id_pending_training'] ?? 'N/A') . "</td>";
            echo "<td>" . ($r['training_name'] ?? 'N/A') . "</td>";
            echo "<td>" . ($r['is_approved'] ?? 'NULL') . "</td>";
            echo "<td>" . ($r['is_disapproved'] ?? 'NULL') . "</td>";
            echo "<td>" . ($r['training_datefrom'] ?? 'N/A') . "</td>";
            echo "<td>" . ($r['training_dateto'] ?? 'N/A') . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No records found for this employee.</p>";
    }
    
    // Show ALL records in database (regardless of employee)
    echo "<h3>ALL Records in Database (Last 20):</h3>";
    $allDbRecords = $db->table('pending_trainings')
        ->orderBy('id_pending_training', 'DESC')
        ->limit(20)
        ->get()
        ->getResultArray();
    
    if (!empty($allDbRecords)) {
        echo "<table border='1' cellpadding='5'>";
        echo "<tr><th>ID</th><th>Employee ID</th><th>Training Name</th><th>is_approved</th><th>is_disapproved</th><th>Date From</th><th>Date To</th></tr>";
        foreach ($allDbRecords as $r) {
            $bg = '';
            if ($r['is_approved'] == 1) $bg = 'style="background-color:lightgreen"';
            elseif ($r['is_disapproved'] == 1) $bg = 'style="background-color:lightcoral"';
            elseif ($r['is_approved'] == 0 || $r['is_approved'] === null) $bg = 'style="background-color:lightyellow"';
            
            echo "<tr {$bg}>";
            echo "<td>" . ($r['id_pending_training'] ?? 'N/A') . "</td>";
            echo "<td>" . ($r['emp_idno'] ?? 'N/A') . "</td>";
            echo "<td>" . ($r['training_name'] ?? 'N/A') . "</td>";
            echo "<td>" . ($r['is_approved'] ?? 'NULL') . "</td>";
            echo "<td>" . ($r['is_disapproved'] ?? 'NULL') . "</td>";
            echo "<td>" . ($r['training_datefrom'] ?? 'N/A') . "</td>";
            echo "<td>" . ($r['training_dateto'] ?? 'N/A') . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No records in database at all!</p>";
    }
    
} catch (\Exception $e) {
    echo "<p style='color:red'><strong>Error:</strong> " . esc($e->getMessage()) . "</p>";
    echo "<pre>" . esc($e->getTraceAsString()) . "</pre>";
}
