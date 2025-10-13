<?php include __DIR__ . '/../layout/public_header.php'; ?>

<!-- Hero Section -->
<div class="hero-section bg-gradient-primary text-white py-3 mb-3">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8 mx-auto text-center">
                <h1 class="h2 fw-bold mb-2">
                    <i class="fas fa-hashtag me-2"></i>
                    Vote by Shortcode
                </h1>
                <p class="mb-0">Enter a nominee's shortcode to quickly find and vote for your favorite contestant!</p>
            </div>
        </div>
    </div>
</div>

<!-- Shortcode Search Section -->
<div class="container mb-3">
    <div class="row">
        <!-- Search Column -->
        <div class="col-lg-5">
            <div class="card shadow border-0 h-100">
                <div class="card-body p-4">
                    <div class="text-center mb-3">
                        <i class="fas fa-search fa-2x text-primary mb-2"></i>
                        <h4 class="card-title mb-1">Find Nominee</h4>
                        <p class="text-muted small mb-0">Enter shortcode to search</p>
                    </div>

                    <!-- Search Form -->
                    <form id="shortcodeSearchForm" class="mb-3">
                        <div class="input-group mb-2">
                            <span class="input-group-text bg-primary text-white">
                                <i class="fas fa-hashtag"></i>
                            </span>
                            <input type="text" 
                                   class="form-control" 
                                   id="shortcodeInput" 
                                   placeholder="e.g., AM06" 
                                   autocomplete="off"
                                   maxlength="6"
                                   style="text-transform: uppercase;">
                            <button class="btn btn-primary" type="submit" id="searchBtn">
                                <i class="fas fa-search me-1"></i>Search
                            </button>
                        </div>
                        <div class="form-text text-center small">
                            <i class="fas fa-info-circle me-1"></i>
                            Usually 3-6 characters
                        </div>
                    </form>

                    <!-- Loading Indicator -->
                    <div id="loadingIndicator" class="text-center d-none">
                        <div class="spinner-border spinner-border-sm text-primary" role="status">
                            <span class="visually-hidden">Searching...</span>
                        </div>
                        <p class="mt-1 text-muted small">Searching...</p>
                    </div>

                    <!-- Error Message -->
                    <div id="errorMessage" class="alert alert-danger d-none" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <span id="errorText"></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Results Column -->
        <div class="col-lg-7">
            <div id="searchResults" class="d-none">
                <!-- Results will be populated here -->
            </div>
            <div id="noResultsPlaceholder" class="card border-2 border-dashed h-100">
                <div class="card-body d-flex align-items-center justify-content-center text-center p-4">
                    <div>
                        <i class="fas fa-user-friends fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">Nominee Details</h5>
                        <p class="text-muted small mb-0">Enter a shortcode to see nominee information</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- How It Works Section -->
<div class="container mb-3">
    <div class="row">
        <div class="col-12">
            <div class="card bg-light border-0">
                <div class="card-body p-3">
                    <h5 class="text-center mb-3">
                        <i class="fas fa-question-circle text-primary me-1"></i>
                        How It Works
                    </h5>
                    <div class="row text-center">
                        <div class="col-6 col-md-3 mb-2">
                            <div class="step-icon bg-primary text-white rounded-circle mx-auto mb-2" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-hashtag"></i>
                            </div>
                            <h6 class="small mb-1">1. Enter Code</h6>
                            <p class="text-muted" style="font-size: 0.75rem;">Type shortcode above</p>
                        </div>
                        <div class="col-6 col-md-3 mb-2">
                            <div class="step-icon bg-success text-white rounded-circle mx-auto mb-2" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-user-check"></i>
                            </div>
                            <h6 class="small mb-1">2. Confirm</h6>
                            <p class="text-muted" style="font-size: 0.75rem;">Review nominee details</p>
                        </div>
                        <div class="col-6 col-md-3 mb-2">
                            <div class="step-icon bg-warning text-white rounded-circle mx-auto mb-2" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-vote-yea"></i>
                            </div>
                            <h6 class="small mb-1">3. Vote</h6>
                            <p class="text-muted" style="font-size: 0.75rem;">Choose quantity & pay</p>
                        </div>
                        <div class="col-6 col-md-3 mb-2">
                            <div class="step-icon bg-info text-white rounded-circle mx-auto mb-2" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <h6 class="small mb-1">4. Done</h6>
                            <p class="text-muted" style="font-size: 0.75rem;">Vote confirmed</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.hero-section {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
}

.card {
    border-radius: 12px;
}

.input-group .form-control {
    border-radius: 0 8px 8px 0;
}

.input-group .input-group-text {
    border-radius: 8px 0 0 8px;
}

.input-group .btn {
    border-radius: 0 8px 8px 0;
}

#shortcodeInput {
    font-weight: 600;
    letter-spacing: 1px;
}

