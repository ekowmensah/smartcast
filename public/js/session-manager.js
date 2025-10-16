/**
 * Session Manager - Handles automatic logout after idle time
 * Shows warning prompt and manages session expiry
 */
class SessionManager {
    constructor(options = {}) {
        this.idleTime = options.idleTime || 10 * 60 * 1000; // 10 minutes in milliseconds
        this.warningTime = options.warningTime || 2 * 60 * 1000; // 2 minutes before expiry
        this.checkInterval = options.checkInterval || 30 * 1000; // Check every 30 seconds
        this.logoutUrl = options.logoutUrl || '/logout';
        this.loginUrl = options.loginUrl || '/login';
        
        this.lastActivity = Date.now();
        this.warningShown = false;
        this.intervalId = null;
        this.warningModal = null;
        
        this.init();
    }
    
    init() {
        // Only initialize if user is logged in
        if (!this.isUserLoggedIn()) {
            return;
        }
        
        this.createWarningModal();
        this.bindActivityEvents();
        this.startMonitoring();
        
        console.log('Session Manager initialized - Idle timeout: ' + (this.idleTime / 60000) + ' minutes');
    }
    
    isUserLoggedIn() {
        // Check if user session exists (you can customize this check)
        return document.body.dataset.userLoggedIn === 'true' || 
               document.querySelector('.user-avatar') !== null ||
               localStorage.getItem('user_logged_in') === 'true';
    }
    
    bindActivityEvents() {
        const events = ['mousedown', 'mousemove', 'keypress', 'scroll', 'touchstart', 'click'];
        
        events.forEach(event => {
            document.addEventListener(event, () => {
                this.updateActivity();
            }, true);
        });
    }
    
    updateActivity() {
        this.lastActivity = Date.now();
        
        // Hide warning if it's shown and user becomes active
        if (this.warningShown) {
            this.hideWarning();
        }
    }
    
    startMonitoring() {
        this.intervalId = setInterval(() => {
            this.checkIdleTime();
        }, this.checkInterval);
    }
    
    stopMonitoring() {
        if (this.intervalId) {
            clearInterval(this.intervalId);
            this.intervalId = null;
        }
    }
    
    checkIdleTime() {
        const now = Date.now();
        const timeSinceLastActivity = now - this.lastActivity;
        
        // Check if it's time to show warning
        if (timeSinceLastActivity >= (this.idleTime - this.warningTime) && !this.warningShown) {
            this.showWarning();
        }
        
        // Check if it's time to logout
        if (timeSinceLastActivity >= this.idleTime) {
            this.performLogout();
        }
    }
    
    createWarningModal() {
        const modalHtml = `
            <div id="session-warning-modal" class="session-modal-overlay" style="display: none;">
                <div class="session-modal">
                    <div class="session-modal-header">
                        <i class="fas fa-clock text-warning"></i>
                        <h4>Session Expiring Soon</h4>
                    </div>
                    <div class="session-modal-body">
                        <p>Your session will expire in <span id="countdown-timer" class="countdown-text">2:00</span> due to inactivity.</p>
                        <p>Would you like to stay logged in?</p>
                    </div>
                    <div class="session-modal-footer">
                        <button id="stay-logged-in" class="btn btn-primary">
                            <i class="fas fa-check"></i> Stay Logged In
                        </button>
                        <button id="logout-now" class="btn btn-outline-secondary">
                            <i class="fas fa-sign-out-alt"></i> Logout Now
                        </button>
                    </div>
                </div>
            </div>
        `;
        
        document.body.insertAdjacentHTML('beforeend', modalHtml);
        this.warningModal = document.getElementById('session-warning-modal');
        
        // Bind modal events
        document.getElementById('stay-logged-in').addEventListener('click', () => {
            this.extendSession();
        });
        
        document.getElementById('logout-now').addEventListener('click', () => {
            this.performLogout();
        });
        
        // Add modal styles
        this.addModalStyles();
    }
    
