<?php

// namespace App\Entity;

// use App\Repository\CartRepository;
// use Doctrine\Common\Collections\ArrayCollection;
// use Doctrine\Common\Collections\Collection;
// use Doctrine\ORM\Mapping as ORM;

// #[ORM\Entity(repositoryClass: CartRepository::class)]
// class Cart
// {
//     #[ORM\Id]
//     #[ORM\GeneratedValue]
//     #[ORM\Column]
//     private ?int $id = null;

//     #[ORM\OneToOne(cascade: ['persist', 'remove'])]
//     private ?User $user = null;

//     /**
//      * @var Collection<int, CartLine>
//      */
//     #[ORM\OneToMany(targetEntity: CartLine::class, mappedBy: 'cart')]
//     private Collection $cartLine;

//     public function __construct()
//     {
//         $this->cartLine = new ArrayCollection();
//     }

//     public function getId(): ?int
//     {
//         return $this->id;
//     }

//     public function getUser(): ?User
//     {
//         return $this->user;
//     }

//     public function setUser(?User $user): static
//     {
//         $this->user = $user;

//         return $this;
//     }

//     /**
//      * @return Collection<int, CartLine>
//      */
//     public function getCartLine(): Collection
//     {
//         return $this->cartLine;
//     }

//     public function addCartLine(CartLine $cartLine): static
//     {
//         if (!$this->cartLine->contains($cartLine)) {
//             $this->cartLine->add($cartLine);
//             $cartLine->setCart($this);
//         }

//         return $this;
//     }

//     public function removeCartLine(CartLine $cartLine): static
//     {
//         if ($this->cartLine->removeElement($cartLine)) {
//             // set the owning side to null (unless already changed)
//             if ($cartLine->getCart() === $this) {
//                 $cartLine->setCart(null);
//             }
//         }

//         return $this;
//     }
// }





