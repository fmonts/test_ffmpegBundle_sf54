<?php

declare(strict_types=1);

namespace App\Controller;

use FFMpeg\Coordinate\Dimension;
use FFMpeg\FFMpeg;
use FFMpeg\Filters\Video\ResizeFilter;
use FFMpeg\Format\Video\X264;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    /**
     * @Route("/")
     */
    public function resize(FFMpeg $FFMpeg): Response
    {
        if (!is_writable($this->getParameter('kernel.project_dir') . '/public')) {
            return new Response('<html><body>Cannot write to public directory</body></html>');
        }

        // Open video
        $video = $FFMpeg->open($this->getParameter('kernel.project_dir') . '/public/9968971-uhd_3840_2160_25fps.mp4');

        // Resize to 1280x720
        $video
            ->filters()
            ->resize(new Dimension(1280, 720), ResizeFilter::RESIZEMODE_INSET)
            ->synchronize();


        // Start transcoding and save video
        $video->save(new X264(), $this->getParameter('kernel.project_dir') . '/public/output.mp4');

        return new Response('<html><body>Video resized and saved</body></html>');
    }
}
