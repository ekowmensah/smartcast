<?php

class SEOHelper {
    
    private static $defaultSEO = [
        'title' => 'SmartCast - Ghana\'s Leading Digital Voting Platform',
        'description' => 'SmartCast - Ghana\'s leading digital voting platform. Secure online voting for events, competitions, talent shows, and elections with MTN, Vodafone, AirtelTigo mobile money integration. Real-time results, fraud prevention, SMS receipts.',
        'keywords' => 'digital voting Ghana, online voting platform, mobile money voting, MTN mobile money, Vodafone Cash, AirtelTigo Money, talent show voting, competition voting, secure voting system, real-time voting results, SMS voting receipts, Ghana elections, event voting, contestant voting, democratic voting, fraud-free voting, Paystack voting, Hubtel voting, Ghana voting app, digital democracy Ghana',
        'og_title' => 'SmartCast - Ghana\'s #1 Digital Voting Platform',
        'og_description' => 'Vote securely with mobile money! MTN, Vodafone, AirtelTigo supported. Real-time results, SMS receipts, fraud prevention. Ghana\'s most trusted digital voting platform for events, competitions & elections.',
        'twitter_title' => 'SmartCast - Ghana Digital Voting Platform',
        'twitter_description' => 'Secure mobile money voting in Ghana 🇬🇭 MTN • Vodafone • AirtelTigo supported. Real-time results, SMS receipts, fraud prevention. #DigitalVoting #Ghana'
    ];
    
    private static $pageSEO = [
        'home' => [
            'title' => 'SmartCast - Digital Voting Platform Ghana | Mobile Money Voting',
            'description' => 'Ghana\'s most trusted digital voting platform. Vote securely with MTN Mobile Money, Vodafone Cash, AirtelTigo Money. Real-time results, SMS receipts, fraud prevention for events, competitions & elections.',
            'keywords' => 'digital voting Ghana, mobile money voting, MTN mobile money voting, Vodafone Cash voting, AirtelTigo Money voting, online voting platform Ghana, secure voting system, real-time voting results, Ghana voting app, talent show voting Ghana, competition voting, event voting Ghana, democratic voting platform',
            'og_title' => 'SmartCast - Ghana\'s #1 Digital Voting Platform | Mobile Money Integration',
            'og_description' => '🇬🇭 Vote with confidence! Secure mobile money voting platform trusted by thousands. MTN, Vodafone, AirtelTigo supported. Real-time results, SMS receipts, fraud prevention.',
            'twitter_title' => 'SmartCast Ghana - Secure Mobile Money Voting Platform',
            'twitter_description' => '🇬🇭 Ghana\'s #1 digital voting platform! Vote with MTN Mobile Money, Vodafone Cash, AirtelTigo Money. Real-time results & SMS receipts. #VoteGhana #MobileMoney'
        ],
        'about' => [
            'title' => 'About SmartCast - Ghana\'s Premier Digital Voting Solution',
            'description' => 'Learn about SmartCast, Ghana\'s leading digital voting platform. Secure, transparent, and accessible voting with mobile money integration. Trusted by organizations across Ghana for events, competitions, and democratic processes.',
            'keywords' => 'about SmartCast, digital voting company Ghana, voting platform Ghana, mobile money voting solution, secure voting technology, Ghana voting innovation, transparent voting system, accessible voting platform, voting platform features',
            'og_title' => 'About SmartCast - Revolutionizing Digital Voting in Ghana',
            'og_description' => 'Discover how SmartCast is transforming digital voting in Ghana with secure mobile money integration, real-time results, and fraud prevention technology.',
            'twitter_title' => 'About SmartCast - Ghana\'s Digital Voting Revolution',
            'twitter_description' => 'Learn how SmartCast is revolutionizing digital voting in Ghana 🇬🇭 Secure • Transparent • Accessible #DigitalVoting #GhanaInnovation'
        ],
        'events' => [
            'title' => 'Browse Voting Events - SmartCast Ghana | Live Competitions & Elections',
            'description' => 'Discover active voting events on SmartCast Ghana. Vote in talent shows, competitions, elections, and community events using mobile money. Real-time results and secure voting.',
            'keywords' => 'voting events Ghana, talent show voting, competition voting Ghana, election voting, community voting, live voting events, Ghana competitions, talent contests Ghana, voting competitions, democratic voting events',
            'og_title' => 'Live Voting Events in Ghana - SmartCast Platform',
            'og_description' => 'Join thousands voting in live events across Ghana! Talent shows, competitions, elections & more. Vote securely with mobile money.',
            'twitter_title' => 'Live Voting Events Ghana - SmartCast',
            'twitter_description' => '🗳️ Live voting events in Ghana! Talent shows, competitions & elections. Vote with mobile money. Join now! #VoteGhana #LiveEvents'
        ],
        'verify-receipt' => [
            'title' => 'Verify Voting Receipt - SmartCast Ghana | Check Vote Status',
            'description' => 'Verify your voting receipt on SmartCast Ghana. Check vote status, transaction details, and ensure your vote was counted. Transparent and secure vote verification system.',
            'keywords' => 'verify voting receipt, check vote status Ghana, voting receipt verification, vote confirmation, transparent voting, vote tracking, secure vote verification, voting receipt checker',
            'og_title' => 'Verify Your Vote - SmartCast Receipt Verification',
            'og_description' => 'Verify your voting receipt and ensure your vote was counted. Transparent verification system for peace of mind.',
            'twitter_title' => 'Verify Your Vote - SmartCast Ghana',
            'twitter_description' => '✅ Verify your voting receipt and check vote status. Transparent verification for peace of mind. #VoteVerification #Transparency'
        ]
    ];
    
