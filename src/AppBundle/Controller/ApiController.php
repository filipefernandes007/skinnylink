<?php
    /**
     * Filipe <filipefernandes007@gmail.com>
     */

    namespace AppBundle\Controller;

    use AppBundle\Entity\SkinnyLink;
    use AppBundle\Repository\SkinnyLinkRepository;
    use AppBundle\Service\SkinnyLinkService;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
    use Symfony\Bundle\FrameworkBundle\Controller\Controller;
    use Symfony\Component\HttpFoundation\JsonResponse;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;

    /**
     * Class ApiController
     * @package AppBundle\Controller
     *
     * @Route("/api")
     *
     */
    class ApiController extends Controller
    {
        /**
         * @Route("/new", name="skinnylink_new_api")
         * @Method({"POST"})
         * @param Request $request
         * @return JsonResponse
         * @throws \Exception
         */
        public function newAction(Request $request) : JsonResponse {
            /** @var SkinnyLink|null $entity */
            $entity = null;

            /** @var string $error */
            $error  = null;

            $url = $request->get('url');

            if (!empty($url)) {
                $skinnyLink = new SkinnyLink();
                $skinnyLink->setUrl($url);

                try {
                    $entity = $this->container->get(SkinnyLinkService::class)->create($skinnyLink);
                } catch (\InvalidArgumentException $e) {
                    $error = $e->getMessage();
                }
            }

            return new JsonResponse(['data' => $entity !== null ? $entity->toArray() : null, 'error' => $error], $error === null ? 200 : 404);
        }

        /**
         * Finds and displays a skinnyLink entity.
         *
         * @Route("/{id}", name="skinnylink_get_api")
         * @Method("GET")
         * @param string $id
         * @return JsonResponse
         */
        public function getAction(string $id) : JsonResponse
        {
            /** @var SkinnyLinkRepository $repository */
            $repository = $this->getDoctrine()->getRepository(SkinnyLink::class);

            if (filter_var($id, FILTER_VALIDATE_INT)) {
                $skinnyLink = $repository->find($id);
            } else {
                $skinnyLink = $repository->findOneBy(['skinnyUrl' => $id]);
            }

            // One last try, with original url
            if ($skinnyLink === null) {
                $skinnyLink = $repository->findOneBy(['url' => $id]);
            }

            if ($skinnyLink !== null) {
                return new JsonResponse($skinnyLink->toArray());
            }

            return new JsonResponse(null, 404);
        }
    }