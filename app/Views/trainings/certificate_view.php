<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Certificate') ?></title>
    
    <!-- Bootstrap CSS (CDN) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome (CDN) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body {
            background-color: #f0f2f5;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 20px;
            margin: 0;
        }
        
        .certificate-wrapper {
            max-width: 1123px; /* A4 width in pixels at 96 DPI */
            margin: 0 auto;
            position: relative;
        }
        
        #certificate-content {
            position: relative;
            width: 100%;
            height: 794px; /* A4 height in pixels at 96 DPI */
            background: white;
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
            overflow: visible !important;
        }
        
        /* Force text to be visible */
        #certificate-content div[style*="z-index: 100"] {
            visibility: visible !important;
            opacity: 1 !important;
            display: block !important;
        }
        
        .action-buttons {
            margin-top: 20px;
            text-align: center;
        }
        
        .action-buttons .btn {
            margin: 5px;
        }
        
        @media print {
            body {
                background: white;
                padding: 0;
                margin: 0;
            }
            
            .action-buttons {
                display: none;
            }
            
            .certificate-wrapper {
                box-shadow: none;
            }
            
            #certificate-content {
                box-shadow: none;
            }
            
            /* Remove browser headers and footers */
            @page {
                margin: 0;
                size: auto;
            }
        }
    </style>
</head>
<body>
    <div class="certificate-wrapper">
        <!-- Certificate Content -->
        <div id="certificate-content" style="position: relative;">
            <?= $cert_content ?>
        </div>
        
        <!-- Action Buttons -->
        <div class="action-buttons">
            <button onclick="downloadCertificate()" class="btn btn-success btn-lg">
                <i class="fas fa-download"></i> Download Certificate
            </button>
            <a href="<?= site_url('mytrainings') ?>" class="btn btn-secondary btn-lg">
                <i class="fas fa-arrow-left"></i> Back to My Trainings
            </a>
        </div>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- html2canvas for downloading -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <!-- jsPDF for PDF generation -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    
    <script>
        async function downloadCertificate() {
            const { jsPDF } = window.jspdf;
            const certificateContent = document.getElementById('certificate-content');
            
            // Show loading state
            const downloadBtn = event.target.closest('button');
            const originalText = downloadBtn.innerHTML;
            downloadBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Generating...';
            downloadBtn.disabled = true;
            
            try {
                // Capture the certificate as canvas
                const canvas = await html2canvas(certificateContent, {
                    scale: 2,
                    useCORS: true,
                    allowTaint: true,
                    backgroundColor: '#ffffff'
                });
                
                // Convert canvas to image
                const imgData = canvas.toDataURL('image/png');
                
                // Create PDF
                const pdf = new jsPDF({
                    orientation: 'landscape',
                    unit: 'px',
                    format: [canvas.width, canvas.height]
                });
                
                // Add image to PDF
                pdf.addImage(imgData, 'PNG', 0, 0, canvas.width, canvas.height);
                
                // Download the PDF
                const trainingTitle = '<?= esc($title ?? "Certificate") ?>';
                pdf.save(`Certificate_${trainingTitle.replace(/\s+/g, '_')}.pdf`);
                
            } catch (error) {
                console.error('Error generating certificate:', error);
                alert('Error generating certificate. Please try again.');
            } finally {
                // Restore button state
                downloadBtn.innerHTML = originalText;
                downloadBtn.disabled = false;
            }
        }
    </script>
</body>
</html>
