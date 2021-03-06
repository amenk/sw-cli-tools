<?php
/**
 * (c) shopware AG <info@shopware.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Shopware\PluginCreator\Services;

/**
 * Class Template is a very simple template system, which will make it easier,
 * to separate view and controller logic
 */
class Template
{
    private $_templateVars = [];

    private $_path = '';

    /**
     * Set the base path of the templates
     *
     * @param string $path
     */
    public function setTemplatePath($path): void
    {
        $this->_path = rtrim($path, '/') . '/';
    }

    /**
     * Assign a variable to the template.
     *
     * @param string $name
     */
    public function assign($name, $value): void
    {
        $this->_templateVars[$name] = $value;
    }

    /**
     * Render the given template and return the result
     */
    public function fetch($_template): string
    {
        return $this->doRender($_template, true);
    }

    /**
     * Render the given template and return the result
     */
    public function display($_template): void
    {
        $this->doRender($_template);
    }

    public function errorReporter($severity, $message, $filename, $lineno): void
    {
        ob_clean();
        throw new \ErrorException($message . ': ' . $filename . ', line ' . $lineno, 0, $severity, $filename, $lineno);
    }

    /**
     * Will actually render the template
     *
     * While rendering the template, any notice / warning will result in an exception, mainly to avoid the generation
     * of plugins with notices inside. So be strict here and switch back to default error reporting mode after that
     *
     * @param bool $return
     *
     * @return string
     */
    private function doRender($_template, $return = false): ?string
    {
        // Extract the template vars into the current scope.
        if ($this->_templateVars) {
            extract($this->_templateVars, EXTR_SKIP);
        }

        if ($return) {
            ob_start();
        }
        $old = set_error_handler([$this, 'errorReporter']);
        include $this->_path . $_template;
        set_error_handler($old);
        if ($return) {
            return ob_get_clean();
        }
    }
}
