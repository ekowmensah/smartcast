<!-- Multi-Step Event Creation Wizard -->
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <!-- Plan Limits Alert -->
            <?php 
            $currentEventCount = isset($tenantLimits['current_events']) ? $tenantLimits['current_events'] : 0;
            $maxEvents = isset($tenantLimits['max_events']) ? $tenantLimits['max_events'] : null;
            $canCreateEvent = is_null($maxEvents) || $currentEventCount < $maxEvents;
            $isAtLimit = !is_null($maxEvents) && $currentEventCount >= $maxEvents;
            
            // Check if we're editing a draft event and at limit
            $isDraftAtLimit = ($isEditing ?? false) && 
                             ($currentEventStatus ?? '') === 'draft' && 
                             !$canCreateEvent;
            ?>
            
            <?php if (!$canCreateEvent && !($isEditing ?? false)): ?>
            <div class="alert alert-danger mb-4" role="alert">
                <div class="d-flex align-items-center">
                    <i class="fas fa-exclamation-triangle fa-2x me-3"></i>
                    <div class="flex-grow-1">
                        <h5 class="alert-heading mb-1">Event Limit Reached</h5>
                        <p class="mb-2">You have reached your plan limit of <strong><?= $maxEvents ?> published events</strong>. You currently have <strong><?= $currentEventCount ?> published events</strong>.</p>
                        <p class="mb-2"><small class="text-muted"><i class="fas fa-info-circle me-1"></i>Note: Draft events don't count toward your limit. You can still save events as drafts.</small></p>
                        <a href="<?= ORGANIZER_URL ?>/switch-plan" class="btn btn-warning btn-sm me-2">
                            <i class="fas fa-arrow-up me-2"></i>Upgrade Plan
                        </a>
                        <a href="<?= ORGANIZER_URL ?>/events" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-arrow-left me-2"></i>Back to Events
                        </a>
                    </div>
                </div>
            </div>
            <?php elseif ($isDraftAtLimit): ?>
            <div class="alert alert-warning mb-4" role="alert">
                <div class="d-flex align-items-center">
                    <i class="fas fa-exclamation-triangle fa-2x me-3"></i>
                    <div class="flex-grow-1">
                        <h5 class="alert-heading mb-1">Cannot Publish Draft Event</h5>
                        <p class="mb-2">You have reached your plan limit of <strong><?= $maxEvents ?> published events</strong>. This draft event cannot be published until you upgrade your plan.</p>
                        <p class="mb-2"><small class="text-muted"><i class="fas fa-info-circle me-1"></i>You can continue editing and saving as draft, but publishing requires a plan upgrade.</small></p>
                        <a href="<?= ORGANIZER_URL ?>/switch-plan" class="btn btn-warning btn-sm me-2">
                            <i class="fas fa-arrow-up me-2"></i>Upgrade Plan
                        </a>
                    </div>
                </div>
            </div>
            <?php elseif (!is_null($maxEvents)): ?>
            <div class="alert alert-info mb-4" role="alert">
                <div class="d-flex align-items-center">
                    <i class="fas fa-info-circle me-3"></i>
                    <div class="flex-grow-1">
                        <strong>Plan Usage:</strong> <?= $currentEventCount ?> of <?= $maxEvents ?> published events used
                        <?php if ($currentEventCount >= $maxEvents - 1): ?>
                            <span class="text-warning ms-2">
                                <i class="fas fa-exclamation-triangle"></i> Almost at limit!
                            </span>
                        <?php endif; ?>
                    </div>
                    <?php if ($currentEventCount >= $maxEvents - 2): ?>
                    <a href="<?= ORGANIZER_URL ?>/switch-plan" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-arrow-up me-2"></i>Upgrade
                    </a>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>
            
            <!-- Progress Steps -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="mb-0">
                            <i class="fas fa-magic me-2"></i>
                            Create New Event
                        </h4>
                        <a href="<?= ORGANIZER_URL ?>/events" class="btn btn-outline-secondary">
                            <i class="fas fa-times me-2"></i>Cancel
                        </a>
                    </div>
                    
                    <!-- Progress Bar -->
                    <div class="progress mb-3" style="height: 8px;">
                        <div class="progress-bar bg-primary" role="progressbar" style="width: 25%" id="progressBar"></div>
                    </div>
                    
                    <!-- Step Indicators -->
                    <div class="d-flex justify-content-between">
                        <div class="step-indicator active" data-step="1">
                            <div class="step-circle">
                                <i class="fas fa-info-circle"></i>
                            </div>
                            <div class="step-label">Event Details</div>
                        </div>
                        <div class="step-indicator" data-step="2">
                            <div class="step-circle">
                                <i class="fas fa-tags"></i>
                            </div>
                            <div class="step-label">Categories</div>
                        </div>
                        <div class="step-indicator" data-step="3">
                            <div class="step-circle">
                                <i class="fas fa-users"></i>
                            </div>
                            <div class="step-label">Nominees</div>
                        </div>
                        <div class="step-indicator" data-step="4">
                            <div class="step-circle">
                                <i class="fas fa-eye"></i>
                            </div>
                            <div class="step-label">Preview</div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Step Content -->
            <form id="eventWizardForm" method="POST" action="<?= ORGANIZER_URL ?>/events/wizard" enctype="multipart/form-data">
                <?php if ($isEditing ?? false): ?>
                    <input type="hidden" name="edit_event_id" value="<?= $editEventId ?>">
                <?php endif; ?>
                
                <!-- Step 1: Event Details -->
                <div class="step-content active" id="step1">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">
                                        <i class="fas fa-info-circle me-2"></i>
                                        Event Information
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="name" class="form-label">Event Name *</label>
                                                <input type="text" class="form-control" id="name" name="name" 
                                                       value="<?= htmlspecialchars($eventData['name'] ?? '') ?>" required>
                                                <div class="form-text">Choose a memorable name for your event</div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="code" class="form-label">Event Code *</label>
                                                <input type="text" class="form-control" id="code" name="code" 
                                                       value="<?= htmlspecialchars($eventData['code'] ?? '') ?>" required>
                                                <div class="form-text">Unique identifier (auto-generated if empty)</div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="description" class="form-label">Description</label>
                                        <textarea class="form-control" id="description" name="description" rows="3" placeholder="Describe your voting event..."><?= htmlspecialchars($eventData['description'] ?? '') ?></textarea>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="featured_image" class="form-label">Featured Image</label>
                                        <input type="file" class="form-control" id="featured_image" name="featured_image" accept="image/*" onchange="previewEventImage(this)">
                                        <div class="form-text">Upload a banner image (JPG, PNG, max 5MB)</div>
                                        <?php if (!empty($eventData['featured_image'])): ?>
                                            <div class="mt-2" id="current-image">
                                                <small class="text-success">✓ Current image: <?= basename($eventData['featured_image']) ?></small>
                                                <div class="mt-1">
                                                    <img src="<?= htmlspecialchars(image_url($eventData['featured_image'])) ?>" 
                                                         alt="Current featured image" 
                                                         style="max-width: 200px; max-height: 100px; object-fit: cover;" 
                                                         class="img-thumbnail">
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                        <div id="image-preview" class="mt-2" style="display: none;">
                                            <small class="text-info">New image preview:</small>
                                            <div class="mt-1">
                                                <img id="preview-img" src="" alt="Preview" 
                                                     style="max-width: 200px; max-height: 100px; object-fit: cover;" 
                                                     class="img-thumbnail">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="start_date" class="form-label">Start Date & Time *</label>
                                                <input type="datetime-local" class="form-control" id="start_date" name="start_date" 
                                                       value="<?= isset($eventData['start_date']) ? date('Y-m-d\TH:i', strtotime($eventData['start_date'])) : '' ?>" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="end_date" class="form-label">End Date & Time *</label>
                                                <input type="datetime-local" class="form-control" id="end_date" name="end_date" 
                                                       value="<?= isset($eventData['end_date']) ? date('Y-m-d\TH:i', strtotime($eventData['end_date'])) : '' ?>" required>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="vote_price" class="form-label">Vote Price ($)</label>
                                                <input type="number" class="form-control" id="vote_price" name="vote_price" 
                                                       value="<?= htmlspecialchars($eventData['vote_price'] ?? '0.50') ?>" step="0.01" min="0">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="visibility" class="form-label">Visibility</label>
                                                <select class="form-select" id="visibility" name="visibility">
                                                    <option value="public" <?= ($eventData['visibility'] ?? 'public') === 'public' ? 'selected' : '' ?>>Public</option>
                                                    <option value="private" <?= ($eventData['visibility'] ?? 'public') === 'private' ? 'selected' : '' ?>>Private</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="card-title">
                                        <i class="fas fa-lightbulb me-2"></i>
                                        Tips for Success
                                    </h6>
                                    <ul class="small mb-0">
                                        <li>Choose a clear, descriptive event name</li>
                                        <li>Set realistic start and end dates</li>
                                        <li>Upload an eye-catching banner image</li>
                                        <li>Consider your target audience for pricing</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Step 2: Categories -->
                <div class="step-content" id="step2" style="display: none;">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0">
                                        <i class="fas fa-tags me-2"></i>
                                        Event Categories
                                    </h5>
                                    <button type="button" class="btn btn-primary btn-sm" onclick="addCategory()">
                                        <i class="fas fa-plus me-2"></i>Add Category
                                    </button>
                                </div>
                                <div class="card-body">
                                    <div class="text-center text-muted py-4" id="noCategoriesMessage">
                                        <i class="fas fa-tags fa-2x mb-2"></i>
                                        <p>No categories added yet. Click "Add Category" to get started.</p>
                                    </div>
                                    <div id="categoriesContainer">
                                        <!-- Categories will be added here dynamically -->
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="card-title">
                                        <i class="fas fa-info-circle me-2"></i>
                                        About Categories
                                    </h6>
                                    <p class="small mb-3">Categories help organize your nominees. Examples:</p>
                                    <ul class="small mb-0">
                                        <li><strong>Music Awards:</strong> Best Artist, Best Song, Best Album</li>
                                        <li><strong>Beauty Contest:</strong> Miss Photogenic, Best Talent</li>
                                        <li><strong>Sports:</strong> MVP, Best Goal, Fan Favorite</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Step 3: Nominees -->
                <div class="step-content" id="step3" style="display: none;">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0">
                                        <i class="fas fa-users me-2"></i>
                                        Event Nominees
                                    </h5>
                                    <button type="button" class="btn btn-primary btn-sm" onclick="addNominee()">
                                        <i class="fas fa-plus me-2"></i>Add Nominee
                                    </button>
                                </div>
                                <div class="card-body">
                                    <div class="text-center text-muted py-4" id="noNomineesMessage">
                                        <i class="fas fa-users fa-2x mb-2"></i>
                                        <p>No nominees added yet. Click "Add Nominee" to get started.</p>
                                    </div>
                                    <div id="nomineesContainer">
                                        <!-- Nominees will be added here dynamically -->
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="card-title">
                                        <i class="fas fa-mobile-alt me-2"></i>
                                        USSD Voting
                                    </h6>
                                    <p class="small mb-2">Each nominee gets a unique shortcode for USSD voting:</p>
                                    <div class="bg-white p-2 rounded border small mb-2">
                                        <strong>Example:</strong><br>
                                        JO01 - John Doe<br>
                                        MA02 - Mary Jane<br>
                                        AL03 - Alice Smith
                                    </div>
                                    <p class="small mb-0">Shortcodes are auto-generated but can be customized.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Step 4: Preview -->
                <div class="step-content" id="step4" style="display: none;">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">
                                        <i class="fas fa-eye me-2"></i>
                                        Event Preview
                                    </h5>
                                    <p class="text-muted mb-0">Review your event details before creating</p>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <!-- Event Details Preview -->
                                        <div class="col-md-6">
                                            <div class="card bg-light mb-3">
                                                <div class="card-header">
                                                    <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i>Event Information</h6>
                                                </div>
                                                <div class="card-body">
                                                    <div class="row mb-2">
                                                        <div class="col-sm-4"><strong>Name:</strong></div>
                                                        <div class="col-sm-8" id="preview-name">-</div>
                                                    </div>
                                                    <div class="row mb-2">
                                                        <div class="col-sm-4"><strong>Code:</strong></div>
                                                        <div class="col-sm-8" id="preview-code">-</div>
                                                    </div>
                                                    <div class="row mb-2">
                                                        <div class="col-sm-4"><strong>Description:</strong></div>
                                                        <div class="col-sm-8" id="preview-description">-</div>
                                                    </div>
                                                    <div class="row mb-2">
                                                        <div class="col-sm-4"><strong>Start Date:</strong></div>
                                                        <div class="col-sm-8" id="preview-start-date">-</div>
                                                    </div>
                                                    <div class="row mb-2">
                                                        <div class="col-sm-4"><strong>End Date:</strong></div>
                                                        <div class="col-sm-8" id="preview-end-date">-</div>
                                                    </div>
                                                    <div class="row mb-2">
                                                        <div class="col-sm-4"><strong>Vote Price:</strong></div>
                                                        <div class="col-sm-8" id="preview-vote-price">-</div>
                                                    </div>
                                                    <div class="row mb-2">
                                                        <div class="col-sm-4"><strong>Visibility:</strong></div>
                                                        <div class="col-sm-8" id="preview-visibility">-</div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-4"><strong>Featured Image:</strong></div>
                                                        <div class="col-sm-8" id="preview-image">
                                                            <div id="preview-image-container" style="display: none;">
                                                                <img id="preview-image-display" src="" alt="Featured Image" 
                                                                     style="max-width: 150px; max-height: 100px; object-fit: cover;" 
                                                                     class="img-thumbnail">
                                                            </div>
                                                            <span id="preview-no-image" class="text-muted">No image uploaded</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Categories and Nominees Preview -->
                                        <div class="col-md-6">
                                            <div class="card bg-light mb-3">
                                                <div class="card-header">
                                                    <h6 class="mb-0"><i class="fas fa-tags me-2"></i>Categories</h6>
                                                </div>
                                                <div class="card-body">
                                                    <div id="preview-categories">
                                                        <p class="text-muted">No categories added</p>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="card bg-light">
                                                <div class="card-header">
                                                    <h6 class="mb-0"><i class="fas fa-users me-2"></i>Nominees</h6>
                                                </div>
                                                <div class="card-body">
                                                    <div id="preview-nominees">
                                                        <p class="text-muted">No nominees added</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Summary Stats -->
                                    <div class="row mt-3">
                                        <div class="col-12">
                                            <div class="alert alert-info">
                                                <h6 class="alert-heading"><i class="fas fa-chart-bar me-2"></i>Event Summary</h6>
                                                <div class="row">
                                                    <div class="col-md-3 text-center">
                                                        <div class="h4 mb-0" id="preview-category-count">0</div>
                                                        <small class="text-muted">Categories</small>
                                                    </div>
                                                    <div class="col-md-3 text-center">
                                                        <div class="h4 mb-0" id="preview-nominee-count">0</div>
                                                        <small class="text-muted">Nominees</small>
                                                    </div>
                                                    <div class="col-md-3 text-center">
                                                        <div class="h4 mb-0" id="preview-duration">0</div>
                                                        <small class="text-muted">Days Duration</small>
                                                    </div>
                                                    <div class="col-md-3 text-center">
                                                        <div class="h4 mb-0" id="preview-estimated-revenue">$0</div>
                                                        <small class="text-muted">Est. Revenue (100 votes)</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Navigation Buttons -->
                <div class="card mt-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <button type="button" class="btn btn-outline-secondary" id="prevBtn" onclick="changeStep(-1)" style="display: none;">
                                <i class="fas fa-arrow-left me-2"></i>Previous
                            </button>
                            <div></div>
                            <div>
                                <button type="button" class="btn btn-primary" id="nextBtn" onclick="changeStep(1)">
                                    Next <i class="fas fa-arrow-right ms-2"></i>
                                </button>
                                <?php if ((!$canCreateEvent && !($isEditing ?? false)) || $isDraftAtLimit): ?>
                                    <a href="<?= ORGANIZER_URL ?>/switch-plan" class="btn btn-warning" id="upgradeBtn" style="display: none;" 
                                       title="Upgrade plan to publish events">
                                        <i class="fas fa-crown me-2"></i>Upgrade to Publish
                                    </a>
                                <?php else: ?>
                                    <button type="submit" class="btn btn-success" id="submitBtn" style="display: none;" name="action" value="publish">
                                        <i class="fas fa-check me-2"></i><?= ($isEditing ?? false) ? 'Publish Event' : 'Create Event' ?>
                                    </button>
                                <?php endif; ?>
                                <button type="submit" class="btn btn-outline-primary" id="draftBtn" name="action" value="draft">
                                    <i class="fas fa-save me-2"></i>Save Draft
                                    <?php if (!$canCreateEvent && !($isEditing ?? false)): ?>
                                        <small class="d-block">Drafts don't count toward limit</small>
                                    <?php endif; ?>
                                </button>
                                <!-- Debug: Simple submit button that's always visible -->
                                <button type="submit" class="btn btn-warning" onclick="return confirm('Submit form with current data?')" name="action" value="debug">
                                    <i class="fas fa-bug me-2"></i>Debug Submit
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.step-indicator {
    text-align: center;
    flex: 1;
}

