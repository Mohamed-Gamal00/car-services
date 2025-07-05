<?php

namespace App\Livewire\Products;

use App\Models\Company;
use App\Models\MainCategory;
use App\Models\Product;
use App\Models\SubSettings;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class ProductEdit extends Component
{
    use WithFileUploads;

    public $categories;
    public $firstSubCategory;
    public $companies;
    public $secSubCategory;
    public $productId;
    public $product;

    public $selectedCategory = null;
    public $selectedFirstSubCategory = null;

    public $name;
    public $description;
    public $image;
    public $price;
    public $discount_price;
    public $quantity;
    public $status;
    public $category_id;
    public $company_id;
    public $main_category_setting_id;


    protected $rules = [
        'name' => ['required', 'string', 'max:255'],
        'discount_price' => ['nullable', 'numeric'],
        'image' => ['nullable', 'image'],
        'status' => ['required', 'in:active,archived'],
        'price' => ['required', 'numeric'],
        'category_id' => ['required', 'exists:main_categories,id'],
        'company_id' => ['nullable', 'exists:companies,id'],
        'main_category_setting_id' => ['nullable', 'exists:main_category_settings,id'],
        'description' => ['nullable'],
        'quantity' => ['nullable', 'numeric', 'min:1'],
    ];

    public function mount($productId = null)
    {

        $this->categories = MainCategory::all();
        $this->companies = Company::all();

        if ($productId) {
            // Product ID is provided, attempt to retrieve the product
            $product = Product::find($productId);

            if ($product) {
                $this->product = $product;
                $this->name = $product->name;
                $this->price = $product->price;
                $this->discount_price = $product->discount_price;
                $this->quantity = $product->quantity;
                $this->description = $product->description;
                $this->category_id = $product->category_id;
                $this->company_id = $product->company_id;
                $this->status = $product->status;
                $this->main_category_setting_id = $product->main_category_setting_id;
                $this->firstSubCategory = MainCategory::findOrFail($this->category_id);
                $this->secSubCategory = SubSettings::where('main_category_setting_id', $this->main_category_setting_id)->get();
                $this->selectedFirstSubCategory = $product->main_category_setting_id;
            } else {
                // Handle the case where the product is not found
                abort(404, 'Product not found');
            }
        }
    }

    public function save($FormData)
    {

        \dd($FormData);
        $this->validate();

        $oldImage = $this->product->image;
        $newImage = null;
        $uploadedImage = $oldImage;

        if ($this->image) {
            $newImage = $this->image->store('uploads/products', 'public');
        }

        if ($newImage != null) {
            $uploadedImage = $newImage;
        }

        if ($oldImage && $newImage) {
            Storage::disk('public')->delete($oldImage);
        }

        $this->product->update([
            'name' => $this->name,
            'image' => $uploadedImage,
            'price' => $this->price,
            'discount_price' => $this->discount_price,
            'quantity' => $this->quantity,
            'description' => $this->description,
            'category_id' => $this->category_id,
            'status' => $this->status,
            'main_category_setting_id' => $this->main_category_setting_id,
            'company_id' => $this->company_id
        ]);

        return redirect()->route('products.index')->with('success', 'تم تحديث المنتج بنجاح');
    }

    public function updatedSelectedCategory($category)
    {
        // $this->selectedFirstSubCategory = null;
        $this->firstSubCategory = MainCategory::findOrFail($category);
    }

    public function updatedSelectedFirstSubCategory($subCategory)
    {
        $this->main_category_setting_id = $subCategory;
    }


    public function render()
    {
        return view('livewire.products.product-edit');
    }
}
