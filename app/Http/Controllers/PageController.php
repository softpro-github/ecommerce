<?php

namespace App\Http\Controllers;

use App\Mail\ContactFormMail;
use App\Models\Faq;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class PageController extends Controller
{
    public function faqs()
    {
        $faqs = Faq::where('enabled', true)->orderBy('sort_order')->get();

        return view('pages.faqs', compact('faqs'));
    }

    public function contact()
    {
        return view('pages.contact');
    }

    public function submitContact(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:50',
            'message' => 'required|string|max:5000',
        ]);

        $recipient = Setting::get('contact_email') ?: config('mail.from.address');

        try {
            Mail::to($recipient)->send(new ContactFormMail(
                senderName: $validated['name'],
                senderEmail: $validated['email'],
                senderPhone: $validated['phone'],
                messageBody: $validated['message'],
            ));
        } catch (\Throwable $e) {
            report($e);

            return back()->withErrors(['message' => 'Something went wrong sending your message. Please try again or contact us directly.'])->withInput();
        }

        return back()->with('status', "Thanks for reaching out! We'll get back to you soon.");
    }
}
