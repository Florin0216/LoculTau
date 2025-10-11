<?php

namespace AppBundle\EventSubscriber;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mailer\Event\MessageEvent;
use Symfony\Component\Mime\BodyRendererInterface;
use Symfony\Component\Mime\MimeTypes;
use Twig\Environment;

class MailerMessageSubscriber implements EventSubscriberInterface
{

    public function __construct(
        protected Environment $twig,
        protected ?BodyRendererInterface $renderer,
        protected ParameterBagInterface $parameterBag,
    )
    {
    }

    private function parseHtml($html): string
    {
        $crawler = new Crawler($html);

        $crawler->filter('img')->each(function (Crawler $node, $i) {
            $newSrc = 'cid:' . 'ds-tickets@id_' . $i;

            $node->getNode(0)->setAttribute('src', $newSrc);
        });

        return $crawler->html();
    }

    private function getCidMap($html): array
    {
        $cidMap = [];

        $crawler = new Crawler($html);

        $crawler->filter('img')->each(function (Crawler $node, $i) use (&$cidMap) {
            $originalSrc = $node->attr('src');

            $cid = 'ds-tickets@id_' . $i;

            $cidMap[$cid] = $originalSrc;
        });

        return $cidMap;
    }

    public function onMessage(MessageEvent $event): void
    {

        $message = $event->getMessage();

        //Attempted to call an undefined method named "getHtmlTemplate
        $htmlTemplate = '';
        if(method_exists(get_class($message), 'getHtmlTemplate')) {
            $htmlTemplate = $this->twig->render($message->getHtmlTemplate(), $message->getContext());

            $responseHtml = $this->parseHtml($htmlTemplate);

            $message->htmlTemplate(null);
            $message->textTemplate(null);
            $message->html($responseHtml);
        }

        $publicHtmlDir = $this->parameterBag->get('private_files_dir');

        $mimeTypes = new MimeTypes();
        $allowedMimeTypes = ['image/png', 'image/jpeg', 'image/gif', 'image/jpg', 'image/webp'];

        foreach ($this->getCidMap($htmlTemplate) as $cid => $imageRelativeUrl) {
            $imageRelativeUrl = trim($imageRelativeUrl, '/');
            $imageAbsolutePath = $publicHtmlDir . DIRECTORY_SEPARATOR . $imageRelativeUrl;

            if (in_array($mimeTypes->guessMimeType($imageAbsolutePath), $allowedMimeTypes)) {
                $message->embedFromPath($imageAbsolutePath, name: $cid);
            }
        }
    }
    /**
     * @inheritDoc
     */
    public static function getSubscribedEvents(): array
    {
        return [
            MessageEvent::class => ['onMessage', 1]
        ];
    }
}
