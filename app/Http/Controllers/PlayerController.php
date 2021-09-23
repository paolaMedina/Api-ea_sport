<?php

namespace App\Http\Controllers;

use App\Repositories\ClubRepository;
use App\Repositories\CountryRepository;
use App\Repositories\PlayerRepository;
use Illuminate\Http\Request;
use GuzzleHttp\Client;

class PlayerController extends Controller
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

        return json_encode(['message' => 'carga de datos']);
    }
}