.step-circle {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: #e9ecef;
    color: #6c757d;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 8px;
    transition: all 0.3s ease;
}

.step-indicator.active .step-circle {
    background: #0d6efd;
    color: white;
}

.step-indicator.completed .step-circle {
    background: #198754;
    color: white;
}

.step-label {
    font-size: 0.875rem;
    font-weight: 500;
    color: #6c757d;
}

.step-indicator.active .step-label {
    color: #0d6efd;
}

.step-indicator.completed .step-label {
    color: #198754;
}

.category-item, .nominee-item {
    border: 1px solid #dee2e6;
    border-radius: 8px;
    padding: 1rem;
    margin-bottom: 1rem;
    background: #f8f9fa;
}

.category-item:hover, .nominee-item:hover {
    border-color: #0d6efd;
    background: #fff;
}
</style>

<script>
let currentStep = 1;
let categories = [];
let nominees = [];

// Step navigation
function changeStep(direction) {
    if (direction === 1 && !validateCurrentStep()) {
        return;
    }
    
    const newStep = currentStep + direction;
    if (newStep < 1 || newStep > 4) return;
    
    // Hide current step
    document.getElementById(`step${currentStep}`).style.display = 'none';
    document.querySelector(`[data-step="${currentStep}"]`).classList.remove('active');
    
    // Show new step
    currentStep = newStep;
    document.getElementById(`step${currentStep}`).style.display = 'block';
    document.querySelector(`[data-step="${currentStep}"]`).classList.add('active');
    
    // Update progress bar
    const progress = (currentStep / 4) * 100;
    document.getElementById('progressBar').style.width = progress + '%';
    
    // Update navigation buttons
    document.getElementById('prevBtn').style.display = currentStep === 1 ? 'none' : 'inline-block';
    document.getElementById('nextBtn').style.display = currentStep === 4 ? 'none' : 'inline-block';
    document.getElementById('submitBtn').style.display = currentStep === 4 ? 'inline-block' : 'none';
    
    // Mark completed steps
    for (let i = 1; i < currentStep; i++) {
        document.querySelector(`[data-step="${i}"]`).classList.add('completed');
    }
    
    // If moving to preview step, populate preview data
    if (currentStep === 4) {
        populatePreview();
    }
}

