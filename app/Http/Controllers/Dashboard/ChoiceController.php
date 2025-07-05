<?php

namespace App\Http\Controllers\Dashboard;

use App\Helper\Helper;
use App\Http\Controllers\Controller;
use App\Models\Choice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ChoiceController extends Controller
{
    use Helper;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $choices = Choice::latest()->paginate();
        return view('dashboard.choices.index', compact('choices'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'service_price' => 'required|string|max:255',
            'image' => 'nullable',
        ]);
        $data['image'] = $this->uploadedImage(request(), 'image', 'choices');
//        return $data;

        DB::transaction(function () use ($data) {
            Choice::create($data);
        });
        return redirect()->route('main_choices.index')
            ->with('success', 'تم الاضافة بنجاح');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $mainChoices = Choice::latest()->paginate();
        return view('dashboard.choices.create', compact('mainChoices'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $choice = Choice::findOrFail($id);
        return view('dashboard.choices.edit', compact('choice',));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $choice = Choice::findOrFail($id);

        $data = $request->validate([
            'name' => 'nullable|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'service_price' => 'required|string|max:255',

        ]);
        $oldImage = $choice->image;
        $newImage = $this->uploadedImage(request(), 'image', 'choices');
        if ($newImage) {
            $data['image'] = $newImage;
        }
        if ($newImage && $oldImage) {
            Storage::disk('public')->delete($oldImage);
        }
        // dd($data);
        DB::transaction(function () use ($choice, $data) {
            $choice->update($data);
        });
        return redirect()->route('main_choices.index')
            ->with('success', ('تم التحديث بنجاح'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $choice = Choice::findOrFail($id);
        Storage::disk('public')->delete($choice->image);
        $choice->delete();
        return back()
            ->with('dark', __('تم الحذف بنجاح'));
    }
}
