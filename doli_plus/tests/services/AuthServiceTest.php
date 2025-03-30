<?php

namespace tests\services;

use PHPUnit\Framework\TestCase;
use services\AuthService;

class AuthServiceTest extends TestCase
{
    private AuthService $authService;
    private string $filePath = 'static/config/url.conf';

    protected function setUp(): void
    {
        $this->authService = new AuthService();
        $_SESSION = []; // Réinitialisation de la session pour chaque test

        // S'assurer que le fichier de configuration existe
        if (!file_exists(dirname($this->filePath))) {
            mkdir(dirname($this->filePath), 0777, true);
        }
    }

    protected function tearDown(): void
    {
        // Nettoyer le fichier après chaque test
        if (file_exists($this->filePath)) {
            unlink($this->filePath);
        }
    }

//    public function testAuthentificationSuccess()
//    {
//        // GIVEN un utilisateur avec des identifiants valides
//        $username = 'test_user';
//        $password = 'test_pass';
//        $url = 'https://fake-api.com';
//
//        $this->authService = $this->getMockBuilder(AuthService::class)
//            ->onlyMethods(['executer_requete_api'])
//            ->getMock();
//
//        $this->authService->method('executer_requete_api')->willReturn(true);
//
//        // WHEN l'utilisateur s'authentifie
//        $result = $this->authService->authentification($username, $password, $url);
//
//        // THEN il doit obtenir un token
//        $this->assertTrue($result);
//        $this->assertArrayHasKey('api_token', $_SESSION);
//    }

    public function testSetUrlFichier()
    {
        // GIVEN une URL à enregistrer
        $url = 'https://test-url.com';

        // WHEN on enregistre l'URL
        AuthService::setUrlFichier($url);

        // THEN le fichier doit contenir cette URL en premier
        $lines = file($this->filePath, FILE_IGNORE_NEW_LINES);
        $this->assertNotEmpty($lines);
        $this->assertEquals($url, $lines[0]);
    }

    public function testGetUrlFichier()
    {
        // GIVEN un fichier avec plusieurs URLs
        $urls = [
            'https://url1.com',
            'https://url2.com',
            'https://url3.com'
        ];
        file_put_contents($this->filePath, implode(PHP_EOL, $urls) . PHP_EOL);

        // WHEN on récupère les URLs
        $result = AuthService::getUrlFichier();

        // THEN elles doivent être identiques à celles enregistrées
        $this->assertEquals($urls, array_map('trim', $result));
    }

    public function testDeconnexion()
    {
        // GIVEN un utilisateur connecté
        $_SESSION['api_token'] = 'token';
        $_SESSION['user_name'] = 'test_user';

        // WHEN il se déconnecte
        $this->authService->deconnexion();

        // THEN la session doit être vide
        $this->assertEmpty($_SESSION);
    }
}
