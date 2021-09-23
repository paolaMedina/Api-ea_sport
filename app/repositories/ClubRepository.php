<?php

namespace App\Repositories;

use App\Models\Club;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Collection;

class ClubRepository extends BaseRepository
{

    public function getModel()
    {
        return Club::query();
    }


    public function create(array $data)
    {
        $club = $this->getModel()->firstOrCreate(['name' =>  $data['name']], $data);
        return $club;
    }

    public function findForClub(string $club, int $page)
    {
        $result = [];
        $club = $this->getModel()
            ->where('name', 'ilike', '%' . $club . '%')
            ->first();

        $players = $club->players;

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
        return collect($result);
    }
}
