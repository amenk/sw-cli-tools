<?php

namespace ShopwareCli;

use Symfony\Component\Console\Helper\DialogHelper;

class Utilities
{

    /**
     * Checks if a given path is a shopware installation (by checking for shopware.php)
     *
     * @param $path
     * @return bool
     */
    public function isShopwareInstallation($path)
    {
        return is_readable($path . '/shopware.php');
    }

    /**
     * Ask for a valid shopware path until the user enters it
     *
     * @param null $shopwarePath
     * @param $output
     * @param DialogHelper $dialog
     * @return mixed|null|string
     */
    function getValidShopwarePath($shopwarePath=null, $output, DialogHelper $dialog)
    {
        if (!$shopwarePath) {
            $shopwarePath = realpath(getcwd());
        }

        if ($this->isShopwareInstallation($shopwarePath)) {
            return $shopwarePath;
        }

        return $dialog->askAndValidate($output, "Path to your Shopware installation: ", array($this, 'validateShopwarePath'));

    }

    /**
     * Shopware path validator - can be used in askAndValidate methods
     *
     * @param $shopwarePath
     * @return string
     * @throws \RuntimeException
     */
    public function validateShopwarePath($shopwarePath)
    {
        $shopwarePathReal = realpath($shopwarePath);

        if (!$this->isShopwareInstallation($shopwarePathReal)) {
            throw new \RuntimeException("{$shopwarePathReal} is not a valid shopware path");
        }
        return $shopwarePathReal;
    }

    /**
     * This could / should be switched do symfony's process component
     * Currently it seems to have issues with realtime output,
     * so keeping "exec" for the time being
     *
     * @param $cmd
     * @param bool $mayFail
     * @return string
     * @throws \RuntimeException
     */
    function executeCommand($cmd, $mayFail=false)
    {
        $output = array();
        $returnCode = 0;
        exec($cmd, $output, $returnCode);

        if (!$mayFail && $returnCode !== 0) {
            throw new \RuntimeException(sprintf("An exception occurred: %s", implode("\n", $output)));
        }

        return implode("\n", $output) . "\n";
    }

    /**
     * Clears the screen in the terminal
     */
    function cls()
    {
        system('clear');
    }

    /**
     * Changes a directory
     *
     * @param $path
     */
    function changeDir($path)
    {
        if (!chdir($path)) {
            echo "Could not cd into $path";
            exit(1);
        }
    }
}