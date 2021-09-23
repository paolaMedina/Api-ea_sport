<?php

namespace App\Repositories;

use App\Models\Country;
use App\Repositories\BaseRepository;

class CountryRepository extends BaseRepository
{

    public function getModel()
    {
        return Country::query();
    }


    public function create(array $data)
    {
        $acount = $this->getModel()->firstOrCreate(['name' =>  $data['name']], $data);
        return $acount;
    }
}
