<?php

namespace App\Models\Service;

use CodeIgniter\Model;
use Config\Database;

class Mdl_files extends Model
{
    protected $DBGroup = 'service';

    public function view_file($filename)
    {
        $filename = basename($filename);

        $possible_paths = [
            FCPATH . '../hrmis-template/downloadables/' . $filename,       
            FCPATH . '../hrmis-template/downloads/' . $filename,          
            FCPATH . '../hrmis-template/public/downloadables/' . $filename, 
            FCPATH . 'public/downloadables/' . $filename                 
        ];
        
        $filepath = null;
        foreach ($possible_paths as $path) {
            if (file_exists($path)) {
                $filepath = $path;
                break;
            }
        }
        
        if ($filepath === null) {
            $error_info = [
                'filename' => $filename,
                'checked_paths' => $possible_paths,
                'file_found' => false
            ];
            return $error_info;
        }

        return [
            'filepath' => $filepath,
            'filename' => $filename,
            'content_type' => mime_content_type($filepath),
            'content' => file_get_contents($filepath),
            'file_found' => true
        ];
    }
}