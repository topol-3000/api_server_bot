<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\TelegramUserRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass=TelegramUserRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
#[ApiResource]
class TelegramUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     */
    private int $telegramId;

    /**
     * @ORM\Column(type="string", length=40, nullable=true)
     */
    #[Assert\Length(max: 40)]
    private ?string $username;

    /**
     * @ORM\Column(type="string", length=200, nullable=true)
     */
    #[Assert\Length(max: 200)]
    private ?string $fullName;

    /**
     * @ORM\Column(type="integer")
     */
    private int $balance = 0;

    /**
     * @ORM\Column(type="boolean")
     */
    private bool $isManager = False;

    /**
     * @ORM\Column(type="boolean")
     */
    private bool $isAdmin = False;

    /**
     * @ORM\Column(type="datetime")
     */
    private DateTimeInterface $created;

    /**
     * @ORM\Column(type="datetime")
     */
    private DateTimeInterface $updated;

    public function getTelegramId(): int
    {
        return $this->telegramId;
    }

    public function setTelegramId(int $telegramId): self
    {
        $this->telegramId = $telegramId;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(?string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getFullName(): ?string
    {
        return $this->fullName;
    }

    public function setFullName(?string $fullName): self
    {
        $this->fullName = $fullName;

        return $this;
    }

    public function getBalance(): int
    {
        return $this->balance;
    }

    public function setBalance(int $balance): self
    {
        $this->balance = $balance;

        return $this;
    }

    public function getIsManager(): bool
    {
        return $this->isManager;
    }

    public function setIsManager(bool $isManager): self
    {
        $this->isManager = $isManager;

        return $this;
    }

    public function getIsAdmin(): bool
    {
        return $this->isAdmin;
    }

    public function setIsAdmin($isAdmin): self
    {

        $this->isAdmin = $isAdmin;

        return $this;
    }

    public function getCreated(): DateTimeInterface
    {
        return $this->created;
    }

    public function getUpdated(): DateTimeInterface
    {
        return $this->updated;
    }

    /**
     * @ORM\PrePersist
     */
    public function setCreatedValue(): void
    {
        $this->created = new DateTime("now");
        $this->setUpdatedValue();
    }

    /**
     * @ORM\PreUpdate
     */
    public function setUpdatedValue(): void
    {
        $this->updated = new DateTime("now");
    }
}
