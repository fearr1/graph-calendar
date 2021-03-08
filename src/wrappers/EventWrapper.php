<?php

namespace Wrappers;

use Microsoft\Graph\Model\Event;

class EventWrapper
{
    /** @var \Microsoft\Graph\Model\Attendee[] $attendees */
    private $attendees = [];

    /** @var  string $timeZone */
    private $timeZone = 'Europe/Budapest';

    /** @var  \Microsoft\Graph\Model\Event $graphEvent */
    private $graphEvent;

    public function __construct(string $timeZone = '')
    {
        $this->graphEvent = new \Microsoft\Graph\Model\Event();
        if (!empty($timeZone)) {
            $this->timeZone = $timeZone;
        }
    }

    /**
     * @param string $name
     * @param string $email
     * @return EventWrapper
     */
    public function addAttendee(string $name, string $email): EventWrapper
    {
        $emailAddress = new \Microsoft\Graph\Model\EmailAddress();
        $emailAddress
            ->setAddress($email)
            ->setName($name);

        $attendee = new \Microsoft\Graph\Model\Attendee();
        $attendee->setEmailAddress($emailAddress);

        $this->attendees[] = $attendee;

        $this->graphEvent->setAttendees($this->attendees);
        return $this;
    }

    /**
     * @param int $unixTime
     * @return EventWrapper
     */
    public function setStart(int $unixTime): EventWrapper
    {
        $dateTime = date('Y-m-d', $unixTime) . 'T' . date('H:i:s', $unixTime);

        $start = new \Microsoft\Graph\Model\DateTimeTimeZone();
        $start
            ->setTimeZone($this->timeZone)
            ->setDateTime($dateTime);

        $this->graphEvent->setStart($start);
        return $this;
    }

    /**
     * @param int $unixTime
     * @return EventWrapper
     */
    public function setEnd(int $unixTime): EventWrapper
    {
        $dateTime = date('Y-m-d', $unixTime) . 'T' . date('H:i:s', $unixTime);

        $end = new \Microsoft\Graph\Model\DateTimeTimeZone();
        $end
            ->setTimeZone($this->timeZone)
            ->setDateTime($dateTime);

        $this->graphEvent->setEnd($end);
        return $this;
    }

    /**
     * @param string $displayName
     * @return EventWrapper
     */
    public function setLocation(string $displayName): EventWrapper
    {
        $location = new \Microsoft\Graph\Model\Location();
        $location->setDisplayName($displayName);

        $this->graphEvent->setLocation($location);
        return $this;
    }

    /**
     * @param string $subjectName
     * @return EventWrapper
     */
    public function setSubject(string $subjectName): EventWrapper
    {
        $this->graphEvent->setSubject($subjectName);
        return $this;
    }

    /**
     * @param string $content
     * @param string $type can be one of these options: 'html', 'text'
     * @return EventWrapper
     */
    public function setBody(string $content, string $type): EventWrapper
    {
        $itemBody = new \Microsoft\Graph\Model\ItemBody();
        $bodyType = new \Microsoft\Graph\Model\BodyType($type);
        $itemBody
            ->setContent($content)
            ->setContentType($bodyType);

        $this->graphEvent->setBody($itemBody);
        return $this;
    }

    /**
     * @return \Microsoft\Graph\Model\Event
     */
    public function getEvent(): Event
    {
        return $this->graphEvent;
    }
}