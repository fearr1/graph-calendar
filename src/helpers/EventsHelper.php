<?php

namespace Helpers;

use Microsoft\Graph\Graph;
use Wrappers\EventWrapper;

class EventsHelper
{
    /** @var Graph $graph */
    private $graph;

    public function __construct(Graph $graph)
    {
        $this->graph = $graph;
    }

    /**
     * @param EventWrapper $eventWrapper
     * @return int
     */
    public function createEvent(EventWrapper $eventWrapper): int
    {
        $result = $this->graph
            ->createRequest('POST', '/me/calendar/events')
            ->attachBody($eventWrapper->getEvent())
            ->execute();


        return $result->getStatus();
    }
}