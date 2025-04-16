<?php

namespace App\Http\Controllers;

use App\Mail\ContactMail;
use App\Mail\ServiceContactMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
class ContactController extends Controller
{
    public function index()
    {
        return view('pages.contactUs');
    }

    public function serviceContact(Request $request)
    {
        // Validate the form fields
        $validator = Validator::make($request->all(), [
            'form_name' => 'required|string|max:255',
            'form_email' => 'required|email',
            'form_phone' => 'required',
            'address' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        // Get the validated data
        $data = $request->all();

        // Send the email
        Mail::to(env('MAIL_USERNAME'))->send(new ServiceContactMail($data));

        return response()->json([
            'success' => true,
            'message' => 'Your message has been successfully sent!',
        ]);
    }


    public function contact(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'form_name' => 'required|string|max:255',
            'form_email' => 'required|email',
            'form_phone' => 'nullable|string',
            'form_subject' => 'nullable|string|max:255',
            'form_message' => 'required|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        // Send the email
        $data = $request->all(); // Get the form data

        Mail::to(env('MAIL_USERNAME'))->send(new ContactMail($data)); // Replace with the recipient's email

        return response()->json([
            'success' => true,
            'message' => 'Your message has been successfully sent!',
        ]);
    }
}
