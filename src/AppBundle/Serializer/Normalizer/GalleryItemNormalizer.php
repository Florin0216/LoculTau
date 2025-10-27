<?php

namespace AppBundle\Serializer\Normalizer;

use AppBundle\Entity\GalleryItem;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

class GalleryItemNormalizer implements NormalizerInterface
{
    public function __construct(
        #[Autowire(service: 'serializer.normalizer.object')]
        protected NormalizerInterface $normalizer,
        protected Security $security,
        protected UploaderHelper $uploaderHelper,
        protected ParameterBagInterface  $params,
    )
    {
    }

    /**
     * @inheritDoc
     */
    public function normalize(mixed $data, ?string $format = null, array $context = []): array|string|int|float|bool|\ArrayObject|null
    {
        $normalizedData = $this->normalizer->normalize($data, $format, $context, );

        if($this->uploaderHelper->asset($data, 'imageFile')){
            $normalizedData['image']['url'] = $this->params->get('public_files_dir') . $this->uploaderHelper->asset($data, 'imageFile');
        }

        if($this->uploaderHelper->asset($data, 'thumbnailFile')){
            $normalizedData['thumbnail']['url'] = $this->params->get('public_files_dir') . $this->uploaderHelper->asset($data, 'thumbnailFile');
        }

        return $normalizedData;
    }

    /**
     * @inheritDoc
     */
    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        return $data instanceof GalleryItem;
    }

    /**
     * @inheritDoc
     */
    public function getSupportedTypes(?string $format): array
    {
        return [
            GalleryItem::class => true,
        ];
    }
}
