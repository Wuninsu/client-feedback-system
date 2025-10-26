<?php

namespace App\Livewire\Forms;

use App\Models\MessageTemplate;
use Illuminate\Support\Facades\Validator;
use Livewire\Attributes\Title;
use Livewire\Component;

class TemplateFrom extends Component
{
    public $templateId;
    public $name;
    public $type = 'sms';
    public $subject;
    public $body;
    public $is_active = true;

    public function mount(MessageTemplate $template)
    {
        if ($template->id) {
            $this->name = $template->name;
            $this->type = $template->type;
            $this->subject = $template->subject;
            $this->body = $template->body;
            $this->is_active = $template->is_active;
            $this->templateId = $template->id;
        }
    }

    public function save()
    {
        $validator = Validator::make([
            'name' => $this->name,
            'type' => $this->type,
            'subject' => $this->subject,
            'body' => $this->body,
            'is_active' => $this->is_active,
        ], [
            'name' => 'required|string|unique:message_templates,name,' . $this->templateId,
            'type' => 'required|in:sms,email',
            'body' => 'required|string',
            'is_active' => 'boolean',
        ]);

        // Conditionally require subject if type is email
        $validator->sometimes('subject', 'required|string|max:255', function () {
            return $this->type === 'email';
        });

        $validator->validate();

        MessageTemplate::updateOrCreate(
            ['id' => $this->templateId],
            [
                'name' => $this->name,
                'type' => $this->type,
                'subject' => $this->type === 'email' ? $this->subject : null,
                'body' => $this->body,
                'is_active' => $this->is_active,
            ]
        );

        sweetalert()->success($this->templateId ? 'Template updated successfully' : 'Template created successfully');

        return redirect()->route('templates.index');
    }

    #[Title('Manage Template')]
    public function render()
    {
        return view('livewire.forms.template-from');
    }
}
