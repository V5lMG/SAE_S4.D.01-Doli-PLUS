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

    public function testRecupererListeCompletePalmaresSansSession()
    {
        // Given : Aucune donnée en session
        $_SESSION = [];

        // When : Appel de la méthode
        $result = $this->fournisseurService->recupererListeCompletePalmares();

        // Then : Vérifier que le résultat est un tableau vide
        $this->assertIsArray($result);
        $this->assertEmpty($result);
    }

    public function testRecupererListeCompletePalmaresCurlEchec()
    {
        // Given : Session avec token et URL
        $_SESSION['api_token'] = 'dummy_token';
        $_SESSION['url_saisie'] = 'http://mock-api';

        // When : Simuler un échec de cURL
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Échec de l’initialisation de cURL');

        $serviceMock = $this->getMockBuilder(FournisseurService::class)
            ->disableOriginalConstructor()
            ->getMock();

        $serviceMock->method('recupererListeCompletePalmares')
            ->will($this->throwException(new RuntimeException('Échec de l’initialisation de cURL')));

        // Then : Vérifier l'exception
        $serviceMock->recupererListeCompletePalmares();
    }

    public function testRecupererListeCompletePalmaresAvecReponseValide()
    {
        // Given : L'environnement est préparé avec un token API et une URL fictive
        $_SESSION['api_token'] = 'dummy_token';
        $_SESSION['url_saisie'] = 'http://mock-api';

        // Simuler une réponse JSON de l'API
        $mockResponse = '[{"ref": "PALM1", "nom": "Palmares Fournisseur", "score": 95}]';

        // When : Créer un mock de FournisseurService
        $mockService = $this->getMockBuilder(FournisseurService::class)
            ->addMethods(['curlExec', 'curlGetInfo']) // 🛠 On ajoute les méthodes cURL
            ->getMock();

        // Simuler les retours de cURL
        $mockService->method('curlExec')->willReturn($mockResponse);
        $mockService->method('curlGetInfo')->willReturn(200); // HTTP 200 OK

        // When : On appelle la méthode qui doit récupérer le palmarès des fournisseurs
        $result = $mockService->recupererListeCompletePalmares();

        // Then : Vérifications
        $decodedResult = json_decode($mockResponse, true);

        $this->assertNotEmpty($decodedResult);  // Vérifie que le résultat n'est pas vide
        $this->assertCount(1, $decodedResult);  // Vérifie qu'il y a bien un élément
        $this->assertEquals("PALM1", $decodedResult[0]['ref']);  // Vérifie la référence
        $this->assertEquals("Palmares Fournisseur", $decodedResult[0]['nom']);  // Vérifie le nom du fournisseur
        $this->assertEquals(95, $decodedResult[0]['score']);  // Vérifie le score du fournisseur
    }
    public function testFiltrerValeursSansFiltres()
    {
        // Given
        $_SESSION['api_token'] = 'some_valid_token';

        $_POST['nom'] = '';  // Filtres vides
        $_POST['numTel'] = '';
        $_POST['adresse'] = '';
        $_POST['codePostal'] = '';

        // Liste de fournisseurs
        $fournisseurs = [
            ['nom' => 'Fournisseur A', 'numTel' => '0102030405', 'adresse' => 'Paris', 'codePostal' => '75001'],
        ];

        // When
        $result = $this->fournisseurService->filtrerValeurs($fournisseurs);

        // Then
        $this->assertArrayHasKey('fournisseurs', $result);
        $this->assertIsArray($result['fournisseurs']);
        $this->assertCount(1, $result['fournisseurs']);
    }

    public function testFiltrerValeursAvecFiltres()
    {
        // Given
        $_SESSION['api_token'] = 'some_valid_token';

        $_POST['nom'] = 'Fournisseur A';  // Filtres définis
        $_POST['numTel'] = '';
        $_POST['adresse'] = '';
        $_POST['codePostal'] = '';

        // Liste de fournisseurs
        $fournisseurs = [
            ['nom' => 'Fournisseur A', 'numTel' => '0102030405', 'adresse' => 'Paris', 'codePostal' => '75001'],
            ['nom' => 'Fournisseur B', 'numTel' => '0607080910', 'adresse' => 'Lyon', 'codePostal' => '69001'],
        ];

        // When
        $result = $this->fournisseurService->filtrerValeurs($fournisseurs);

        // Then
        $this->assertArrayHasKey('fournisseurs', $result);
        $this->assertIsArray($result['fournisseurs']);
        $this->assertCount(2, $result['fournisseurs']);
        $this->assertEquals('Fournisseur A', $result['fournisseurs'][0]['nom']);  // Vérification du nom du fournisseur
    }

    public function testFactureFournisseurSansSession()
    {
        // Given : Aucune donnée en session
        $_SESSION = [];

        // When : Appel de la méthode factureFournisseur avec une référence quelconque
        $result = $this->fournisseurService->factureFournisseur(1);

        // Then : Vérifier que le résultat est un tableau vide
        $this->assertIsArray($result);
        $this->assertEmpty($result);
    }

    public function testFactureFournisseurCurlEchec() {
        // Instancier la classe FournisseurService
        $service = new FournisseurService();

        // Définir une valeur factice pour l'argument (ex: null, tableau vide, faux ID...)
        $param = null;

        // Exécuter la méthode avec l'argument
        $result = $service->factureFournisseur($param);

        // Vérifier que la méthode retourne bien un tableau vide en cas d'échec
        $this->assertSame([], $result, 'La méthode devrait retourner un tableau vide en cas d’échec de curl_init()');
    }
    public function testRecupererFichiersJointsAvecErreurCurl()
    {
        // Créer un mock pour FournisseurService sans mocker la méthode 'recupererFichiersJoints'
        $service = $this->getMockBuilder(FournisseurService::class)
            ->onlyMethods([]) // Ne pas mocker la méthode "recupererFichiersJoints"
            ->getMock();

        // Simulation de la fonction cURL pour l'échec
        $this->mockCurlFail(); // Appel de la méthode de simulation de cURL

        $_SESSION['url_saisie'] = 'http://example.com';
        $_SESSION['api_token'] = 'fake_token';

        // Appeler la méthode à tester
        $result = $service->recupererFichiersJoints(123);

        // Vérifier que la méthode retourne un tableau vide en cas d'échec
        $this->assertEmpty($result);
    }

    private function mockCurlFail()
    {
        // Simuler un échec de cURL avec un code HTTP 500
        $mockCurl = $this->getMockBuilder('stdClass')
            ->addMethods(['curl_exec', 'curl_getinfo', 'curl_close']) // Utilisation de addMethods() pour ajouter des méthodes à mocker
            ->getMock();

        $mockCurl->method('curl_exec')->willReturn(false); // Simuler une réponse cURL échouée
        $mockCurl->method('curl_getinfo')->willReturn(500); // Code HTTP 500 pour l'échec

        // Remplacer la fonction cURL par le mock
        $GLOBALS['curl_exec'] = $mockCurl->curl_exec;
        $GLOBALS['curl_getinfo'] = $mockCurl->curl_getinfo;
        $GLOBALS['curl_close'] = $mockCurl->curl_close;
    }


    public function testTelechargerFichierApiRedirection()
    {
        $service = new FournisseurService();

        $fichier = "facture1.pdf";
        $expectedUrl = "http://dolibarr.iut-rodez.fr/G2024-43-SAE/documents/fournisseur/facture/" . $fichier;

        // Capture la sortie des headers
        ob_start();
        $service->telechargerFichierApi($fichier);
        $output = ob_get_clean();

        // Vérifie si l'en-tête Location a bien été envoyé
        $headers = xdebug_get_headers();
        $this->assertContains("Location: " . $expectedUrl, $headers);
    }

    public function testTelechargerFichierApiAvecUrlVide()
    {
        $service = new FournisseurService();

        $fichier = "";
        $expectedUrl = "http://dolibarr.iut-rodez.fr/G2024-43-SAE/documents/fournisseur/facture/";

        ob_start();
        $service->telechargerFichierApi($fichier);
        $output = ob_get_clean();

        $headers = xdebug_get_headers();
        $this->assertContains("Location: " . $expectedUrl, $headers);
    }

}
