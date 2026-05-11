<?php

namespace App\Repositories\Car;

use App\Helper\Helper;
use App\Models\Car;


class CarRepository implements CarInterface
{

    use Helper;

    public $car;

    public function __construct(Car $car)
    {
        $this->car = $car;
    }

    public function getMain()
    {
        return $this->car->paginate(15);
    }

    public function store($data)
    {
        return $this->car->create($data);
    }

    public function getById($id)
    {
        return $this->car->findOrFail($id);
    }

    public function update($data, $id)
    {
        $car = $this->car->findOrFail($id);
        $car->update($data);
        return $car;
    }

    public function delete($id)
    {
        $car = $this->car->findOrFail($id);
        $car->delete();
    }


}
