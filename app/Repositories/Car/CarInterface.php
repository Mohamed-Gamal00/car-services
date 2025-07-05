<?php

namespace App\Repositories\Car;

/*
    Separates databases layer from services layer
    contain all databse work
    handel the database operation as create update delete ..
*/

interface CarInterface
{
    public function getMain();

    public function store($params);

    public function getById($id);

    public function update($params, $id);

    public function delete($id);
}
