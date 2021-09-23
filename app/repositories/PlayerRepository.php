<?php

namespace App\Repositories;

use App\Models\Player;
use App\Repositories\BaseRepository;

class PlayerRepository extends BaseRepository
{

    public function getModel()
    {
        return Player::query();
    }


    public function create(array $data)
    {
        $acount = $this->getModel()->firstOrCreate(['name' =>  $data['name']], $data);
        return $acount;
    }

    public function find(string $name, string $order)
    {
        $players = $this->getModel()
            ->with(['nation:id,name', 'club:id,name'])
            ->where('name', 'ilike', '%' . $name . '%')
            ->orderBy('name', $order)
            ->get();
        return $players;
    }
}
