<?php

namespace App\Entity;

use App\Repository\UserClientRepository;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\ORM\Mapping as ORM;
use Hateoas\Configuration\Annotation as Hateoas;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation\Groups;

/**
 * @Hateoas\Relation(
 *      "self",
 *      href = @Hateoas\Route(
 *          "app_user_client_show",
 *          parameters = { "id" = "expr(object.getId())" }
 *      ),
 * )
 *
 * @Hateoas\Relation(
 *      "update",
 *      href = @Hateoas\Route(
 *          "app_user_client_edit",
 *          parameters = { "id" = "expr(object.getId())" }
 *      ),
 * )
 *
 * @Hateoas\Relation(
 *      "delete",
 *      href = @Hateoas\Route(
 *          "app_user_client_delete",
 *          parameters = { "id" = "expr(object.getId())" }
 *      ),
 * )
 *
 */
#[ORM\Entity(repositoryClass: UserClientRepository::class)]
#[UniqueEntity(fields: 'email', message: 'L\'adresse email est déjà utilisée')]
class UserClient
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(['message' => 'Vous devez inscrire un email.'])]
    #[Assert\Email(message: 'Veuillez saisir une adresse email valide.')]
    #[Groups(['create', 'update'])]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(['message' => 'Vous devez inscrire un prénom.'])]
    #[Groups(['create', 'update'])]
    private ?string $firstname = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(['message' => 'Vous devez inscrire un nom.'])]
    #[Groups(['create', 'update'])]
    private ?string $lastname = null;

    #[ORM\ManyToOne(inversedBy: 'userClients')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    public function __construct()
    {
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): static
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): static
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }
}
