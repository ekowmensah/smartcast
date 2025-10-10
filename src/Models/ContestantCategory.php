<?php

namespace SmartCast\Models;

/**
 * ContestantCategory Model
 * Manages many-to-many relationships between contestants and categories
 * Handles USSD shortcodes for voting
 */
class ContestantCategory extends BaseModel
{
    protected $table = 'contestant_categories';
    protected $fillable = [
        'contestant_id', 'category_id', 'short_code', 'display_order', 'active'
    ];
    
    /**
     * Get all contestants in a specific category with their shortcodes
     */
    public function getContestantsByCategory($categoryId)
    {
        $sql = "
            SELECT cc.*, c.name as contestant_name, c.image_url, c.bio,
                   COALESCE(SUM(v.quantity), 0) as total_votes
            FROM contestant_categories cc
            INNER JOIN contestants c ON cc.contestant_id = c.id
            LEFT JOIN votes v ON c.id = v.contestant_id
            WHERE cc.category_id = :category_id AND cc.active = 1 AND c.active = 1
            GROUP BY cc.id
            ORDER BY cc.display_order ASC, total_votes DESC
        ";
        
        return $this->db->select($sql, ['category_id' => $categoryId]);
    }
    
    /**
     * Get all categories for a specific contestant
     */
    public function getCategoriesByContestant($contestantId)
    {
        $sql = "
            SELECT cc.*, cat.name as category_name, cat.description
            FROM contestant_categories cc
            INNER JOIN categories cat ON cc.category_id = cat.id
            WHERE cc.contestant_id = :contestant_id AND cc.active = 1
            ORDER BY cat.display_order ASC
        ";
        
        return $this->db->select($sql, ['contestant_id' => $contestantId]);
    }
    
    /**
     * Find contestant by USSD shortcode within a category
     */
    public function findByShortCode($shortCode, $categoryId = null)
    {
        $sql = "
            SELECT cc.*, c.name as contestant_name, c.image_url, cat.name as category_name
            FROM contestant_categories cc
            INNER JOIN contestants c ON cc.contestant_id = c.id
            INNER JOIN categories cat ON cc.category_id = cat.id
            WHERE cc.short_code = :short_code AND cc.active = 1 AND c.active = 1
        ";
        
        $params = ['short_code' => $shortCode];
        
        if ($categoryId) {
            $sql .= " AND cc.category_id = :category_id";
            $params['category_id'] = $categoryId;
        }
        
        return $this->db->selectOne($sql, $params);
    }
    
    /**
     * Generate globally unique shortcode for USSD voting
     * Each nominee gets a unique shortcode for each category they're in
     * Shortcodes are globally unique across all tenants and events
     */
    public function generateShortCode($categoryId, $contestantName, $contestantId = null)
    {
        // Get tenant and event info for better uniqueness
        $categoryInfo = $this->db->selectOne("
            SELECT cat.event_id, e.tenant_id, e.code as event_code
            FROM categories cat 
            INNER JOIN events e ON cat.event_id = e.id 
            WHERE cat.id = :category_id
        ", ['category_id' => $categoryId]);
        
        $tenantId = $categoryInfo['tenant_id'] ?? 1;
        $eventCode = $categoryInfo['event_code'] ?? 'EVT';
        
        // Clean contestant name for code generation
        $cleanName = preg_replace('/[^A-Z]/', '', strtoupper($contestantName));
        
        // Generate tenant prefix (T1, T2, etc.) for global uniqueness
        $tenantPrefix = 'T' . $tenantId;
        
        // Try different shortcode patterns with global uniqueness
        $patterns = [
            // Tenant + First 2 letters + number (T1SA01)
            $tenantPrefix . substr($cleanName, 0, 2),
            // Tenant + First 3 letters (T1SAR01)
            $tenantPrefix . substr($cleanName, 0, 3),
            // Tenant + First letter + last letter (T1SE01)
            $tenantPrefix . substr($cleanName, 0, 1) . substr($cleanName, -1, 1),
            // Event code + First 2 letters (GMA24SA01 if event code is GMA24)
            substr($eventCode, 0, 4) . substr($cleanName, 0, 2),
            // Simple patterns with higher numbers for global uniqueness
            substr($cleanName, 0, 2),
            substr($cleanName, 0, 3),
            substr($cleanName, 0, 1) . substr($cleanName, -1, 1)
        ];
        
        // Remove empty patterns and ensure reasonable length
        $patterns = array_filter($patterns, function($p) { 
            return !empty($p) && strlen($p) >= 2 && strlen($p) <= 8; 
        });
        
        foreach ($patterns as $pattern) {
            // Try with numbers 001-999 for better global uniqueness
            for ($i = 1; $i <= 999; $i++) {
                $shortCode = $pattern . str_pad($i, 3, '0', STR_PAD_LEFT);
                
                // Ensure shortcode is not too long for USSD (max 10 chars)
                if (strlen($shortCode) > 10) {
                    $shortCode = substr($shortCode, 0, 10);
                }
                
                // Check global uniqueness
                if (!$this->isShortCodeTakenGlobally($shortCode)) {
                    return $shortCode;
                }
            }
        }
        
        // Fallback: Use tenant + contestant ID + category ID for guaranteed uniqueness
        if ($contestantId) {
            $fallbackCode = 'T' . $tenantId . 'N' . $contestantId . 'C' . $categoryId;
            if (!$this->isShortCodeTakenGlobally($fallbackCode)) {
                return $fallbackCode;
            }
        }
        
        // Final fallback: UUID-like code with timestamp for absolute uniqueness
        do {
            $timestamp = substr(time(), -4); // Last 4 digits of timestamp
            $randomCode = 'U' . $timestamp . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT);
        } while ($this->isShortCodeTakenGlobally($randomCode));
        
        return $randomCode;
    }
    
