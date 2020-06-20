<?php

namespace App\Controller;

use App\Entity\BrowserStatistic;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class HomeController
 *
 * @package App\Controller
 */
class HomeController extends AbstractController
{
    /**
     * @Route("/", name="index")
     * @Template
     */
    public function index()
    {
        return [
        ];
    }

    /**
     * @Template(template="home/_browser_statistic.html.twig")
     */
    public function browserStatistic()
    {
        return [
            'browser_statistic' => $this->getDoctrine()->getRepository(BrowserStatistic::class)->calculatedData(),
        ];
    }
}
