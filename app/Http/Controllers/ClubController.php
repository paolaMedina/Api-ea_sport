<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Repositories\ClubRepository;
use App\Repositories\CountryRepository;
use App\Repositories\PlayerRepository;
use Exception;

class ClubController extends ApiController
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

    public function findTeam(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
        ]);
        try {

            $name = $request->name;
            $page = $request->page ? (int)$request->page : 1;
            $players = $this->clubRepository->findForClub($name, $page);

            return $this->showAll($players);
        } catch (Exception $e) {
        }
    }
}
