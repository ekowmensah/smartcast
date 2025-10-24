<?php

namespace SmartCast\Models;

/**
 * USSD Session Model
 */
class UssdSession extends BaseModel
{
    protected $table = 'ussd_sessions';
    protected $fillable = [
        'session_id', 'msisdn', 'state', 'data'
    ];
    
    // USSD States
    const STATE_WELCOME = 'welcome';
    const STATE_SELECT_EVENT = 'select_event';
    const STATE_SELECT_CATEGORY = 'select_category';
    const STATE_SELECT_CONTESTANT = 'select_contestant';
    const STATE_SELECT_BUNDLE = 'select_bundle';
    const STATE_CONFIRM_VOTE = 'confirm_vote';
    const STATE_PAYMENT = 'payment';
    const STATE_SUCCESS = 'success';
    const STATE_ERROR = 'error';
    const STATE_ENDED = 'ended';
    
    public function createSession($sessionId, $msisdn, $initialState = self::STATE_WELCOME, $initialData = [])
    {
        // Clean up old sessions for this MSISDN
        $this->cleanupOldSessions($msisdn);
        
        return $this->create([
            'session_id' => $sessionId,
            'msisdn' => $msisdn,
            'state' => $initialState,
            'data' => json_encode($initialData)
        ]);
    }
    
    public function getSession($sessionId)
    {
        $session = $this->findAll(['session_id' => $sessionId], null, 1);
        
        if (empty($session)) {
            return null;
        }
        
        $session = $session[0];
        $session['data'] = json_decode($session['data'], true) ?: [];
        
        return $session;
    }
    
    public function updateSession($sessionId, $newState, $data = null)
    {
        $updateData = ['state' => $newState];
        
        if ($data !== null) {
            $session = $this->getSession($sessionId);
            if ($session) {
                $sessionData = array_merge($session['data'], $data);
                $updateData['data'] = json_encode($sessionData);
            }
        }
        
        return $this->db->update(
            $this->table,
            $updateData,
            'session_id = :session_id',
            ['session_id' => $sessionId]
        );
    }
    
    public function setSessionData($sessionId, $key, $value)
    {
        $session = $this->getSession($sessionId);
        if (!$session) {
            return false;
        }
        
        $sessionData = $session['data'];
        $sessionData[$key] = $value;
        
        return $this->db->update(
            $this->table,
            ['data' => json_encode($sessionData)],
            'session_id = :session_id',
            ['session_id' => $sessionId]
        );
    }
    
    /**
     * Update session columns (tenant_id, service_code, etc.)
     */
    public function updateSessionColumns($sessionId, $columns)
    {
        return $this->db->update(
            $this->table,
            $columns,
            'session_id = :session_id',
            ['session_id' => $sessionId]
        );
    }
    
    public function getSessionData($sessionId, $key = null)
    {
        $session = $this->getSession($sessionId);
        if (!$session) {
            return null;
        }
        
        if ($key === null) {
            return $session['data'];
        }
        
        return $session['data'][$key] ?? null;
    }
    
    public function endSession($sessionId)
    {
        return $this->updateSession($sessionId, self::STATE_ENDED);
    }
    
    public function deleteSession($sessionId)
    {
        return $this->db->delete(
            $this->table,
            'session_id = :session_id',
            ['session_id' => $sessionId]
        );
    }
    
    public function cleanupOldSessions($msisdn, $olderThanMinutes = 30)
    {
        $cutoffTime = date('Y-m-d H:i:s', time() - ($olderThanMinutes * 60));
        
        return $this->db->delete(
            $this->table,
            'msisdn = :msisdn AND updated_at < :cutoff_time',
            [
                'msisdn' => $msisdn,
                'cutoff_time' => $cutoffTime
            ]
        );
    }
    
    public function getActiveSessionByMsisdn($msisdn)
    {
        $sql = "
            SELECT * FROM {$this->table} 
            WHERE msisdn = :msisdn 
            AND state NOT IN (:ended_state, :success_state)
            ORDER BY updated_at DESC 
            LIMIT 1
        ";
        
        $session = $this->db->selectOne($sql, [
            'msisdn' => $msisdn,
            'ended_state' => self::STATE_ENDED,
            'success_state' => self::STATE_SUCCESS
        ]);
        
        if ($session) {
            $session['data'] = json_decode($session['data'], true) ?: [];
        }
        
        return $session;
    }
    
