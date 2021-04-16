<?php
declare(strict_types=1);

namespace SwaggerCustom\Lib\Utility;

use LogicException;
use SwaggerCustom\Lib\Configuration;
use SwaggerCustom\Lib\Exception\SwaggerCustomRunTimeException;

/**
 * Class ValidateConfiguration
 *
 * @package SwaggerCustom\Lib\Utility\
 */
class ValidateConfiguration
{
    /**
     * Validates the supplied Configuration, if null is passed then it will create an instance of Configuration in the
     * local scope and validate that
     *
     * @param \SwaggerCustom\Lib\Configuration|null $config Configuration or null
     * @return void
     */
    public static function validate(?Configuration $config): void
    {
        $config = $config ?? new Configuration();
        $ymlFile = $config->getYml();

        if (empty($ymlFile) || !strstr($ymlFile, '.yml')) {
            throw new LogicException('YML file is required, given ' . $ymlFile);
        }

        if (!file_exists($ymlFile)) {
            throw new LogicException('YML file not found, try specifying full path, given ' . $ymlFile);
        }

        $prefix = $config->getPrefix();

        if (empty($prefix)) {
            throw new LogicException('Prefix is required');
        }

        $output = $config->getJson();

        if (!file_exists($output) && !touch($output)) {
            throw new SwaggerCustomRunTimeException(
                'Unable to create swagger file. Try creating an empty file first or checking permissions'
            );
        }
    }
}
