<?php

namespace App\Http\Livewire;
use App\Contact;
use Livewire\Component;

class Contactt extends Component
{
    public $data,$name,$email,$selected_id,$search;
    public $updateMode=false;
    public function render()
    {
        //$this->data=Contact::all();
        $this->data = Contact::where('name', 'like', '%'.$this->search.'%')
               ->Orwhere('email', 'like', '%'.$this->search.'%')
            //   ->Orwhere('gender', 'like', '%'.$this->search.'%')
                ->get();
        return view('livewire.contactt');
    }

    protected $updatesQueryString = ['search'];

    public function mount()
    {
        $this->search = request()->query('search', $this->search);
    }

    private function resetInput(){
        $this->name = null;
        $this->email =null;

    }
    public function store(){
        $this->validate([
            'name' => 'required|min:5',
            'email' => 'required|email:rfc,dns'
        ]);
        Contact::create([
            'name' => $this->name,
            'email' => $this->email
        ]);
        $this->resetInput();
    }
    public function edit($id)
    {
        $record = Contact::findOrFail($id);
        $this->selected_id = $id;
        $this->name = $record->name;
        $this->email = $record->email;
        $this->updateMode = true;
    }
    public function update()
    {
        $this->validate([
            'selected_id' => 'required|numeric',
            'name' => 'required|min:5',
            'email' => 'required|email:rfc,dns'
        ]);
        if ($this->selected_id) {
            $record = Contact::find($this->selected_id);
            $record->update([
                'name' => $this->name,
                'email' => $this->email
            ]);
            $this->resetInput();
            $this->updateMode = false;
        }
    }
    public function destroy($id)
    {
        if ($id) {
            $record = Contact::where('id', $id);
            $record->delete();
        }
    }
}

