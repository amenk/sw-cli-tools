<?php
/**
 * (c) shopware AG <info@shopware.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ShopwareCli\Tests;

use ShopwareCli\Application;

trait ApplicationTestCaseTrait
{
    /**
     * @var Application
     */
    private static $application;

    public static function getApplication(): Application
    {
        if (!self::$application) {
            self::bootApplication();
        }

        return self::$application;
    }

    public static function bootApplication(): void
    {
        self::$application = new Application(\TestLoaderProvider::getLoader());
    }

    /**
     * @after
     */
    protected function destroyApplicationAfter(): void
    {
        self::$application = false;
    }
}
