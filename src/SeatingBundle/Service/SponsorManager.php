<?php

namespace SeatingBundle\Service;

use SeatingBundle\Entity\Sponsor;
use Symfony\Component\Uid\Uuid;

class SponsorManager
{

    public function newInstance(): Sponsor
    {
        $sponsor = new Sponsor();

        $uuid = Uuid::v4()->toRfc4122();

        $sponsor
            ->setUuid($uuid);

        return $sponsor;
    }

}
