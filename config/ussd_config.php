<?php

/**
 * USSD Configuration
 * 
 * Configure your base USSD code and related settings
 */

// Load env helper if not already loaded
if (!function_exists('env')) {
    require_once __DIR__ . '/../src/Helpers/env.php';
}

return [
    /**
     * Base USSD Code (without asterisks and hash)
     * 
     * Examples:
     * - '920' for *920*XX#
     * - '713' for *713*XX#
     * - '384' for *384*XX#
     * 
     * This is the short code you register with your telecom provider (Hubtel, MTN, etc.)
     * IMPORTANT: This MUST match the code registered in your Hubtel/MTN dashboard
     */
    'base_code' => env('USSD_BASE_CODE', '711'),
    
    /**
     * USSD Code Format
     * 
     * How the full USSD code is displayed
     * {base} = base code (e.g., 920)
     * {tenant} = tenant code (e.g., 01)
     */
    'format' => '*{base}*{tenant}#',
    
    /**
     * Tenant Code Length
     * 
     * Number of digits for tenant codes
     * 2 = 01-99 (99 tenants max)
     * 3 = 001-999 (999 tenants max)
     */
    'tenant_code_length' => 2,
    
    /**
     * Maximum Tenants
     * 
     * Based on tenant_code_length:
     * 2 digits = 99 tenants
     * 3 digits = 999 tenants
     */
    'max_tenants' => 99,
    
    /**
     * Callback URL
     * 
     * Where Hubtel/telecom sends USSD requests
     */
    'callback_url' => env('USSD_CALLBACK_URL', APP_URL . '/api/ussd/callback'),
    
    /**
     * Session Timeout (seconds)
     * 
     * How long before USSD session expires
     */
    'session_timeout' => 180, // 3 minutes
    
    /**
     * Provider
     * 
     * Your USSD service provider
     * Options: 'hubtel', 'mtn', 'vodafone', 'airtel'
     */
    'provider' => env('USSD_PROVIDER', 'hubtel'),
    
    /**
     * Enable USSD Globally
     * 
     * Master switch to enable/disable all USSD functionality
     */
    'enabled' => env('USSD_ENABLED', true),
];
