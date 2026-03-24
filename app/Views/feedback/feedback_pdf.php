<?php
function getImageDataURI(string $imagePath): string {
    if (file_exists($imagePath) && is_readable($imagePath)) {
        $imageData = file_get_contents($imagePath);
        $base64 = base64_encode($imageData);
        $mimeType = mime_content_type($imagePath);
        return "data:$mimeType;base64,$base64";
    }
    return '';
}

$logoPath = ROOTPATH . 'public/images/pdf_header.png';
$logo_base64 = getImageDataURI($logoPath);

$sqd_images = [
    1 => getImageDataURI(ROOTPATH . 'public/images/strongly_disagree.png'),
    2 => getImageDataURI(ROOTPATH . 'public/images/disagree.png'),
    3 => getImageDataURI(ROOTPATH . 'public/images/neither_agree_nor_disagree.png'),
    4 => getImageDataURI(ROOTPATH . 'public/images/agree.png'),
    5 => getImageDataURI(ROOTPATH . 'public/images/strongly_agree.png'),
    6 => getImageDataURI(ROOTPATH . 'public/images/not_applicable.png'),
];
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback Form</title>
    <style>
        @page {
            size: A4;
            margin: 0 0.3in 0.3in 0.3in; 
        }
        
        @media print {
            body {
                -webkit-print-color-adjust: exact;
                color-adjust: exact;
            }
        }
       
