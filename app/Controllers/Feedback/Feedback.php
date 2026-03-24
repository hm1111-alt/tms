<?php

namespace App\Controllers\Feedback;

use App\Controllers\BaseController;
use App\Models\feedback\Mdl_feedback;
use App\Models\preferences\mdl_setting;

require_once COMPOSER_PATH;

class Feedback extends BaseController
{
    protected $mdl_setting;
    protected $class_name;
    
    public function __construct() {
        $this->mdl_setting = new mdl_setting();
        $this->class_name = 'feedback';
    }

    public function submit_feedback($request_id, $document_id)
    {
        $page = $this->mdl_setting->get_page_details($this->class_name);
        
        if (empty($page)) {
            $page = new \stdClass();
            $page->page_name2 = 'Feedback Form';
            $page->page_parent = 0;
            $page->class_name = 'feedback';
        }
        
        $model = new Mdl_feedback();
        $questions = $model->getQuestions();
        
        $data = [
            'request_id'  => $request_id,
            'document_id' => $document_id,
            'questions'   => $questions,
            'page'        => $page
        ];
        
        return view('feedback/feedback_form', $data);
    }

    public function save_feedback($request_id, $document_id)
    {
        $model = new Mdl_feedback();
        $post = $this->request->getPost();

        if (empty($post['overall_rating'])) {
            return redirect()->back()->withInput()->with('error', 'Please provide an overall rating.');
        }

        $experienced_harassment = isset($post['experienced_harassment']) ? (int)$post['experienced_harassment'] : 0;
        $harassment_details = $experienced_harassment ? ($post['harassment_details'] ?? null) : null;

        $recommend_clsu = isset($post['recommend_clsu']) ? (int)$post['recommend_clsu'] : 0;

        $data = [
            'request_id'             => $request_id,
            'document_id'            => $document_id,
            'overall_rating'         => $post['overall_rating'] ?? null,
            'experienced_harassment' => $experienced_harassment,
            'harassment_details'     => $harassment_details,
            'recommend_clsu'         => $recommend_clsu,
            'suggestions'            => $post['suggestions'] ?? null,
            'created_at'             => date('Y-m-d H:i:s'),
        ];

        $answers = [];
        $answered_questions = 0;
        foreach ($post as $key => $value) {
            if (strpos($key, 'question_') === 0) {
                $questionId = str_replace('question_', '', $key);
                $answers[$questionId] = $value;
                if (!empty($value) && $value != '6') { 
                    $answered_questions++;
                }
            }
        }

        if ($answered_questions == 0) {
            return redirect()->back()->withInput()->with('error', 'Please answer at least one question (excluding N/A responses).');
        }

        $model->insertFeedback($data, $answers);
        return redirect()->to('services/requests')->with('success', 'Thank you for your feedback!');
    }


    public function view_feedback_pdf($request_id, $document_id)
    {
        $model = new Mdl_feedback();

        $feedback = $model->getFeedback($request_id, $document_id);
        $raw_answers = $model->getAnswers($request_id, $document_id);
        $logo_file = FCPATH . 'images/pdf_header.png';
        $logo_base64 = file_exists($logo_file) ? 'data:image/png;base64,' . base64_encode(file_get_contents($logo_file)) : null;

        $sqd_image_names = [
            1 => 'strongly_disagree.png',
            2 => 'disagree.png',
            3 => 'neither_agree_nor_disagree.png',
            4 => 'agree.png',
            5 => 'strongly_agree.png',
            6 => 'not_applicable.png'
        ];

        $sqd_images = [];
        foreach ($sqd_image_names as $key => $filename) {
            $file_path = FCPATH . 'images/' . $filename; 
            $sqd_images[$key] = file_exists($file_path) ? 'data:image/png;base64,' . base64_encode(file_get_contents($file_path)) : null;
        }

        $sqd_answers = [];
        foreach ($raw_answers as $answer) {
            if (isset($answer['code']) && preg_match('/SQD(\d+)/', $answer['code'], $matches)) {
                $question_index = (int)$matches[1] + 1;
                $sqd_answers["question_$question_index"] = $answer['answer'] ?? '';
            }
        }

        $data = [
            'feedback'    => $feedback,
            'sqd_answers' => $sqd_answers,
            'request_id'  => $request_id,
            'document_id' => $document_id,
            'logo_base64' => $logo_base64,
            'sqd_images'  => $sqd_images
        ];

        $dompdf = new \Dompdf\Dompdf();
        $html = view('feedback/feedback_pdf', $data);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $filename = 'feedback_' . $request_id . '_' . $document_id . '.pdf';

        return $this->response
            ->setHeader('Content-Type', 'application/pdf')
            ->setHeader('Content-Disposition', 'inline; filename="' . $filename . '"')
            ->setBody($dompdf->output());
    }

    public function download_feedback_pdf($request_id, $document_id)
    {
        require_once APPPATH . '../vendor/dompdf/dompdf/src/Autoloader.php';
        \Dompdf\Autoloader::register();

        $dompdf = new \Dompdf\Dompdf();
        $html = view('feedback/feedback_pdf', $this->view_feedback_pdf($request_id, $document_id));
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $filename = 'feedback_' . $request_id . '_' . $document_id . '.pdf';
        $dompdf->stream($filename, ['Attachment' => 1]);
    }
}
