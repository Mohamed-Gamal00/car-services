<?php

namespace App\Repositories\Captains;

use App\Helper\Helper;

use App\Models\Captain;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class CaptainRepository implements CaptainInterface
{
    use Helper;

    protected $captain;

    public function __construct(Captain $captain)
    {
        $this->captain = $captain;
    }

    public function getMainCaptain()
    {
        return $this->captain::paginate(1);
    }

    public function updatePassword($data, $id)
    {
//        $captain = $this->captain->findOrFail($id);
//        $captain->update([
//            'new_password' => Hash::make($data)
//        ]);
    }

    public function canUpdateCaptainStatus($id)
    {
        $captain = $this->captain->findOrFail($id);

        // تحقق من الطلبات المرتبطة بالكابتن
        $hasPendingOrders = $captain->orders()->whereIn('order_status_id', [12, 3])->exists();

        // إذا كان لديه طلبات مقبولة أو جاري التنفيذ، فلا يمكن تحديث الحالة
        return !$hasPendingOrders;
    }

    public function update($data, $id)
    {
        $captain = $this->captain->findOrFail($id);
        $captain->update($data);

        return $captain->wasChanged();
    }

    public function delete($id)
    {
        $captain = Captain::findOrFail($id);
        $captain->delete();
    }
}