body {
    font-family: DejaVu Sans, Times, "Times New Roman", serif;
    font-size: 11px;
    margin: 0;   
    padding: 0;
    line-height: 1.4;
}

        .header {
    width: calc(100% + 0.6in);   
    margin-left: -0.3in;
    margin-right: -0.3in;
    margin-bottom: 12px;
    text-align: center;
}


        .header img {
            width: 70px;
            margin-bottom: 5px;
        }

        .header .country {
            font-size: 13px;
        }

        .header .school {
            font-weight: bold;
            font-size: 14px;
            text-transform: uppercase;
        }

        .header .address {
            font-size: 12px;
        }

        hr {
            border: 1px solid #000;
            margin: 4px 0;
        }

        .title {
            text-align: center;
            font-weight: bold;
            font-size: 13px;
            margin: 5px 0;
        }

        .subtitle {
            text-align: center;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .text {
            text-align: justify;
            margin-bottom: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 4px;
            table-layout: auto;
        }
        
        .form-row {
            display: flex;
            align-items: baseline;
            gap: 8px;
        }
        
        .form-label {
            white-space: nowrap;
        }
        
        .form-input {
            flex-grow: 1;
        }

        td, th {
            padding: 2px 4px;
            vertical-align: middle;
        }

        .line {
            border-bottom: 1px solid #000;
            height: 16px;
            display: inline-block;
            min-width: 80px;
            max-width: 100%;
            vertical-align: middle;
            margin-top: 2px;
            position: relative;
        }

        .checkbox {
            width: 14px;
            height: 14px;
            border: 1px solid #000;
            display: inline-block;
            margin-right: 8px;
            position: relative;
            vertical-align: middle;
            margin-top: 2px;
            text-align: center;
            line-height: 14px;
        }

        .checkbox.checked::after {
            content: "✔";
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 10px;
            font-weight: normal;
            font-family: DejaVu Sans, sans-serif;
        }

        .sqd-table th,
        .sqd-table td {
            border: 1px solid #000;
            text-align: center;
            font-size: 10px;
        }

        .sqd-table td.desc {
            text-align: left;
            width: 55%;
        }

        .footer {
            font-size: 9px;
            margin-top: 15px;
            text-align: center;
        }
        
        .signature-line {
            border-bottom: 1px solid #000;
            height: 18px;
            width: 160px;
            display: inline-block;
            margin: 0 8px;
        }
        
        .text-center {
            text-align: center;
        }
        
        .text-right {
            text-align: right;
        }
        
        .mt-20 {
            margin-top: 15px;
        }
        
        .mb-10 {
            margin-bottom: 8px;
        }

        .option-group {
    display: inline-block;
    margin-right: 14px;
    vertical-align: middle;
}
.sqd-table td.selected {
    background-color: #000;
    color: #fff;
    font-weight: bold;
}
    </style>
</head>

<body>
<div class="header">
    <?php if (!empty($logo_base64)): ?>
        <img 
            src="<?= $logo_base64 ?>" 
            alt="PDF Header Logo"
            style="
                width:100%;
                height:140px;
                object-fit:cover;
                display:block;
            ">
    <?php else: ?>
        <div style="height:120px; width:100%; border:1px dashed #ccc;"></div>
    <?php endif; ?>
</div>

<div style="text-align: right; margin-bottom: 2px;">
    No.: <span class="line" style="width: 140px;"></span>
</div>

<div class="title">FEEDBACK FORM</div>
<div class="subtitle">HELP US SERVE YOU BETTER!</div>

<div class="text">
    This Client Satisfaction Measurement (CSM) tracks the customer experience of government offices.
    Your feedback on your recently concluded transaction will help this office provide a better service.
    Personal information shared will be kept confidential and you always have the option to not answer this form.
</div>

<table>
    <tr>
        <td width="25%">Client type:</td>
        <td>
           <span style="margin-right:100px;">
    <span class="checkbox <?= (isset($feedback['client_type']) && $feedback['client_type'] == 'Citizen') ? 'checked' : '' ?>"></span> Citizen
</span>

<span style="margin-right:100px;">
    <span class="checkbox <?= (isset($feedback['client_type']) && $feedback['client_type'] == 'Business') ? 'checked' : '' ?>"></span> Business
</span>

<span>
    <span class="checkbox <?= (isset($feedback['client_type']) && $feedback['client_type'] == 'Government') ? 'checked' : '' ?>"></span> Government
</span>

        </td>
    </tr>
    <tr>
        <td>Client Classification:</td>
        <td>
<span style="margin-right:94px;">
    <span class="checkbox <?= (isset($feedback['client_classification']) && $feedback['client_classification'] == 'Student') ? 'checked' : '' ?>"></span>
    Student
</span>

<span style="margin-right:60px;">
    <span class="checkbox <?= (isset($feedback['client_classification']) && $feedback['client_classification'] == 'Faculty Member') ? 'checked' : '' ?>"></span>
    Faculty Member
</span>

<span>
    <span class="checkbox <?= (isset($feedback['client_classification']) && $feedback['client_classification'] == 'Non-Academic Staff') ? 'checked' : '' ?>"></span>
    Non-Academic Staff
</span>

        </td>
    </tr>
    <tr>
        <td>Type of Transaction:</td>
        <td>
            <span style="margin-right:94px;">
    <span class="checkbox <?= (isset($feedback['transaction_type']) && $feedback['transaction_type'] == 'Internal') ? 'checked' : '' ?>"></span>
    Internal
</span>

<span>
    <span class="checkbox <?= (isset($feedback['transaction_type']) && $feedback['transaction_type'] == 'External') ? 'checked' : '' ?>"></span>
    External
</span>

        </td>
    </tr>
</table>

<table style="margin-top:4px;">
    <tr>
        <td width="7%" style="white-space:nowrap;">Date:</td>
        <td width="33%">
            <span class="line" style="width:100%;">
                <?= isset($feedback['date']) ? htmlspecialchars($feedback['date'], ENT_QUOTES, 'UTF-8') : '' ?>
            </span>
        </td>

        <td width="6%" style="white-space:nowrap;">Sex:</td>
        <td width="29%">
            <span class="option-group">
                <span class="checkbox <?= (isset($feedback['sex']) && $feedback['sex'] == 'Male') ? 'checked' : '' ?>"></span>Male
            </span>
            <span class="option-group">
                <span class="checkbox <?= (isset($feedback['sex']) && $feedback['sex'] == 'Female') ? 'checked' : '' ?>"></span>Female
            </span>
        </td>

        <td width="5%" style="text-align:right; white-space:nowrap;">Age:</td>
        <td width="20%">
            <span class="line" style="width:80%;">
                <?= isset($feedback['age']) ? htmlspecialchars($feedback['age'], ENT_QUOTES, 'UTF-8') : '' ?>
            </span>
        </td>
    </tr>
</table>

<table>
    <tr>
        <td width="18%" style="white-space:nowrap;">Region of Residence:</td>
        <td width="32%">
            <span class="line" style="width:100%;">
                <?= isset($feedback['region_residence']) ? htmlspecialchars($feedback['region_residence'], ENT_QUOTES, 'UTF-8') : '' ?>
            </span>
        </td>

        <td width="15%" style="white-space:nowrap;">Service Availed:</td>
        <td width="35%">
            <span class="line" style="width:100%;">
                <?= isset($feedback['service_availed']) ? htmlspecialchars($feedback['service_availed'], ENT_QUOTES, 'UTF-8') : '' ?>
            </span>
        </td>
    </tr>

    <tr>
        <td style="white-space:nowrap;">Name of Office:</td>
        <td colspan="3">
            <span class="line" style="width:100%;">
                <?= isset($feedback['office_name']) ? htmlspecialchars($feedback['office_name'], ENT_QUOTES, 'UTF-8') : '' ?>
            </span>
        </td>
    </tr>

    <tr>
        <td style="white-space:nowrap;">Name of Service Provider:</td>
        <td>
            <span class="line" style="width:100%;">
                <?= isset($feedback['service_provider']) ? htmlspecialchars($feedback['service_provider'], ENT_QUOTES, 'UTF-8') : '' ?>
            </span>
        </td>

        <td style="white-space:nowrap;">Position of Service Provider:</td>
        <td>
            <span class="line" style="width:100%;">
                <?= isset($feedback['provider_position']) ? htmlspecialchars($feedback['provider_position'], ENT_QUOTES, 'UTF-8') : '' ?>
            </span>
        </td>
    </tr>
</table>

<div><strong>INSTRUCTIONS:</strong></div>
<div>For SQD 0–8, please put a check mark (✓) on the column that best corresponds to your answer.</div>

<table class="sqd-table">
    <tr>
        <th></th>
        <?php for ($i = 1; $i <= 6; $i++): ?>
            <th>
                <?php if (!empty($sqd_images[$i])): ?>
                    <img src="<?= $sqd_images[$i] ?>" style="height:45px;">
                <?php endif; ?>
            </th>
        <?php endfor; ?>
    </tr>

    <?php
    $questions = [
        "SQD0. I am satisfied with the service that I availed.",
        "SQD1. I spent a reasonable amount of time for my transaction.",
        "SQD2. The office followed the transaction's requirements.",
        "SQD3. The steps (including payment) were easy and simple.",
        "SQD4. I easily found information about my transaction.",
        "SQD5. I paid a reasonable amount of fees.",
        "SQD6. The office was fair to everyone.",
        "SQD7. I was treated courteously by the staff.",
        "SQD8. I got what I needed from the office."
    ];

    $sqd_answers = $sqd_answers ?? [];

    foreach ($questions as $index => $question):
        $q_index = $index + 1;
        $answer_value = $sqd_answers["question_$q_index"] ?? '';
    ?>
    <tr>
        <td class="desc"><?= htmlspecialchars($question, ENT_QUOTES, 'UTF-8') ?></td>
        <?php for ($i = 1; $i <= 6; $i++): ?>
            <td>
                <?= ($answer_value == $i) ? '&#10004;' : '' ?>
            </td>
        <?php endfor; ?>
    </tr>
    
    <?php endforeach; ?>
<tr>
    <td class="desc" style="font-weight:bold;">
        Overall, how would you rate your entire educational experience at CLSU? (1 as the lowest and 5 as the highest)
    </td>

    <?php
    $overall_rating = $feedback['overall_rating'] ?? '';

    for ($i = 1; $i <= 6; $i++):
        $isSelected = ($overall_rating == $i);
    ?>
        <td style="text-align:center; vertical-align:middle;">
            <?php if ($isSelected): ?>
                <div style="
                    width:22px;
                    height:22px;
                    background-color:#000;
                    color:#fff;
                    border-radius:50%;
                    display:flex;
                    align-items:center;
                    justify-content:center;
                    margin:0 auto;
                    font-weight:bold;
                    font-size:11px;
                ">
                    <?= $i ?>
                </div>
            <?php else: ?>
                <?= $i ?>
            <?php endif; ?>
        </td>
    <?php endfor; ?>
</tr>


</table>

<div>
    Have you experienced any form of harassment during the transaction?
    <span class="checkbox <?= ($feedback['experienced_harassment'] ?? 0) == 1 ? 'checked' : '' ?>"></span> YES
    <span class="checkbox <?= ($feedback['experienced_harassment'] ?? 0) == 0 ? 'checked' : '' ?>"></span> NO
</div>

<div>
    If YES, please specify: <span class="line" style="width: 100%; display: inline-block; border-bottom: 1px solid #000;"><?= htmlspecialchars($feedback['harassment_details'] ?? '', ENT_QUOTES, 'UTF-8') ?></span>
</div>

<br>
<div>
    Overall, I would recommend CLSU to my peers.
    <span class="checkbox <?= ($feedback['recommend_clsu'] ?? 0) == 1 ? 'checked' : '' ?>"></span> YES
    <span class="checkbox <?= ($feedback['recommend_clsu'] ?? 0) == 0 ? 'checked' : '' ?>"></span> NO
</div>

<div>Suggestions on how we can further improve our services (optional): <span class="line" style="width: 100%; display: inline-block; border-bottom: 1px solid #000;"><?= htmlspecialchars($feedback['suggestions'] ?? '', ENT_QUOTES, 'UTF-8') ?></span></div>

<br>
<div>Email address (optional): <?= htmlspecialchars($feedback['email'] ?? '____________________________', ENT_QUOTES, 'UTF-8') ?></div>

<br>

<div style="text-align:center;">
    THANK YOU!
    <p style="font-size: 7px; margin: 0; font-style; italic;">
        **Adopter from ARTA as per MC 2022-05
    </p>
</div>

<hr style="border: 0; border-top: 1px solid #000; margin: 10px 0;">
<div class="footer" style="text-align: left; font-style: italic;">
    OUP.XXX.YYY.F.003 (Revision No. 6; January 20, 2026)
</div>

</body>

</html>