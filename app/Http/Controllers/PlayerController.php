<?php

namespace App\Http\Controllers;

use App\Repositories\ClubRepository;
use App\Repositories\CountryRepository;
use App\Repositories\PlayerRepository;
use Exception;
use Illuminate\Http\Request;
use GuzzleHttp\Client;

class PlayerController extends ApiController
{
    private $clubRepository, $countryRepository, $playerRepository;

    public function __construct(
        ClubRepository $clubRepository,
        CountryRepository $countryRepository,
        PlayerRepository $playerRepository
    ) {
        $this->clubRepository = $clubRepository;
        $this->countryRepository = $countryRepository;
        $this->playerRepository = $playerRepository;
    }
    public function loadData()
    {
        try {
            set_time_limit(8000000);
            $totalPages = 1;
            $currentPage = 1;
            do {
                $url = config('app.url_easports') . "?page=$currentPage";
                $client = new Client();
                $res = $client->request('GET', $url);
                $body = $res->getBody();
                $data = json_decode($body->getContents());
                foreach ($data->items as $item) {
                    $club = $this->clubRepository->create((array)$item->club);
                    $country = $this->countryRepository->create((array)$item->nation);
                    $data_player = [
                        'name' => $item->name,
                        'position' => $item->position,
                        'nation_id' => $country->id,
                        'club_id' => $club->id
                    ];
                    $this->playerRepository->create($data_player);
                }
                if ($totalPages != $data->totalPages) {
                    $totalPages = $data->totalPages;
                }
                $currentPage++;
            } while ($currentPage <= $totalPages);

            return $this->successResponse('carga de datos');
        } catch (Exception $e) {
            return $this->errorResponse('Se presento un error', 500);
        }
    }

    public function index(Request $request)
    {
        try {
            $result = [];
            !$request->page && $request->request->add(['page' => 1]);

            $name = $request->name ? $request->name : '';
            $order =  $request->order ? $request->order : 'asc';

            $players = $this->playerRepository->find($name, $order);

            foreach ($players as $value) {
                array_push(
                    $result,
                    [
                        "name" => $value->name,
                        "position" => $value->position,
                        "nation"  => $value->nation->name,
                        "team" => $value->club->name
                    ]
                );
            }

            return $this->showAll(collect($result));
        } catch (Exception $e) {
            return $this->errorResponse('Se presento un error', 500);
        }
    }
}
