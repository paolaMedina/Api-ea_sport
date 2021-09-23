<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Repositories\ClubRepository;
use App\Repositories\CountryRepository;
use App\Repositories\PlayerRepository;
use Exception;

class ClubController extends ApiController
{

    private $clubRepository;

    public function __construct(
        ClubRepository $clubRepository
    ) {
        $this->clubRepository = $clubRepository;
    }

    public function findTeam(Request $request)
    {
        try {
            $result = [];
            $request->validate([
                'name' => 'required|string',
            ]);
            !$request->page && $request->request->add(['page' => 1]);
            $name = $request->name;

            $players = $this->clubRepository->findForClub($name);

            foreach ($players as $player) {
                array_push(
                    $result,
                    [
                        "name" => $player->name,
                        "position" => $player->position,
                        "nation"  => $player->nation->name
                    ]
                );
            };

            return $this->showAll(collect($result));
        } catch (Exception $e) {
            return $this->errorResponse('Se presento un error', 500);
        }
    }
}
