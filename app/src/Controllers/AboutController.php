<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\AppSettings;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class AboutController extends BaseController
{
    private const API_NAME = 'YOUR_PROJECT_NAME';

    private const API_VERSION = '1.0.0';

    public function handleAboutWebService(Request $request, Response $response): Response
    {
        $data = array(
            'api' => self::API_NAME,
            'version' => self::API_VERSION,
            'about' => 'Welcome to our Product API where we introduce information about food products specifically. ',
            'authors' => 'Grechelle Uy, Janna Lomibao, Bridjette Nania Centro',
            'pagination' => 'TBA',
            'sorting' => 'TBA',
            'resources' => [

                [
                    'resource_number' => 1,
                    'uri' => '/products',
                    'description' => "",
                    'filters_supported' => ['', '', '', '', ''],
                    'sorting' =>  [

                        [
                            '' => ['', '', '']
                        ],

                        [
                            '' => ['', '']
                        ]
                    ]
                ],

                [
                    'resource_number' => 2,
                    'uri' => '/players/{player_id}',
                    'description' => "Gets the details of the specified player",
                    'filters_supported' => "N/A"
                ],

                [
                    'resource_number' => 3,
                    'uri' => '/players/{player_id}/goals',
                    'description' => "Gets a list of goals scored by the specified player",
                    'filters_supported' => ['tournament_id', 'match_id']
                ],

                [
                    'resource_number' => 4,
                    'uri' => '/players/{player_id}/appearances',
                    'description' => "Gets a list of the specified player’s appearances",
                    'filters_supported' => "N/A"
                ],

                [
                    'resource_number' => 5,
                    'uri' => '/teams',
                    'description' => "Gets a list of zero or more teams matching the specified filter.",
                    'filters_supported' => ['region']
                ],

                [
                    'resource_number' => 6,
                    'uri' => '/teams/{team_id}/appearances',
                    'description' => "Gets a list of zero or more appearances of the specified team.",
                    'filters_supported' => ['match_result']
                ],

                [
                    'resource_number' => 7,
                    'uri' => '/tournaments',
                    'description' => "Gets a list of zero or more World Cup tournaments",
                    'filters_supported' => ['start_date', 'winner', 'host_country', 'tournament_type']
                ],

                [
                    'resource_number' => 8,
                    'uri' => '/tournaments/{tournament_id}/matches',
                    'description' => "Gets the list of matches that took place in the specified tournament and match the request filter.",
                    'filters_supported' => ['stage']
                ],

                [
                    'resource_number' => 9,
                    'uri' => '/matches/{match_id}/players ',
                    'description' => "Gets the list of players who played in the specified match FROM 1970.",
                    'filters_supported' => ['position']
                ],

                [
                    'resource_number' => 10,
                    'uri' => '/stadiums',
                    'description' => "Gets the list of stadiums where World Cup matches took place.",
                    'filters_supported' => ['country', 'city', 'capacity'],
                    'sorting' =>  [

                        [
                            'sortBy' => ['stadium_id', 'stadium_name', 'country_name', 'stadium_capacity']
                        ],

                        [
                            'orderBy' => ['ASC', 'DESC']
                        ]
                    ]
                ],

                [
                    'resource_number' => 11,
                    'uri' => '/stadiums/{stadium_id}/matches',
                    'description' => "Gets List of matches that took place the specified stadium.",
                    'filters_supported' => ['tournament', 'stage_name']
                ],
            ]
        );

        return $this->renderJson($response, $data);
    }
}