    /**
     * Check if shortcode is already taken globally across all tenants and events
     */
    public function isShortCodeTaken($shortCode, $categoryId = null)
    {
        // Check global uniqueness across ALL tenant events
        $sql = "
            SELECT COUNT(*) as count 
            FROM contestant_categories cc
            INNER JOIN categories cat ON cc.category_id = cat.id
            INNER JOIN events e ON cat.event_id = e.id
            WHERE cc.short_code = :short_code AND cc.active = 1
        ";
        
        $params = ['short_code' => $shortCode];
        
        // Optional: exclude current category if updating existing assignment
        if ($categoryId) {
            $sql .= " AND cc.category_id != :category_id";
            $params['category_id'] = $categoryId;
        }
        
        $result = $this->db->selectOne($sql, $params);
        
        return $result['count'] > 0;
    }
    
    /**
     * Check if shortcode is taken globally (simpler version)
     */
    public function isShortCodeTakenGlobally($shortCode)
    {
        $sql = "
            SELECT COUNT(*) as count 
            FROM contestant_categories 
            WHERE short_code = :short_code AND active = 1
        ";
        
        $result = $this->db->selectOne($sql, ['short_code' => $shortCode]);
        
        return $result['count'] > 0;
    }
    
    /**
     * Assign contestant to category with auto-generated unique shortcode
     * Each contestant gets a unique shortcode for each category
     */
    public function assignContestantToCategory($contestantId, $categoryId, $customShortCode = null)
    {
        // Get contestant name for shortcode generation
        $contestant = $this->db->selectOne(
            "SELECT name FROM contestants WHERE id = :id",
            ['id' => $contestantId]
        );
        
        if (!$contestant) {
            throw new \Exception("Contestant not found");
        }
        
        // Check if assignment already exists
        $existing = $this->db->selectOne(
            "SELECT id, short_code FROM contestant_categories WHERE contestant_id = :contestant_id AND category_id = :category_id",
            ['contestant_id' => $contestantId, 'category_id' => $categoryId]
        );
        
        // Generate or validate shortcode
        if ($customShortCode) {
            // Validate custom shortcode is not taken globally
            if ($this->isShortCodeTakenGlobally($customShortCode)) {
                throw new \Exception("Shortcode '{$customShortCode}' is already taken globally across all events");
            }
            $shortCode = strtoupper($customShortCode);
        } else {
            // Generate globally unique shortcode
            $shortCode = $this->generateShortCode($categoryId, $contestant['name'], $contestantId);
        }
        
        if ($existing) {
            // Update existing assignment with new shortcode
            return $this->update($existing['id'], [
                'short_code' => $shortCode,
                'active' => 1
            ]);
        } else {
            // Create new assignment with unique shortcode
            return $this->create([
                'contestant_id' => $contestantId,
                'category_id' => $categoryId,
                'short_code' => $shortCode,
                'active' => 1
            ]);
        }
    }
    
