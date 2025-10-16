<?php

namespace SmartCast\Middleware;

/**
 * Session Activity Middleware
 * Updates last activity timestamp for session management
 */
class SessionActivityMiddleware
{
    /**
     * Handle the request and update session activity
     */
    public function handle($request, $next)
    {
        // Update last activity timestamp if user is logged in
        if (isset($_SESSION['user_id'])) {
            $_SESSION['last_activity'] = time();
            
            // Initialize session start time if not set
            if (!isset($_SESSION['session_start'])) {
                $_SESSION['session_start'] = time();
            }
            
            // Check if session has expired (server-side check)
            $maxIdleTime = 10 * 60; // 10 minutes
            $lastActivity = $_SESSION['last_activity'] ?? time();
            
            if ((time() - $lastActivity) > $maxIdleTime) {
                // Session expired - destroy it
                session_destroy();
                
                // Redirect to login with expired message
                header('Location: ' . PUBLIC_URL . '/login?session_expired=1');
                exit;
            }
        }
        
        return $next($request);
    }
}
