/**
 * Session Initialization Script
 * Simple script to initialize session management on page load
 */
(function() {
    'use strict';
    
    // Check if user is logged in
    function isUserLoggedIn() {
        return document.body.dataset.userLoggedIn === 'true' || 
               document.querySelector('.user-avatar') !== null ||
               document.querySelector('[data-user-id]') !== null;
    }
    
    // Initialize session management when DOM is ready
    function initializeSession() {
        if (!isUserLoggedIn()) {
            console.log('User not logged in - skipping session management');
            return;
        }
        
        // Check if SessionManager is available
        if (typeof SessionManager === 'undefined') {
            console.warn('SessionManager not loaded - session timeout disabled');
            return;
        }
        
        // Initialize session manager with custom settings
        window.sessionManager = new SessionManager({
            idleTime: 10 * 60 * 1000,      // 10 minutes idle timeout
            warningTime: 2 * 60 * 1000,    // 2 minutes warning before timeout
            checkInterval: 30 * 1000,      // Check every 30 seconds
            logoutUrl: '/logout',          // Logout endpoint
            loginUrl: '/login'             // Login page
        });
        
        console.log('Session management initialized successfully');
        
        // Set localStorage flag for session manager detection
        localStorage.setItem('user_logged_in', 'true');
        
        // Listen for beforeunload to clean up
        window.addEventListener('beforeunload', function() {
            if (window.sessionManager) {
                // Don't destroy on page navigation, only on actual close
                // sessionManager.destroy();
            }
        });
        
        // Listen for page visibility changes to pause/resume monitoring
        document.addEventListener('visibilitychange', function() {
            if (window.sessionManager) {
                if (document.hidden) {
                    console.log('Page hidden - session monitoring continues');
                } else {
                    console.log('Page visible - updating activity');
                    window.sessionManager.resetSession();
                }
            }
        });
    }
    
    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initializeSession);
    } else {
        initializeSession();
    }
    
    // Expose utility functions globally
    window.SessionUtils = {
        isLoggedIn: isUserLoggedIn,
        resetSession: function() {
            if (window.sessionManager) {
                window.sessionManager.resetSession();
            }
        },
        extendSession: function() {
            if (window.sessionManager) {
                window.sessionManager.extendSession();
            }
        }
    };
    
})();
