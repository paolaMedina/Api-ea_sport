<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Auth;

abstract class BaseRepository
{
    /**
     *
     *
     * @return \Logistic\Entities\Entity
     */
    abstract public function getModel();

    /**
     *
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function newQuery()
    {
        return $this->getModel()->newQuery();
    }

    /**
     *
     *
     * @param $id
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function findOrFail($id)
    {
        return $this->newQuery()->findOrFail($id);
    }

    /**
     *
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function firstOrFail()
    {
        return $this->newQuery()->firstOrFail();
    }

    /**
     * Display current auth user.
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable
     */
    public function currentUser()
    {
        return Auth::user();
    }
}
