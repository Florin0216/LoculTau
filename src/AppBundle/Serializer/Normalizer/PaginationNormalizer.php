<?php

namespace AppBundle\Serializer\Normalizer;

use Knp\Bundle\PaginatorBundle\Pagination\SlidingPagination;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class PaginationNormalizer implements NormalizerInterface
{

    /**
     * @inheritDoc
     */
    public function normalize(mixed $data, ?string $format = null, array $context = []): array|string|int|float|bool|\ArrayObject|null
    {
        $paginationData = $data->getPaginationData();

        return [
            'page' => $paginationData['current'],
            'itemsPerPage' => $paginationData['numItemsPerPage'],
            'pageCount' => $paginationData['pageCount'],
            'itemCount' => $paginationData['totalCount'],
        ];
    }

    /**
     * @inheritDoc
     */
    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        return $data instanceof SlidingPagination;
    }

    /**
     * @inheritDoc
     */
    public function getSupportedTypes(?string $format): array
    {
        return [
            SlidingPagination::class => true
        ];
    }
}
