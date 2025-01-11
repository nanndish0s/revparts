<?php

namespace App\Livewire;

use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;

class ProductFilter extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    public $search = '';
    public $category = '';
    public $sortOption = 'default';

    protected $queryString = [
        'search' => ['except' => ''],
        'category' => ['except' => ''],
        'sortOption' => ['except' => 'default']
    ];

    // Define available categories
    public function getCategories()
    {
        return [
            'engine-parts' => 'Engine Parts',
            'brake-system' => 'Brake System',
            'electrical' => 'Electrical',
            'suspension' => 'Suspension',
            'transmission' => 'Transmission'
        ];
    }

    public function mount()
    {
        $this->search = '';
        $this->category = '';
        $this->sortOption = 'default';
    }

    public function clearFilters()
    {
        $this->search = '';
        $this->category = '';
        $this->sortOption = 'default';
        $this->resetPage();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingCategory()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Product::query();

        // Search filter
        if ($this->search) {
            $query->where(function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%');
            });
        }

        // Category filter
        if ($this->category) {
            $query->where('category', $this->category);
        }

        // Sorting
        switch ($this->sortOption) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            default:
                $query->latest();
        }

        $products = $query->paginate(12);

        return view('livewire.product-filter', [
            'products' => $products,
            'categories' => $this->getCategories()
        ]);
    }
}
