<?php
declare(strict_types=1);

namespace SwaggerCustom\Lib\OpenApi;

use JsonSerializable;

/**
 * Class PathSecurity
 *
 * @package SwaggerCustom\Lib\OpenApi
 * @see https://swagger.io/docs/specification/authentication/
 */
class PathSecurity implements JsonSerializable
{
    /**
     * @var string
     */
    private $name = '';

    /**
     * @var string[]
     */
    private $scopes = [];

    /**
     * @return array|array[]
     */
    public function toArray(): array
    {
        return [
            $this->name => array_values($this->scopes),
        ];
    }

    /**
     * @return array|array[]|mixed
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name Name
     * @return $this
     */
    public function setName(string $name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return array
     */
    public function getScopes(): array
    {
        return $this->scopes;
    }

    /**
     * @param array $scopes Security scopes
     * @return $this
     */
    public function setScopes(array $scopes)
    {
        $this->scopes = $scopes;

        return $this;
    }
}
