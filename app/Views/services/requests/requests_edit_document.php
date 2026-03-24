<?= $this->extend('/layout/main') ?>

<?= $this->section('header_actions') ?>
<div class="mt-4">
    <ul class="page_title_button" style="list-style: none; float:right;">
        <li>
            <a href="<?= site_url('services/requests/view/' . $request['id_request'] . '/' . $document['id']) ?>" class="btn btn-light" role="button">
                <i class="fas fa-arrow-circle-left"></i>
                <div style="color:#000">Back to Request</div>
            </a>
        </li>
    </ul>
</div>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<style>
.doc-table th, .doc-table td { vertical-align: middle; }
#editDocumentForm { margin-top: 15px; }
.purpose-sub { margin-left: 20px; font-size: 0.9em; }
.purpose-detail-input { margin-top: 5px; }
#childPurposeContainer { display:none; margin-top:10px; }
#travelInputs { display:none; margin-top:10px; }
#travelInputs input { margin-bottom:5px; }
</style>

<div style="min-height:50vh">
    <form method="post" action="<?= site_url('services/requests/edit_document/' . $request['id_request'] . '/' . $document['id']) ?>" id="editDocumentForm">
        <?= csrf_field() ?>

        <div class="row">
            <!-- Employee Info -->
            <div class="col-lg-3 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h4 class="mb-3">Employee Details</h4>
                        <div class="mb-2"><b>Name:</b> <?= esc($employee['emp_fname'].' '.$employee['emp_lname']) ?></div>
                        <div class="mb-2"><b>Unit / College:</b> <?= esc($unit_name) ?></div>
                        <div class="mb-2">
                            <b>Contact Number:</b> <?= isset($employee['emp_cpno']) ? esc($employee['emp_cpno']) : 'Not provided' ?>
                        </div>
                        <div class="mb-2">
                            <b>Date Requested:</b> <?= date('F j, Y') ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Document Edit Form -->
            <div class="col-lg-9 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h4 class="mb-3">Edit Document</h4>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label>Document <span class="text-danger">*</span></label>
                                <select name="document_id" id="documentSelect" class="form-control" required>
                                    <option value="">Select document...</option>
                                    <?php foreach($documents as $d): ?>
                                        <option value="<?= $d['id'] ?>" <?= $document['document_id'] == $d['id'] ? 'selected' : '' ?>>
                                            <?= esc($d['document_name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <input type="text" id="otherDocumentInput" name="other_documents" class="form-control mt-1" 
                                       placeholder="Specify if Others" 
                                       value="<?= !empty($document['other_documents']) ? esc($document['other_documents']) : '' ?>"
                                       style="<?= (isset($document['document_name']) && strtolower($document['document_name']) === 'others') ? 'display:block;' : 'display:none;' ?>">
                            </div>

                            <div class="col-md-6">
                                <label>Purpose</label>
                                <select id="purposeSelect" class="form-control">
                                    <option value="">Select purpose...</option>
                                    <?php foreach($purpose_groups as $g): ?>
                                        <option value="group-<?= $g['id'] ?>" class="purpose-group" 
                                                data-group-id="<?= $g['id'] ?>" 
                                                data-group-name="<?= esc($g['purpose_group_name']) ?>"
                                                <?= (isset($document_purpose['purpose_group_id']) && $document_purpose['purpose_group_id'] == $g['id']) ? 'selected' : '' ?>>
                                            <?= esc($g['purpose_group_name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                    <?php foreach($purposes as $p): ?>
                                        <option value="<?= $p['id'] ?>" 
                                                data-with-details="<?= $p['with_details'] ?>" 
                                                data-name="<?= strtolower($p['purpose_name']) ?>"
                                                <?= (isset($document_purpose['purpose_id']) && $document_purpose['purpose_id'] == $p['id'] && empty($document_purpose['purpose_group_id'])) ? 'selected' : '' ?>>
                                            <?= esc($p['purpose_name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <input type="hidden" id="selectedPurposeId" name="purpose_id" 
                                       value="<?= (isset($document_purpose['purpose_id']) && empty($document_purpose['purpose_group_id'])) ? $document_purpose['purpose_id'] : '' ?>">
                                <input type="text" id="purposeDetails" name="purpose_details" class="form-control mt-1 purpose-detail-input" 
                                       placeholder="Enter details" 
                                       value="<?= isset($document_purpose['purpose_details']) ? esc($document_purpose['purpose_details']) : '' ?>"
                                       style="display:none;">
                            </div>
                        </div>

                        <div id="childPurposeContainer" class="mb-3">
                            <label>Choose Sub-Purpose</label>
                            <select id="childPurposeSelect" class="form-control">
                                <option value="">Select sub-purpose...</option>
                            </select>
                        </div>

                        <div id="travelInputs" class="mb-3">
                            <div class="row">
                                <div class="col-md-6">
                                    <input type="text" name="travel_place" id="travelPlaceInput" class="form-control" placeholder="Place of Travel"
                                           value="<?php 
                                           $purpose_details = $document_purpose['purpose_details'] ?? '';
                                           if (!empty($purpose_details)) {
                                               if (strpos($purpose_details, 'Place:') !== false) {
                                                   preg_match('/Place:\s*(.*?)(?:,\s*Date:|$)/', $purpose_details, $matches);
                                                   echo isset($matches[1]) ? htmlspecialchars(trim($matches[1])) : '';
                                               } elseif (strpos($purpose_details, 'Place of Travel:') !== false) {
                                                   preg_match('/Place of Travel:\s*(.*?)(?:,\s*Date of Travel:|$)/', $purpose_details, $matches);
                                                   echo isset($matches[1]) ? htmlspecialchars(trim($matches[1])) : '';
                                               } else {
                                                   $parts = explode(',', $purpose_details);
                                                   if (count($parts) > 0 && !preg_match('/\d{4}-\d{2}-\d{2}/', trim($parts[0]))) {
                                                       echo htmlspecialchars(trim($parts[0]));
                                                   }
                                               }
                                           }
                                           ?>">
                                </div>
                                <div class="col-md-6">
                                    <input type="date" name="travel_date" id="travelDateInput" class="form-control" 
                                           value="<?php 
                                           $purpose_details = $document_purpose['purpose_details'] ?? '';
                                           if (!empty($purpose_details)) {
                                               if (strpos($purpose_details, 'Date:') !== false) {
                                                   preg_match('/Date:\s*(\d{4}-\d{2}-\d{2})/', $purpose_details, $matches);
                                                   echo isset($matches[1]) ? $matches[1] : '';
                                               } elseif (strpos($purpose_details, 'Date of Travel:') !== false) {
                                                   preg_match('/Date of Travel:\s*(\d{4}-\d{2}-\d{2})/', $purpose_details, $matches);
                                                   echo isset($matches[1]) ? $matches[1] : '';
                                               } else {
                                                   preg_match('/(\d{4}-\d{2}-\d{2})/', $purpose_details, $matches);
                                                   echo isset($matches[1]) ? $matches[1] : '';
                                               }
                                           }
                                           ?>">
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 text-end">
                            <button type="submit" class="btn btn-success">Update Document</button>
                            <a href="<?= site_url('services/requests/view/' . $request['id_request'] . '/' . $document['id']) ?>" class="btn btn-light">Cancel</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<?= $this->endSection('content') ?>

<?= $this->section('footer_jscript') ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const purposes = <?= json_encode($purposes) ?>;
    const groupedPurposes = <?= json_encode($grouped_purposes) ?>;
    const purposeGroups = <?= json_encode($purpose_groups) ?>;

    document.getElementById('documentSelect').addEventListener('change', function() {
        const otherInput = document.getElementById('otherDocumentInput');
        if(this.selectedOptions[0].text.toLowerCase() === 'others') {
            otherInput.style.display = 'block';
            otherInput.required = true;
        } else {
            otherInput.style.display = 'none';
            otherInput.required = false;
            otherInput.value = '';
        }
    });

    function handlePurposeChange(select) {
        resetSpecialInputs();
        let value = select.value;

        if(value.startsWith('group-')) {
            let groupId = value.split('-')[1];
            let options = groupedPurposes[groupId] || [];
            let group = purposeGroups.find(g => g.id == groupId);

            if(group && group.purpose_group_name.toLowerCase() === 'travel abroad') {
                document.getElementById('travelInputs').style.display = 'block';

                let childOptions = groupedPurposes[groupId] || [];
                if (childOptions.length > 0) {
                    let firstChild = childOptions[0];
                    document.getElementById('selectedPurposeId').value = firstChild.id;
                    console.log('Travel Abroad group selected, setting purpose ID:', firstChild.id);
                } else {
                    console.log('No child options found for Travel Abroad group');
                }
                return; 
            }

            if(options.length) {
                let childSelect = document.getElementById('childPurposeSelect');
                childSelect.innerHTML = '<option value="">Select sub-purpose...</option>';
                options.forEach(p => {
                    let opt = document.createElement('option');
                    opt.value = p.id;
                    opt.text = p.purpose_name;
                    opt.dataset.withDetails = p.with_details;
                    opt.dataset.name = p.purpose_name.toLowerCase();
                    if (<?= isset($document_purpose['purpose_id']) ? $document_purpose['purpose_id'] : 'null' ?> == p.id) {
                        opt.selected = true;
                    }
                    childSelect.appendChild(opt);
                });
                document.getElementById('childPurposeContainer').style.display = 'block';
            }
        } else {
            let purpose = purposes.find(p => p.id == value);
            if(purpose) {
                if(purpose.with_details == 1) {
                    document.getElementById('purposeDetails').style.display = 'block';
                }
                if(purpose.purpose_name.toLowerCase() === 'travel abroad') {
                    document.getElementById('travelInputs').style.display = 'block';
                }
            }
        }
    }

    function resetSpecialInputs() {
        document.getElementById('purposeDetails').style.display = 'none';
        document.getElementById('purposeDetails').value = '';
        document.getElementById('childPurposeContainer').style.display = 'none';
        document.getElementById('childPurposeSelect').innerHTML = '';
        document.getElementById('travelInputs').style.display = 'none';
        document.querySelectorAll('#travelInputs input').forEach(i => i.value = '');
    }

    document.getElementById('childPurposeSelect').addEventListener('change', function(e) {
        let selected = e.target.selectedOptions[0];
     
        document.getElementById('selectedPurposeId').value = selected.value;
        console.log('Child purpose selected, setting purpose ID:', selected.value);
        
        if(selected.dataset.withDetails == '1') {
            document.getElementById('purposeDetails').style.display = 'block';
            
            const purposeDetails = "<?= isset($document_purpose['purpose_details']) ? esc($document_purpose['purpose_details'], 'js') : '' ?>";
            if (purposeDetails) {
                const isTravelRelated = purposeDetails.toLowerCase().includes('place') || purposeDetails.toLowerCase().includes('date');
                if (!isTravelRelated) {
                    document.getElementById('purposeDetails').value = purposeDetails;
                    console.log('Populated purpose details for child purpose:', purposeDetails);
                }
            }
        } else {
            document.getElementById('purposeDetails').style.display = 'none';
            document.getElementById('purposeDetails').value = '';
        }

        if(selected.text.toLowerCase() === 'travel abroad') {
            document.getElementById('travelInputs').style.display = 'block';
        }
    });

    document.getElementById('purposeSelect').addEventListener('change', function(e) {
        let selected = e.target.selectedOptions[0];
        
        if (!selected.value.startsWith('group-')) {
            document.getElementById('selectedPurposeId').value = selected.value;
        } else {
            const groupId = selected.value.split('-')[1];
            const group = purposeGroups.find(g => g.id == groupId);
            if (!(group && group.purpose_group_name.toLowerCase() === 'travel abroad')) {
                document.getElementById('selectedPurposeId').value = '';
            }
        }
        
        handlePurposeChange(e.target);
    });

    if (document.getElementById('documentSelect').value) {
        document.getElementById('documentSelect').dispatchEvent(new Event('change'));
    }
    
    console.log('Initial document purpose data:', <?= json_encode($document_purpose ?? null) ?>);
    console.log('Purpose details:', "<?= isset($document_purpose['purpose_details']) ? esc($document_purpose['purpose_details'], 'js') : '' ?>");
    
    const initialPurposeSelect = document.getElementById('purposeSelect');
    if (initialPurposeSelect.value) {
        console.log('Initial purpose value:', initialPurposeSelect.value);
        console.log('Initial selected purpose ID:', document.getElementById('selectedPurposeId').value);

        if (initialPurposeSelect.value.startsWith('group-')) {
            handlePurposeChange(initialPurposeSelect);
            
            const groupId = initialPurposeSelect.value.split('-')[1];
            const group = purposeGroups.find(g => g.id == groupId);
            
            if (group && group.purpose_group_name.toLowerCase() === 'travel abroad') {
                const childOptions = groupedPurposes[groupId] || [];
                if (childOptions.length > 0) {
                    const firstChild = childOptions[0];
                    document.getElementById('selectedPurposeId').value = firstChild.id;
            
                    document.getElementById('travelInputs').style.display = 'block';
                    const purposeDetails = "<?= isset($document_purpose['purpose_details']) ? esc($document_purpose['purpose_details'], 'js') : '' ?>";
                    if (purposeDetails) {
                        let place = '';
                        let date = '';
                        
                        const placeMatch = purposeDetails.match(/(?:Place:|Place of Travel:)\s*([^,]+?)(?:,\s*(?:Date:|Date of Travel:)|$)/i);
                        const dateMatch = purposeDetails.match(/(?:Date:|Date of Travel:)\s*(\d{4}-\d{2}-\d{2})/i);
                        
                        if (placeMatch) {
                            place = placeMatch[1].trim();
                        }
                        if (dateMatch) {
                            date = dateMatch[1];
                        }
                        
                        if (!place && !date) {
                            const parts = purposeDetails.split(',');
                            if (parts.length > 0) {
                                const firstPart = parts[0].trim();
                                if (!firstPart.match(/^\d{4}-\d{2}-\d{2}$/)) {
                                    place = firstPart;
                                }
                            }
                            if (parts.length > 1) {
                                const dateMatch2 = parts[1].match(/(\d{4}-\d{2}-\d{2})/);
                                if (dateMatch2) {
                                    date = dateMatch2[1];
                                }
                            }
                        }
                        
                        if (place) {
                            document.getElementById('travelPlaceInput').value = place;
                        }
                        if (date) {
                            document.getElementById('travelDateInput').value = date;
                        }
                        
                        console.log('Parsed travel details - Place:', place, 'Date:', date);
                    }
                }
            } else {
                const childPurposeId = <?= isset($document_purpose['purpose_id']) ? $document_purpose['purpose_id'] : 'null' ?>;
                if (childPurposeId) {
                    setTimeout(() => {
                        const childSelect = document.getElementById('childPurposeSelect');
                        if (childSelect) {
                            childSelect.value = childPurposeId;
                            if (childSelect.value) {
                                childSelect.dispatchEvent(new Event('change'));
                            }
                        }
                    }, 100);
                }
            }
        } else {
            document.getElementById('selectedPurposeId').value = initialPurposeSelect.value;
            handlePurposeChange(initialPurposeSelect);
            
            const selectedOption = initialPurposeSelect.selectedOptions[0];
            if (selectedOption && selectedOption.text.toLowerCase() === 'travel abroad') {
                document.getElementById('travelInputs').style.display = 'block';
                
                const purposeDetails = "<?= isset($document_purpose['purpose_details']) ? esc($document_purpose['purpose_details'], 'js') : '' ?>";
                if (purposeDetails) {
                    let place = '';
                    let date = '';
                    
                    const placeMatch = purposeDetails.match(/(?:Place:|Place of Travel:)\s*([^,]+?)(?:,\s*(?:Date:|Date of Travel:)|$)/i);
                    const dateMatch = purposeDetails.match(/(?:Date:|Date of Travel:)\s*(\d{4}-\d{2}-\d{2})/i);
                    
                    if (placeMatch) {
                        place = placeMatch[1].trim();
                    }
                    if (dateMatch) {
                        date = dateMatch[1];
                    }
                    
                    if (place) {
                        document.getElementById('travelPlaceInput').value = place;
                    }
                    if (date) {
                        document.getElementById('travelDateInput').value = date;
                    }
                    
                    console.log('Parsed regular travel details - Place:', place, 'Date:', date);
                }
            } else if (selectedOption && selectedOption.dataset.withDetails == '1') {
                document.getElementById('purposeDetails').style.display = 'block';

                const purposeDetails = "<?= isset($document_purpose['purpose_details']) ? esc($document_purpose['purpose_details'], 'js') : '' ?>";
                if (purposeDetails) {
                    const isTravelRelated = purposeDetails.toLowerCase().includes('place') || purposeDetails.toLowerCase().includes('date');
                    if (!isTravelRelated) {
                        document.getElementById('purposeDetails').value = purposeDetails;
                        console.log('Populated purpose details for individual purpose:', purposeDetails);
                    }
                }
            }
        }
    }
});
</script>
<?= $this->endSection() ?>