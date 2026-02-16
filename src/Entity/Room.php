<?php

namespace App\Entity;

use App\enum\Difficulty;
use App\Repository\RoomRepository;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity(repositoryClass: RoomRepository::class)]
class Room
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;


    #[ORM\Column(type: 'string', enumType: Difficulty::class)]
    private ?Difficulty $difficulty = null;

    #[ORM\Column]
    private ?int $number = null;

    #[ORM\Column]
    private ?string $component = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getDifficulty(): ?Difficulty
    {
        return $this->difficulty;
    }

    public function setDifficulty(?Difficulty $difficulty): static
    {
        $this->difficulty = $difficulty;
        return $this;
    }
    
    public function getNumber(): ?int
    {
        return $this->number;
    }

    public function setNumber(int $number): static
    {
        $this->number = $number;
        return $this;
    }

    public function getComponent(): ?string
    {
        return $this->component;
    }

    public function setComponent(string $component): static
    {
        $this->component = $component;
        return $this;
    }   
}
