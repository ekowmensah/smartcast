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
     * Generate random shortcode in format: 2 letters + 2 numbers (e.g., AA87, BT14)
     * Letters: ABCDEFGHJKLMNPQRSTUVWXYZ (24 chars, no I/O)
     * Numbers: 0123456789 (10 chars)
     * Total combinations: 24² × 10² = 57,600 unique codes
     * Random generation makes codes difficult to guess
     */
    public function generateShortCode($categoryId, $contestantName, $contestantId = null)
    {
        // Character sets (excluding I and O to avoid confusion)
        $letters = 'ABCDEFGHJKLMNPQRSTUVWXYZ'; // 24 letters
        $numbers = '0123456789'; // 10 numbers
        
        $maxAttempts = 100;
        $attempts = 0;
        
        do {
            // Generate random 2 letters + 2 numbers format
            $shortCode = '';
            
            // Add 2 random letters
            $shortCode .= $letters[random_int(0, strlen($letters) - 1)];
            $shortCode .= $letters[random_int(0, strlen($letters) - 1)];
            
            // Add 2 random numbers
            $shortCode .= $numbers[random_int(0, strlen($numbers) - 1)];
            $shortCode .= $numbers[random_int(0, strlen($numbers) - 1)];
            
            $attempts++;
            
            // Check if this code is already taken globally
            if (!$this->isShortCodeTakenGlobally($shortCode)) {
                return strtoupper($shortCode);
            }
            
        } while ($attempts < $maxAttempts);
        
        // If we can't find a unique random code, try with extended format
        $attempts = 0;
        do {
            // Fallback: 3 letters + 2 numbers for more combinations (24³ × 10² = 1,382,400)
            $shortCode = '';
            
            // Add 3 random letters
            $shortCode .= $letters[random_int(0, strlen($letters) - 1)];
            $shortCode .= $letters[random_int(0, strlen($letters) - 1)];
            $shortCode .= $letters[random_int(0, strlen($letters) - 1)];
            
            // Add 2 random numbers
            $shortCode .= $numbers[random_int(0, strlen($numbers) - 1)];
            $shortCode .= $numbers[random_int(0, strlen($numbers) - 1)];
            
            $attempts++;
            
        } while ($this->isShortCodeTakenGlobally($shortCode) && $attempts < $maxAttempts);
        
        // Final fallback with timestamp if all else fails
        if ($this->isShortCodeTakenGlobally($shortCode)) {
            $timestamp = substr(time(), -2);
            $shortCode = $letters[random_int(0, strlen($letters) - 1)] . 
                        $letters[random_int(0, strlen($letters) - 1)] . 
                        $timestamp;
        }
        
        return strtoupper($shortCode);
    }
    
    /**
     * Get shortcode generation statistics for random format
     */
    public function getShortCodeStats()
    {
        $letters = 'ABCDEFGHJKLMNPQRSTUVWXYZ'; // 24 letters
        $numbers = '0123456789'; // 10 numbers
        
        // Calculate limits for different formats
        $standardLimit = pow(24, 2) * pow(10, 2); // 57,600 (2 letters + 2 numbers)
        $extendedLimit = pow(24, 3) * pow(10, 2); // 1,382,400 (3 letters + 2 numbers)
        $totalLimit = $standardLimit + $extendedLimit; // 1,439,000
        
        // Get current counts
        $standardCount = $this->db->selectOne("
            SELECT COUNT(*) as count 
            FROM contestant_categories 
            WHERE short_code IS NOT NULL 
            AND short_code != ''
            AND LENGTH(short_code) = 4
            AND short_code REGEXP '^[ABCDEFGHJKLMNPQRSTUVWXYZ]{2}[0-9]{2}$'
        ")['count'] ?? 0;
        
        $extendedCount = $this->db->selectOne("
            SELECT COUNT(*) as count 
            FROM contestant_categories 
            WHERE short_code IS NOT NULL 
            AND short_code != ''
            AND LENGTH(short_code) = 5
            AND short_code REGEXP '^[ABCDEFGHJKLMNPQRSTUVWXYZ]{3}[0-9]{2}$'
        ")['count'] ?? 0;
        
        $totalUsed = $standardCount + $extendedCount;
        
        return [
            'format' => '2 Letters + 2 Numbers (e.g., AA87, BT14)',
            'letters' => $letters,
            'numbers' => $numbers,
            'limits' => [
                'standard' => $standardLimit,
                'extended' => $extendedLimit,
                'total' => $totalLimit
            ],
            'usage' => [
                'standard_used' => $standardCount,
                'extended_used' => $extendedCount,
                'total_used' => $totalUsed
            ],
            'remaining' => [
                'standard' => max(0, $standardLimit - $standardCount),
                'extended' => max(0, $extendedLimit - $extendedCount),
                'total' => max(0, $totalLimit - $totalUsed)
            ],
            'percentages' => [
                'standard_used' => $standardLimit > 0 ? round(($standardCount / $standardLimit) * 100, 2) : 0,
                'extended_used' => $extendedLimit > 0 ? round(($extendedCount / $extendedLimit) * 100, 4) : 0,
                'total_used' => $totalLimit > 0 ? round(($totalUsed / $totalLimit) * 100, 4) : 0
            ],
            'current_mode' => $standardCount < $standardLimit ? 'Standard (2L+2N)' : 'Extended (3L+2N)',
            'generation_type' => 'Random (Difficult to Guess)',
            'sample_codes' => $this->generateSampleCodes()
        ];
    }
    
    /**
     * Generate sample codes for demonstration
     */
    private function generateSampleCodes($count = 10)
    {
        $letters = 'ABCDEFGHJKLMNPQRSTUVWXYZ';
        $numbers = '0123456789';
        $samples = [];
        
        for ($i = 0; $i < $count; $i++) {
            $code = '';
            $code .= $letters[random_int(0, strlen($letters) - 1)];
            $code .= $letters[random_int(0, strlen($letters) - 1)];
            $code .= $numbers[random_int(0, strlen($numbers) - 1)];
            $code .= $numbers[random_int(0, strlen($numbers) - 1)];
            $samples[] = $code;
        }
        
        return $samples;
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
            // Update existing assignment - preserve shortcode if custom one provided
            $updateData = ['active' => 1];
            if ($customShortCode || !$existing['short_code']) {
                // Only update shortcode if custom one provided or existing is empty
                $updateData['short_code'] = $shortCode;
            }
            return $this->update($existing['id'], $updateData);
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
    
}