// Validate current step
function validateCurrentStep() {
    if (currentStep === 1) {
        const name = document.getElementById('name').value;
        const startDate = document.getElementById('start_date').value;
        const endDate = document.getElementById('end_date').value;
        
        if (!name || !startDate || !endDate) {
            alert('Please fill in all required fields');
            return false;
        }
    } else if (currentStep === 2) {
        if (categories.length === 0) {
            alert('Please add at least one category');
            return false;
        }
    } else if (currentStep === 3) {
        if (nominees.length === 0) {
            alert('Please add at least one nominee');
            return false;
        }
        // Check that each nominee has at least one category
        for (let nominee of nominees) {
            if (!nominee.categories || nominee.categories.length === 0) {
                alert(`Please assign at least one category to "${nominee.name}"`);
                return false;
            }
        }
    }
    return true;
}

// Populate preview step with current data
function populatePreview() {
    // Event details
    document.getElementById('preview-name').textContent = document.getElementById('name').value || '-';
    document.getElementById('preview-code').textContent = document.getElementById('code').value || '-';
    document.getElementById('preview-description').textContent = document.getElementById('description').value || 'No description';
    
    // Format dates
    const startDate = document.getElementById('start_date').value;
    const endDate = document.getElementById('end_date').value;
    document.getElementById('preview-start-date').textContent = startDate ? new Date(startDate).toLocaleString() : '-';
    document.getElementById('preview-end-date').textContent = endDate ? new Date(endDate).toLocaleString() : '-';
    
    // Vote price
    const votePrice = document.getElementById('vote_price').value || '0';
    document.getElementById('preview-vote-price').textContent = '$' + parseFloat(votePrice).toFixed(2);
    
    // Visibility
    const visibility = document.getElementById('visibility').value;
    document.getElementById('preview-visibility').textContent = visibility.charAt(0).toUpperCase() + visibility.slice(1);
    
    // Featured image
    const imageInput = document.getElementById('featured_image');
    const previewImageContainer = document.getElementById('preview-image-container');
    const previewImageDisplay = document.getElementById('preview-image-display');
    const previewNoImage = document.getElementById('preview-no-image');
    
    if (imageInput.files && imageInput.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            previewImageDisplay.src = e.target.result;
            previewImageContainer.style.display = 'block';
            previewNoImage.style.display = 'none';
        };
        reader.readAsDataURL(imageInput.files[0]);
    } else {
        previewImageContainer.style.display = 'none';
        previewNoImage.style.display = 'inline';
    }
    
    // Categories
    const categoriesContainer = document.getElementById('preview-categories');
    if (categories.length > 0) {
        const categoriesHtml = categories.map(cat => `
            <div class="badge bg-primary me-2 mb-2">${cat.name}</div>
        `).join('');
        categoriesContainer.innerHTML = categoriesHtml;
    } else {
        categoriesContainer.innerHTML = '<p class="text-muted">No categories added</p>';
    }
    
    // Nominees
    const nomineesContainer = document.getElementById('preview-nominees');
    if (nominees.length > 0) {
        const nomineesHtml = nominees.map(nominee => {
            const assignedCategories = nominee.categories ? 
                nominee.categories.map(catId => {
                    const cat = categories.find(c => c.id == catId);
                    return cat ? cat.name : 'Unknown';
                }).join(', ') : 'No categories';
            
            return `
                <div class="border rounded p-2 mb-2">
                    <strong>${nominee.name}</strong>
                    <br><small class="text-muted">Categories: ${assignedCategories}</small>
                    ${nominee.bio ? `<br><small>${nominee.bio}</small>` : ''}
                </div>
            `;
        }).join('');
        nomineesContainer.innerHTML = nomineesHtml;
    } else {
        nomineesContainer.innerHTML = '<p class="text-muted">No nominees added</p>';
    }
    
    // Summary stats
    document.getElementById('preview-category-count').textContent = categories.length;
    document.getElementById('preview-nominee-count').textContent = nominees.length;
    
    // Calculate duration
    if (startDate && endDate) {
        const start = new Date(startDate);
        const end = new Date(endDate);
        const diffTime = Math.abs(end - start);
        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
        document.getElementById('preview-duration').textContent = diffDays;
    } else {
        document.getElementById('preview-duration').textContent = '0';
    }
    
    // Estimated revenue (100 votes * vote price)
    const estimatedRevenue = (100 * parseFloat(votePrice)).toFixed(2);
    document.getElementById('preview-estimated-revenue').textContent = '$' + estimatedRevenue;
}

