<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;

/**
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Table(name="url")
 * @ApiResource(attributes={"order"={"id": "DESC"}})
 */
class Url
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(length=255)
     * @Assert\Url(
     *    message = "The url {{ value }} is not a valid url",
     * )
     */
    private string $origin_url;

    /**
     * @ApiFilter(SearchFilter::class)
     * @ORM\Column(type="string", length=15, nullable=true)
     * @Assert\Ip
     */
    private $ip;

    /**
     * @ORM\Column(length=6, unique=true)
     */
    private string $slug;

    /**
     * @ORM\Column(type="integer")
     */
    private int $visit_count = 0;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private ?\DateTime $finished_at = null;

    /**
     * @ORM\Column(type="datetime")
     */
    private ?\DateTime $created_at = null;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getOriginUrl(): ?string
    {
        return $this->origin_url;
    }

    /**
     * @param string $origin_url
     * @return $this
     */
    public function setOriginUrl(string $origin_url): self
    {
        $this->origin_url = $origin_url;

        return $this;
    }

    /**
     * @return string
     */
    public function getSlug(): string
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     */
    public function setSlug(string $slug): void
    {
        $this->slug = $slug;
    }

    /**
     * @return int|null
     */
    public function getVisitCount(): ?int
    {
        return $this->visit_count;
    }

    /**
     * @param int $visit_count
     * @return $this
     */
    public function setVisitCount(int $visit_count): self
    {
        $this->visit_count = $visit_count;

        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getFinishedAt(): ?\DateTime
    {
        return $this->finished_at;
    }

    /**
     * @param \DateTime|null $finished_at
     * @return $this
     */
    public function setFinishedAt(?\DateTime $finished_at): self
    {
        $this->finished_at = $finished_at;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getIp(): mixed
    {
        return $this->ip;
    }

    /**
     * @param mixed $ip
     */
    public function setIp($ip): void
    {
        $this->ip = $ip;
    }

    /**
     * @return \DateTime|null
     */
    public function getCreateAt(): ?\DateTime
    {
        return $this->created_at;
    }

    /**
     * @ORM\PrePersist
     */
    public function updatedTimestamps()
    {
        if ($this->created_at == null)
            $this->created_at = new \DateTime('now');
    }

    /**
     * @ORM\PrePersist
     */
    public function setSlugValue()
    {
        $this->slug = bin2hex(random_bytes(3));
    }

}
