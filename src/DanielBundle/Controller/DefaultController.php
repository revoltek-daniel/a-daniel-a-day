<?php

namespace DanielBundle\Controller;

use DanielBundle\Entity\Image;
use DanielBundle\Manager\ImageManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class DefaultController
 *
 * @package DanielBundle\Controller
 */
class DefaultController extends Controller
{
    /**
     * @Route("/", name="index")
     * @Method("GET")
     *
     * @param ImageManager $imageManager
     *
     * @return array
     * @Template()
     */
    public function indexAction(ImageManager $imageManager)
    {
        $image = $imageManager->getLastEntry();
        $image = reset($image);

        $beforeEntry = $imageManager->getBeforeEntry($image->getId());

        return [
            'image' => $image,
            'before' => reset($beforeEntry),
        ];
    }

    /**
     *
     * @Route("/image/{id}/{slug}", name="image_detail")
     * @Method("GET")
     * @param Image        $image
     * @param ImageManager $imageManager
     *
     * @return array
     * @Template()
     */
    public function showAction(Image $image, ImageManager $imageManager)
    {
        $beforeEntry = $imageManager->getBeforeEntry($image->getId());
        $nextEntry = $imageManager->getNextEntry($image->getId());

        return [
            'image' => $image,
            'before' => reset($beforeEntry),
            'next' => reset($nextEntry),
        ];
    }

    /**
     *
     * @Route("/rss", name="rss")
     * @Method("GET")
     *
     * @param ImageManager $imageManager
     *
     * @return Response
     */
    public function rssAction(ImageManager $imageManager)
    {
        $images = $imageManager->findAllByReverseOrder();

        $response = new Response();
        $response->headers->set('Content-Type', 'text/xml');

        return $this->render(
            "@Daniel/Default/rss.xml.twig",
            [
                'images' => $images,
            ],
            $response
        );
    }

    /**
     * @Route("/overview", name="image_overview")
     * @Method("GET")
     *
     * @return array
     * @Template()
     */
    public function overviewAction()
    {
        $em = $this->getDoctrine()->getManager();

        $images = $em->getRepository(Image::class)->findAll();

        return [
            'images' => $images,
        ];
    }
}
