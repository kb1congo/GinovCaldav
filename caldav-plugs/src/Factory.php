<?php

namespace Ginov\CaldavPlugs;

use InvalidArgumentException;
use Ginov\CaldavPlugs\Plateforms\Baikal;
use Ginov\CaldavPlugs\Plateforms\Google;
use Ginov\CaldavPlugs\Plateforms\Zimbra;
use Ginov\CaldavPlugs\PlateformInterface;
<<<<<<< HEAD
use Ginov\CaldavPlugs\Plateforms\Bluemind;
use Ginov\CaldavPlugs\Plateforms\Outlook;
=======
use Ginov\CaldavPlugs\Plateforms\OutLook;
>>>>>>> e09e1f8e3a0df83e58ed0c831f917fb0f9fa8b56
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

abstract class Factory implements PlateformInterface
{
    protected string $srvUrl;

    private static array $_plateformMap = [
        'baikal' => Baikal::class,
        'google' => Google::class,
        'outlook' => Outlook::class,
        'zimbra' => Zimbra::class,
<<<<<<< HEAD
        'bluemind' => Bluemind::class,
=======
        'outlook' => OutLook::class,
>>>>>>> e09e1f8e3a0df83e58ed0c831f917fb0f9fa8b56
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
}
