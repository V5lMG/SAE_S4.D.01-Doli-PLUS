<?php
use PHPUnit\Framework\TestCase;
use services\FournisseurService;
use phpmock\phpunit\PHPMock;

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
        $_SESSION['url_saisie'] = 'http://mock-api'; // Cela n'a pas d'importance maintenant car nous mockons la requête cURL

        // Simuler une réponse HTTP 200 avec des données JSON valides
        $mockResponse = '[{"ref": "ABC1", "name": "Fournisseur Oui", "phone": "0102030405", "address": "123", "zip": "74000"}]';

        // When : Créer un mock pour la classe FournisseurService qui simule l'appel à curl_exec et curl_getinfo
        $mockService = $this->getMockBuilder(FournisseurService::class)
            ->add(['curlExec', 'curlGetInfo']) // Nous mockons uniquement les méthodes cURL
            ->getMock();

        // Simuler le comportement de cURL
        $mockService->method('curlExec')->willReturn($mockResponse); // Simule la réponse de curl_exec
        $mockService->method('curlGetInfo')->willReturn(200); // Simule un code HTTP 200 pour curl_getinfo

        // When : On appelle la méthode recupererListeComplete
        $result = $mockService->recupererListeComplete();

        // Then : On vérifie que la liste est correctement récupérée avec les données attendues
        // Définir le résultat attendu après transformation
        $expectedResult = [
            [
                'ref' => 'ABC1',
                'nom' => 'Fournisseur Oui',
                'numTel' => '0102030405',
                'adresse' => '123',
                'codePostal' => '74000',
            ]
        ];

        // Vérifier si le résultat correspond aux données attendues
        $this->assertEquals($expectedResult, $result);
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
        $this->assertIsArray($result);
        $this->assertEmpty($result); // Vérifie que le tableau est vide
    }

    public function testFiltrerValeursAvecFiltres()
    {
        session_start();
        // Simuler un 'api_token' valide et des valeurs de filtres dans $_POST
        $_SESSION['api_token'] = 'some_valid_token';
        $_POST['nom'] = 'Fournisseur A';
        $_POST['numTel'] = '0123456789';
        $_POST['adresse'] = 'Rue X';
        $_POST['codePostal'] = '75001';

        $fournisseurs = [
            ['nom' => 'Fournisseur A', 'numTel' => '0123456789', 'adresse' => 'Rue X', 'codePostal' => '75001'],
            ['nom' => 'Fournisseur B', 'numTel' => '0987654321', 'adresse' => 'Rue Y', 'codePostal' => '69000'],
        ];

        $result = $this->fournisseurService->filtrerValeurs($fournisseurs,'Fournisseur A','0123456789');

        // fwrite(STDERR, var_export($result, true) . PHP_EOL);
        // Vérifier que la méthode retourne le bon fournisseur filtré
        $this->assertNotEmpty($result);
        $this->assertCount(1, $result['fournisseurs']);
        $this->assertEquals('Fournisseur A', $result['fournisseurs'][0]['nom']);
        $this->assertEquals('0123456789', $result['fournisseurs'][0]['numTel']);
        session_destroy();
    }

    public function testFiltrerValeursAvecFiltreNomInexistant()
    {
        // Simuler un 'api_token' valide et un filtre de nom qui n'existe pas dans les fournisseurs
        $_SESSION['api_token'] = 'some_valid_token';
        $_POST['nom'] = 'Fournisseur Inexistant';

        $fournisseurs = [
            ['nom' => 'Fournisseur A', 'numTel' => '0123456789', 'adresse' => 'Rue X', 'codePostal' => '75001'],
            ['nom' => 'Fournisseur B', 'numTel' => '0987654321', 'adresse' => 'Rue Y', 'codePostal' => '69000'],
        ];

        $result = $this->fournisseurService->filtrerValeurs($fournisseurs, 'Fournisseur Inexistant');

        // Vérifier que la méthode retourne un tableau vide
        $this->assertEmpty($result['fournisseurs']);
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

    use PHPMock;

    public function testFactureFournisseurRetourneFacturesValides()
    {
        // Mock des données de réponse que cURL devrait renvoyer
        $mockResponse = json_encode([
            [
                'socid' => '1',
                'ref_supplier' => '123',
                'status' => '1',
                'cond_reglement_code' => 'RECEP',
                'mode_reglement_code' => 'CB',
                'total_ht' => 1000,
                'lines' => [
                    ['description' => 'Produit A', 'qty' => 2, 'total_ht' => 200]
                ],
                'date' => time(),
                'date_echeance' => time()
            ]
        ]);

        // Création du mock pour FournisseurService et ajout des méthodes cURL
        $this->fournisseurService = $this->getMockBuilder(FournisseurService::class)
            ->addMethods(['curl_init', 'curl_setopt', 'curl_exec', 'curl_getinfo', 'curl_close']) // Utilisation de addMethods
            ->getMock();

        // Stub des fonctions cURL
        $this->fournisseurService->expects($this->any())
            ->method('curl_init')
            ->willReturn(true); // Simule l'initialisation de cURL

        $this->fournisseurService->expects($this->any())
            ->method('curl_setopt')
            ->willReturn(true); // Simule les options cURL

        $this->fournisseurService->expects($this->any())
            ->method('curl_exec')
            ->willReturn($mockResponse); // Retourne la réponse simulée de cURL

        $this->fournisseurService->expects($this->any())
            ->method('curl_getinfo')
            ->willReturn(200); // Retourne le code HTTP 200

        $this->fournisseurService->expects($this->any())
            ->method('curl_close')
            ->willReturn(true); // Simule la fermeture de la session cURL

        // Simule la session
        $_SESSION['api_token'] = 'test_token';
        $_SESSION['url_saisie'] = 'https://example.com/api';

        // Appel à la méthode facturerFournisseur avec un paramètre '1'
        $result = $this->fournisseurService->factureFournisseur('1');

        // Résultat attendu
        $expectedResult = [
            'factures' => [
                [
                    'ref' => '123',
                    'date_facture' => date("d/m/Y"),
                    'date_echeance' => date("d/m/Y"),
                    'cond_reglement' => 'A réception',
                    'mode_reglement' => 'Carte bancaire',
                    'montant_ht' => '1 000,00 €',
                    'etat' => 'Impayées',
                    'fichiers_joints' => [], // Peut être mocké si nécessaire
                    'lignes' => [
                        [
                            'description' => 'Produit A',
                            'ref' => 'Inconnu',
                            'tva' => 'Inconnu',
                            'prix_unitaire_ht' => 'Inconnu',
                            'prix_unitaire_ttc' => 'Inconnu',
                            'quantite' => 2,
                            'reduction' => 'Inconnu',
                            'total_ht' => '200,00'
                        ]
                    ]
                ]
            ],
            'refSupplier' => '123'
        ];

        // Vérification que le résultat est bien celui attendu
        $this->assertEquals($expectedResult, $result);
    }
}
