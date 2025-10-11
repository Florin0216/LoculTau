<?php

namespace AppBundle\Vich\Uploader\Namer;

use Vich\UploaderBundle\Mapping\PropertyMapping;
use Vich\UploaderBundle\Naming\NamerInterface;
use Vich\UploaderBundle\Util\Transliterator;

class CustomFileNamer implements NamerInterface
{
    public function __construct(
        protected Transliterator $transliterator
    )
    {
    }

    /**
     * @inheritDoc
     */
    public function name(object $object, PropertyMapping $mapping): string
    {
        $file = $mapping->getFile($object);
        $originalName = $file->getClientOriginalName();

        $name = strtolower($this->transliterator->transliterate($originalName));

        return substr(md5(\uniqid(rand())), 0, 10) .  '_' . $name;
    }
}
