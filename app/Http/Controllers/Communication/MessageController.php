<?php

namespace App\Http\Controllers\Communication;

use App\Http\Controllers\Controller;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function index(Request $request)
    {
        $query = Message::where('recipient_id', Auth::id())
            ->orWhere('sender_id', Auth::id())
            ->with(['sender', 'recipient']);

        // Apply filters
        switch ($request->filter) {
            case 'unread':
                $query->whereNull('read_at')
                    ->where('recipient_id', Auth::id());
                break;
            case 'sent':
                $query->where('sender_id', Auth::id());
                break;
            case 'archived':
                $query->whereNotNull('archived_at');
                break;
        }

        $messages = $query->latest()->paginate(10);

        return view('communication.messages.index', compact('messages'));
    }

    public function create()
    {
        $users = \App\Models\User::where('id', '!=', Auth::id())->get();
        return view('communication.messages.create', compact('users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'content' => 'required|string',
            'recipient_id' => 'required|exists:users,id',
        ]);

        $message = Message::create([
            'subject' => $validated['subject'],
            'content' => $validated['content'],
            'sender_id' => Auth::id(),
            'recipient_id' => $validated['recipient_id'],
        ]);

        return redirect()->route('communication.messages.show', $message)
            ->with('success', 'Message sent successfully.');
    }

    public function show(Message $message)
    {
        if ($message->recipient_id === Auth::id() && !$message->isRead()) {
            $message->update(['read_at' => now()]);
        }

        return view('communication.messages.show', compact('message'));
    }

    public function markAsRead(Message $message)
    {
        if ($message->recipient_id === Auth::id()) {
            $message->update(['read_at' => now()]);
            return back()->with('success', 'Message marked as read.');
        }

        return back()->with('error', 'Unauthorized action.');
    }

    public function archive(Message $message)
    {
        if ($message->recipient_id === Auth::id() || $message->sender_id === Auth::id()) {
            $message->update(['archived_at' => now()]);
            return back()->with('success', 'Message archived.');
        }

        return back()->with('error', 'Unauthorized action.');
    }

    public function destroy(Message $message)
    {
        if ($message->recipient_id === Auth::id() || $message->sender_id === Auth::id()) {
            $message->delete();
            return redirect()->route('communication.messages.index')
                ->with('success', 'Message deleted successfully.');
        }

        return back()->with('error', 'Unauthorized action.');
    }
} 