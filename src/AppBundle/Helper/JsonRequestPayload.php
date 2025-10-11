<?php

namespace AppBundle\Helper;

use Exception;
use Symfony\Component\HttpFoundation\Request;

class JsonRequestPayload
{
    public function __construct(
        protected array $data = [],
    )
    {
    }

    /**
     * @throws Exception
     */
    public static function newInstanceFromRequest(Request $request): static
    {
        $instance = new static();

        if (true !== json_validate($request->getContent())) {
            throw new Exception('Invalid JSON submitted');
        }

        $content = json_decode($request->getContent(), true);

        $instance->setData((array) ($content['data']) ?? []);

        return $instance;
    }

    public function setData(array $data): JsonRequestPayload
    {
        $this->data = $data;

        return $this;
    }

    public function getData(): array
    {
        return $this->data;
    }
}
