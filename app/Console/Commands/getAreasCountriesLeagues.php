<?php

namespace App\Console\Commands;

use App\Models\Area;
use App\Models\Country;
use App\Models\League;
use App\Models\Season;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class getAreasCountriesLeagues extends Command
{
    public $index = 0;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:getAreasCountriesLeagues';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->getAreasCountruesLeagues();
    }

    public function getAreasCountruesLeagues()
    {
        $data = $this->getDatas();
        $data = $this->convertEncodingToUTF8($data);
        return response()->json($data, 200, [], JSON_UNESCAPED_UNICODE);
    }
    public function getDatas()
    {
        $url = "https://football.nowgoal19.com/jsData/leftData/leftData.js";

        $client = new Client();
        $response = $client->request('GET', $url);
        $content = $response->getBody()->getContents();

        return $this->parseJavaScriptArray($content);
    }

    private function parseJavaScriptArray($js)
    {
        $js = preg_replace('/var\s+arrArea\s*=\s*new\s+Array\s*\(\s*\)\s*;/', '', $js);

        preg_match_all('/arrArea\[\d+\]\s*=\s*(.*?);/s', $js, $matches);
        $arrays = [];
        foreach ($matches[1] as $arrayString) {
            $json = $this->jsArrayToJson($arrayString);

            $decoded = json_decode($json);

            if ($decoded === null) {
                throw new \Exception('JSON parse error: ' . json_last_error_msg());
            }

            $englishData = $this->extractEnglishParts($decoded);

            $englishData = $this->structureData($englishData, $decoded);

            $arrays[] = $englishData;
        }

        return $arrays;
    }

    private function jsArrayToJson($jsArrayString)
    {
        $jsArrayString = str_replace("'", '"', $jsArrayString);

        $jsArrayString = preg_replace('/new\s+Array\s*\(\s*\)/', '[]', $jsArrayString);

        $jsArrayString = preg_replace('/,\s*]/', ']', $jsArrayString);
        $jsArrayString = preg_replace('/,\s*}/', '}', $jsArrayString);

        $jsArrayString = preg_replace('/undefined/', 'null', $jsArrayString);

        return $jsArrayString;
    }

    private function extractEnglishParts($data)
    {
        $result = [];
        foreach ($data as $item) {
            if (is_array($item)) {
                $englishName = isset($item[2]) ? $item[2] : null;


                $newItem = [
                    'country_id' => $this->index,
                    'name' => $englishName,
                    'slug' => Str::slug($englishName),
                    'area_id' => null,
                ];
                $leagues = [];

                if (isset($item[5]) && is_array($item[5])) {
                    $leagues = array_merge($leagues, $this->extractLeagues($item[5], $this->index));
                }

                if (isset($item[4]) && is_array($item[4])) {
                    $leagues = array_merge($leagues, $this->extractLeagues($item[4], $this->index));
                }
                $this->index++;

                if (!empty($leagues)) {

                    $newItem['leagues'] = array_merge($leagues);
                }

                $result[] = $newItem;
            }
        }

        return $result;
    }

    private function extractLeagues($data, $countryId)
    {
        $leagues = [];

        foreach ($data as $item) {
            if (is_array($item) && isset($item[0]) && isset($item[3])) {
                $leagueId = $item[0];
                $leagueEnglishName = $item[3];
                $type = $item[4];


                $leagueData = $this->getLeagueInfo($leagueId);
                if ($leagueData != null) {
                    $leagues[] = [
                        'league_id' => $leagueId,
                        'league_short_name' => $leagueEnglishName,
                        'league_name' => $leagueData['league_name'],
                        'slug' => Str::slug($leagueData['league_name']),
                        'country_id' => $countryId,
                        'logo' => $leagueData['league_image'] ?? null,
                        'type' => $type,
                    ];

                    League::updateOrCreate(
                        [
                            'league_id' => $leagueId,
                            'league_short_name' => $leagueEnglishName,
                        ],
                        [
                            'league_name' => $leagueData['league_name'],
                            'slug' => Str::slug($leagueData['league_name']),
                            'country_id' => $countryId,
                            'logo' => $leagueData['league_image'] ?? null,
                            'type' => $type,
                        ]
                    );
                } else {
                    $leagues[] = [
                        'league_id' => $leagueId,
                        'league_short_name' => $leagueEnglishName,
                        'league_name' => null,
                        'slug' => Str::slug($leagueEnglishName),
                        'country_id' => $countryId,
                        'logo' => null,
                        'type' => $type,
                        'text' => null,
                    ];

                    League::updateOrCreate(
                        [
                            'league_id' => $leagueId,
                            'league_short_name' => $leagueEnglishName,
                        ],
                        [
                            'league_name' => null,
                            'slug' => Str::slug($leagueEnglishName),
                            'country_id' => $countryId,
                            'logo' => null,
                            'type' => $type,
                            'text' => null,
                        ]
                    );
                }
            }
        }
        return $leagues;
    }

    private function structureData($data, $originalData)
    {
        $title = isset($data[0]['name']) ? $data[0]['name'] : null;
        $areaId = isset($originalData[0][3]) ? $originalData[0][3] : null;


        $data = array_values($data);

        foreach ($data as &$country) {
            $country['area_id'] = $areaId;

            Country::updateOrCreate(
                [
                    'country_id' => $country['country_id'],
                    'slug' => $country['slug']
                ],
                [
                    'name' => $country['name'],
                    'area_id' => $areaId
                ]
            );

        }

        Area::updateOrCreate(
            [
                'area_id' => $areaId,
                'slug' => Str::slug($title)
            ],
            [
                'title' => $title
            ]
        );


        return [
            'id' => $areaId,
            'area_id' => $areaId,
            'title' => $title,
            'slug' => Str::slug($title),
            'country' => $data,
        ];

    }

    private function convertEncodingToUTF8($data)
    {
        if (is_array($data)) {
            $result = [];
            foreach ($data as $key => $value) {
                $result[$key] = $this->convertEncodingToUTF8($value);
            }
            return $result;
        } elseif (is_string($data)) {
            return mb_convert_encoding($data, 'UTF-8', 'UTF-8');
        } else {
            return $data;
        }
    }


    public function getLeagueInfo($id)
    {
        // The URL of the JavaScript data
        $url = "https://football.nowgoal19.com/jsdata/teaminfo/team{$id}.js?v=20240925052403";

        // Initialize Guzzle HTTP client
        $client = new Client();

        try {
            // Send a GET request to the URL
            $response = $client->request('GET', $url);

            // Get the response body as a string
            $body = $response->getBody()->getContents();

            // Extract the arrLeague array using regex
            if (preg_match("/var\s+arrLeague\s*=\s*(\[[^\]]+\]);/i", $body, $matches)) {
                $arrLeagueString = $matches[1];

                // Convert the JavaScript array to a JSON-compatible string
                // Replace single quotes with double quotes
                $jsonString = preg_replace("/'/", '"', $arrLeagueString);

                // Optionally, remove trailing commas (if any)
                $jsonString = rtrim($jsonString, ',');

                // Decode the JSON string into a PHP array
                $arrLeague = json_decode($jsonString, true);

                if (json_last_error() !== JSON_ERROR_NONE) {
                    throw new Exception('JSON Decode Error: ' . json_last_error_msg());
                }


                $leagueName = $arrLeague[3]; // English name
                $leagueImagePath = $arrLeague[6]; // Image path

                // If the image path is relative, prepend the base URL
                $leagueImage = $leagueImagePath;

                // Return the data as JSON
                return [
                    'league_name' => $leagueName,
                    'league_image' => $leagueImage,
                ];
            } else {
                throw new Exception('arrLeague array not found in the response.');
            }
        } catch (Exception $e) {
            return null;
        }
    }

    public function getSeasons($id)
    {
        $url = "https://football.nowgoal19.com/jsdata/leagueseason/sea{$id}.js";

        $client = new Client();

        $response = $client->request('GET', $url);

        $content = $response->getBody()->getContents();

        preg_match("/var\s+arrSeason\s*=\s*(\[[^\]]*\]);/s", $content, $matches);

        if (!isset($matches[1])) {
            throw new \Exception('arrSeason tapılmadı.');
        }

        $arrSeasonString = $matches[1];


        $arrSeasonString = str_replace("'", '"', $arrSeasonString);

        $arrSeasonString = str_replace("undefined", 'null', $arrSeasonString);

        $arrSeasonString = preg_replace('/,\s*(\]|\})/', '$1', $arrSeasonString);

        $seasons = json_decode($arrSeasonString, true);

        if ($seasons === null) {
            throw new \Exception('JSON parse xətası: ' . json_last_error_msg());
        }
        foreach ($seasons as $season) {
            Season::updateOrCreate(['league_id' => $id, 'title' => $season]);
        }
        return $seasons;
    }
}
