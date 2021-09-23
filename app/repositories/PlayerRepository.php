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
}
