<?php

namespace App\Entity;
use App\Repository\ClientInfoRepository;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;
use Doctrine\DBAL\Types\Types;
use App\Entity\crud;
#[ORM\Entity(repositoryClass: ClientInfoRepository::class)]
class ClientInfo implements JsonSerializable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $Name;

    #[ORM\Column(type: 'string', length: 255)]
    private $Surname;

    #[ORM\Column(type: 'string', length: 255)]
    private $City;

    #[ORM\Column(type: 'string', length: 255)]
    private $PostCode;

    #[ORM\Column(type: 'string', length: 255)]
    private $Address;

    #[ORM\Column(type: 'integer', nullable:true)]
    private $OrderNumber;

    public function jsonSerialize():array
    {
        return [
            'id' => $this->getId(),
            'Name' => $this->getName(),
            'Surname' => $this->getSurname(),
            'City' => $this->getCity(),
            'PostCode' => $this->getPostCode(),
            'Address' => $this->getAddress(),
            'OrderNumber' => $this->getOrderNumber(),
        ];
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->Name;
    }

    public function setName(string $Name): self
    {
        $this->Name = $Name;

        return $this;
    }

    public function getSurname(): ?string
    {
        return $this->Surname;
    }

    public function setSurname(string $Surname): self
    {
        $this->Surname = $Surname;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->City;
    }

    public function setCity(string $City): self
    {
        $this->City = $City;

        return $this;
    }

    public function getPostCode(): ?string
    {
        return $this->PostCode;
    }

    public function setPostCode(string $PostCode): self
    {
        $this->PostCode = $PostCode;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->Address;
    }

    public function setAddress(string $Address): self
    {
        $this->Address = $Address;

        return $this;
    }

    public function getOrderNumber(): ?int
    {
        return $this->OrderNumber;
    }

    public function setOrderNumber(?int $OrderNumber): self
    {
        $this->OrderNumber = $OrderNumber;

        return $this;
    }
}