    addModalStyles() {
        const styles = `
            <style id="session-manager-styles">
                .session-modal-overlay {
                    position: fixed;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    background: rgba(0, 0, 0, 0.7);
                    backdrop-filter: blur(5px);
                    z-index: 10000;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    animation: fadeIn 0.3s ease;
                }
                
                .session-modal {
                    background: white;
                    border-radius: 1rem;
                    box-shadow: 0 25px 50px rgba(0, 0, 0, 0.3);
                    max-width: 450px;
                    width: 90%;
                    animation: slideIn 0.3s ease;
                    overflow: hidden;
                }
                
                .session-modal-header {
                    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                    color: white;
                    padding: 1.5rem;
                    text-align: center;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    gap: 0.75rem;
                }
                
                .session-modal-header i {
                    font-size: 1.5rem;
                }
                
                .session-modal-header h4 {
                    margin: 0;
                    font-weight: 600;
                }
                
                .session-modal-body {
                    padding: 2rem;
                    text-align: center;
                    color: #374151;
                    line-height: 1.6;
                }
                
                .session-modal-body p {
                    margin-bottom: 1rem;
                }
                
                .countdown-text {
                    font-weight: 700;
                    color: #dc2626;
                    font-size: 1.25rem;
                    background: #fee2e2;
                    padding: 0.25rem 0.75rem;
                    border-radius: 0.5rem;
                    display: inline-block;
                    min-width: 60px;
                }
                
                .session-modal-footer {
                    padding: 1.5rem;
                    background: #f9fafb;
                    display: flex;
                    gap: 1rem;
                    justify-content: center;
                }
                
                .session-modal-footer .btn {
                    padding: 0.75rem 1.5rem;
                    border-radius: 0.75rem;
                    font-weight: 600;
                    border: none;
                    cursor: pointer;
                    transition: all 0.3s ease;
                    display: flex;
                    align-items: center;
                    gap: 0.5rem;
                }
                
                .session-modal-footer .btn-primary {
                    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                    color: white;
                }
                
                .session-modal-footer .btn-primary:hover {
                    transform: translateY(-2px);
                    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
                }
                
                .session-modal-footer .btn-outline-secondary {
                    background: transparent;
                    color: #6b7280;
                    border: 2px solid #e5e7eb;
                }
                
                .session-modal-footer .btn-outline-secondary:hover {
                    background: #f3f4f6;
                    border-color: #d1d5db;
                }
                
                @keyframes fadeIn {
                    from { opacity: 0; }
                    to { opacity: 1; }
                }
                
                @keyframes slideIn {
                    from { 
                        opacity: 0;
                        transform: translateY(-50px) scale(0.9);
                    }
                    to { 
                        opacity: 1;
                        transform: translateY(0) scale(1);
                    }
                }
                
                @media (max-width: 480px) {
                    .session-modal {
                        width: 95%;
                        margin: 1rem;
                    }
                    
                    .session-modal-footer {
                        flex-direction: column;
                    }
                    
                    .session-modal-footer .btn {
                        width: 100%;
                        justify-content: center;
                    }
                }
            </style>
        `;
        
        document.head.insertAdjacentHTML('beforeend', styles);
    }
    
    showWarning() {
        this.warningShown = true;
        this.warningModal.style.display = 'flex';
        
        // Start countdown timer
        this.startCountdown();
        
        // Play notification sound (optional)
        this.playNotificationSound();
        
        console.log('Session warning displayed');
    }
    
    hideWarning() {
        this.warningShown = false;
        this.warningModal.style.display = 'none';
        
        if (this.countdownInterval) {
            clearInterval(this.countdownInterval);
        }
        
        console.log('Session warning hidden');
    }
    
    startCountdown() {
        const countdownElement = document.getElementById('countdown-timer');
        let timeLeft = this.warningTime / 1000; // Convert to seconds
        
        this.countdownInterval = setInterval(() => {
            const minutes = Math.floor(timeLeft / 60);
            const seconds = timeLeft % 60;
            
            countdownElement.textContent = `${minutes}:${seconds.toString().padStart(2, '0')}`;
            
            timeLeft--;
            
            if (timeLeft < 0) {
                clearInterval(this.countdownInterval);
                this.performLogout();
            }
        }, 1000);
    }
    
