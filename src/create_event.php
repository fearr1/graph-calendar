<?php

require_once __DIR__ . '/config/cfg.php';
require_once __DIR__ . '/autoload.php';

$authHelper = new \Helpers\AuthHelper();
$authHelper->refreshTokens();
$accessToken = $authHelper->getTokens()['access_token'];

$parameters = getopt(
    null,
    [
        "subject:",
        "start:",
        "end:",
        "attendees:",
        "location:",
        "body:"
    ]
);

$requriedOptions = ['subject', 'start', 'end'];

if(array_diff($requriedOptions, array_keys($parameters))) {
    $msg = 'Please use all the required parameters..' . PHP_EOL . PHP_EOL;
    $msg .= 'Example usage: php create_event.php --subject="Drinking beer" --start="20.01.2021 13:00" --end="20.01.2021 14:30"' . PHP_EOL . PHP_EOL;
    $msg .= 'Optional parameters are:' . PHP_EOL;
    $msg .= '--location="Central park"' . PHP_EOL;
    $msg .= '--attendees="attendee1@example.com,example2@example.com"' . PHP_EOL;
    $msg .= '--body="description of the event"'. PHP_EOL;

    die($msg);
}

$eventWrapper = new \Wrappers\EventWrapper();
$eventWrapper
    ->setSubject($parameters['subject'])
    ->setStart(strtotime($parameters['start']))
    ->setEnd(strtotime($parameters['end']));

if(!empty($parameters['location'])) {
    $eventWrapper->setLocation($parameters['location']);
}

if(!empty($parameters['body'])) {
    $eventWrapper->setBody($parameters['body'], 'text');
}

if(!empty($parameters['attendees'])) {
    $attendees = preg_replace('/\s+/', '', $parameters['attendees']);
    $attendees = explode(',', $attendees);

    foreach($attendees as $attendee) {
        if(!filter_var($attendee, FILTER_VALIDATE_EMAIL)) {
            die($attendee . ' - email not valid');
        }
        
        $eventWrapper->addAttendee($attendee, $attendee);
    }
}

$graph = new \Microsoft\Graph\Graph();
$graph->setAccessToken($accessToken);

$eventsHelper = new \Helpers\EventsHelper($graph);

$status = $eventsHelper->createEvent($eventWrapper);
if($status === 201) {
    die('Event was successfully created !');
}

die('Something went wrong, try again later.');

