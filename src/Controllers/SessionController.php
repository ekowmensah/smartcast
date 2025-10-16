<?php

namespace SmartCast\Controllers;

use SmartCast\Core\Controller;

class SessionController extends Controller
{
    /**
     * Extend the current session
     */
    public function extendSession()
    {
        try {
            // Check if user is logged in
            if (!isset($_SESSION['user_id'])) {
                return $this->jsonResponse([
                    'success' => false,
                    'message' => 'No active session found'
                ], 401);
            }

            // Regenerate session ID for security
            session_regenerate_id(true);
            
            // Update session timestamp
            $_SESSION['last_activity'] = time();
            $_SESSION['session_extended'] = time();
            
            // Log session extension
            error_log("Session extended for user ID: " . $_SESSION['user_id']);
            
            return $this->jsonResponse([
                'success' => true,
                'message' => 'Session extended successfully',
                'timestamp' => time(),
                'expires_at' => time() + (10 * 60) // 10 minutes from now
            ]);
            
        } catch (\Exception $e) {
            error_log("Session extension error: " . $e->getMessage());
            
            return $this->jsonResponse([
                'success' => false,
                'message' => 'Failed to extend session'
            ], 500);
        }
    }
    
    /**
     * Check session status
     */
    public function checkSession()
    {
        try {
            if (!isset($_SESSION['user_id'])) {
                return $this->jsonResponse([
                    'active' => false,
                    'message' => 'No active session'
                ]);
            }
            
            $lastActivity = $_SESSION['last_activity'] ?? time();
            $idleTime = time() - $lastActivity;
            $maxIdleTime = 10 * 60; // 10 minutes
            
            if ($idleTime > $maxIdleTime) {
                // Session expired
                session_destroy();
                
                return $this->jsonResponse([
                    'active' => false,
                    'expired' => true,
                    'message' => 'Session expired due to inactivity'
                ]);
            }
            
            return $this->jsonResponse([
                'active' => true,
                'user_id' => $_SESSION['user_id'],
                'last_activity' => $lastActivity,
                'idle_time' => $idleTime,
                'expires_in' => $maxIdleTime - $idleTime
            ]);
            
        } catch (\Exception $e) {
            error_log("Session check error: " . $e->getMessage());
            
            return $this->jsonResponse([
                'active' => false,
                'error' => 'Failed to check session status'
            ], 500);
        }
    }
    
    /**
     * Get session configuration for frontend
     */
    public function getSessionConfig()
    {
        return $this->jsonResponse([
            'idle_timeout' => 10 * 60 * 1000, // 10 minutes in milliseconds
            'warning_time' => 2 * 60 * 1000,  // 2 minutes in milliseconds
            'check_interval' => 30 * 1000,    // 30 seconds in milliseconds
            'server_time' => time()
        ]);
    }
    
    /**
     * Manual logout endpoint
     */
    public function logout()
    {
        try {
            $userId = $_SESSION['user_id'] ?? null;
            
            // Destroy session
            session_destroy();
            
            // Clear session cookie
            if (isset($_COOKIE[session_name()])) {
                setcookie(session_name(), '', time() - 3600, '/');
            }
            
            // Log logout
            if ($userId) {
                error_log("User logged out: " . $userId);
            }
            
            // Redirect to login page
            header('Location: ' . PUBLIC_URL . '/login?logged_out=1');
            exit;
            
        } catch (\Exception $e) {
            error_log("Logout error: " . $e->getMessage());
            
            // Force redirect even if there's an error
            header('Location: ' . PUBLIC_URL . '/login');
            exit;
        }
    }
    
    /**
     * Helper method to return JSON response
     */
    private function jsonResponse($data, $statusCode = 200)
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        return json_encode($data);
    }
}
