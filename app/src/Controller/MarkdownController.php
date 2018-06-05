<?php

namespace App\Controller;

use Parsedown;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class MarkdownController extends Controller
{
    /**
     * @Route("/markdown/to-html", name="markdown_to_html")
     * @param Request $request
     * @return Response
     */
    public function toHtml(Request $request)
    {
        $parseDown = new Parsedown();
        $parseDown->setSafeMode(true);

        if (!$content = $request->get('content')) {
            return new Response("<b>no content!</b>");
        }

        return new Response($parseDown->text($content));
    }
}
