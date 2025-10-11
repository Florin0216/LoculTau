<?php

namespace AppBundle\Vich\Uploader\Namer;

use Random\RandomException;
use Vich\UploaderBundle\Mapping\PropertyMapping;
use Vich\UploaderBundle\Naming\ConfigurableInterface;
use Vich\UploaderBundle\Naming\DirectoryNamerInterface;
use Vich\UploaderBundle\Util\Transliterator;

class CustomDirectoryNamer implements DirectoryNamerInterface, ConfigurableInterface
{

    protected int $charsPerDir = 2;

    protected int $dirs = 1;

    public function __construct(protected Transliterator $transliterator)
    {

    }


    /**
     * @param array $options Options for this namer. The following options are accepted:
     *                       - chars_per_dir: how many chars use for each dir.
     *                       - dirs: how many dirs create
     */
    public function configure(array $options): void
    {
        $options = \array_merge(['chars_per_dir' => $this->charsPerDir, 'dirs' => $this->dirs], $options);

        $this->charsPerDir = $options['chars_per_dir'];
        $this->dirs = $options['dirs'];
    }

    public function directoryName($object, PropertyMapping $mapping): string
    {
        $fileName = $mapping->getFileName($object);

        $parts = [];
        for ($i = 0, $start = 0; $i < $this->dirs; $i++, $start += $this->charsPerDir) {
            $parts[] = \substr($fileName, $start, $this->charsPerDir);
        }

        return implode('/', $parts);
    }
}
