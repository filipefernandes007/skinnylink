<?php
    /**
     * Filipe <filipefernandes007@gmail.com>
     */

    namespace AppBundle\Service;


    use AppBundle\Entity\SkinnyLink;
    use Doctrine\Common\Persistence\ObjectManager;

    class SkinnyLinkService
    {
        const TIME_OUT_CHECK_URL = 5;
        const INVALID_URL        = 'Invalid url';
        const URL_DOES_NOT_EXIST = 'Url does not exist!';

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
            /** @var SkinnyLink $existingSkinnyLink */
            $existingSkinnyLink = $this->repository->findOneBy(['url' => $skinnyLink->getUrl()]);

            // Already defined? Return existing skinny link
            if ($existingSkinnyLink !== null) {
                $skinnyLink = $existingSkinnyLink;

                return $skinnyLink;
            }

            if (!filter_var($skinnyLink->getUrl(), FILTER_VALIDATE_URL)) {
                throw new \InvalidArgumentException(self::INVALID_URL);
            }

            if (!@file_get_contents($skinnyLink->getUrl(), false, $this->getCheckUrlOptions())) {
                throw new \InvalidArgumentException(self::URL_DOES_NOT_EXIST);
            }

            $this->objectManager->persist($skinnyLink);
            $this->objectManager->flush();

            return $skinnyLink;
        }

        /**
         * @return resource
         */
        private function getCheckUrlOptions()
        {
            /** @var array $timeout */
            $timeout = ['timeout' => self::TIME_OUT_CHECK_URL];

            /** @var resource $options */
            return stream_context_create(['http' => $timeout], ['https' => $timeout]);
        }
    }