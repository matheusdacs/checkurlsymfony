<?php

namespace App\Entity;

use App\Repository\UrllogRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UrllogRepository::class)
 */
class Urllog
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $body;

    /**
     * @ORM\Column(type="integer")
     */
    private $responsecode;

    /**
     * @ORM\ManyToOne(targetEntity=Url::class, inversedBy="url")
     * @ORM\JoinColumn(nullable=false)
     */
    private $url;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBody(): ?string
    {
        return $this->body;
    }

    public function setBody(string $body): self
    {
        $this->body = $body;

        return $this;
    }

    public function getResponsecode(): ?int
    {
        return $this->responsecode;
    }

    public function setResponsecode(int $responsecode): self
    {
        $this->responsecode = $responsecode;

        return $this;
    }

    public function getUrl(): ?Url
    {
        return $this->url;
    }

    public function setUrl(?Url $url): self
    {
        $this->url = $url;

        return $this;
    }
}