    public function processUssdInput($sessionId, $input)
    {
        $session = $this->getSession($sessionId);
        if (!$session) {
            return $this->createErrorResponse('Session not found');
        }
        
        switch ($session['state']) {
            case self::STATE_WELCOME:
                return $this->handleWelcomeState($sessionId, $input);
                
            case self::STATE_SELECT_EVENT:
                return $this->handleSelectEventState($sessionId, $input);
                
            case self::STATE_SELECT_CATEGORY:
                return $this->handleSelectCategoryState($sessionId, $input);
                
            case self::STATE_SELECT_CONTESTANT:
                return $this->handleSelectContestantState($sessionId, $input);
                
            case self::STATE_SELECT_BUNDLE:
                return $this->handleSelectBundleState($sessionId, $input);
                
            case self::STATE_CONFIRM_VOTE:
                return $this->handleConfirmVoteState($sessionId, $input);
                
            default:
                return $this->createErrorResponse('Invalid session state');
        }
    }
    
    private function handleWelcomeState($sessionId, $input)
    {
        // Get session data to extract tenant_id
        $sessionData = $this->getSessionData($sessionId);
        $tenantId = $sessionData['tenant_id'] ?? null;
        
        // Get active events for this tenant only
        $eventModel = new Event();
        
        if ($tenantId) {
            // Filter by tenant
            $events = $eventModel->findAll([
                'tenant_id' => $tenantId,
                'status' => 'active'
            ]);
        } else {
            // Fallback to public events if no tenant
            $events = $eventModel->getPublicEvents();
        }
        
        if (empty($events)) {
            return $this->createResponse('No active events available. Thank you!', true);
        }
        
        // Update session state
        $this->updateSession($sessionId, self::STATE_SELECT_EVENT, ['events' => $events]);
        
        // Build menu
        $menu = "Welcome to SmartCast Voting!\nSelect an event:\n";
        foreach ($events as $index => $event) {
            $menu .= ($index + 1) . ". " . $event['name'] . "\n";
        }
        $menu .= "0. Exit";
        
        return $this->createResponse($menu);
    }
    
    private function handleSelectEventState($sessionId, $input)
    {
        if ($input == '0') {
            $this->endSession($sessionId);
            return $this->createResponse('Thank you for using SmartCast!', true);
        }
        
        $sessionData = $this->getSessionData($sessionId);
        $events = $sessionData['events'] ?? [];
        
        $eventIndex = (int)$input - 1;
        if (!isset($events[$eventIndex])) {
            return $this->createResponse('Invalid selection. Please try again.');
        }
        
        $selectedEvent = $events[$eventIndex];
        
        // Get categories for this event
        $categoryModel = new Category();
        $categories = $categoryModel->getCategoriesByEvent($selectedEvent['id']);
        
        if (empty($categories)) {
            // No categories, go directly to contestants
            $contestantModel = new Contestant();
            $contestants = $contestantModel->getContestantsByEvent($selectedEvent['id']);
            
            $this->updateSession($sessionId, self::STATE_SELECT_CONTESTANT, [
                'selected_event' => $selectedEvent,
                'contestants' => $contestants
            ]);
            
            return $this->buildContestantMenu($contestants);
        }
        
        // Update session with selected event and categories
        $this->updateSession($sessionId, self::STATE_SELECT_CATEGORY, [
            'selected_event' => $selectedEvent,
            'categories' => $categories
        ]);
        
        return $this->buildCategoryMenu($categories);
    }
    
    private function handleSelectCategoryState($sessionId, $input)
    {
        if ($input == '0') {
            return $this->handleWelcomeState($sessionId, '');
        }
        
        $sessionData = $this->getSessionData($sessionId);
        $categories = $sessionData['categories'] ?? [];
        
        $categoryIndex = (int)$input - 1;
        if (!isset($categories[$categoryIndex])) {
            return $this->createResponse('Invalid selection. Please try again.');
        }
        
        $selectedCategory = $categories[$categoryIndex];
        
        // Get contestants for this category
        $contestantModel = new Contestant();
        $contestants = $contestantModel->getContestantsByCategory($selectedCategory['id']);
        
        $this->updateSession($sessionId, self::STATE_SELECT_CONTESTANT, [
            'selected_category' => $selectedCategory,
            'contestants' => $contestants
        ]);
        
        return $this->buildContestantMenu($contestants);
    }
    
