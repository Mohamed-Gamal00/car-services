<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Http\Requests\profile\CreateNewAddressRequest;
use App\Http\Requests\profile\UpdateNewAddressRequest;
use App\Http\Requests\profile\UserRequest;
use App\Models\Admin;
use App\Models\City;
use App\Models\Product;
use App\Models\User;
use App\Models\UserAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserProfileController extends Controller
{
    public function index()
    {
        return view('front.profile.index');
    }

    public function userWishList()
    {
        $user = Auth::guard('web')->user();
        $products = $user->wishlistProducts()->paginate(9);
        return view('front.profile.user_wishlist', \compact('products'));
    }

    public function userInfo()
    {
        $user = Auth::guard('web')->user();
        return view('front.profile.user_info', \compact('user'));
    }

    public function updateUserInfo(UserRequest $request)
    {
        $user = Auth::guard('web')->user();
        $user->update($request->all());
        return to_route('user.info')->with('info', 'تم تغيير البيانات بنجاح');
    }

    public function userAddresses()
    {
        $user = Auth::guard('web')->user();
        // $returnProducts = User::with('returnProducts', 'orders.products')->where('id', $user->id)->first();
        return view('front.profile.user_addresses', compact('user'));
    }

    public function setMainAddress($addressId)
    {
        $user = Auth::guard('web')->user();

        // Set all addresses main_address to false
        $user->addresses()->update(['main_address' => 0]);

        // Set the selected address main_address to true
        $user->addresses()->where('id', $addressId)->update(['main_address' => 1]);

        return redirect()->route('user.addresses')->with('success', 'Main address updated successfully.');
    }

    public function userAddressesCreate()
    {
        $user = Auth::guard('web')->user();
        $countryId = UserAddress::select('country_id')->where('user_id', $user->id)
            ->where('main_address', 1)
            ->first();
        // dd($countryId);
        $cities = City::where('country_id', $countryId->country_id)
            ->where('status', 'used')
            ->get();

        return view('front.profile.create_edit_addresses.create', \compact('user', 'cities', 'countryId'));
    }

    public function userAddressesStore(CreateNewAddressRequest $request)
    {

        UserAddress::create($request->all());
        return to_route('user.addresses')->with('success', 'تم اضافة العنوان');
    }

    public function userAddressesEdit($addressId)
    {
        $user = Auth::guard('web')->user();
        $address = UserAddress::findOrFail($addressId);


        $cities = City::where('country_id', $address->country_id)
            ->where('status', 'used')
            ->get();

        return view('front.profile.create_edit_addresses.edit', \compact('user', 'address', 'cities'));
    }

    public function userAddressesUpdate(UpdateNewAddressRequest $request, $addressId)
    {
        $address = UserAddress::findOrFail($addressId);
        $address->update($request->all());
        return to_route('user.addresses')->with('success', 'تم تحديث العنوان');
    }

    public function userAddressesDestroy(Request $request, $addressId)
    {
        $address = UserAddress::findOrFail($addressId);
        $address->delete();
        return to_route('user.addresses')->with('danger', 'تم مسح العنوان');
    }

    public function changePasswordView()
    {
        return view('front.profile.change_password');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'new_password' => 'required|confirmed|min:6'
        ]);
        $user = Auth::guard('web')->user();
        $user->update([
            'password' => Hash::make($request->new_password)
        ]);
        return to_route('user.info')->with('info', 'تم تغيير الرقم السري بنجاح');
    }
}
