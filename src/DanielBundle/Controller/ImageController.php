<?php

namespace DanielBundle\Controller;

use DanielBundle\Entity\Image;
use DanielBundle\Manager\ImageManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\File;

/**
 * Class ImageController
 *
 * @Route("image")
 * @package DanielBundle\Controller
 */
class ImageController extends Controller
{
    /**
     * Lists all image entities.
     *
     * @Route("/", name="image_index")
     * @Method("GET")
     * @Template()
     * @param Request      $request
     * @param ImageManager $imageManager
     *
     * @return array
     */
    public function indexAction(Request $request, ImageManager $imageManager)
    {
        // $imageManager = $this->container->get('daniel.image.manager');
        $paginatedList = $imageManager->getPaginatedList($request->query->get('page', 1), 20);

        return ['pagination' => $paginatedList];
    }

    /**
     * Creates a new image entity.
     *
     * @param Request      $request
     * @param ImageManager $imageManager
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     * @Route("/new", name="image_new")
     * @Method({"GET", "POST"})
     * @Template()
     *
     * @throws \Exception
     */
    public function newAction(Request $request, ImageManager $imageManager)
    {
        $image = new Image();
        $form = $this->createForm(\DanielBundle\Form\Type\ImageType::class, $image);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $data = $form->getData();
            if ($data->getImage()) {
                $filename = $imageManager->uploadNewPicture($data->getImage(), $image->getId());

                $imageManager->removeOldPicture($image->getImage());
                $image->setImage($filename);
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($image);
            $em->flush();

            return $this->redirectToRoute('image_show', ['id' => $image->getId()]);
        }

        return [
            'image' => $image,
            'form'  => $form->createView(),
        ];
    }

    /**
     * Finds and displays a image entity.
     *
     * @Route("/{id}", name="image_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction(Image $image)
    {
        $deleteForm = $this->createDeleteForm($image);

        return [
            'image'       => $image,
            'delete_form' => $deleteForm->createView(),
        ];
    }

    /**
     * Displays a form to edit an existing image entity.
     *
     * @Route("/{id}/edit", name="image_edit")
     * @Method({"GET", "POST"})
     * @Template()
     *
     * @param Request      $request
     * @param Image        $image
     * @param ImageManager $imageManager
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     * @throws \Exception
     */
    public function editAction(Request $request, Image $image, ImageManager $imageManager)
    {
        $deleteForm = $this->createDeleteForm($image);

        $oldLogo = $image->getImage();

        if ($oldLogo) {
            $logo = new File($this->getParameter('daniel.file.path') . DIRECTORY_SEPARATOR . $oldLogo);
            $image->setImage($logo);
        }

        $editForm = $this->createForm(\DanielBundle\Form\Type\ImageType::class, $image);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {

            $data = $editForm->getData();
            if ($data->getImage()) {
                $filename = $imageManager->uploadNewPicture($data->getImage(), $image->getId());

                $imageManager->removeOldPicture($oldLogo);
                $image->setImage($filename);
            } else {
                $image->setImage($oldLogo);
            }

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('image_edit', ['id' => $image->getId()]);
        }

        return [
            'image'       => $image,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ];
    }

    /**
     * Deletes a image entity.
     *
     * @Route("/{id}", name="image_delete")
     * @Method("DELETE")
     *
     * @param Request      $request
     * @param Image        $image
     * @param ImageManager $imageManager
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request, Image $image, ImageManager $imageManager)
    {
        $form = $this->createDeleteForm($image);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $imageManager->removeOldPicture($image->getImage());

            $em = $this->getDoctrine()->getManager();
            $em->remove($image);
            $em->flush();
        }

        return $this->redirectToRoute('image_index');
    }

    /**
     * Creates a form to delete a image entity.
     *
     * @param Image $image The image entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Image $image)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('image_delete', ['id' => $image->getId()]))
            ->setMethod('DELETE')
            ->getForm();
    }
}
