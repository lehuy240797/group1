<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Feedback; // We'll make this next

class ContactController extends Controller
{
    public function submit(Request $request)
    {
        // Validate form input
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email',
            'message' => 'required|string',
        ]);

        // Save to database
        Feedback::create([
            'name' => $request->name,
            'email' => $request->email,
            'message' => $request->message,
        ]);

        // Optional: return success message
        return redirect()->back()->with('success', 'Thank you for your feedback!');
    }
}
