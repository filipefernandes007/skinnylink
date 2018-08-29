<?php
    /**
     * Filipe <filipefernandes007@gmail.com>
     */

    namespace AppBundle\Service;


    use AppBundle\Entity\SkinnyLink;
    use Doctrine\Common\Persistence\ObjectManager;
    use Symfony\Component\Debug\Debug;

    class SkinnyLinkService
    {

        /** @var ObjectManager */
        protected $objectManager;

        /** @var \AppBundle\Repository\SkinnyLinkRepository repository */
        protected $repository;

        /**
         * MakeSkinnyLinkService constructor.
         * @param ObjectManager $objectManager
         */
        public function __construct(ObjectManager $objectManager)
        {
            $this->objectManager = $objectManager;
            $this->repository    = $this->objectManager->getRepository('AppBundle:SkinnyLink');
        }

        /**
         * @param SkinnyLink $skinnyLink
         * @return SkinnyLink
         * @throws \InvalidArgumentException
         */
        public function create(SkinnyLink $skinnyLink) : SkinnyLink
        {
            if (!filter_var($skinnyLink->getUrl(), FILTER_VALIDATE_URL)) {
                throw new \InvalidArgumentException('Invalid url');
            }

            /** @var SkinnyLink $existingSkinnyLink */
            $existingSkinnyLink = $this->repository->findOneBy(['url' => $skinnyLink->getUrl()]);

            // Already defined? Return existing skinny link
            if ($existingSkinnyLink !== null) {
                return $existingSkinnyLink;
            }

            $this->objectManager->persist($skinnyLink);
            $this->objectManager->flush();

            return $skinnyLink;
        }
    }