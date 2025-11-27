<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Models\Member;

class MemberController extends Controller
{
    /**
     * Display the public profile of a member.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\View\View
     */
    public function show($id)
    {
        $member = Member::with(['person', 'card'])->findOrFail($id);

        return view('guest.members.show', compact('member'));
    }
}
