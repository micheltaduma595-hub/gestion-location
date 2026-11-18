<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\View\View;

class MemberController extends Controller
{
    public function index(): View
    {
        abort_unless(
            in_array(auth()->user()->role, [User::ROLE_ADMIN, User::ROLE_MANAGER], true),
            403
        );

        $members = User::query()->orderBy('name')->get();

        return view('members.index', compact('members'));
    }
}
