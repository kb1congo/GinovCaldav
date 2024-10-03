<?php

namespace Ginov\CaldavPlugs;

use InvalidArgumentException;
use Ginov\CaldavPlugs\Plateforms\Baikal;
use Ginov\CaldavPlugs\Plateforms\Google;
use Ginov\CaldavPlugs\Plateforms\Zimbra;
use Ginov\CaldavPlugs\Dto\EventCalDAV;
use Ginov\CaldavPlugs\Dto\CalendarCalDAV;
use Ginov\CaldavPlugs\PlateformInterface;
use Ginov\CaldavPlugs\Plateforms\Outlook;
use Ginov\CaldavPlugs\Plateforms\Bluemind;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

abstract class Factory implements PlateformInterface
{
    protected string $srvUrl;

    //******** for setCalendar (depricate) */
    public string $calendarID;

    private static array $_plateformMap = [
        'baikal' => Baikal::class,
        'google' => Google::class,
        'outlook' => Outlook::class,
        'zimbra' => Zimbra::class,
        'bluemind' => Bluemind::class,
    ];

    /**
     * @param string $type
     * @return self
     */
    public static function create(string $type, ParameterBagInterface $params): self
    {
        if (!array_key_exists($type, self::$_plateformMap)) {
            throw new InvalidArgumentException("Invalid plateform type: $type");
        }

        $className = self::$_plateformMap[$type];
        return new $className($params);
    }

    /**
     * @param string $type
     * @return self
     */
    public function getInstance(string $type, string $url): self
    {
        if (!array_key_exists($type, self::$_plateformMap)) {
            throw new InvalidArgumentException("Invalid plateform type: $type");
        }

        $className = self::$_plateformMap[$type];
        return new $className($url);
    }

    /**
     * Convert calendar format from Platefom Calendar to CalendarCalDAV
     *
     * @param [type] $plateformCalendar
     * @param string $calID
     * @return CalendarCalDAV
     */
    abstract protected static function parseCalendar($plateformCalendar, string $calID): CalendarCalDAV;

    /**
     * Convert event format from Platefom Event to CalendarCalDAV
     *
     * @param [type] $plateformEvent
     * @return EventCalDAV
     */
    abstract protected static function parseEvent($plateformEvent): EventCalDAV;

    /**
     * Convert Attendee format from Platefom Attendee to Attendee
     *
     * @param array $attendees
     * @return array
     */
    abstract protected static function parseAttendees(array $attendees): array;

    /**
     * Convert Attendee format from Attendee to Platefom Attendee
     *
     * @param array $attendees
     * @return array
     */
    abstract protected static function toPlateformAttendees(array $attendees): array;

    /**
     * Parse credentials (obsolete*)
     *
     * @param string $credentials
     * @return PlateformUserInterface
     */
    abstract protected static function parseCredentials(string $credentials): PlateformUserInterface;    

}