    private function handleSelectContestantState($sessionId, $input)
    {
        if ($input == '0') {
            // Go back to category selection or event selection
            $sessionData = $this->getSessionData($sessionId);
            if (isset($sessionData['categories'])) {
                $this->updateSession($sessionId, self::STATE_SELECT_CATEGORY);
                return $this->buildCategoryMenu($sessionData['categories']);
            } else {
                return $this->handleWelcomeState($sessionId, '');
            }
        }
        
        $sessionData = $this->getSessionData($sessionId);
        $contestants = $sessionData['contestants'] ?? [];
        
        $contestantIndex = (int)$input - 1;
        if (!isset($contestants[$contestantIndex])) {
            return $this->createResponse('Invalid selection. Please try again.');
        }
        
        $selectedContestant = $contestants[$contestantIndex];
        
        // Get vote bundles for this event
        $bundleModel = new VoteBundle();
        $bundles = $bundleModel->getBundlesByEvent($sessionData['selected_event']['id']);
        
        $this->updateSession($sessionId, self::STATE_SELECT_BUNDLE, [
            'selected_contestant' => $selectedContestant,
            'bundles' => $bundles
        ]);
        
        return $this->buildBundleMenu($bundles, $selectedContestant['name']);
    }
    
    private function handleSelectBundleState($sessionId, $input)
    {
        if ($input == '0') {
            $sessionData = $this->getSessionData($sessionId);
            $this->updateSession($sessionId, self::STATE_SELECT_CONTESTANT);
            return $this->buildContestantMenu($sessionData['contestants']);
        }
        
        $sessionData = $this->getSessionData($sessionId);
        $bundles = $sessionData['bundles'] ?? [];
        
        $bundleIndex = (int)$input - 1;
        if (!isset($bundles[$bundleIndex])) {
            return $this->createResponse('Invalid selection. Please try again.');
        }
        
        $selectedBundle = $bundles[$bundleIndex];
        
        $this->updateSession($sessionId, self::STATE_CONFIRM_VOTE, [
            'selected_bundle' => $selectedBundle
        ]);
        
        // Build confirmation message
        $contestant = $sessionData['selected_contestant'];
        $event = $sessionData['selected_event'];
        
        $message = "Confirm your vote:\n";
        $message .= "Event: " . $event['name'] . "\n";
        $message .= "Contestant: " . $contestant['name'] . "\n";
        $message .= "Votes: " . $selectedBundle['votes'] . "\n";
        $message .= "Amount: $" . number_format($selectedBundle['price'], 2) . "\n\n";
        $message .= "1. Confirm\n0. Cancel";
        
        return $this->createResponse($message);
    }
    
    private function handleConfirmVoteState($sessionId, $input)
    {
        if ($input == '0') {
            $sessionData = $this->getSessionData($sessionId);
            $this->updateSession($sessionId, self::STATE_SELECT_BUNDLE);
            return $this->buildBundleMenu($sessionData['bundles'], $sessionData['selected_contestant']['name']);
        }
        
        if ($input == '1') {
            return $this->processVote($sessionId);
        }
        
        return $this->createResponse('Invalid selection. Please enter 1 to confirm or 0 to cancel.');
    }
    
