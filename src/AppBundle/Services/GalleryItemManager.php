<?php

namespace AppBundle\Services;

use AppBundle\Entity\GalleryItem;
use Doctrine\ORM\EntityManagerInterface;
use Imagine\Gd\Imagine;
use Imagine\Image\Box;
use Imagine\Image\Point;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class GalleryItemManager
{
    public function __construct(
        protected EntityManagerInterface $em,
    )
    {
    }

    public function generateThumbnail(GalleryItem $galleryItem, int $width, int $height): void
    {
        $uploadedFile = $galleryItem->getImageFile();

        $imagine = new Imagine();
        $image = $imagine->open($uploadedFile->getPathname());

        $size = $image->getSize();
        $originalWidth = $size->getWidth();
        $originalHeight = $size->getHeight();

        $scale = max($width / $originalWidth, $height / $originalHeight);
        $newWidth = (int)($originalWidth * $scale);
        $newHeight = (int)($originalHeight * $scale);

        $resized = $image->resize(new Box($newWidth, $newHeight));

        $cropX = (int)(($newWidth - $width) / 2);
        $cropY = (int)(($newHeight - $height) / 2);
        $thumbnail = $resized->crop(new Point($cropX, $cropY), new Box($width, $height));

        $thumbTmpPath = tempnam(sys_get_temp_dir(), 'thumb_') . '.jpg';
        $thumbnail->save($thumbTmpPath);

        $thumbnailFile = new UploadedFile(
            $thumbTmpPath,
            $uploadedFile->getClientOriginalName(),
            mime_content_type($thumbTmpPath),
            null,
            true
        );

        $galleryItem->setThumbnailFile($thumbnailFile);
    }

}
