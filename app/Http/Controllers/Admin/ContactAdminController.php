<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactAdminController extends Controller
{
    public function index(Request $request)
    {
        if ($request->has('open')) {
            Contact::where('id', $request->open)
                ->update(['is_read' => 1]);
        }

        $contacts = Contact::orderBy('is_replied')
            ->orderByDesc('created_at')
            ->get();

        return view('admin.pages.contact', compact('contacts'));
    }

    public function replyContact(Request $request)
    {
        $request->validate([
            'contact_id' => 'required|exists:contacts,id',
            'message' => 'required|string',
        ]);

        Contact::where('id', $request->contact_id)
            ->update(['is_replied' => 1]);

        return response()->json([
            'status' => true,
            'message' => 'Phản hồi thành công!'
        ]);
    }
}
