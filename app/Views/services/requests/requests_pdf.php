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

$logoPath = ROOTPATH . 'public/images/header3.png';
$logo_base64 = getImageDataURI($logoPath);

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Request Slip</title>
<style>
   @page {
    size: A4;
    margin: 0.5in 1in 1in 1in; 
}


body {
    font-family: Arial, Helvetica, sans-serif;
    font-size: 12px;
    line-height: 1.2;
    margin: 0;
}


    .center {
        text-align: center;
        font-weight: bold;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    td {
        padding: 4px 6px;
        vertical-align: middle;
    }

    .section-title {
        font-weight: bold;
        margin: 6px 0 3px 0;
        text-decoration: underline;
        font-size: 10px;
    }

    .checkbox {
        font-family: DejaVu Sans, Arial, sans-serif;
        white-space: nowrap;
        margin: 1px 0;
        font-size: 9px;
        line-height: 1.3;
    }

    .follow-up {
        margin-top: 8px;
        border-top: 1px dashed #000;
        padding-top: 4px;
        font-size: 9px;
    }


    
    .purpose-details {
        font-style: italic;
        color: #666;
        margin: 2px 0 2px 20px;
        font-size: 11px;
    }

    .form-field-new {
        font-size: 9px;
        margin: 2px 0;
    }

    .form-label-new {
        font-weight: bold;
        margin-right: 4px;
    }

    .form-line {
        display: inline-block;
        border-bottom: 1px solid #000;
        padding-bottom: 1px;
        min-width: 200px;
    }

    .left-align {
        text-align: left;
        padding-left: 3px;
    }

    .form-line-empty {
        display: inline-block;
        border-bottom: 1px solid #000;
        height: 12px;
    }

    .name-line { min-width: 200px; }
    .signature-line { min-width: 180px; }
    .unit-line { min-width: 190px; }
    .contact-line { min-width: 150px; }
    .date-line { min-width: 180px; }
    .submitted-line { min-width: 170px; }
    .other-doc-line { 
        display: inline-block;
        min-width: 180px;
        border-bottom: 1px solid #000;
        text-align: left;
        padding-left: 3px;
        padding-bottom: 1px;
    }
    .travel-place-line,
    .travel-date-line {
        display: inline-block;
        min-width: 180px;
        border-bottom: 1px solid #000;
        text-align: left;
        padding-left: 3px;
        padding-bottom: 1px;
    }
    .document-line { min-width: 150px; }

    .form-field-new {
    display: flex;
    align-items: flex-end;
    gap: 4px; 
}
.name-line {
    width: 220px;   
}

.signature-line {
    width: 200px;   
}

.unit-line {
    width: 170px;   
}

.contact-line {
    width: 175px;  
}

.date-line {
    width: 135px;  
}

.submitted-line {
    width: 187px;   
}

</style>

</head>
<body>
<div class="pdf-content">

<div class="header" style="text-align: center; margin-bottom: 8px;">
    <?php if (!empty($logo_base64)): ?>
        <img 
            src="<?= $logo_base64 ?>" 
            alt="PDF Header Logo"
            style="max-width: 100%; height: auto; display: inline-block; margin: 0 auto;">
    <?php else: ?>
        <div style="height:120px; width:100%; border:1px dashed #ccc;"></div>
    <?php endif; ?>
</div>

<div class="center" style="font-size: 12px; margin-bottom: 5px;">HUMAN RESOURCE MANAGEMENT OFFICE</div>
<div class="center" style="font-size: 12px; margin-bottom: 5px;">REQUEST SLIP FORM</div>

<table style="margin-top: 0; border-collapse: collapse;">
    <tr>
        <td>
            <div class="form-field-new" style="margin-bottom: 0; line-height: 1.2;">
                <span class="form-label-new">Name:</span>
                <span class="form-line name-line">
                    <?= isset($request) ? htmlspecialchars($request['emp_name'] ?? '') : '' ?>
                </span>
            </div>
        </td>

        <td>
            <div class="form-field-new" style="margin-bottom: 0; line-height: 1.2;">
                <span class="form-label-new">Signature:</span>
                <span class="form-line signature-line"></span>
            </div>
        </td>
    </tr>

    <tr>
        <td>
            <div class="form-field-new" style="margin-bottom: 0; line-height: 1.2;">
                <span class="form-label-new">Unit/College:</span>
                <span class="form-line unit-line">
                    <?= isset($request) ? htmlspecialchars($request['unit_college'] ?? '') : '' ?>
                </span>
            </div>
        </td>

       <td>
            <div class="form-field-new" style="margin-bottom: 0; line-height: 1.2;">
                <span class="form-label-new">Contact Number:</span>
                <span class="form-line contact-line">
                    <?= isset($request) ? htmlspecialchars($request['contact_num'] ?? '') : '' ?>
                </span>
            </div>
        </td>
    </tr>

    <tr>
        <td>
            <div class="form-field-new" style="margin-bottom: 0; line-height: 1.2;">
                <span class="form-label-new">Date Requested:</span>
                <span class="form-line date-line">
                    <?= isset($request) ? date('F j, Y', strtotime($request['date_requested'] ?? '')) : '' ?>
                </span>
            </div>
        </td>

        <td>
            <div class="form-field-new" style="margin-bottom: 0; line-height: 1.2;">
                <span class="form-label-new">Submitted by:</span>
                <span class="form-line submitted-line">
                    <?= isset($request) ? htmlspecialchars($request['emp_name'] ?? '') : '' ?>
                </span>
            </div>
        </td>
    </tr>
</table>


<div class="section-title">DOCUMENT REQUESTED (pls. check requested document)</div>

<table style="border-collapse: collapse;">
<?php
$currentDocName = isset($document['document_name']) ? $document['document_name'] : '';
$otherDocuments = isset($document['other_documents']) ? $document['other_documents'] : '';

if (!empty($all_documents)):
    foreach ($all_documents as $doc):
        if (strtolower(trim($doc['document_name'])) === 'others' && !empty($otherDocuments)) {
            continue;
        }
        
        $isChecked = in_array(strtolower(trim($doc['document_name'])), $selected_doc_names ?? []);

        $displayName = $doc['document_name'];
?>
    <tr> 
        <td class="checkbox" style="padding: 2px 0; line-height: 1.1;">
            <?php echo $isChecked ? '<span class="checkbox">☑</span>' : '☐'; ?> 
            <?php echo htmlspecialchars($displayName); ?>
        </td>
    </tr>
<?php 
    endforeach;
endif;
?>
    <tr>
       <td class="checkbox" style="padding: 2px 0; line-height: 1.1;">
            <div class="form-field-new" style="margin: 0; gap: 4px;">
                <span class="form-label-new" style="margin: 0;">
                    <?php echo !empty($otherDocuments) ? '<span class="checkbox">☑</span>' : '☐'; ?> Others (specify):
                </span>
                <span class="form-line other-doc-line" style="margin: 0;">
                    <?php echo htmlspecialchars($otherDocuments); ?>
                </span>
            </div>
        </td>
    </tr>
</table>


<div class="section-title">PURPOSE (pls. check purpose of request)</div>

<table style="border-collapse: collapse;">
<?php 
$document_purposes = $document_purposes ?? [];
$all_ungrouped_purposes = $all_purposes['ungrouped'] ?? [];
$all_grouped_purposes = $all_purposes['grouped'] ?? [];
$purpose_groups = $purpose_groups ?? [];

$properly_grouped_purposes = [];
foreach ($purpose_groups as $group) {
    $group_id = $group['id'] ?? null;
    if ($group_id && isset($all_grouped_purposes[$group_id])) {
        $properly_grouped_purposes[] = [
            'group_name' => $group['purpose_group_name'] ?? '',
            'purposes' => $all_grouped_purposes[$group_id]
        ];
    }
}

function has_purpose_by_group($purposes, $group_name) {
    if (!is_array($purposes)) return false;
    foreach ($purposes as $purpose) {
        if (isset($purpose['purpose_group_name']) && strtolower(trim($purpose['purpose_group_name'])) === strtolower(trim($group_name))) {
            return true;
        }
    }
    return false;
}

function has_purpose_by_name($purposes, $purpose_name, $group_name = null) {
    if (!is_array($purposes)) return false;
    foreach ($purposes as $purpose) {
        $matches_name = isset($purpose['purpose_name']) && strtolower(trim($purpose['purpose_name'])) === strtolower(trim($purpose_name));
        $matches_group = is_null($group_name) || (isset($purpose['purpose_group_name']) && strtolower(trim($purpose['purpose_group_name'])) === strtolower(trim($group_name)));
        if ($matches_name && $matches_group) return true;
    }
    return false;
}

function extract_travel_details($purpose_details) {
    $travel_info = ['place' => '', 'date' => ''];
    if (preg_match('/Place:\s*(.*?)(?:,\s*Date:|$)/i', $purpose_details, $place_matches)) {
        $travel_info['place'] = trim($place_matches[1]);
    }
    if (preg_match('/Date:\s*(.*?)(?:,\s*Place:|$)/i', $purpose_details, $date_matches)) {
        $travel_info['date'] = trim($date_matches[1]);
    }
    return $travel_info;
}

foreach ($properly_grouped_purposes as $group):
    if (strtolower(trim($group['group_name'])) === 'travel abroad') continue; 
    $group_checked = has_purpose_by_group($document_purposes, $group['group_name']);
?>
<tr style="line-height: 1.2;">
    <td class="checkbox" style="padding: 2px 0;">
        <?php echo $group_checked ? '<span class="checkbox">☑</span>' : '☐'; ?>
        <strong><?php echo htmlspecialchars($group['group_name']); ?>:</strong>
        <?php foreach ($group['purposes'] as $purpose):
            $is_checked = has_purpose_by_name($document_purposes, $purpose['purpose_name'], $group['group_name']);
        ?>
            &nbsp;&nbsp;<?php echo $is_checked ? '<span class="checkbox">☑</span>' : '☐'; ?>
            <?php echo htmlspecialchars($purpose['purpose_name']); ?>
        <?php endforeach; ?>
    </td>
</tr>
<?php endforeach; ?>

<?php
$travel_details = ['place' => '', 'date' => ''];
$has_travel_abroad_selected = false;

foreach ($properly_grouped_purposes as $group) {
    if (strtolower(trim($group['group_name'])) !== 'travel abroad') continue;
    foreach ($group['purposes'] as $purpose) {
        if (has_purpose_by_name($document_purposes, $purpose['purpose_name'], $group['group_name'])) {
            $has_travel_abroad_selected = true;
            foreach ($document_purposes as $doc_purpose) {
                if (isset($doc_purpose['purpose_name'], $doc_purpose['purpose_group_name'])
                    && strtolower(trim($doc_purpose['purpose_group_name'])) === 'travel abroad'
                    && strtolower(trim($doc_purpose['purpose_name'])) === strtolower(trim($purpose['purpose_name']))
                ) {
                    $travel_details = extract_travel_details($doc_purpose['purpose_details'] ?? '');
                    break 2;
                }
            }
        }
    }
}
?>
<tr style="line-height: 1.2;">
    <td class="checkbox" style="padding: 2px 0;">
        <?php echo $has_travel_abroad_selected ? '<span class="checkbox">☑</span>' : '☐'; ?>
        Travel Abroad<br>
        &nbsp;&nbsp;&nbsp;&nbsp;(specify) Place of Travel: 
        <span style="display: inline-block; width: 300px; border-bottom: 1px solid #000;">
            <?php echo htmlspecialchars($travel_details['place'] ?? ''); ?>
        </span><br>
        &nbsp;&nbsp;&nbsp;&nbsp;Date of Travel: 
        <span style="display: inline-block; width: 250px; border-bottom: 1px solid #000;">
            <?php echo htmlspecialchars($travel_details['date'] ?? ''); ?>
        </span>
    </td>
</tr>

<?php
foreach ($all_ungrouped_purposes as $purpose):
    $is_checked = has_purpose_by_name($document_purposes, $purpose['purpose_name']);

    $requires_details = isset($purpose['with_details']) && $purpose['with_details'] == 1;

    $purpose_details = '';
    if ($is_checked && $requires_details) {
        foreach ($document_purposes as $doc_purpose) {
            if (isset($doc_purpose['purpose_name']) &&
                strtolower(trim($doc_purpose['purpose_name'])) === strtolower(trim($purpose['purpose_name']))) {
                $purpose_details = htmlspecialchars($doc_purpose['purpose_details'] ?? '');
                break;
            }
        }
    }
?>
<tr style="line-height: 1.2;">
    <td class="checkbox" style="padding: 2px 0;">
        <?= $is_checked ? '<span class="checkbox">☑</span>' : '☐'; ?>
        <?= htmlspecialchars($purpose['purpose_name']); ?>
        <?php if ($requires_details): ?>
            (specify): 
            <span style="display: inline-block; border-bottom: 1px solid #000; width: 300px;">
                <?= $purpose_details ?>
            </span>
        <?php endif; ?>
    </td>
</tr>
<?php endforeach; ?>

<tr style="line-height: 1.2;">
    <td style="padding: 2px 0;">
        <div style="border: 1px solid #000; width: 100%; min-height: 50px; padding: 5px;"></div>
    </td>
</tr>
</table>

<div class="follow-up" style="text-align: right;">
    <strong>FOLLOW-UP SLIP &nbsp;&nbsp;&nbsp;&nbsp; COPY FOR EMPLOYEE</strong><br>
</div>


<table style="margin-top: 0; border-collapse: collapse;">
    <tr>
        <td>
            <div class="form-field-new" style="margin-bottom: 0; line-height: 1.2;">
                <span class="form-label-new">Name:</span>
                <span class="form-line name-line">
                    <?= isset($request) ? htmlspecialchars($request['emp_name'] ?? '') : '' ?>
                </span>
            </div>
        </td>
    </tr>

    <tr>
        <td>
            <div class="form-field-new" style="margin-bottom: 0; line-height: 1.2;">
                <span class="form-label-new">Unit/College:</span>
                <span class="form-line unit-line">
                    <?= isset($request) ? htmlspecialchars($request['unit_college'] ?? '') : '' ?>
                </span>
            </div>
        </td>
    </tr>

    <tr>
        <td>
            <div class="form-field-new" style="margin-bottom: 0; line-height: 1.2;">
                <span class="form-label-new">Date Requested:</span>
                <span class="form-line date-line">
                    <?= isset($request) ? date('F j, Y', strtotime($request['date_requested'] ?? '')) : '' ?>
                </span>
            </div>
        </td>
    </tr>
<tr>
    <td colspan="2">
        <div class="form-field-new" style="margin-bottom: 0; line-height: 1.2;">
            <span class="form-label-new">Document Requested:</span>
            <span style="display: inline-block; border-bottom: 1px solid #000; min-width: 300px; padding-left: 2px;">
                <?php 
                $requested_docs = [];
                
                if (!empty($selected_documents)) {
                    foreach ($selected_documents as $sel_doc) {
                        if (strtolower(trim($sel_doc['document_name'])) === 'others' && !empty($sel_doc['other_documents'])) {
                            $requested_docs[] = htmlspecialchars($sel_doc['other_documents']);
                        } else {
                            $requested_docs[] = htmlspecialchars($sel_doc['document_name']);
                        }
                    }
                }
                
                echo implode(', ', $requested_docs);
                ?>
            </span>
        </div>
    </td>
</tr>


</table>

<table style="margin-top: 2px;">
    <tr>
        <td>
            <div style="border: 1px solid #000; width: 100%; min-height: 50px; padding: 5px;"></div>
        </td>
    </tr>
</table>

<p style="font-size: 7px; font-style: italic;">
    ADM.ADS.HRM.F.001 (Revision No. 0; January 6, 2016)
</p>


</div>

</body>
</html>