<?php
namespace App\Ecommerce\Modele\DataObject;

use App\Ecommerce\Lib\MotDePasse;

class Utilisateur extends AbstractDataObject {
    private ?int $id_utilisateur;
    private string $login;
    private string $email;
    private ?string $telephone;
    private string $password;
    private string $nom;
    private string $prenom;
    private string $nonce_email;
    private string $nonce_telephone;
    private bool $admin;
    private string $url_image;
    private ?string $dateCreation;

    public function __construct(?int $id_utilisateur, string $login, string $email,?string $telephone,string $password, string $nom, string $prenom, string $nonce_email, string $nonce_telephone, bool $admin, string $url_image,?string $dateCreation, bool $raw = true){
        if ($raw) {
            $this->id_utilisateur = $id_utilisateur;
            $this->login = $login;
            $this->email = $email;
            $this->telephone = $telephone;
            $this->password = $password;
            $this->nom = $nom;
            $this->prenom = $prenom;
            $this->nonce_email = $nonce_email;
            $this->nonce_telephone = $nonce_telephone;
            $this->admin = $admin;
            $this->url_image = $url_image;
            $this->dateCreation = $dateCreation;
        } else {
            $this->setIdUtilisateur($id_utilisateur);
            $this->setLogin($login);
            $this->setEmail($email);
            $this->setTelephone($telephone);
            $this->setPassword($password);
            $this->setNom($nom);
            $this->setPrenom($prenom);
            $this->setNonceEmail($nonce_email);
            $this->setNonceTelephone($nonce_telephone);
            $this->setAdmin($admin);
            $this->setUrlImage($url_image);
            $this->setDateCreation($dateCreation);
        }
    }

    public function formatTableau(): array{
        return array(
            "id_utilisateur" => $this->getIdUtilisateur(),
            "login" => $this->getLogin(),
            "email" => $this->getEmail(),
            "telephone" => $this->getTelephone(),
            "password" => $this->getPassword(),
            "nom" => $this->getNom(),
            "prenom" => $this->getPrenom(),
            "nonce_email" => $this->getNonceEmail(),
            "nonce_telephone" => $this->getNonceTelephone(),
            "admin" => $this->getAdmin()*1,
            "url_image" => $this->getUrlImage(),
            "dateCreation" => $this->getDateCreation()
        );
    }

    /**
     * @return int|null
     */
    public function getIdUtilisateur(): ?int
    {
        return $this->id_utilisateur;
    }

    /**
     * @param int|null $id_utilisateur
     */
    public function setIdUtilisateur(?int $id_utilisateur): void
    {
        $this->id_utilisateur = $id_utilisateur;
    }

    /**
     * @return string
     */
    public function getLogin(): string
    {
        return $this->login;
    }

    /**
     * @param string $login
     */
    public function setLogin(string $login): void
    {
        $this->login = $login;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return string|null
     */
    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    /**
     * @param string|null $telephone
     */
    public function setTelephone(?string $telephone): void
    {
        $this->telephone = $telephone;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password): void
    {
        $this->password = MotDePasse::hacher($password);
    }

    /**
     * @return string
     */
    public function getNom(): string
    {
        return $this->nom;
    }

    /**
     * @param string $nom
     */
    public function setNom(string $nom): void
    {
        $this->nom = $nom;
    }

    /**
     * @return string
     */
    public function getPrenom(): string
    {
        return $this->prenom;
    }

    /**
     * @param string $prenom
     */
    public function setPrenom(string $prenom): void
    {
        $this->prenom = $prenom;
    }

    /**
     * @return string
     */
    public function getNonceEmail(): string
    {
        return $this->nonce_email;
    }

    /**
     * @param string $nonce_email
     */
    public function setNonceEmail(string $nonce_email): void
    {
        $this->nonce_email = $nonce_email;
    }

    /**
     * @return string
     */
    public function getNonceTelephone(): string
    {
        return $this->nonce_telephone;
    }

    /**
     * @param string $nonce_telephone
     */
    public function setNonceTelephone(string $nonce_telephone): void
    {
        $this->nonce_telephone = $nonce_telephone;
    }

    /**
     * @return bool
     */
    public function getAdmin(): bool
    {
        return $this->admin;
    }

    /**
     * @param bool $admin
     */
    public function setAdmin(bool $admin): void
    {
        $this->admin = $admin;
    }

    /**
     * @return int|null
     */
    public function getUrlImage(): string
    {
        return $this->url_image;
    }

    /**
     * @param string $url_image
     */
    public function setUrlImage(string $url_image): void
    {
        $this->url_image = $url_image;
    }

    public function getDateCreation(): ?string
    {
        return $this->dateCreation;
    }

    public function setDateCreation(?string $dateCreation): void
    {
        $this->dateCreation = $dateCreation;
    }
}