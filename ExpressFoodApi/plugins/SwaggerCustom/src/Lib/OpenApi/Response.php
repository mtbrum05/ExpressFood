<?php
declare(strict_types=1);

namespace SwaggerCustom\Lib\OpenApi;

use JsonSerializable;

/**
 * Class Response
 *
 * @package SwaggerCustom\Lib\OpenApi
 * @see https://swagger.io/docs/specification/describing-responses/
 */
class Response implements JsonSerializable
{
    /**
     * @var string
     */
    private $code;

    /**
     * @var string
     */
    private $description = '';

    /**
     * @var \SwaggerCustom\Lib\OpenApi\Content[]
     */
    private $content = [];

    /**
     * @return array
     */
    public function toArray(): array
    {
        $vars = get_object_vars($this);
        unset($vars['code']);
        if (empty($vars['content'])) {
            unset($vars['content']);
        }

        return $vars;
    }

    /**
     * @return array|mixed
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @param string|int $code Http status code
     * @return $this
     */
    public function setCode($code)
    {
        $this->code = (string)$code;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description Description
     * @return $this
     */
    public function setDescription(string $description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return \SwaggerCustom\Lib\OpenApi\Content[]
     */
    public function getContent(): array
    {
        return $this->content;
    }

    /**
     * @param string $mimeType Mime type i.e. application/json, application/xml
     * @return \SwaggerCustom\Lib\OpenApi\Content|null
     */
    public function getContentByMimeType(string $mimeType): ?Content
    {
        return $this->content[$mimeType] ?? null;
    }

    /**
     * Sets the array of Content[]
     *
     * @param \SwaggerCustom\Lib\OpenApi\Content[] $contents Content
     * @return $this
     */
    public function setContent(array $contents)
    {
        $this->content = $contents;

        return $this;
    }

    /**
     * Appends to array of Content[]
     *
     * @param \SwaggerCustom\Lib\OpenApi\Content $content Content
     * @return $this
     */
    public function pushContent(Content $content)
    {
        $this->content[$content->getMimeType()] = $content;

        return $this;
    }
}
