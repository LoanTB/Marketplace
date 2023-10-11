<?php
namespace App\Ecommerce\Modele\DataObject;

use App\Ecommerce\Lib\MotDePasse;

class Utilisateur extends AbstractDataObject {
    private string $login;
    private string $email;
    private string $password;
    private string $nom;
    private string $prenom;
    private bool $estAdmin;
    private string $emailAValider;
    private string $nonce;

    public function __construct(string $login, string $email,string $password, string $nom, string $prenom, bool $estAdmin, string $emailAValider, string $nonce, bool $raw = true){
        if ($raw) {
            $this->login = $login;
            $this->email = $email;
            $this->password = $password;
            $this->nom = $nom;
            $this->prenom = $prenom;
            $this->estAdmin = $estAdmin;
            $this->emailAValider = $emailAValider;
            $this->nonce = $nonce;
        } else {
            $this->setLogin($login);
            $this->setEmail($email);
            $this->setPassword($password);
            $this->setNom($nom);
            $this->setPrenom($prenom);
            $this->setEstAdmin($estAdmin);
            $this->setEmailAValider($emailAValider);
            $this->setNonce($nonce);
        }
    }

    public function formatTableau(): array{
        return array(
            "login" => $this->getLogin(),
            "email" => $this->getEmail(),
            "password" => $this->getPassword(),
            "nom" => $this->getNom(),
            "prenom" => $this->getPrenom(),
            "estAdmin" => (int)$this->getEstAdmin(),
            "emailAValider" => $this->getEmailAValider(),
            "nonce" => $this->getNonce()
        );
    }

    public function getLogin(): string {
        return $this->login;
    }

    public function setLogin(string $login): void {
        $this->login = $login;
    }

    public function getEmail(): string {
        return $this->email;
    }

    public function setEmail(string $email): void {
        $this->email = $email;
    }

    public function getPassword(): string {
        return $this->password;
    }

    public function setPassword(string $password): void {
        $this->password = MotDePasse::hacher($password);
    }

    public function getNom(): string {
        return $this->nom;
    }

    public function setNom(string $nom): void {
        $this->nom = $nom;
    }

    public function getPrenom(): string {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): void {
        $this->prenom = $prenom;
    }

    public function getEstAdmin(): bool {
        return $this->estAdmin;
    }

    public function setEstAdmin(bool $estAdmin): void {
        $this->estAdmin = $estAdmin;
    }

    public function getEmailAValider(): string {
        return $this->emailAValider;
    }

    public function setEmailAValider(string $emailAValider): void {
        $this->emailAValider = $emailAValider;
    }

    public function getNonce(): string {
        return $this->nonce;
    }

    public function setNonce(string $nonce): void {
        $this->nonce = $nonce;
    }
}