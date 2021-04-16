<?php
declare(strict_types=1);

namespace SwaggerCustom\Lib\Extension;

/**
 * Interface ExtensionInterface
 *
 * @package SwaggerCustom\Lib\Extension
 */
interface ExtensionInterface
{
    /**
     * Whether this extension can be supported. For instance, if the extension requires a plugin such as Search, then
     * you would check if that plugin is loaded and return a boolean result
     *
     * @return bool
     */
    public function isSupported(): bool;

    /**
     * This method will load any custom annotations provided by the extension.
     *
     * @example SwaggerCustom\Lib\AnnotationLoader
     * @return void
     */
    public function loadAnnotations(): void;

    /**
     * This will register the listener
     *
     * @see https://book.cakephp.org/4/en/core-libraries/events.html#registering-anonymous-listeners
     * @return void
     */
    public function registerListeners(): void;
}
