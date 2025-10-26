<?php

namespace App\Livewire\Main;

use App\Models\Setup;
use Livewire\Attributes\Title;
use Livewire\Component;

class SetupIndex extends Component
{
    public $settings = [];
    public function mount()
    {
        $settingsData = Setup::all();

        foreach ($settingsData as $setting) {
            if (in_array($setting->key, ['why_choose_us_points'])) {
                $this->settings[$setting->key] = json_decode($setting->value, true) ?? [];
            } else {
                $this->settings[$setting->key] = $setting->value;
            }
        }
    }


    public function addWhyChooseUsPoint()
    {
        $points = json_decode($this->settings['why_choose_us_points'] ?? '[]', true);
        $points[] = ['title' => '', 'description' => ''];
        $this->settings['why_choose_us_points'] = $points;
    }


    public function updateData()
    {
        foreach ($this->settings as $key => $value) {
            // Find the setting by 'key' instead of 'id'
            $setting = Setup::where('key', $key)->first();

            if ($setting) {
                // Handle file uploads for logo and favicon
                if ((in_array($key, ['logo', 'favicon', 'main_background_image', 'footer_background_image'])) && $this->settings[$key] instanceof TemporaryUploadedFile) {
                    $newFileName = $key . '_' . time() . '.' . $this->settings[$key]->extension();
                    $filePath = uploadFile($this->settings[$key], 'uploads', $newFileName);
                    $value = $filePath; // Set value to file path
                }
                // Handle JSON encoding for structured keys
                if ($key === 'why_choose_us_points' && is_array($value)) {
                    $value = json_encode($value);
                }

                // Update the settings table using 'key'
                Setup::where('key', $key)->update(['value' => $value]);
            }
        }


        sweetalert()->success('Settings updated successfully!');
    }



    #[Title('Setups')]

    public function render()
    {
        return view('livewire.main.setup-index');
    }
}