    /**
     * Remove contestant from category
     */
    public function removeContestantFromCategory($contestantId, $categoryId)
    {
        return $this->db->update(
            'contestant_categories',
            ['active' => 0],
            'contestant_id = :contestant_id AND category_id = :category_id',
            [
                'contestant_id' => $contestantId,
                'category_id' => $categoryId
            ]
        );
    }
    
    /**
     * Get leaderboard for a category
     */
    public function getCategoryLeaderboard($categoryId, $limit = 10)
    {
        $sql = "
            SELECT cc.short_code, c.name as contestant_name, c.image_url,
                   COALESCE(SUM(v.quantity), 0) as total_votes,
                   COUNT(DISTINCT v.id) as vote_count
            FROM contestant_categories cc
            INNER JOIN contestants c ON cc.contestant_id = c.id
            LEFT JOIN votes v ON c.id = v.contestant_id
            WHERE cc.category_id = :category_id AND cc.active = 1 AND c.active = 1
            GROUP BY cc.id
            ORDER BY total_votes DESC, c.name ASC
            LIMIT :limit
        ";
        
        return $this->db->select($sql, [
            'category_id' => $categoryId,
            'limit' => $limit
        ]);
    }
    
    /**
     * Get USSD voting menu for a category
     */
    public function getUSSDMenu($categoryId)
    {
        $sql = "
            SELECT cc.short_code, c.name as contestant_name
            FROM contestant_categories cc
            INNER JOIN contestants c ON cc.contestant_id = c.id
            WHERE cc.category_id = :category_id AND cc.active = 1 AND c.active = 1
            ORDER BY cc.display_order ASC, c.name ASC
        ";
        
        return $this->db->select($sql, ['category_id' => $categoryId]);
    }
    
    /**
     * Get all shortcodes for a specific contestant across all categories
     * This shows how the same nominee has different codes per category
     */
    public function getContestantShortCodes($contestantId)
    {
        $sql = "
            SELECT cc.short_code, cc.category_id, cat.name as category_name,
                   c.name as contestant_name
            FROM contestant_categories cc
            INNER JOIN categories cat ON cc.category_id = cat.id
            INNER JOIN contestants c ON cc.contestant_id = c.id
            WHERE cc.contestant_id = :contestant_id AND cc.active = 1
            ORDER BY cat.name ASC
        ";
        
        return $this->db->select($sql, ['contestant_id' => $contestantId]);
    }
    
    /**
     * Bulk assign contestant to multiple categories with unique shortcodes
     */
    public function assignContestantToMultipleCategories($contestantId, $categoryIds)
    {
        $results = [];
        
        foreach ($categoryIds as $categoryId) {
            try {
                $result = $this->assignContestantToCategory($contestantId, $categoryId);
                $results[] = [
                    'category_id' => $categoryId,
                    'success' => true,
                    'assignment_id' => $result
                ];
            } catch (\Exception $e) {
                $results[] = [
                    'category_id' => $categoryId,
                    'success' => false,
                    'error' => $e->getMessage()
                ];
            }
        }
        
        return $results;
    }
    
    /**
     * Get shortcode usage statistics for debugging
     */
    public function getShortCodeStats($categoryId = null)
    {
        $sql = "
            SELECT 
                cc.category_id,
                cat.name as category_name,
                COUNT(cc.id) as total_assignments,
                COUNT(DISTINCT cc.short_code) as unique_shortcodes,
                COUNT(DISTINCT cc.contestant_id) as unique_contestants
            FROM contestant_categories cc
            INNER JOIN categories cat ON cc.category_id = cat.id
            WHERE cc.active = 1
        ";
        
        if ($categoryId) {
            $sql .= " AND cc.category_id = :category_id";
            $params = ['category_id' => $categoryId];
        } else {
            $params = [];
        }
        
        $sql .= " GROUP BY cc.category_id ORDER BY cat.name ASC";
        
        return $this->db->select($sql, $params);
    }
}