// Category management
function addCategory() {
    const categoryName = prompt('Enter category name:');
    if (!categoryName) return;
    
    const category = {
        id: Date.now(),
        name: categoryName,
        description: ''
    };
    
    categories.push(category);
    renderCategories();
}

function removeCategory(categoryId) {
    categories = categories.filter(c => c.id !== categoryId);
    renderCategories();
}

function renderCategories() {
    const container = document.getElementById('categoriesContainer');
    const noMessage = document.getElementById('noCategoriesMessage');
    
    // Check if elements exist
    if (!container || !noMessage) {
        console.error('Required elements not found');
        return;
    }
    
    if (categories.length === 0) {
        noMessage.style.display = 'block';
        container.innerHTML = '';
        return;
    }
    
    noMessage.style.display = 'none';
    
    const html = categories.map(category => `
        <div class="category-item" data-category-id="${category.id}">
            <div class="d-flex justify-content-between align-items-start">
                <div class="flex-grow-1">
                    <h6 class="mb-2">${category.name}</h6>
                    <input type="hidden" name="categories[${category.id}][name]" value="${category.name}">
                    <textarea class="form-control form-control-sm" 
                              name="categories[${category.id}][description]" 
                              placeholder="Category description (optional)" 
                              rows="2" 
                              onchange="updateCategoryDescription(${category.id}, this.value)">${category.description}</textarea>
                </div>
                <button type="button" class="btn btn-outline-danger btn-sm ms-2" onclick="removeCategory(${category.id})">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>
    `).join('');
    
    container.innerHTML = html;
}

