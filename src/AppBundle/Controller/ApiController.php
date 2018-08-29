<?php
    /**
     * Filipe <filipefernandes007@gmail.com>
     */

    namespace AppBundle\Controller;

    use AppBundle\Entity\SkinnyLink;
    use AppBundle\Repository\SkinnyLinkRepository;
    use AppBundle\Service\SkinnyLinkService;
    use Monolog\Logger;
    use Psr\Log\LoggerInterface;
    use Symfony\Component\Routing\Annotation\Route;
    use Symfony\Bundle\FrameworkBundle\Controller\Controller;
    use Symfony\Component\HttpFoundation\JsonResponse;
    use Symfony\Component\HttpFoundation\Request;

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
         * @Route("", name="skinnylink_new_api", methods={"POST"})
         * @param LoggerInterface $logger
         * @param Request $request
         * @return JsonResponse
         * @throws \Exception
         */
        public function newAction(LoggerInterface $logger, Request $request) : JsonResponse {
            $url = $request->get('url');

            /** @var SkinnyLink|null $skinnyLink */
            $skinnyLink = null;

            if (!empty($url)) {
                $skinnyLink = new SkinnyLink();
                $skinnyLink->setUrl($url);

                try {
                    $skinnyLink = $this->container->get(SkinnyLinkService::class)->create($skinnyLink);
                } catch (\InvalidArgumentException $e) {
                    $logger->error("Error creating SkinnyLink : {$e->getMessage()}");

                    return new JsonResponse(['error' => $e->getMessage()], 200);
                }
            }

            return new JsonResponse($skinnyLink->toArray(), 200);
        }

        /**
         * Finds and displays a skinnyLink entity.
         *
         * @Route("/{id}", name="skinnylink_get_api", methods={"GET"})
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