.nominee-card {
    border: 2px solid #e9ecef;
    border-radius: 12px;
    transition: all 0.3s ease;
}

.nominee-card:hover {
    border-color: #667eea;
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.2);
}

.vote-btn {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    border-radius: 20px;
    padding: 8px 20px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    transition: all 0.3s ease;
}

.vote-btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
}

.step-icon {
    transition: transform 0.3s ease;
}

.step-icon:hover {
    transform: scale(1.1);
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('shortcodeSearchForm');
    const input = document.getElementById('shortcodeInput');
    const searchBtn = document.getElementById('searchBtn');
    const loadingIndicator = document.getElementById('loadingIndicator');
    const searchResults = document.getElementById('searchResults');
    const errorMessage = document.getElementById('errorMessage');
    const errorText = document.getElementById('errorText');

    // Helper function to get proper image URL
    function getImageUrl(imagePath) {
        console.log('getImageUrl input:', imagePath);
        
        if (!imagePath) {
            console.log('getImageUrl: No image path provided');
            return null;
        }
        
        const appUrl = '<?= APP_URL ?>';
        console.log('APP_URL:', appUrl);
        
        // If it's already a full URL, return as is
        if (imagePath.startsWith('http://') || imagePath.startsWith('https://')) {
            console.log('getImageUrl: Already full URL');
            return imagePath;
        }
        
        // If it starts with APP_URL, return as is
        if (imagePath.startsWith(appUrl)) {
            console.log('getImageUrl: Already has APP_URL');
            return imagePath;
        }
        
        // Clean up the path - remove leading slashes and normalize
        let cleanPath = imagePath.replace(/^\/+/, '');
        
        // If it's a relative path starting with /, add APP_URL
        if (imagePath.startsWith('/')) {
            const result = appUrl + imagePath;
            console.log('getImageUrl: Added APP_URL to absolute path:', result);
            return result;
        }
        
        // Otherwise, assume it's a relative path and add APP_URL with leading slash
        const result = appUrl + '/' + cleanPath;
        console.log('getImageUrl: Added APP_URL to relative path:', result);
        return result;
    }

    // Auto-uppercase input
    input.addEventListener('input', function() {
        this.value = this.value.toUpperCase();
    });

    // Handle form submission
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        searchNominee();
    });

    function searchNominee() {
        const shortcode = input.value.trim();
        
        if (!shortcode) {
            showEnhancedError('Please enter a shortcode', '');
            return;
        }

        if (shortcode.length < 2) {
            showEnhancedError('Shortcode must be at least 2 characters long', shortcode);
            return;
        }

        // Show loading and clear previous states
        showLoading();
        hideError();
        hideResults();
        hidePlaceholder();

        // Make AJAX request
        console.log('Making request to:', '<?= APP_URL ?>/api/shortcode-lookup');
        fetch('<?= APP_URL ?>/api/shortcode-lookup', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ shortcode: shortcode })
        })
        .then(response => {
            console.log('Response status:', response.status);
            console.log('Response headers:', response.headers);
            return response.text(); // Get as text first to see what we're getting
        })
        .then(text => {
            console.log('Raw response:', text);
            try {
                const data = JSON.parse(text);
                hideLoading();
                
                if (data.success) {
                    showResults(data.nominee);
                } else {
                    showEnhancedError(data.message || 'Nominee not found', shortcode);
                }
            } catch (e) {
                console.error('JSON parse error:', e);
                console.error('Response was:', text);
                hideLoading();
                showError('Server returned invalid response. Check console for details.');
            }
        })
        .catch(error => {
            hideLoading();
            showError('An error occurred while searching. Please try again.');
            console.error('Fetch error:', error);
        });
    }

    function showLoading() {
        loadingIndicator.classList.remove('d-none');
        searchBtn.disabled = true;
        searchBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Searching...';
    }

    function hideLoading() {
        loadingIndicator.classList.add('d-none');
        searchBtn.disabled = false;
        searchBtn.innerHTML = '<i class="fas fa-search me-2"></i>Search';
    }

    function showResults(nominee) {
        console.log('Nominee data:', nominee);
        console.log('Original image_url:', nominee.image_url);
        
        const processedImageUrl = getImageUrl(nominee.image_url);
        console.log('Processed image_url:', processedImageUrl);
        
        const resultsHtml = `
            <div class="card shadow border-0 h-100">
                <div class="card-body p-4">
                    <div class="text-center mb-3">
                        ${nominee.image_url ? 
                            `<img src="${processedImageUrl}" alt="${nominee.name}" class="img-fluid rounded-circle mb-3" style="width: 100px; height: 100px; object-fit: cover;" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                             <div class="bg-light rounded-circle align-items-center justify-content-center mb-3" style="width: 100px; height: 100px; margin: 0 auto; display: none;">
                                <i class="fas fa-user fa-2x text-muted"></i>
                             </div>` :
                            `<div class="bg-light rounded-circle d-flex align-items-center justify-content-center mb-3" style="width: 100px; height: 100px; margin: 0 auto;">
                                <i class="fas fa-user fa-2x text-muted"></i>
                            </div>`
                        }
                        <h4 class="mb-2">${nominee.name}</h4>
                        <span class="badge bg-primary mb-3">${nominee.short_code}</span>
                    </div>
                    
                    <div class="mb-3">
                        <p class="text-muted mb-2 small">
                            <i class="fas fa-trophy me-2"></i>
                            <strong>Event:</strong> ${nominee.event_name}
                        </p>
                        <p class="text-muted mb-2 small">
                            <i class="fas fa-tag me-2"></i>
                            <strong>Category:</strong> ${nominee.category_name}
                        </p>
                        ${nominee.bio ? `<p class="text-muted small"><i class="fas fa-info-circle me-2"></i>${nominee.bio.substring(0, 120)}${nominee.bio.length > 120 ? '...' : ''}</p>` : ''}
                    </div>
                    
                    <div class="text-center">
                        <button class="btn btn-primary vote-btn" onclick="proceedToVote('${nominee.contestant_id}', '${nominee.category_id}', '${nominee.event_id}')">
                            <i class="fas fa-vote-yea me-2"></i>
                            Vote Now
                        </button>
                        <p class="text-muted small mt-2 mb-0">
                            Vote Price: <strong>$${nominee.vote_price}</strong>
                        </p>
                    </div>
                </div>
            </div>
        `;
        
        // Hide placeholder and show results
        document.getElementById('noResultsPlaceholder').classList.add('d-none');
        searchResults.innerHTML = resultsHtml;
        searchResults.classList.remove('d-none');
    }

    function showError(message) {
        errorText.textContent = message;
        errorMessage.classList.remove('d-none');
    }

    function showEnhancedError(message, searchedCode) {
        // Hide results and show placeholder
        hideResults();
        showPlaceholder();
        
        const isNotFound = message.toLowerCase().includes('not found') || message.toLowerCase().includes('no nominee found');
        
        if (isNotFound) {
            const enhancedMessage = `
                <div class="mb-2">
                    <strong>Shortcode "${searchedCode}" not found</strong>
                </div>
                <div class="mb-2">
                    <small class="text-muted">
                        This could happen if:
                    </small>
                    <ul class="small text-muted mt-1 mb-0" style="padding-left: 1.2rem;">
                        <li>Shortcode was typed incorrectly</li>
                        <li>Nominee is not in any active event</li>
                        <li>Event voting period has ended</li>
                        <li>Shortcode belongs to different event</li>
                    </ul>
                </div>
                <div class="mt-2">
                    <small class="text-info">
                        <i class="fas fa-lightbulb me-1"></i>
                        <strong>Tip:</strong> Double-check spelling and try again
                    </small>
                </div>
                <div class="mt-2 text-center">
                    <button class="btn btn-outline-primary btn-sm" onclick="clearSearchAndFocus()">
                        <i class="fas fa-redo me-1"></i>
                        Try Again
                    </button>
                </div>
            `;
            errorMessage.innerHTML = `
                <i class="fas fa-search me-2"></i>
                ${enhancedMessage}
            `;
        } else {
            errorMessage.innerHTML = `
                <i class="fas fa-exclamation-triangle me-2"></i>
                <span>${message}</span>
            `;
        }
        
        // Ensure error message stays visible
        errorMessage.classList.remove('d-none');
    }

    function hideError() {
        errorMessage.classList.add('d-none');
    }

    function hideResults() {
        searchResults.classList.add('d-none');
    }

    function showPlaceholder() {
        document.getElementById('noResultsPlaceholder').classList.remove('d-none');
    }

    function hidePlaceholder() {
        document.getElementById('noResultsPlaceholder').classList.add('d-none');
    }

    // Focus on input when page loads
    input.focus();
});

function clearSearchAndFocus() {
    const input = document.getElementById('shortcodeInput');
    const errorMessage = document.getElementById('errorMessage');
    const searchResults = document.getElementById('searchResults');
    const placeholder = document.getElementById('noResultsPlaceholder');
    
    // Clear input and hide messages
    input.value = '';
    errorMessage.classList.add('d-none');
    searchResults.classList.add('d-none');
    
    // Show placeholder
    placeholder.classList.remove('d-none');
    
    // Focus on input for new search
    input.focus();
}

function proceedToVote(contestantId, categoryId, eventId) {
    // Redirect to the voting page with the nominee information
    window.location.href = `<?= APP_URL ?>/vote?contestant_id=${contestantId}&category_id=${categoryId}&event_id=${eventId}&source=shortcode`;
}
</script>

<?php include __DIR__ . '/../layout/public_footer.php'; ?>
