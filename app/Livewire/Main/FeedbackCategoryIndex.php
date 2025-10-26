<?php

namespace App\Livewire\Main;

use App\Models\FeedbackCategory;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

class FeedbackCategoryIndex extends Component
{
    public $categories;
    public $categoryId = null;
    public $name = '';
    public $description = '';
    public $is_active = true;

    public function mount()
    {
        $this->loadCategories();
    }

    public function loadCategories()
    {
        $this->categories = FeedbackCategory::orderBy('name')->get();
    }

    public function edit($id)
    {
        $category = FeedbackCategory::findOrFail($id);
        $this->categoryId = $category->id;
        $this->name = $category->name;
        $this->description = $category->description;
        $this->is_active = $category->is_active;
    }

    public function resetForm()
    {
        $this->reset(['categoryId', 'name', 'description', 'is_active']);
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|unique:feedback_categories,name,' . $this->categoryId,
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        FeedbackCategory::updateOrCreate(
            ['id' => $this->categoryId],
            [
                'name' => $this->name,
                'description' => $this->description,
                'is_active' => $this->is_active,
            ]
        );

        session()->flash('success', 'Category saved successfully.');
        $this->resetForm();
        $this->loadCategories();
    }



    public function confirmDelete($id)
    {
        $this->dispatch('confirm', id: $id);
    }

    #[On('delete')]
    public function handleDelete($id)
    {
        $category = FeedbackCategory::find($id);
        if ($category) {
            $category->delete();
            $this->loadCategories();
            sweetalert()->success('Category deleted successfully.');
        } else {
            sweetalert()->error('Category not found.');
        }
    }

    #[Title('Manage Feedback Category')]
    public function render()
    {
        return view('livewire.main.feedback-category-index');
    }
}
