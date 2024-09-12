<?php

namespace Ginov\CaldavPlugs;

use InvalidArgumentException;
use Ginov\CaldavPlugs\Plateforms\Baikal;
use Ginov\CaldavPlugs\Plateforms\Google;
use Ginov\CaldavPlugs\Plateforms\Zimbra;
use Ginov\CaldavPlugs\PlateformInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

abstract class Plateform implements PlateformInterface
{

    protected string $srvUrl;

    private static array $_plateformMap = [
        'baikal' => Baikal::class,
        'google' => Google::class,
        'zimbra' => Zimbra::class,
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