    extendSession() {
        // Reset activity time
        this.updateActivity();
        this.hideWarning();
        
        // Make AJAX call to extend session on server
        this.extendServerSession();
        
        console.log('Session extended');
    }
    
    extendServerSession() {
        fetch('/api/extend-session', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            credentials: 'same-origin'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                console.log('Server session extended successfully');
            }
        })
        .catch(error => {
            console.error('Failed to extend server session:', error);
        });
    }
    
    performLogout() {
        this.stopMonitoring();
        this.hideWarning();
        
        // Show logout message
        this.showLogoutMessage();
        
        // Perform logout after short delay
        setTimeout(() => {
            this.redirectToLogout();
        }, 2000);
        
        console.log('Performing automatic logout');
    }
    
    showLogoutMessage() {
        const messageHtml = `
            <div id="logout-message" class="session-modal-overlay">
                <div class="session-modal">
                    <div class="session-modal-header" style="background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);">
                        <i class="fas fa-sign-out-alt"></i>
                        <h4>Session Expired</h4>
                    </div>
                    <div class="session-modal-body">
                        <p>Your session has expired due to inactivity.</p>
                        <p>You will be redirected to the login page...</p>
                        <div style="margin-top: 1rem;">
                            <i class="fas fa-spinner fa-spin text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        document.body.insertAdjacentHTML('beforeend', messageHtml);
    }
    
    redirectToLogout() {
        // Clear any stored session data
        localStorage.removeItem('user_logged_in');
        sessionStorage.clear();
        
        // Redirect to logout URL
        window.location.href = this.logoutUrl;
    }
    
    playNotificationSound() {
        // Create and play a subtle notification sound
        try {
            const audioContext = new (window.AudioContext || window.webkitAudioContext)();
            const oscillator = audioContext.createOscillator();
            const gainNode = audioContext.createGain();
            
            oscillator.connect(gainNode);
            gainNode.connect(audioContext.destination);
            
            oscillator.frequency.setValueAtTime(800, audioContext.currentTime);
            oscillator.frequency.setValueAtTime(600, audioContext.currentTime + 0.1);
            
            gainNode.gain.setValueAtTime(0.1, audioContext.currentTime);
            gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.2);
            
            oscillator.start(audioContext.currentTime);
            oscillator.stop(audioContext.currentTime + 0.2);
        } catch (error) {
            // Fallback: no sound if audio context fails
            console.log('Audio notification not available');
        }
    }
    
    // Public method to manually reset session
    resetSession() {
        this.updateActivity();
        console.log('Session manually reset');
    }
    
    // Public method to destroy session manager
    destroy() {
        this.stopMonitoring();
        
        if (this.warningModal) {
            this.warningModal.remove();
        }
        
        const styles = document.getElementById('session-manager-styles');
        if (styles) {
            styles.remove();
        }
        
        console.log('Session Manager destroyed');
    }
}

// Auto-initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Check if user is logged in before initializing
    const userLoggedIn = document.body.dataset.userLoggedIn === 'true' || 
                        document.querySelector('.user-avatar') !== null;
    
    if (userLoggedIn) {
        // Initialize session manager
        window.sessionManager = new SessionManager({
            idleTime: 10 * 60 * 1000,      // 10 minutes
            warningTime: 2 * 60 * 1000,    // 2 minutes warning
            checkInterval: 30 * 1000,      // Check every 30 seconds
            logoutUrl: '/logout',
            loginUrl: '/login'
        });
        
        // Set flag for session manager
        localStorage.setItem('user_logged_in', 'true');
    }
});

// Export for use in other scripts
if (typeof module !== 'undefined' && module.exports) {
    module.exports = SessionManager;
}
