<?php

namespace App\Livewire\Admin\Users;

use App\Models\User;
use Livewire\Component;

class EditUser extends Component
{
    public User $user;

    public $fname;
    public $lname;
    public $username;
    public $email;
    public $phone;
    public $kyc_status;

    public function mount(User $user)
    {
        $this->user = $user;
        $this->fname = $user->fname;
        $this->lname = $user->lname;
        $this->username = $user->username;
        $this->email = $user->email;
        $this->phone = $user->phone;
        $this->kyc_status = $user->kyc_status;
    }

    public function updateUser()
    {
        $this->validate([
            'fname' => 'required|string|max:255',
            'lname' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $this->user->id,
            'email' => 'required|email|max:255|unique:users,email,' . $this->user->id,
            'phone' => 'nullable|string|max:20',
            'kyc_status' => 'required|in:active,suspended,blocked,verified',
        ]);

        $this->user->update([
            'fname' => $this->fname,
            'lname' => $this->lname,
            'username' => $this->username,
            'email' => $this->email,
            'phone' => $this->phone,
            'kyc_status' => $this->kyc_status,
        ]);

        session()->flash('message', 'User updated successfully.');

        return $this->redirect(route('admin.users'), navigate: true);
    }

    public function render()
    {
        return view('livewire.admin.users.edit-user')->layout('layouts.admin.app', [
            'title' => 'Edit User',
            'description' => 'Edit user information',
        ]);
    }
}
