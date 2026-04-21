<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Submit Feedback') ?> - TMS</title>
    
    <!-- Bootstrap CSS (CDN) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome (CDN) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .feedback-container {
            max-width: 500px;
            margin: 100px auto;
            padding: 20px;
        }
        
        .feedback-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 40px;
            text-align: center;
        }
        
        .training-info {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 30px;
            border-left: 4px solid #0d6efd;
            text-align: left;
        }
        
        .icon-circle {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
        }
        
        .icon-circle i {
            font-size: 40px;
            color: white;
        }
    </style>
</head>
<body>
    <div class="feedback-container">
        <div class="feedback-card">
            <div class="icon-circle">
                <i class="fas fa-comment-dots"></i>
            </div>
            
            <h3 class="mb-3">Submit Feedback</h3>
            
            <?php if(session()->getFlashdata('errors')): ?>
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        <?php foreach(session()->getFlashdata('errors') as $error): ?>
                            <li><?= esc($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
            
            <!-- Training Information -->
            <div class="training-info">
                <h6 class="mb-2 fw-bold"><?= esc($training['training_name']) ?></h6>
                <p class="mb-1 text-muted small">
                    <i class="fas fa-calendar"></i> <?= date('M d, Y', strtotime($training['training_datefrom'])) ?> 
                    <?php if($training['training_dateto'] != $training['training_datefrom']): ?>
                        - <?= date('M d, Y', strtotime($training['training_dateto'])) ?>
                    <?php endif; ?>
                </p>
            </div>
            
            <p class="text-muted mb-4">
                Click the button below to submit your feedback for this training.
            </p>
            
            <!-- Simple Submit Form -->
            <form action="<?= site_url('feedback/process') ?>" method="post">
                <?= csrf_field() ?>
                
                <input type="hidden" name="training_id" value="<?= esc($training_id) ?>">
                <input type="hidden" name="rating" value="5">
                
                <button type="submit" class="btn btn-primary btn-lg w-100 mb-3">
                    <i class="fas fa-check-circle"></i> Submit Feedback
                </button>
                
                <a href="<?= site_url('mytrainings') ?>" class="btn btn-outline-secondary w-100">
                    <i class="fas fa-times"></i> Cancel
                </a>
            </form>
        </div>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="<?= base_url('assets/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
</body>
</html>
