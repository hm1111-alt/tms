<?php
// app/Libraries/PdfService.php

namespace App\Libraries;

use Dompdf\Dompdf;

class PdfService
{
    protected $dompdf;

    public function __construct()
    {
        // Instantiate Dompdf
        $this->dompdf = new Dompdf();
        
        // Enable remote resources
        $options = $this->dompdf->getOptions();
        $options->set('isRemoteEnabled', true);
        
    $options->set('defaultFont', 'Arial Narrow'); // Set Arial Narrow
        
        
        // Register custom font
        $fontDir = WRITEPATH . 'fonts'; // CI4 writable directory
        $fontCache = $fontDir . '/dompdf_fonts.php';
    
        $options->set('fontDir', $fontDir);
        $options->set('fontCache', $fontCache);

        $this->dompdf->getFontMetrics()->getFont("Arial Narrow", "normal", $fontDir);
        
//        $this->dompdf->getFontMetrics()->registerFont([
//            "family" => "Arial Narrow",
//            "style" => "normal",
//            "weight" => "normal",
//            "ttf" => FCPATH . "fonts/ArialNarrow.ttf"
//        ]);
    

//        // Add Arial Narrow font files
//        $fontFamily = [
//            'normal' => $fontDir . '/arialnarrow.ttf',
//            'bold' => $fontDir . '/arialnarrow_bold.ttf',
//            'italic' => $fontDir . '/arialnarrow_italic.ttf',
//            'bold_italic' => $fontDir . '/arialnarrow_bolditalic.ttf',
//        ];
//        $this->dompdf->getFontMetrics()->registerFontFamily('Arial Narrow', $fontFamily);
        
        
        $this->dompdf->setOptions($options);
    }

    public function loadHtml($html)
    {
        // Load the HTML content
        $this->dompdf->loadHtml($html);
    }

    public function pdf_create_a4($filename = 'document.pdf', $stream = true)
    {
        // Set paper size (e.g., 'A4', 'letter') and orientation (e.g., 'portrait', 'landscape')
        $this->dompdf->setPaper('A4', 'portrait');

        // Render the PDF
        $this->dompdf->render();

        
        
        // Output the PDF
        if ($stream) {
            // Stream the PDF directly to the browser
            //$this->dompdf->stream($filename, ["Attachment" => 0]);
            
            // Display in browser
            header('Content-Type: application/pdf');
            header('Content-Disposition: inline; filename="' . $filename . '"');
            header('Cache-Control: private, max-age=0, must-revalidate');
            header('Pragma: public');

            echo $this->dompdf->output();
            exit;
            
        } else {
            // Return the generated PDF file
            return $this->dompdf->stream($filename, ["Attachment" => 1]); // Force download
        }
    }

    public function pdf_create($filename = 'document.pdf', $stream = true, $size='A4', $orientation='portrait')
    {
        //$dompdf->set_paper(array(0, 0, 612, 936), $orientation); //8.5 x 13
        // Set paper size (e.g., 'A4', 'letter') and orientation (e.g., 'portrait', 'landscape')
        $this->dompdf->setPaper($size, $orientation);

        // Render the PDF
        $this->dompdf->render();

        // Output the PDF
        if ($stream) {
            // Stream the PDF directly to the browser
            //$this->dompdf->stream($filename, ["Attachment" => 0]);
            //
            // Display in browser
            header('Content-Type: application/pdf');
            header('Content-Disposition: inline; filename="' . $filename . '"');
            header('Cache-Control: private, max-age=0, must-revalidate');
            header('Pragma: public');

            echo $this->dompdf->output();
            exit;
        } else {
            // Return the generated PDF file
            return $this->dompdf->stream($filename, ["Attachment" => 1]); // Force download
        }
    }
}
