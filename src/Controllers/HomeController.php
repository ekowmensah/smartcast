<?php

namespace SmartCast\Controllers;

use SmartCast\Models\Event;

/**
 * Home Controller
 */
class HomeController extends BaseController
{
    private $eventModel;
    
    public function __construct()
    {
        parent::__construct();
        $this->eventModel = new Event();
    }
    
    public function index()
    {
        // Get public events
        $events = $this->eventModel->getPublicEvents();
        
        $this->view('home/index', [
            'events' => $events,
            'title' => 'Welcome to SmartCast'
        ]);
    }
}