function updateCategoryDescription(categoryId, description) {
    const category = categories.find(c => c.id === categoryId);
    if (category) {
        category.description = description;
    }
}

function updateNomineeBio(nomineeId, bio) {
    const nominee = nominees.find(n => n.id === nomineeId);
    if (nominee) {
        nominee.bio = bio;
    }
}

function updateNomineeCategories(nomineeId, selectElement) {
    const nominee = nominees.find(n => n.id === nomineeId);
    if (nominee) {
        nominee.categories = Array.from(selectElement.selectedOptions).map(option => parseInt(option.value));
    }
}

function previewNomineePhoto(nomineeId, fileInput) {
    const previewDiv = document.getElementById(`nominee-preview-${nomineeId}`);
    const previewImg = document.getElementById(`nominee-preview-img-${nomineeId}`);
    const currentImageDiv = document.getElementById(`current-nominee-image-${nomineeId}`);
    const noImageDiv = document.getElementById(`no-nominee-image-${nomineeId}`);
    
    if (fileInput.files && fileInput.files[0]) {
        const file = fileInput.files[0];
        
        // Validate file size (2MB max)
        if (file.size > 2 * 1024 * 1024) {
            alert('Photo must be less than 2MB');
            fileInput.value = '';
            previewDiv.style.display = 'none';
            if (currentImageDiv) currentImageDiv.style.opacity = '1';
            if (noImageDiv) noImageDiv.style.opacity = '1';
            return;
        }
        
        // Validate file type
        if (!file.type.startsWith('image/')) {
            alert('Please select an image file');
            fileInput.value = '';
            previewDiv.style.display = 'none';
            if (currentImageDiv) currentImageDiv.style.opacity = '1';
            if (noImageDiv) noImageDiv.style.opacity = '1';
            return;
        }
        
        // Update nominee data
        const nominee = nominees.find(n => n.id === nomineeId);
        if (nominee) {
            nominee.photo_file = file;
        }
        
        // Show preview
        const reader = new FileReader();
        reader.onload = function(e) {
            previewImg.src = e.target.result;
            previewDiv.style.display = 'block';
            if (currentImageDiv) currentImageDiv.style.opacity = '0.5';
            if (noImageDiv) noImageDiv.style.opacity = '0.5';
        };
        reader.readAsDataURL(file);
    } else {
        // No file selected, hide preview
        previewDiv.style.display = 'none';
        if (currentImageDiv) currentImageDiv.style.opacity = '1';
        if (noImageDiv) noImageDiv.style.opacity = '1';
    }
}