    public static function getSEOData($page = 'home', $customData = []) {
        $seoData = isset(self::$pageSEO[$page]) ? self::$pageSEO[$page] : self::$defaultSEO;
        
        // Merge with custom data
        $seoData = array_merge($seoData, $customData);
        
        // Add canonical URL
        $seoData['canonical_url'] = APP_URL . $_SERVER['REQUEST_URI'];
        
        return $seoData;
    }
    
    public static function getEventSEO($event) {
        $eventName = htmlspecialchars($event['name'] ?? 'Event');
        $eventDescription = htmlspecialchars($event['description'] ?? '');
        
        return [
            'title' => "Vote for {$eventName} - SmartCast Ghana | Mobile Money Voting",
            'description' => "Vote in {$eventName} using mobile money on SmartCast Ghana. Secure voting with MTN, Vodafone, AirtelTigo. Real-time results and SMS receipts. {$eventDescription}",
            'keywords' => "vote {$eventName}, {$eventName} voting, Ghana voting event, mobile money voting, talent show voting, competition voting, secure online voting, real-time voting results",
            'og_title' => "Vote in {$eventName} - SmartCast Ghana",
            'og_description' => "🗳️ Vote in {$eventName} with mobile money! Secure, fast, and transparent voting. Real-time results and SMS receipts.",
            'twitter_title' => "Vote {$eventName} - SmartCast Ghana",
            'twitter_description' => "🗳️ Vote in {$eventName} now! Mobile money voting with real-time results. #Vote{$eventName} #Ghana"
        ];
    }
    
    public static function getContestantSEO($contestant, $event) {
        $contestantName = htmlspecialchars($contestant['name'] ?? 'Contestant');
        $eventName = htmlspecialchars($event['name'] ?? 'Event');
        
        return [
            'title' => "Vote for {$contestantName} in {$eventName} - SmartCast Ghana",
            'description' => "Vote for {$contestantName} in {$eventName} using mobile money on SmartCast Ghana. Secure voting with real-time results and SMS receipts.",
            'keywords' => "vote {$contestantName}, {$contestantName} voting, {$eventName} contestant, Ghana voting, mobile money voting, talent show contestant, competition voting",
            'og_title' => "Vote for {$contestantName} - {$eventName}",
            'og_description' => "🌟 Vote for {$contestantName} in {$eventName}! Secure mobile money voting with real-time results.",
            'twitter_title' => "Vote {$contestantName} - {$eventName}",
            'twitter_description' => "🌟 Vote for {$contestantName} in {$eventName}! Mobile money voting available. #Vote{$contestantName} #Ghana"
        ];
    }
    
    public static function generateBreadcrumbSchema($breadcrumbs) {
        $items = [];
        $position = 1;
        
        foreach ($breadcrumbs as $breadcrumb) {
            $items[] = [
                "@type" => "ListItem",
                "position" => $position++,
                "name" => $breadcrumb['name'],
                "item" => $breadcrumb['url']
            ];
        }
        
        return [
            "@context" => "https://schema.org",
            "@type" => "BreadcrumbList",
            "itemListElement" => $items
        ];
    }
    
    public static function generateEventSchema($event) {
        return [
            "@context" => "https://schema.org",
            "@type" => "Event",
            "name" => $event['name'],
            "description" => $event['description'],
            "startDate" => $event['start_date'],
            "endDate" => $event['end_date'],
            "eventStatus" => "https://schema.org/EventScheduled",
            "eventAttendanceMode" => "https://schema.org/OnlineEventAttendanceMode",
            "location" => [
                "@type" => "VirtualLocation",
                "url" => APP_URL . "/events/" . $event['code']
            ],
            "organizer" => [
                "@type" => "Organization",
                "name" => "SmartCast Ghana",
                "url" => APP_URL
            ],
            "offers" => [
                "@type" => "Offer",
                "price" => $event['vote_price'] ?? "1.00",
                "priceCurrency" => "GHS",
                "availability" => "https://schema.org/InStock"
            ]
        ];
    }
    
    public static function getKeywordDensityOptimizedContent($baseContent, $targetKeywords) {
        $keywords = explode(', ', $targetKeywords);
        $primaryKeywords = array_slice($keywords, 0, 5); // Focus on top 5 keywords
        
        // Add keyword-rich content suggestions
        $suggestions = [
            'digital voting Ghana' => 'Experience the future of democratic participation with digital voting in Ghana.',
            'mobile money voting' => 'Vote conveniently using your mobile money wallet - MTN, Vodafone, or AirtelTigo.',
            'secure voting system' => 'Our secure voting system ensures your vote is protected with bank-level encryption.',
            'real-time voting results' => 'Watch real-time voting results as they update live during the event.',
            'Ghana voting platform' => 'Join thousands of Ghanaians using our trusted voting platform.'
        ];
        
        return $baseContent;
    }
    
    public static function generateSitemap() {
        $urls = [
            [
                'loc' => APP_URL,
                'changefreq' => 'daily',
                'priority' => '1.0'
            ],
            [
                'loc' => APP_URL . '/about',
                'changefreq' => 'weekly',
                'priority' => '0.8'
            ],
            [
                'loc' => APP_URL . '/events',
                'changefreq' => 'daily',
                'priority' => '0.9'
            ],
            [
                'loc' => APP_URL . '/verify-receipt',
                'changefreq' => 'monthly',
                'priority' => '0.7'
            ]
        ];
        
        return $urls;
    }
}
