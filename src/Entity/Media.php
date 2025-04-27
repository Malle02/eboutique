<?php

// namespace App\Entity;

// use App\Repository\MediaRepository;
// use Doctrine\ORM\Mapping as ORM;

// #[ORM\Entity(repositoryClass: MediaRepository::class)]
// class Media
// {
//     #[ORM\Id]
//     #[ORM\GeneratedValue]
//     #[ORM\Column]
//     private ?int $id = null;

//     #[ORM\Column(length: 50)]
//     private ?string $type = null;

//     #[ORM\Column(length: 50)]
//     private ?string $path = null;

//     #[ORM\Column(length: 255)]
//     private ?string $alt = null;

//     #[ORM\ManyToOne(inversedBy: 'media')]
//     private ?Product $product = null;

//     public function getId(): ?int
//     {
//         return $this->id;
//     }

//     public function getType(): ?string
//     {
//         return $this->type;
//     }

//     public function setType(string $type): static
//     {
//         $this->type = $type;

//         return $this;
//     }

//     public function getPath(): ?string
//     {
//         return $this->path;
//     }

//     public function setPath(string $path): static
//     {
//         $this->path = $path;

//         return $this;
//     }

//     public function getAlt(): ?string
//     {
//         return $this->alt;
//     }

//     public function setAlt(string $alt): static
//     {
//         $this->alt = $alt;

//         return $this;
//     }

//     public function getProduct(): ?Product
//     {
//         return $this->product;
//     }

//     public function setProduct(?Product $product): static
//     {
//         $this->product = $product;

//         return $this;
//     }
// }
