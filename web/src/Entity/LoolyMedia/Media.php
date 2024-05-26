<?php

namespace App\Entity\LoolyMedia;

use Oosaulenko\MediaBundle\Entity\Media as BaseMedia;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'media')]
class Media extends BaseMedia
{
}