// Nominee management
function addNominee() {
    if (categories.length === 0) {
        alert('Please add categories first');
        return;
    }
    
    const nomineeName = prompt('Enter nominee name:');
    if (!nomineeName) return;
    
    const nominee = {
        id: Date.now(),
        name: nomineeName,
        bio: '',
        image_url: '',
        categories: []
    };
    
    nominees.push(nominee);
    renderNominees();
}

function removeNominee(nomineeId) {
    nominees = nominees.filter(n => n.id !== nomineeId);
    renderNominees();
}

function renderNominees() {
    const container = document.getElementById('nomineesContainer');
    const noMessage = document.getElementById('noNomineesMessage');
    
    // Check if elements exist
    if (!container || !noMessage) {
        console.error('Required elements not found');
        return;
    }
    
    if (nominees.length === 0) {
        noMessage.style.display = 'block';
        container.innerHTML = '';
        return;
    }
    
    noMessage.style.display = 'none';
    
    // Store current file inputs before re-rendering
    const fileInputs = {};
    nominees.forEach(nominee => {
        const fileInput = document.querySelector(`input[name="nominee_photo_${nominee.id}"]`);
        if (fileInput && fileInput.files && fileInput.files[0]) {
            fileInputs[nominee.id] = fileInput.files[0];
        }
    });
    
    const html = nominees.map(nominee => {
        // Create category options with pre-selection for this nominee
        const categoryOptions = categories.map(cat => {
            // Both should now be strings, but double-check for safety
            const isSelected = nominee.categories && nominee.categories.includes(cat.id);
            return `<option value="${cat.id}" ${isSelected ? 'selected' : ''}>${cat.name}</option>`;
        }).join('');
        
        return `
            <div class="nominee-item" data-nominee-id="${nominee.id}">
                <div class="d-flex justify-content-between align-items-start">
                    <div class="flex-grow-1">
                        <h6 class="mb-2">${nominee.name}</h6>
                        <input type="hidden" name="nominees[${nominee.id}][name]" value="${nominee.name}">
                        
                        <div class="row mb-2">
                            <div class="col-md-4">
                                <label class="form-label small">Bio</label>
                                <textarea class="form-control form-control-sm" 
                                          name="nominees[${nominee.id}][bio]" 
                                          placeholder="Nominee bio (optional)" 
                                          rows="2" 
                                          onchange="updateNomineeBio(${nominee.id}, this.value)">${nominee.bio || ''}</textarea>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small">Photo</label>
                                <input type="file" class="form-control form-control-sm" 
                                       name="nominee_photo_${nominee.id}" 
                                       accept="image/*"
                                       onchange="previewNomineePhoto(${nominee.id}, this)">
                                <div class="form-text">JPG, PNG (max 2MB)</div>
                                
                                ${nominee.image_url ? `
                                    <div class="mt-2" id="current-nominee-image-${nominee.id}">
                                        <small class="text-success">✓ Current photo</small>
                                        <div class="mt-1">
                                            <img src="${getImageUrl(nominee.image_url)}" 
                                                 alt="Current nominee photo" 
                                                 style="width: 60px; height: 60px; object-fit: cover;" 
                                                 class="img-thumbnail">
                                        </div>
                                    </div>
                                ` : `
                                    <div class="mt-2" id="no-nominee-image-${nominee.id}">
                                        <small class="text-muted">No photo uploaded</small>
                                        <div class="mt-1">
                                            <div style="width: 60px; height: 60px; background-color: #f8f9fa; border: 1px dashed #dee2e6; display: flex; align-items: center; justify-content: center; border-radius: 4px;">
                                                <i class="fas fa-user text-muted"></i>
                                            </div>
                                        </div>
                                    </div>
                                `}
                                
                                <div id="nominee-preview-${nominee.id}" class="mt-2" style="display: none;">
                                    <small class="text-info">New photo preview:</small>
                                    <div class="mt-1">
                                        <img id="nominee-preview-img-${nominee.id}" src="" alt="Preview" 
                                             style="width: 60px; height: 60px; object-fit: cover;" 
                                             class="img-thumbnail">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small">Categories</label>
                                <select class="form-select form-select-sm" 
                                        name="nominees[${nominee.id}][categories][]" 
                                        multiple 
                                        onchange="updateNomineeCategories(${nominee.id}, this)">
                                    ${categoryOptions}
                                </select>
                                <div class="form-text">Hold Ctrl to select multiple</div>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn btn-outline-danger btn-sm ms-2" onclick="removeNominee(${nominee.id})">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        `;
    }).join('');
    
    container.innerHTML = html;
    
    // Restore file inputs and previews after re-rendering
    Object.keys(fileInputs).forEach(nomineeId => {
        const file = fileInputs[nomineeId];
        const fileInput = document.querySelector(`input[name="nominee_photo_${nomineeId}"]`);
        
        if (fileInput && file) {
            // Create a new FileList with the preserved file
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(file);
            fileInput.files = dataTransfer.files;
            
            // Update nominee data
            const nominee = nominees.find(n => n.id == nomineeId);
            if (nominee) {
                nominee.photo_file = file;
            }
            
            // Show preview
            const previewDiv = document.getElementById(`nominee-preview-${nomineeId}`);
            const previewImg = document.getElementById(`nominee-preview-img-${nomineeId}`);
            const currentImageDiv = document.getElementById(`current-nominee-image-${nomineeId}`);
            const noImageDiv = document.getElementById(`no-nominee-image-${nomineeId}`);
            
            if (previewDiv && previewImg) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    previewDiv.style.display = 'block';
                    if (currentImageDiv) currentImageDiv.style.opacity = '0.5';
                    if (noImageDiv) noImageDiv.style.opacity = '0.5';
                };
                reader.readAsDataURL(file);
            }
        }
    });
}

