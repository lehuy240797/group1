<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Feedback;
use Illuminate\Http\Request;

class AdminFeedbackController extends Controller
{
    public function index()
    {
        $feedbacks = Feedback::all();
        return view('management.tours.feedback_man.index', compact('feedbacks'));
    }

    public function showReplyForm($id)
    {
        $feedback = Feedback::findOrFail($id);
        return view('management.tours.feedback_man.reply', compact('feedback'));
    }

    public function submitReply(Request $request, $id)
    {
        $request->validate([
            'admin_reply' => 'required|string',
        ]);

        $feedback = Feedback::findOrFail($id);
        $feedback->update([
            'admin_reply' => $request->input('admin_reply'),
            'replied_at' => now(),
        ]);

        return redirect()->route('admin.feedbacks.index')->with('success', 'Phản hồi đã được gửi thành công!');
    }
}