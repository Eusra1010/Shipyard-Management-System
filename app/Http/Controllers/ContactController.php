<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ContactController extends Controller
{
    public function send(Request $request)
    {
        $data = $request->validate([
            'name'    => ['required', 'string', 'max:255'],
            'email'   => ['required', 'email', 'max:255'],
            'subject' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:3000'],
        ]);

        $newId = DB::selectOne("SELECT NVL(MAX(id), 0) + 1 AS next_id FROM contact_messages")->next_id;

        DB::insert(
            "INSERT INTO contact_messages (id, name, email, subject, message, is_read, created_at)
             VALUES (?, ?, ?, ?, ?, 0, SYSDATE)",
            [$newId, $data['name'], $data['email'], $data['subject'], $data['message']]
        );

        return redirect()->route('home')->with('contact_success', true);
    }
}
