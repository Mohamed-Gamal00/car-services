<?php

namespace App\Repositories\Captains;

interface CaptainInterface
{
    public function getMainCaptain();

    public function updatePassword($params, $id);

    public function update($params, $id);

    public function delete($id);
}
