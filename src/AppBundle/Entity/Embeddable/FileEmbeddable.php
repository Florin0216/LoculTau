<?php

namespace AppBundle\Entity\Embeddable;

use AppBundle\Entity\Trait\VichFileTrait;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
class FileEmbeddable
{
    use VichFileTrait;
}
