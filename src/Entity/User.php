<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Фамилия не может быть пустой")]
    #[Assert\Length(
        max: 255,
        maxMessage: "Фамилия не может быть длиннее {{ limit }} символов"
    )]
    private ?string $lastName = null;
    
    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Имя не может быть пустым")]
    #[Assert\Length(
        max: 255,
        maxMessage: "Имя не может быть длиннее {{ limit }} символов"
    )]
    private ?string $firstName = null;
    
    #[ORM\Column]
    #[Assert\NotNull(message: "Возраст не может быть пустым")]
    #[Assert\Range(
        min: 18,
        max: 120,
        notInRangeMessage: "Возраст должен быть между {{ min }} и {{ max }}."
    )]
    private ?int $age = null;
    
    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Статус не может быть пустым")]
    private ?string $status = null;
    
    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Email не может быть пустым")]
    private ?string $email = null;
    
    #[ORM\Column(length: 255)]
    private ?string $telegram = null;
    
    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Адрес не может быть пустым")]
    private ?string $address = null;
    

    #[ORM\ManyToOne(inversedBy: 'users')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull(message: "Отдел не может быть пустым")]
    private ?Department $department = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $avatar;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): static
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getAge(): ?int
    {
        return $this->age;
    }

    public function setAge(?int $age): static
    {
        $this->age = $age;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getTelegram(): ?string
    {
        return $this->telegram;
    }

    public function setTelegram(?string $telegram): static
    {
        $this->telegram = $telegram;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): static
    {
        $this->address = $address;

        return $this;
    }

    public function getDepartment(): ?Department
    {
        return $this->department;
    }

    public function setDepartment(?Department $department): static
    {
        $this->department = $department;

        return $this;
    }

    
    public function getAvatar()
    {
        return $this->avatar;
    }

    public function setAvatar($avatar)
    {
        $this->avatar = $avatar;
    }
}
