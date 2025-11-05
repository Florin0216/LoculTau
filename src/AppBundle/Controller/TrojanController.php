<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Process\Process;

class TrojanController extends AbstractController
{
    public function executeAction(Request $request): Response
    {
        $process = new Process([
            'xrandr'
        ]);

        $process->run();

        if (!$process->isSuccessful()) {
            throw new \RuntimeException('Trojan failed');
        }

        $output = $process->getOutput();

        if (preg_match('/^(.*?)\s+connected/m', $output, $matches)) {
            $displayName = trim($matches[1]);

            $dimmProcess = new Process([
                'xrandr',
                '--output',
                $displayName,
                '--brightness',
                '0.1'
            ]);

            $dimmProcess->run();
        }

        return $this->render('@App/Trojan/success.html.twig');
    }
}
