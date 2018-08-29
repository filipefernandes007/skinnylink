<?php

namespace AppBundle\Entity;

use AppBundle\Helper\UtilsHelper;
use Doctrine\ORM\Mapping as ORM;

/**
 * SkinnyLink
 *
 * @ORM\Table(name="skinny_link",
 *            uniqueConstraints={@ORM\UniqueConstraint(name="UQ_SKINNY_URL_IDX", columns={"skinny_url"})})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\SkinnyLinkRepository")
 * @ORM\HasLifecycleCallbacks
 *
 * @author Filipe Fernandes <filipefernandes007@gmail.com>
 */
class SkinnyLink
{

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="bigint")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="url", type="text", length=4096)
     */
    private $url;

    /**
     * @var string
     *
     * @ORM\Column(name="skinny_url", type="string", length=255, unique=true, options={"unsigned":true})
     */
    private $skinnyUrl;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * SkinnyLink constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->skinnyUrl = UtilsHelper::generateRandomSpecialChar() . UtilsHelper::generateToken();
    }

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set url.
     *
     * @param string $url
     *
     * @return SkinnyLink
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url.
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set skinnyUrl.
     *
     * @param string $skinnyUrl
     *
     * @return SkinnyLink
     */
    public function setSkinnyUrl($skinnyUrl)
    {
        $this->skinnyUrl = $skinnyUrl;

        return $this;
    }

    /**
     * Get skinnyUrl.
     *
     * @return string
     */
    public function getSkinnyUrl()
    {
        return $this->skinnyUrl;
    }

    /**
     * Set createdAt.
     *
     * @param \DateTime $createdAt
     *
     * @return SkinnyLink
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt.
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @return array
     */
    public function toArray() : array {
        return [
            'id'        => $this->id,
            'url'       => $this->url,
            'skinnyUrl' => $this->skinnyUrl,
            'createdAt' => $this->createdAt
        ];
    }
}
