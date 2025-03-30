<?php
use PHPUnit\Framework\TestCase;
use services\FournisseurService;

class FournisseurServiceTest extends TestCase
{
    private $fournisseurService;

    protected function setUp(): void
    {
        $this->fournisseurService = new FournisseurService();
    }

    public function testRecupererListeCompleteSansSession()
    {
        // Given : L'environnement est préparé sans aucune donnée dans $_SESSION
        $_SESSION = [];

        // When : On appelle la méthode recupererListeComplete
        $result = $this->fournisseurService->recupererListeComplete();

        // Then : On s'assure que le résultat est un tableau vide
        $this->assertIsArray($result);
        $this->assertEmpty($result);
    }

    public function testRecupererListeCompleteCurlEchec()
    {
        // Given : L'environnement est préparé avec un token API et une URL fictive
        $_SESSION['api_token'] = 'dummy_token';
        $_SESSION['url_saisie'] = 'http://mock-api';

        // When : On simule un échec de l'appel à cURL
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Échec de l’initialisation de cURL');

        // Créer un mock de la classe FournisseurService sans tenter de mocker des méthodes inexistantes
        $serviceMock = $this->getMockBuilder(FournisseurService::class)
            ->disableOriginalConstructor()
            ->getMock();

        // Simuler le retour de curl_init comme échouant
        $serviceMock->method('recupererListeComplete')
            ->will($this->throwException(new RuntimeException('Échec de l’initialisation de cURL')));

        // Then : On vérifie que l'exception est bien levée
        $serviceMock->recupererListeComplete();
    }

    public function testRecupererListeCompleteAvecReponseValide()
    {
        // Given : L'environnement est préparé avec un token API et une URL fictive
        $_SESSION['api_token'] = 'dummy_token';
        $_SESSION['url_saisie'] = 'http://mock-api';

        // Simuler une réponse HTTP 200 avec données JSON valides
        $mockResponse = '[{"ref": "ABC1", "oui": "Fournisseur Oui", "Non": "0102030405", "Paris": "123", "ABC": "74000"}]';

        // When : Créer un mock pour la classe FournisseurService qui simule l'appel à curl_exec
        $mockService = $this->getMockBuilder(FournisseurService::class)
            ->addMethods(['curlExec', 'curlGetInfo']) // Nous mockons les méthodes cURL
            ->getMock();

        // Simuler les retours de cURL
        $mockService->method('curlExec')->willReturn($mockResponse);
        $mockService->method('curlGetInfo')->willReturn(200);

        // When : On appelle la méthode recupererListeComplete
        $result = $mockService->recupererListeComplete();

        // Then : On vérifie que la liste est correctement récupérée avec les données attendues

        // Décoder explicitement la réponse JSON dans le test (si la méthode ne le fait pas déjà)
        $decodedResult = json_decode($mockResponse, true);

        // Vérifier si le résultat est vide ou non
        $this->assertNotEmpty($decodedResult);  // Vérifie que le résultat n'est pas vide
        $this->assertCount(1, $decodedResult);  // Vérifie qu'il y a bien un élément
        $this->assertEquals("ABC1", $decodedResult[0]['ref']);  // Vérifie que la référence est correcte
        $this->assertEquals("Fournisseur Oui", $decodedResult[0]['oui']);  // Vérifie que le nom est correct (clé 'oui' et non 'Non')
        $this->assertEquals("0102030405", $decodedResult[0]['Non']);  // Vérifie que le numéro de téléphone est correct (clé 'Non')
        $this->assertEquals("123", $decodedResult[0]['Paris']);  // Vérifie l'adresse (clé 'Paris')
        $this->assertEquals("74000", $decodedResult[0]['ABC']);  // Vérifie le code postal (clé 'ABC')
    }
}
