<?php
namespace AppBundle\Entity;
use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity()
 * @ORM\Table(name="parsed_image")
 */

class ParsedImage
{
    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="integer", length=11, unique=true, nullable=false)
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @var integer
     */
    private $id;

    /**
     * @ORM\Column(name="url", type="string", length=1000, nullable=false)
     * @var string
     */
    private $url;

    /**
     * @ORM\Column(name="created_at", type="integer", length=11, nullable=true)
     * @var integer
     */
    private $createdAt;

    /**
     * @ORM\Column(name="published_at", type="integer", length=11, nullable=true)
     * @var integer
     */
    private $publishedAt;

    /**
     * @ORM\Column(name="seen", type="integer", length=1, nullable=false)
     * @var boolean
     */
    private $seen = false;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @param string $url
     */
    public function setUrl(string $url): void
    {
        $this->url = $url;
    }

    /**
     * @return int
     */
    public function getCreatedAt(): int
    {
        return $this->createdAt;
    }

    /**
     * @param int $createdAt
     */
    public function setCreatedAt(int $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return int
     */
    public function getPublishedAt(): int
    {
        return $this->publishedAt;
    }

    /**
     * @param int $publishedAt
     */
    public function setPublishedAt(int $publishedAt): void
    {
        $this->publishedAt = $publishedAt;
    }

    /**
     * @return bool
     */
    public function isSeen(): bool
    {
        return $this->seen;
    }

    /**
     * @param bool $seen
     */
    public function setSeen(bool $seen): void
    {
        $this->seen = $seen;
    }

    /**
     * ParsedImage constructor.
     * @param int $id
     * @param string $url
     * @param int|null $createdAt
     * @param int|null $publishedAt
     * @param bool|null $seen
     */
    public function __construct(string $url, ?int $createdAt, ?int $publishedAt, ?bool $seen = false)
    {
        $this->url = $url;
        $this->createdAt = $createdAt;
        $this->publishedAt = $publishedAt;
        $this->seen = $seen;
    }
}