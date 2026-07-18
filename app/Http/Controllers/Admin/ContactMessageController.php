<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class ContactMessageController extends Controller
{
    public function index()
    {
        $messages = DB::select("
            SELECT id, name, email, subject,
                   DBMS_LOB.SUBSTR(message, 300, 1) AS preview,
                   is_read,
                   TO_CHAR(created_at, 'DD Mon YYYY, HH24:MI') AS received_at
            FROM contact_messages
            ORDER BY created_at DESC
        ");

        $unread = collect($messages)->where('is_read', 0)->count();

        return view('admin.messages.index', compact('messages', 'unread'));
    }

    public function show($id)
    {
        $msg = DB::selectOne("
            SELECT id, name, email, subject,
                   DBMS_LOB.SUBSTR(message, 4000, 1) AS message,
                   is_read,
                   TO_CHAR(created_at, 'DD Mon YYYY, HH24:MI') AS received_at
            FROM contact_messages
            WHERE id = :id
        ", ['id' => $id]);

        if (!$msg) abort(404);

        if (!$msg->is_read) {
            DB::update("UPDATE contact_messages SET is_read = 1 WHERE id = :id", ['id' => $id]);
        }

        return view('admin.messages.show', compact('msg'));
    }

    public function destroy($id)
    {
        DB::delete("DELETE FROM contact_messages WHERE id = :id", ['id' => $id]);
        return redirect()->route('admin.messages.index')->with('success', 'Message deleted.');
    }
}
