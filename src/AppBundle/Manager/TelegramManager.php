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

    public function __construct(LoggerInterface $logger, EntityManagerInterface $em)
    {
        $this->logger = $logger;
        $this->em = $em;
    }

    public function getUpdate()
    {
        return json_decode(file_get_contents("php://input"), TRUE);
    }

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
}
