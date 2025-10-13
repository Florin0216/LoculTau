<?php

namespace SeatingBundle\Form\DataTransformer;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class Base64ToFileTransformer implements DataTransformerInterface
{

    public function __construct(
        protected EntityManagerInterface $entityManager,
    )
    {
    }

    /**
     * @inheritDoc
     */
    public function transform(mixed $value): mixed
    {
        return $value;
    }

    /**
     * @inheritDoc
     */
    public function reverseTransform(mixed $value): UploadedFile
    {
        $base64 = $value['base64'];
        $originalName = $value['originalName'];

        if (preg_match('/^data:image\/(\w+);base64,/', $base64, $type)) {
            $base64 = substr($base64, strpos($base64, ',') + 1);
            $extension = strtolower($type[1]);
        }

        $data = base64_decode($base64);

        $tmpFile = tempnam(sys_get_temp_dir(), 'upload_') . '.' . $extension;
        file_put_contents($tmpFile, $data);

        return new UploadedFile(
            $tmpFile,
            $originalName,
            mime_content_type($tmpFile),
            null,
            true
        );
    }
}
