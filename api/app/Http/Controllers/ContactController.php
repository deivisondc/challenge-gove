<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    // display all contacts. return json
    public function index()
    {
        $contacts = Contact::all();
        return response()->json($contacts);
    }

    // store a newly created resource in storage
    public function store(Request $request)
    {
        $validatedFields = $request->validate([
            'name' => 'required|string',
            'email' => 'required|string'
        ]);

        $contact = Contact::create($validatedFields);

        return response()->json($contact);
    }

    public function show(Contact $contact)
    {
        return response()->json($contact);
    }

    public function update(Request $request, Contact $contact)
    {
        $validatedFields = $request->validate([
            'name'=> 'required|string',
            'email'=> 'required|string'
        ]);

        $contact->update($validatedFields);

        return response()->json($contact, 200);
    }

    public function destroy(Contact $contact)
    {
        $contact->delete();

        return response()->json(null, 204);
    }
}
