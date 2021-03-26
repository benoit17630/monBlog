<?php

namespace App\Entity\Admin;

use App\Repository\Admin\ColorRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ColorRepository::class)
 * @UniqueEntity("name")
 */
class Color
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="le nom de la couleur ne doit pas etre vide")
     * @Assert\Length( min="4", max="100",
     *     minMessage="le nom de la couleur doit avoir {{ limit }} caractere au minimun ",
     *     maxMessage="le nom de la couleur doit avoir {{ limit }} caractere au maximun")
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="l hexadecimal de la couleur ne doit pas etre vide")
     * @Assert\Regex("/^((0x){0,1}|#{0,1})([0-9A-F]{8}|[0-9A-F]{6})$/i" ,
     *  message="ceci n est pas une couleur hex"
     *     )
     */
    private $color;

    /**
     * @ORM\OneToMany(targetEntity=Category::class, mappedBy="color")
     */
    private $categories;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(string $color): self
    {
        $this->color = $color;

        return $this;
    }

    /**
     * @return Collection|Category[]
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Category $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories[] = $category;
            $category->setColor($this);
        }

        return $this;
    }

    public function removeCategory(Category $category): self
    {
        if ($this->categories->removeElement($category)) {
            // set the owning side to null (unless already changed)
            if ($category->getColor() === $this) {
                $category->setColor(null);
            }
        }

        return $this;
    }
}
