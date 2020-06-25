<?php

namespace App\Http\Livewire;

use App\Contact;
use App\User;
use Livewire\Component;

class Users extends Component
{
    public $users, $name, $email, $body, $contact_id;
    public $updateMode = false;

    public function render()
    {
        $this->users = Contact::all();
        return view('livewire.users');
    }

    private function resetInputFields(){
        $this->name = '';
        $this->email = '';
        $this->body = '';
    }

    public function store()
    {
        $validatedData = $this->validate([
            'name' => 'required',
            'email' => 'required|email',
            'body' => 'required',
        ]);

        Contact::create($validatedData);

        session()->flash('message', 'Users Created Successfully.');

        $this->resetInputFields();

    }

    public function edit($id)
    {
        $this->updateMode = true;
        $user = Contact::where('id',$id)->first();
        $this->contact_id = $id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->body = $user->body;

    }

    public function cancel()
    {
        $this->updateMode = false;
        $this->resetInputFields();


    }

    public function update()
    {
        $this->validate([
            'name' => 'required',
            'email' => 'required|email',
            'body' => 'required',
        ]);

        if ($this->contact_id) {
            $user = Contact::find($this->contact_id);
            $user->update([
                'name' => $this->name,
                'email' => $this->email,
                'body' => $this->body,
            ]);
            $this->updateMode = false;
            session()->flash('message', 'Users Updated Successfully.');
            $this->resetInputFields();
        }
    }

    public function delete($id)
    {
        if($id){
            Contact::where('id',$id)->delete();
            session()->flash('message', 'Users Deleted Successfully.');
        }
    }
}
