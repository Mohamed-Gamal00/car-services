<?php

namespace App\Repositories\Package;

interface PackageInterface
{
    public function index();
    public function store($params);
    public function update($params,$id);
    public function delete($id);
}