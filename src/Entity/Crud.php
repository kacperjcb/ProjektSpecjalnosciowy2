<?php

namespace App\Entity;

use App\Repository\CrudRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CrudRepository::class)]
class Crud
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    public $id;

    #[ORM\Column(type: 'string', length: 255)]
    public $Product_Name;

    #[ORM\Column(type: 'float')]
    public $Product_Price;

    #[ORM\Column(type: 'integer')]
    public $Stock_Level;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    public $Description;
    #[ORM\ManyToOne(targetEntity: ClientInfo::class)]
    #[ORM\JoinColumn(name: 'order_number', referencedColumnName: 'id')]
    public $clientInfo;
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProduct_Name(): ?string
    {
        return $this->Product_Name;
    }

    public function setProduct_Name(string $Product_Name): self
    {
        $this->Product_Name = $Product_Name;

        return $this;
    }

    public function getProduct_Price(): ?float
    {
        return $this->Product_Price;
    }

    public function setProduct_Price(float $Product_Price): self
    {
        $this->Product_Price = $Product_Price;

        return $this;
    }

    public function getStock_Level(): ?int
    {
        return $this->Stock_Level;
    }

    public function setStock_Level(int $Stock_Level): self
    {
        $this->Stock_Level = $Stock_Level;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->Description;
    }

    public function setDescription(?string $Description): self
    {
        $this->Description = $Description;

        return $this;
    }
}
