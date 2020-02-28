<?php declare(strict_types=1);

namespace Acme\App;

use Psr\Container\ContainerInterface;

/**
 * Containerをstaticに持つだけのもの
 */
class AppContainerHolder
{
    /** @var AppContainerInterface */
    static private $container;

    /**
     * @return AppContainerInterface
     */
    static public function instance()
    {
        if (!isset(self::$container)) {
            throw new \RuntimeException('not initialized');
        }
        return self::$container;
    }

    /**
     * @param AppContainerInterface $container
     */
    public static function init(ContainerInterface $container)
    {
        static::$container = $container;
    }
}
