<?php

namespace AppBundle\Manager;

use AppBundle\Entity\ParsedImage;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

class TelegramManager
{
    /**
     * @var LoggerInterface
     */
    private $logger;
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * TelegramManager constructor.
     * @param LoggerInterface $logger
     * @param EntityManagerInterface $em
     */
    public function __construct(LoggerInterface $logger, EntityManagerInterface $em)
    {
        $this->logger = $logger;
        $this->em = $em;
    }

    /**
     * @return array|null
     */
    public function getUpdate(): ?array
    {
        return json_decode(file_get_contents("php://input"), TRUE);
    }

    /**
     * @param string $url
     * @param int|null $createdAt
     * @param int|null $publishedAt
     * @param bool|null $seen
     * @return ParsedImage
     */
    public function getOrCreateParsedImage(string $url, ?int $createdAt = null, ?int $publishedAt = null, ?bool $seen = false): ParsedImage
    {
        $parsedImage = $this->em->getRepository(ParsedImage::class)->findOneBy(['url' => $url]);
        if(!$parsedImage){
            $parsedImage = new ParsedImage($url, $createdAt, $publishedAt, $seen);
            $this->em->persist($parsedImage);
            $this->em->flush();
        }

        return $parsedImage;
    }

    /**
     * @return array|null
     */
    public function getNotSeenParsedImage(): ?array
    {
        $qb = $this->em->createQueryBuilder();
        $qb->select('parsedImage')->from(ParsedImage::class, 'parsedImage');
        $qb->andWhere($qb->expr()->eq('parsedImage.seen', 0));
        $parsedImages = $qb->getQuery()->getResult();

        return $parsedImages;
    }
}
