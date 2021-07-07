<?php

namespace App\Entity;

use App\Repository\GameRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=GameRepository::class)
 */
class Game
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $launch_at;

    /**
     * @ORM\Column(type="integer")
     */
    private $price;

    /**
     * @ORM\Column(type="integer")
     */
    private $note_global;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $path_img;

    /**
     * @ORM\ManyToMany(targetEntity=GameCategory::class, mappedBy="games")
     */
    private $gameCategories;

    /**
     * @ORM\ManyToMany(targetEntity=Device::class, inversedBy="games")
     */
    private $devices;

    public function __construct()
    {
        $this->gameCategories = new ArrayCollection();
        $this->devices = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getLaunchAt(): ?\DateTimeImmutable
    {
        return $this->launch_at;
    }

    public function setLaunchAt(\DateTimeImmutable $launch_at): self
    {
        $this->launch_at = $launch_at;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getNoteGlobal(): ?int
    {
        return $this->note_global;
    }

    public function setNoteGlobal(int $note_global): self
    {
        $this->note_global = $note_global;

        return $this;
    }

    public function getPathImg(): ?string
    {
        return $this->path_img;
    }

    public function setPathImg(string $path_img): self
    {
        $this->path_img = $path_img;

        return $this;
    }

    /**
     * @return Collection|GameCategory[]
     */
    public function getGameCategories(): Collection
    {
        return $this->gameCategories;
    }

    public function addGameCategory(GameCategory $gameCategory): self
    {
        if (!$this->gameCategories->contains($gameCategory)) {
            $this->gameCategories[] = $gameCategory;
            $gameCategory->addGame($this);
        }

        return $this;
    }

    public function removeGameCategory(GameCategory $gameCategory): self
    {
        if ($this->gameCategories->removeElement($gameCategory)) {
            $gameCategory->removeGame($this);
        }

        return $this;
    }

    /**
     * @return Collection|Device[]
     */
    public function getDevices(): Collection
    {
        return $this->devices;
    }

    public function addDevice(Device $device): self
    {
        if (!$this->devices->contains($device)) {
            $this->devices[] = $device;
        }

        return $this;
    }

    public function removeDevice(Device $device): self
    {
        $this->devices->removeElement($device);

        return $this;
    }
}
