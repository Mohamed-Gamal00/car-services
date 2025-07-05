<?php

namespace App\Repositories\Client;

use App\Helper\Helper;

use App\Models\User;
use Illuminate\Support\Facades\Storage;

class ClientRepository implements ClientInterface
{
    use Helper;

    protected $client;

    public function __construct(User $client)
    {
        $this->client = $client;
    }

    public function getMainClient()
    {
        $request = request();
        return $this->client->latest()->filter($request->query())->paginate();

    }

    public function clientDetails($id)
    {
        $client = $this->client->where('id', $id)->first();

        // Paginate the orders for the client (not inside load)
        $client->orders = $client->orders()->latest()->paginate(); // Paginate orders with 10 per page

        return $client;
    }

    public function updatePassword($data, $id)
    {
        $client = $this->client->findOrFail($id);
        if (isset($data['new_password'])) {
            $data['password'] = bcrypt($data['new_password']);  // Make sure to hash the password
            unset($data['new_password']);  // Remove the raw password from the data
            unset($data['new_password_confirmation']);  // Remove the confirmation field
        }
        $client->update($data);
    }

    public function update($data, $id)
    {
        $client = $this->client->findOrFail($id);
        $client->update($data);

        return $client->wasChanged();
    }

    public function delete($id)
    {
        $client = User::findOrFail($id);
        $client->delete();
    }
}