// Event image preview
function previewEventImage(input) {
    const previewDiv = document.getElementById('image-preview');
    const previewImg = document.getElementById('preview-img');
    const currentImageDiv = document.getElementById('current-image');
    
    if (input.files && input.files[0]) {
        const file = input.files[0];
        
        // Validate file size (5MB max)
        if (file.size > 5 * 1024 * 1024) {
            alert('Image must be less than 5MB');
            input.value = '';
            previewDiv.style.display = 'none';
            return;
        }
        
        // Validate file type
        if (!file.type.startsWith('image/')) {
            alert('Please select an image file');
            input.value = '';
            previewDiv.style.display = 'none';
            return;
        }
        
        const reader = new FileReader();
        reader.onload = function(e) {
            previewImg.src = e.target.result;
            previewDiv.style.display = 'block';
            if (currentImageDiv) {
                currentImageDiv.style.opacity = '0.5';
            }
        };
        reader.readAsDataURL(file);
    } else {
        previewDiv.style.display = 'none';
        if (currentImageDiv) {
            currentImageDiv.style.opacity = '1';
        }
    }
}

// Auto-generate event code from name (only if not editing)
<?php if (!($isEditing ?? false)): ?>
document.getElementById('name').addEventListener('input', function() {
    const name = this.value;
    const code = name.toUpperCase()
                    .replace(/[^A-Z0-9]/g, '')
                    .substring(0, 10);
    document.getElementById('code').value = code;
});
<?php endif; ?>