    private function processVote($sessionId)
    {
        $session = $this->getSession($sessionId);
        $sessionData = $session['data'];
        
        try {
            // Get tenant_id from session data
            $tenantId = $sessionData['tenant_id'] ?? $sessionData['selected_event']['tenant_id'];
            
            // Create transaction
            $transactionModel = new Transaction();
            $transactionId = $transactionModel->createTransaction([
                'tenant_id' => $tenantId,
                'event_id' => $sessionData['selected_event']['id'],
                'contestant_id' => $sessionData['selected_contestant']['id'],
                'bundle_id' => $sessionData['selected_bundle']['id'],
                'amount' => $sessionData['selected_bundle']['price'],
                'msisdn' => $session['msisdn'],
                'status' => 'pending', // Changed from 'success' to 'pending'
                'provider' => 'ussd'
            ]);
            
            // Initiate mobile money payment
            $paymentService = new \SmartCast\Services\PaymentService();
            $paymentResult = $paymentService->initializeMobileMoneyPayment([
                'amount' => $sessionData['selected_bundle']['price'],
                'phone' => $session['msisdn'],
                'description' => "Vote for {$sessionData['selected_contestant']['name']} - {$sessionData['selected_bundle']['votes']} vote(s)",
                'callback_url' => APP_URL . "/api/payment/callback/{$transactionId}",
                'tenant_id' => $tenantId,
                'voting_transaction_id' => $transactionId,
                'related_id' => $transactionId,
                'metadata' => [
                    'transaction_id' => $transactionId,
                    'event_id' => $sessionData['selected_event']['id'],
                    'contestant_id' => $sessionData['selected_contestant']['id'],
                    'category_id' => $sessionData['selected_category']['id'] ?? null,
                    'votes' => $sessionData['selected_bundle']['votes'],
                    'source' => 'ussd'
                ]
            ]);
            
            if ($paymentResult['success']) {
                $this->updateSession($sessionId, self::STATE_PAYMENT);
                
                $message = "Payment initiated!\n";
                $message .= "Please approve the payment on your phone.\n";
                $message .= "Amount: GHS " . number_format($sessionData['selected_bundle']['price'], 2) . "\n";
                $message .= "Your vote will be recorded after payment approval.\n";
                $message .= "Thank you!";
                
                return $this->createResponse($message, true);
            } else {
                throw new \Exception($paymentResult['message'] ?? 'Payment initiation failed');
            }
            
        } catch (\Exception $e) {
            error_log("USSD Vote Error: " . $e->getMessage());
            $this->updateSession($sessionId, self::STATE_ERROR);
            return $this->createResponse('Vote failed. Please try again later.', true);
        }
    }
    
    private function buildCategoryMenu($categories)
    {
        $menu = "Select a category:\n";
        foreach ($categories as $index => $category) {
            $menu .= ($index + 1) . ". " . $category['name'] . "\n";
        }
        $menu .= "0. Back";
        
        return $this->createResponse($menu);
    }
    
    private function buildContestantMenu($contestants)
    {
        if (empty($contestants)) {
            return $this->createResponse('No contestants available for this selection.', true);
        }
        
        $menu = "Select a contestant:\n";
        foreach ($contestants as $index => $contestant) {
            $menu .= ($index + 1) . ". " . $contestant['name'];
            if (!empty($contestant['short_code'])) {
                $menu .= " (" . $contestant['short_code'] . ")";
            }
            $menu .= "\n";
        }
        $menu .= "0. Back";
        
        return $this->createResponse($menu);
    }
    
    private function buildBundleMenu($bundles, $contestantName)
    {
        $menu = "Vote for: " . $contestantName . "\n";
        $menu .= "Select vote package:\n";
        
        foreach ($bundles as $index => $bundle) {
            $menu .= ($index + 1) . ". " . $bundle['name'] . " - $" . number_format($bundle['price'], 2) . "\n";
        }
        $menu .= "0. Back";
        
        return $this->createResponse($menu);
    }
    
    private function createResponse($message, $end = false)
    {
        return [
            'message' => $message,
            'end' => $end
        ];
    }
    
    private function createErrorResponse($message)
    {
        return [
            'message' => 'Error: ' . $message,
            'end' => true
        ];
    }
    
    public function getSessionStats($tenantId = null, $days = 7)
    {
        $since = date('Y-m-d H:i:s', time() - ($days * 24 * 3600));
        
        $sql = "
            SELECT 
                state,
                COUNT(*) as session_count,
                COUNT(DISTINCT msisdn) as unique_users
            FROM {$this->table}
            WHERE created_at >= :since
        ";
        
        $params = ['since' => $since];
        
        $sql .= " GROUP BY state ORDER BY session_count DESC";
        
        return $this->db->select($sql, $params);
    }
}
