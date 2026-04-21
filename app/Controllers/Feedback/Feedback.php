<?php

namespace App\Controllers\Feedback;

use App\Controllers\BaseController;

class Feedback extends BaseController
{
    /**
     * Display feedback form
     */
    public function submit($training_id = null)
    {
        // Check if user is logged in
        if (!session()->get('userid')) {
            session()->setFlashdata('error', 'Please login to submit feedback.');
            return redirect()->to('/login');
        }

        // Validate training ID
        if (!$training_id) {
            session()->setFlashdata('error', 'Invalid training ID.');
            return redirect()->to('mytrainings');
        }

        $db = \Config\Database::connect();
        $user_id = session()->get('userid');

        // Get training details
        $training = $db->table('lib_trainings lt')
            ->select('lt.*, oti.status_id, ts.status_name')
            ->join('other_training_info oti', 'oti.training_id = lt.id_training', 'left')
            ->join('training_status ts', 'ts.id = oti.status_id', 'left')
            ->where('lt.id_training', $training_id)
            ->get()
            ->getRowArray();

        if (!$training) {
            session()->setFlashdata('error', 'Training not found.');
            return redirect()->to('mytrainings');
        }

        // Check if user is enrolled in this training
        $is_enrolled = $db->table('training_attendees')
            ->where('training_id', $training_id)
            ->where('user_id', $user_id)
            ->countAllResults() > 0;

        if (!$is_enrolled) {
            session()->setFlashdata('error', 'You are not enrolled in this training.');
            return redirect()->to('mytrainings');
        }

        // Check if feedback already submitted
        $existing_feedback = $db->table('training_feedback')
            ->where('training_id', $training_id)
            ->where('user_id', $user_id)
            ->get()
            ->getRowArray();

        if ($existing_feedback) {
            session()->setFlashdata('info', 'You have already submitted feedback for this training.');
            return redirect()->to('mytrainings');
        }

        $data = [
            'title' => 'Submit Feedback',
            'training' => $training,
            'training_id' => $training_id
        ];

        return view('feedback/submit_form', $data);
    }

    /**
     * Process feedback submission
     */
    public function process()
    {
        // Check if user is logged in
        if (!session()->get('userid')) {
            session()->setFlashdata('error', 'Please login to submit feedback.');
            return redirect()->to('/login');
        }

        $db = \Config\Database::connect();
        $user_id = session()->get('userid');
        $training_id = $this->request->getPost('training_id');

        // Check if feedback already exists
        $existing_feedback = $db->table('training_feedback')
            ->where('training_id', $training_id)
            ->where('user_id', $user_id)
            ->get()
            ->getRowArray();

        if ($existing_feedback) {
            session()->setFlashdata('error', 'You have already submitted feedback for this training.');
            return redirect()->to('mytrainings');
        }

        // Insert feedback
        $data = [
            'training_id' => $training_id,
            'user_id' => $user_id
        ];

        try {
            $db->table('training_feedback')->insert($data);
            session()->setFlashdata('success', 'Thank you! Your feedback has been submitted successfully.');
            return redirect()->to('mytrainings');
        } catch (\Exception $e) {
            log_message('error', 'Error submitting feedback: ' . $e->getMessage());
            session()->setFlashdata('error', 'Failed to submit feedback. Please try again.');
            return redirect()->back()->withInput();
        }
    }
}
