<?= $this->extend('/layout/main') ?>

<?= $this->section('header_actions') ?>
<div class="mt-4">
    <ul class="page_title_button" style="list-style: none; float:right;">
        <li>
            <a href="<?= site_url('services/requests') ?>" class="btn btn-light" role="button">
                <i class="fas fa-arrow-circle-left"></i>
                <div style="color:#000">Back to Requests</div>
            </a>
        </li>
    </ul>
</div>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<style>
.doc-table th, .doc-table td { vertical-align: middle; }
#addDocumentForm { display: none; margin-top: 15px; }
.purpose-sub { margin-left: 20px; font-size: 0.9em; }
.purpose-detail-input { margin-top: 5px; }
#childPurposeContainer { display:none; margin-top:10px; }
#travelInputs { display:none; margin-top:10px; }
#travelInputs input { margin-bottom:5px; }
</style>

<div style="min-height:50vh">
    <form method="post" action="<?= site_url('services/requests/submit') ?>" id="requestForm">
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
                            <input type="hidden" name="contact_num" value="<?= isset($employee['emp_cpno']) ? esc($employee['emp_cpno']) : '' ?>">
                        </div>
                        <div class="mb-2">
                            <b>Date Requested:</b> <?= date('F j, Y') ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Documents Requested -->
            <div class="col-lg-9 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h4 class="mb-3">Documents Requested</h4>
                        <table class="table table-hover" id="">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Document</th>
                                    <th>Purpose</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="documentsTable">
                                <?php if(isset($request_documents)): ?>
                                    <?php foreach($request_documents as $idx => $doc):
                                        $purposeText = '';
                                        $purpose_details = $doc['purpose_details'] ?? '';
                                        $travel_place = '';
                                        $travel_date = '';

                                        if(!empty($doc['purpose_id'])){
                                            $allPurposes = array_merge($purposes, ...array_values($grouped_purposes));
                                            $purposeRow = array_filter($allPurposes, fn($p)=> $p['id'] == $doc['purpose_id']);
                                            $purposeRow = $purposeRow ? array_values($purposeRow)[0] : null;

                                            if($purposeRow){
                                                $purposeText = $purposeRow['purpose_name'];

                                                if(strtolower($purposeRow['purpose_name']) === 'travel abroad' && !empty($purpose_details)){
                                                    if(str_contains($purpose_details, 'Place:')){
                                                        preg_match('/Place:\s*(.*?),\s*Date:\s*(.*)/', $purpose_details, $matches);
                                                        if($matches){
                                                            $travel_place = $matches[1];
                                                            $travel_date = $matches[2];
                                                        }
                                                    }
                                                }
                                            }
                                        }

                                        if($purpose_details && strtolower($purposeText) !== 'travel abroad'){
                                            $purposeText .= " ($purpose_details)";
                                        }
                                    ?>
                                    <tr>
                                        <td class="doc-counter"><?= $idx+1 ?></td>
                                        <td>
                                            <?= esc($doc['document_name']) ?>
                                            <input type="hidden" class="doc-id-input" value="<?= $doc['document_id'] ?>">
                                            <input type="hidden" class="doc-other-input" value="<?= esc($doc['other_documents']) ?>">
                                        </td>
                                        <td>
                                            <?= esc($purposeText) ?>
                                            <input type="hidden" class="doc-purpose-input" value="<?= $doc['purpose_id'] ?? '' ?>">
                                            <input type="hidden" class="doc-purpose-details-input" value="<?= esc($doc['purpose_details']) ?>">
                                            <input type="hidden" class="travel-place-input" value="<?= esc($travel_place) ?>">
                                            <input type="hidden" class="travel-date-input" value="<?= esc($travel_date) ?>">
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-warning edit-doc">Edit</button>
                                            <button type="button" class="btn btn-sm btn-danger remove-doc">Remove</button>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>

                        <div class="text-end mb-3">
                            <button type="button" class="btn btn-primary" id="addDocBtn">Add Document</button>
                        </div>

                        <!-- Add / Edit Document Form -->
                        <div id="addDocumentForm" class="border p-3 rounded">
                            <div class="row g-2 align-items-end">
                                <div class="col-md-6">
                                    <label>Document</label>
                                    <select id="newDocSelect" class="form-control">
                                        <option value="">Select document...</option>
                                        <?php foreach($documents as $d): ?>
                                            <option value="<?= $d['id'] ?>"><?= esc($d['document_name']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <input type="text" id="newDocOther" class="form-control mt-1" placeholder="Specify if Others" style="display:none;">
                                </div>

                                <div class="col-md-6">
                                    <label>Purpose</label>
                                    <select id="newPurposeSelect" class="form-control">
                                        <option value="">Select purpose...</option>
                                        <?php foreach($purpose_groups as $g): ?>
                                            <option value="group-<?= $g['id'] ?>" class="purpose-group" data-group-id="<?= $g['id'] ?>" data-group-name="<?= esc($g['purpose_group_name']) ?>"><?= esc($g['purpose_group_name']) ?></option>
                                        <?php endforeach; ?>
                                        <?php foreach($purposes as $p): ?>
                                            <option value="<?= $p['id'] ?>" data-with-details="<?= $p['with_details'] ?>" data-name="<?= strtolower($p['purpose_name']) ?>"><?= esc($p['purpose_name']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <input type="text" id="newPurposeDetails" class="form-control mt-1 purpose-detail-input" placeholder="Enter details" style="display:none;">
                                </div>
                            </div>

                            <div id="childPurposeContainer" class="mt-2">
                                <label>Choose Sub-Purpose</label>
                                <select id="childPurposeSelect" class="form-control"></select>
                            </div>

                            <div id="travelInputs">
                                <input type="text" name="travel_place_hidden" class="form-control" placeholder="Place of Travel">
                                <input type="date" name="travel_date_hidden" class="form-control">
                            </div>

                            <div class="mt-2 text-end">
                                <button type="button" class="btn btn-success" id="saveDocBtn">Add</button>
                                <button type="button" class="btn btn-light" id="cancelDocBtn">Cancel</button>
                            </div>
                        </div>

                        <div class="mt-4 text-end">
                            <button type="submit" class="btn btn-success" id="submitBtn">Submit Request</button>
                            <button type="button" class="btn btn-light" id="cancelBtn">Cancel</button>
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
function getDraftKey() {
    return 'request_draft_' + <?= json_encode($employee['id_employee'] ?? 0) ?>;
}

function confirmLeave() {
    const rows = document.querySelectorAll('#documentsTable tr');
    
    if (rows.length > 0) {
        const draftData = [];
        rows.forEach(row => {
            const docIdInput = row.querySelector('.doc-id-input');
            const docOtherInput = row.querySelector('.doc-other-input');
            const purposeInput = row.querySelector('.doc-purpose-input');
            const purposeDetailsInput = row.querySelector('.doc-purpose-details-input');
            const travelPlaceInput = row.querySelector('.travel-place-input');
            const travelDateInput = row.querySelector('.travel-date-input');
            
            draftData.push({
                docId: docIdInput?.value,
                docOther: docOtherInput?.value,
                purposeId: purposeInput?.value,
                purposeDetails: purposeDetailsInput?.value,
                travelPlace: travelPlaceInput?.value,
                travelDate: travelDateInput?.value
            });
        });
        
        localStorage.setItem(getDraftKey(), JSON.stringify(draftData));
        
        if (confirm('You have unsaved documents. They have been saved as a draft. Do you want to leave this page?')) {
            window.location.href = '<?= site_url('services/requests') ?>';
        }
    } else {
        if (confirm('Are you sure you want to cancel?')) {
            window.location.href = '<?= site_url('services/requests') ?>';
        }
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const purposes = <?= json_encode($purposes) ?>;
    const groupedPurposes = <?= json_encode($grouped_purposes) ?>;
    const purposeGroups = <?= json_encode($purpose_groups) ?>;
    
    window.groupedPurposes = groupedPurposes;
    
    let docIndex = document.querySelectorAll('#documentsTable tr').length;

    const restoreDraftKey = sessionStorage.getItem('restore_draft_key');
    if (restoreDraftKey) {
        sessionStorage.removeItem('restore_draft_key');
        
        const savedDraft = localStorage.getItem(restoreDraftKey);
        if (savedDraft) {
            try {
                const draftData = JSON.parse(savedDraft);
                if (draftData && draftData.length > 0) {
                    const table = document.getElementById('documentsTable');
                    
                    document.querySelectorAll('#documentsTable tr').forEach(row => row.remove());
                    
                    draftData.forEach((doc, index) => {
                        const docSelect = document.getElementById('newDocSelect');
                        let docName = 'Unknown Document';
                        if (docSelect) {
                            const option = Array.from(docSelect.options).find(opt => opt.value == doc.docId);
                            if (option) docName = option.text;
                        }
                        
                        if (docName.toLowerCase() === 'others' && doc.docOther) {
                            docName = doc.docOther;
                        }
                        
                        const purposeSelect = document.getElementById('newPurposeSelect');
                        let purposeName = 'Unknown Purpose';
                        let displayText = purposeName;
                        let foundPurpose = false;
                        
                        if (purposeSelect) {
                            const option = Array.from(purposeSelect.options).find(opt => opt.value == doc.purposeId);
                            if (option) {
                                purposeName = option.text;
                                displayText = purposeName;
                                foundPurpose = true;
                            } else {
                                for (const [groupId, childPurposes] of Object.entries(groupedPurposes)) {
                                    const childPurpose = childPurposes.find(p => String(p.id) == String(doc.purposeId));
                                    if (childPurpose) {
                                        const groupOption = Array.from(purposeSelect.options).find(opt => opt.value == `group-${groupId}`);
                                        if (groupOption) {
                                            purposeName = `${groupOption.text} > ${childPurpose.purpose_name}`;
                                        } else {
                                            purposeName = childPurpose.purpose_name;
                                        }
                                        displayText = purposeName;
                                        foundPurpose = true;
                                        break;
                                    }
                                }
                                
                                if (!foundPurpose) {
                                    const childSelect = document.getElementById('childPurposeSelect');
                                    if (childSelect) {
                                        const childOption = Array.from(childSelect.options).find(opt => opt.value == doc.purposeId);
                                        if (childOption) {
                                            purposeName = childOption.text;
                                            displayText = purposeName;
                                            foundPurpose = true;
                                        }
                                    }
                                }
                            }
                        }
                        
                        if (!foundPurpose) {
                            // Check regular purposes
                            const regularPurpose = purposes.find(p => String(p.id) == String(doc.purposeId));
                            if (regularPurpose) {
                                purposeName = regularPurpose.purpose_name;
                                displayText = purposeName;
                                foundPurpose = true;
                            }
                            
                            // Check grouped purposes if not found
                            if (!foundPurpose) {
                                for (const [groupId, childPurposes] of Object.entries(groupedPurposes)) {
                                    const childPurpose = childPurposes.find(p => String(p.id) == String(doc.purposeId));
                                    if (childPurpose) {
                                        purposeName = childPurpose.purpose_name;
                                        displayText = purposeName;
                                        foundPurpose = true;
                                        break;
                                    }
                                }
                            }
                        }
                        
                        // Add purpose details if available
                        if (doc.purposeDetails) {
                            displayText += ' (' + doc.purposeDetails + ')';
                        }
                        
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td class="doc-counter">${index + 1}</td>
                            <td>
                                ${docName}
                                <input type="hidden" class="doc-id-input" value="${doc.docId}">
                                <input type="hidden" class="doc-other-input" value="${doc.docOther || ''}">
                            </td>
                            <td>
                                ${displayText}
                                <input type="hidden" class="doc-purpose-input" value="${doc.purposeId}">
                                <input type="hidden" class="doc-purpose-details-input" value="${doc.purposeDetails || ''}">
                                <input type="hidden" class="travel-place-input" value="${doc.travelPlace || ''}">
                                <input type="hidden" class="travel-date-input" value="${doc.travelDate || ''}">
                            </td>
                            <td>
                                <button type="button" class="btn btn-sm btn-warning edit-doc">Edit</button>
                                <button type="button" class="btn btn-sm btn-danger remove-doc">Remove</button>
                            </td>
                        `;
                        table.appendChild(row);
                    });
                    
                    updateCounters();
                    
                    console.log('Draft restored with', draftData.length, 'documents');
                }
            } catch (e) {
                console.error('Error restoring draft:', e);
                localStorage.removeItem(restoreDraftKey);
            }
        }
    }

    function toggleOthersInput(select) {
        let otherInput = document.getElementById('newDocOther');
        if(select.selectedOptions[0].text.toLowerCase() === 'others'){
            otherInput.style.display = 'block';
        } else {
            otherInput.style.display = 'none';
            otherInput.value = '';
        }
    }

    function resetSpecialInputs(){
        document.getElementById('newPurposeDetails').style.display = 'none';
        document.getElementById('newPurposeDetails').value = '';
        document.getElementById('childPurposeContainer').style.display = 'none';
        document.getElementById('childPurposeSelect').innerHTML = '';
        document.getElementById('travelInputs').style.display = 'none';
        document.querySelectorAll('#travelInputs input').forEach(i=>i.value='');
    }

    function handlePurposeChange(select){
        resetSpecialInputs();
        let value = select.value;

        if(value.startsWith('group-')){
            let groupId = value.split('-')[1];
            let options = groupedPurposes[groupId] || [];
            let group = purposeGroups.find(g => g.id == groupId);

            if(group && group.purpose_group_name.toLowerCase() === 'travel abroad'){
                document.getElementById('travelInputs').style.display = 'block';
                return; 
            }

            if(options.length){
                let childSelect = document.getElementById('childPurposeSelect');
                childSelect.innerHTML = '<option value="">Select sub-purpose...</option>';
                options.forEach(p=>{
                    let opt = document.createElement('option');
                    opt.value = p.id;
                    opt.text = p.purpose_name;
                    opt.dataset.withDetails = p.with_details;
                    opt.dataset.name = p.purpose_name.toLowerCase();
                    childSelect.appendChild(opt);
                });
                document.getElementById('childPurposeContainer').style.display = 'block';
            }

        } else {
            let purpose = purposes.find(p=>p.id == value);
            if(purpose){
                if(purpose.with_details == 1){
                    document.getElementById('newPurposeDetails').style.display = 'block';
                }
                if(purpose.purpose_name.toLowerCase() === 'travel abroad'){
                    document.getElementById('travelInputs').style.display = 'block';
                    return;
                }
            }
        }
    }

    document.getElementById('childPurposeSelect').addEventListener('change', e=>{
        let selected = e.target.selectedOptions[0];
        if(selected.dataset.withDetails == '1'){
            document.getElementById('newPurposeDetails').style.display = 'block';
        } else {
            document.getElementById('newPurposeDetails').style.display = 'none';
            document.getElementById('newPurposeDetails').value = '';
        }

        if(selected.text.toLowerCase() === 'travel abroad'){
            document.getElementById('travelInputs').style.display = 'block';
        }
    });

    document.getElementById('addDocBtn').addEventListener('click', function() {
        document.getElementById('addDocumentForm').style.display = 'block';
    });

    document.getElementById('cancelDocBtn').addEventListener('click', ()=>{
        document.getElementById('addDocumentForm').style.display='none';
        document.getElementById('newDocSelect').selectedIndex=0;
        document.getElementById('newDocOther').value='';
        document.getElementById('newDocOther').style.display='none';
        document.getElementById('newPurposeSelect').selectedIndex=0;
        resetSpecialInputs();
    });

    document.getElementById('newDocSelect').addEventListener('change', e=>toggleOthersInput(e.target));
    document.getElementById('newPurposeSelect').addEventListener('change', e=>handlePurposeChange(e.target));

    document.getElementById('saveDocBtn').addEventListener('click', function() {
        let docSelect = document.getElementById('newDocSelect');
        let purposeSelect = document.getElementById('newPurposeSelect');
        let childSelect = document.getElementById('childPurposeSelect');
        let otherInput = document.getElementById('newDocOther');
        let detailsInput = document.getElementById('newPurposeDetails');
        let travelPlace = document.querySelector('input[name="travel_place_hidden"]').value;
        let travelDate = document.querySelector('input[name="travel_date_hidden"]').value;

        if(!docSelect.value){
            alert('Please select a document!');
            return;
        }

        let hasValidPurpose = false;
        if(childSelect.value) {
            hasValidPurpose = true;
        } else if(purposeSelect.value && !purposeSelect.value.startsWith('group-')) {
            hasValidPurpose = true;
        } else if(purposeSelect.value && purposeSelect.value.startsWith('group-')) {
            let groupId = purposeSelect.value.split('-')[1];
            let group = purposeGroups.find(g => g.id == groupId);
            let options = groupedPurposes[groupId] || [];
            
            let groupName = group ? group.purpose_group_name.toLowerCase() : '';
            if(groupName === 'travel abroad') {
                hasValidPurpose = true;
            } else if(options.length > 0 && !childSelect.value) {
                alert('Please select a sub-purpose for the selected group!');
                return;
            } else if(options.length === 0) {
                hasValidPurpose = true;
            } else {
                hasValidPurpose = true;
            }
        }
        
        if(!hasValidPurpose) {
            alert('Please select a purpose!');
            return;
        }

        let purposeText = '';
        if(childSelect && childSelect.value){
            purposeText = childSelect.selectedOptions[0].text;
        } else {
            purposeText = purposeSelect.selectedOptions[0].text;
        }
        
        if(purposeText.toLowerCase() === 'travel abroad' && (!travelPlace || !travelDate)){
            alert('Please fill Place of Travel and Date of Travel!');
            return;
        }

        let docText = docSelect.selectedOptions[0].text;
        let docValue = docSelect.value;
        let otherValue = otherInput.value;
        
        if (docText.toLowerCase() === 'others' && otherValue) {
            docText = otherValue;
        }

        let purposeValue = '';
        let detailsValue = detailsInput.value;

        if(childSelect && childSelect.value){
            purposeText = childSelect.selectedOptions[0].text;
            purposeValue = childSelect.value;
        } else {
            purposeText = purposeSelect.selectedOptions[0].text;
            purposeValue = purposeSelect.value;
        }
        
        let displayText = purposeText;
        if (detailsValue) {
            displayText += ' (' + detailsValue + ')';
        }

        let table = document.getElementById('documentsTable');
        let row = document.createElement('tr');
        row.innerHTML = `
            <td class="doc-counter"></td>
            <td>
                ${docText}
                <input type="hidden" class="doc-id-input" value="${docValue}">
                <input type="hidden" class="doc-other-input" value="${otherValue}">
            </td>
            <td>
                ${displayText}
                <input type="hidden" class="doc-purpose-input" value="${purposeValue}">
                <input type="hidden" class="doc-purpose-details-input" value="${detailsValue}">
                <input type="hidden" class="travel-place-input" value="${travelPlace}">
                <input type="hidden" class="travel-date-input" value="${travelDate}">
            </td>
            <td>
                <button type="button" class="btn btn-sm btn-warning edit-doc">Edit</button>
                <button type="button" class="btn btn-sm btn-danger remove-doc">Remove</button>
            </td>
        `;
        table.appendChild(row);
        updateCounters();
        document.getElementById('cancelDocBtn').click();
    });

    function saveDraft() {
        const rows = document.querySelectorAll('#documentsTable tr');
        if (rows.length > 0) {
            const draftData = [];
            rows.forEach(row => {
                const docIdInput = row.querySelector('.doc-id-input');
                const docOtherInput = row.querySelector('.doc-other-input');
                const purposeInput = row.querySelector('.doc-purpose-input');
                const purposeDetailsInput = row.querySelector('.doc-purpose-details-input');
                const travelPlaceInput = row.querySelector('.travel-place-input');
                const travelDateInput = row.querySelector('.travel-date-input');
                
                draftData.push({
                    docId: docIdInput?.value,
                    docOther: docOtherInput?.value,
                    purposeId: purposeInput?.value,
                    purposeDetails: purposeDetailsInput?.value,
                    travelPlace: travelPlaceInput?.value,
                    travelDate: travelDateInput?.value
                });
            });
            localStorage.setItem(getDraftKey(), JSON.stringify(draftData));
        } else {
            localStorage.removeItem(getDraftKey());
        }
    }

    function updateCounters(){
        document.querySelectorAll('#documentsTable tr').forEach((tr, idx)=>{
            tr.querySelector('.doc-counter').textContent = idx+1;
            tr.querySelector('.doc-id-input').name = `documents[${idx}][id]`;
            tr.querySelector('.doc-other-input').name = `documents[${idx}][other]`;
            tr.querySelector('.doc-purpose-input').name = `documents[${idx}][purpose]`;
            tr.querySelector('.doc-purpose-details-input').name = `documents[${idx}][purpose_details]`;
            tr.querySelector('.travel-place-input').name = `documents[${idx}][travel_place]`;
            tr.querySelector('.travel-date-input').name = `documents[${idx}][travel_date]`;
        });
        saveDraft(); 
    }

    document.getElementById('documentsTable').addEventListener('click', e=>{
        let row = e.target.closest('tr');
        if(e.target.classList.contains('remove-doc')){
            row.remove();
            updateCounters();
        } else if(e.target.classList.contains('edit-doc')){
            document.getElementById('addDocumentForm').style.display = 'block';

            let docId = row.querySelector('.doc-id-input').value;
            document.getElementById('newDocSelect').value = docId;

            let docOtherValue = row.querySelector('.doc-other-input').value;
            if(docOtherValue) {
                document.getElementById('newDocOther').value = docOtherValue;
                document.getElementById('newDocOther').style.display = 'block';
                document.getElementById('newDocSelect').dispatchEvent(new Event('change'));
            } else {
                document.getElementById('newDocSelect').dispatchEvent(new Event('change'));
            }

            let purposeVal = row.querySelector('.doc-purpose-input').value;
            const purposeSelect = document.getElementById('newPurposeSelect');
            if (purposeSelect) {
                let purposeOption = Array.from(purposeSelect.options).find(opt => opt.value == purposeVal);
                
                if (purposeOption) {
                    purposeSelect.value = purposeVal;
                    handlePurposeChange(purposeSelect);
                    console.log('Found regular purpose for edit:', purposeOption.text);
                } else {
                    let foundGroup = false;
                    console.log('Looking for child purpose in groups for edit:', groupedPurposes);
                    
                    for (const [groupId, childPurposes] of Object.entries(groupedPurposes)) {
                        const childPurpose = childPurposes.find(p => String(p.id) == String(purposeVal));
                        if (childPurpose) {
                            console.log('Found child purpose for edit:', childPurpose.purpose_name, 'in group:', groupId);

                            purposeSelect.value = 'group-' + groupId;
                            handlePurposeChange(purposeSelect);

                            setTimeout(() => {
                                const childSelect = document.getElementById('childPurposeSelect');
                                if (childSelect) {
                                    console.log('Setting child purpose value for edit:', purposeVal);
                                    childSelect.value = purposeVal;
                                    const childEvent = new Event('change', { bubbles: true });
                                    childSelect.dispatchEvent(childEvent);
                                }
                            }, 150);
                            
                            foundGroup = true;
                            break;
                        }
                    }
                    
                    if (!foundGroup) {
                        console.warn('Purpose not found in any group for edit:', purposeVal);
                        purposeSelect.value = purposeVal;
                        handlePurposeChange(purposeSelect);
                    }
                }
            }
            
            document.getElementById('newPurposeDetails').value = row.querySelector('.doc-purpose-details-input').value;
            document.querySelector('input[name="travel_place_hidden"]').value = row.querySelector('.travel-place-input').value;
            document.querySelector('input[name="travel_date_hidden"]').value = row.querySelector('.travel-date-input').value;
            row.remove();
            updateCounters();
        }
    });

    document.getElementById('requestForm').addEventListener('submit', e=>{
        let rows = document.querySelectorAll('#documentsTable tr');
        if(rows.length === 0){
            e.preventDefault();
            alert('Please add at least one document with purpose!');
            return false;
        }
        
        let hasEmptyPurpose = false;
        rows.forEach(row => {
            let purposeInput = row.querySelector('.doc-purpose-input');
            if(!purposeInput || !purposeInput.value){
                hasEmptyPurpose = true;
            }
        });
        if(hasEmptyPurpose){
            e.preventDefault();
            alert('All documents must have a purpose selected!');
            return false;
        }

        localStorage.removeItem(getDraftKey());
    });

    document.getElementById('cancelBtn').addEventListener('click', confirmLeave);
});
</script>
<?= $this->endSection() ?>