// Pre-populate data when editing
<?php if ($isEditing ?? false): ?>
// Wait for DOM to be ready before pre-populating
document.addEventListener('DOMContentLoaded', function() {
    // Add a small delay to ensure all elements are rendered
    setTimeout(function() {
        // Pre-populate categories
        <?php if (!empty($categories)): ?>
        categories = <?= json_encode(array_map(function($cat) {
            return [
                'id' => strval($cat['id']), // Convert to string for consistency
                'name' => $cat['name'],
                'description' => $cat['description'] ?? ''
            ];
        }, $categories)) ?>;
        console.log('Pre-populating categories:', categories);
        renderCategories();
        <?php endif; ?>

        // Pre-populate nominees
        <?php if (!empty($nominees)): ?>
        nominees = <?= json_encode(array_map(function($nominee) {
            return [
                'id' => $nominee['id'],
                'name' => $nominee['name'],
                'bio' => $nominee['bio'] ?? '',
                'image_url' => $nominee['image_url'] ?? '',
                'categories' => array_map('strval', $nominee['categories'] ?? []) // Convert to strings
            ];
        }, $nominees)) ?>;
        console.log('Pre-populating nominees:', nominees);
        renderNominees();
        <?php endif; ?>
    }, 100); // 100ms delay
});
<?php endif; ?>

// Remove duplicate function - already defined above

// Ensure buttons are visible on page load if needed
document.addEventListener('DOMContentLoaded', function() {
    // Check if we're on the final step and show appropriate buttons
    const currentStepElement = document.querySelector('.step-indicator.active');
    if (currentStepElement && currentStepElement.dataset.step === '4') {
        const submitBtn = document.getElementById('submitBtn');
        const upgradeBtn = document.getElementById('upgradeBtn');
        const nextBtn = document.getElementById('nextBtn');
        
        if (submitBtn) {
            submitBtn.style.display = 'inline-block';
            nextBtn.style.display = 'none';
        }
        if (upgradeBtn) {
            upgradeBtn.style.display = 'inline-block';
            nextBtn.style.display = 'none';
        }
    }
});
</script>
