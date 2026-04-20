<?php

namespace App\Entity;

use App\Enum\ValueType;
use App\Repository\TaskOptionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TaskOptionRepository::class)]
class TaskOption
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $option_key = null;

    #[ORM\Column(length: 255)]
    private ?string $option_value = null;

    #[ORM\Column(enumType: ValueType::class)]
    private ?ValueType $value_type = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOptionKey(): ?string
    {
        return $this->option_key;
    }

    public function setOptionKey(string $option_key): static
    {
        $this->option_key = $option_key;

        return $this;
    }

    public function getOptionValue(): ?string
    {
        return $this->option_value;
    }

    public function setOptionValue(string $option_value): static
    {
        $this->option_value = $option_value;

        return $this;
    }

    public function getValueType(): ?ValueType
    {
        return $this->value_type;
    }

    public function setValueType(ValueType $value_type): static
    {
        $this->value_type = $value_type;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }
}
