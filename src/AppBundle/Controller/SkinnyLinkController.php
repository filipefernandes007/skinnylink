<?php

namespace AppBundle\Controller;

use AppBundle\Entity\SkinnyLink;
use AppBundle\Service\SkinnyLinkService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Skinnylink controller.
 */
class SkinnyLinkController extends Controller
{

    /**
     * Creates a new skinnyLink entity.
     *
     * @Route("", name="skinnylink_new", methods={"GET", "POST"})
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function newAction(Request $request)
    {
        $skinnyLink = new Skinnylink();
        $form = $this->createForm('AppBundle\Form\SkinnyLinkType', $skinnyLink);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                /** @var SkinnyLink $entity */
                $entity = $this->container->get(SkinnyLinkService::class)->create($skinnyLink);
            } catch (\InvalidArgumentException $e) {
                $this->addFlash('error', $e->getMessage());
            }

            if (isset($entity)) {
                return $this->redirectToRoute('skinnylink_show', array('id' => $entity->getId()));
            }
        }

        return $this->render('skinnylink/new.html.twig', array(
            'skinnyLink' => $skinnyLink,
            'form'       => $form->createView(),
        ));
    }

    /**
     * Finds and displays a skinnyLink entity.
     *
     * @Route("/{id}", name="skinnylink_show", methods={"GET"})
     * @param string $id
     * @return Response
     */
    public function showAction(string $id) : Response
    {
        /** @var SkinnyLink|null $skinnyLink */
        $skinnyLink = null;

        /** @var JsonResponse $response */
        $response = $this->forward('AppBundle\Controller\ApiController::getAction', array(
            'id' => $id,
        ));

        $object = json_decode($response->getContent());

        if (get_object_vars($object) && property_exists($object, 'id')) {
            $skinnyLink = $this->getDoctrine()
                               ->getRepository(SkinnyLink::class)
                               ->find($object->id);
        }

        return $this->render('skinnylink/show.html.twig', array(
            'skinnyLink' => $skinnyLink,
            'id'         => $id
        ));
    }

    /**
     * @Route("/skinny-link/{url}", requirements={"url":"[$|_|.|+|!|%]*[a-zA-Z0-9]+"},
     *                              name="skinnylink_redirect_to_url",
     *                              methods={"GET"})
     * @param string $url
     * @return Response
     * @throws \Exception
     */
    public function skinnyLinkAction(string $url) : Response {
        /** @var SkinnyLink $skinnyLink */
        $skinnyLink = $this->getDoctrine()
                           ->getManager()
                           ->getRepository(SkinnyLink::class)
                           ->findOneBy(['skinnyUrl' => $url]);

        if ($skinnyLink !== null) {
            return $this->redirect($skinnyLink->getUrl());
        }

        return $this->render('skinnylink/new.html.twig');
    }
}
