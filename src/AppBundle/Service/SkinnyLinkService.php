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
        const TIME_OUT_CHECK_URL = 5;

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
        public function create(SkinnyLink &$skinnyLink) : SkinnyLink
        {
            if (!filter_var($skinnyLink->getUrl(), FILTER_VALIDATE_URL)) {
                throw new \InvalidArgumentException('Invalid url');
            }

            if (!@file_get_contents($skinnyLink->getUrl(), false, $this->getCheckUrlOptions())) {
                throw new \InvalidArgumentException('Url does not exist!');
            }

            /** @var SkinnyLink $existingSkinnyLink */
            $existingSkinnyLink = $this->repository->findOneBy(['url' => $skinnyLink->getUrl()]);

            // Already defined? Return existing skinny link
            if ($existingSkinnyLink !== null) {
                $skinnyLink = $existingSkinnyLink;

                return $skinnyLink;
            }

            $this->objectManager->persist($skinnyLink);
            $this->objectManager->flush();

            return $skinnyLink;
        }

        /**
         * @return resource
         */
        private function getCheckUrlOptions() {
            /** @var array $timeout */
            $timeout = ['timeout' => self::TIME_OUT_CHECK_URL];

            /** @var resource $options */
            return stream_context_create(['http' => $timeout], ['https' => $timeout]);
        }
    }