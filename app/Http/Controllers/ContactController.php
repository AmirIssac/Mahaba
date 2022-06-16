<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function contactUs(){
        return view('Information.contact_us');
    }

    public function storeContactForm(Request $request)

    {

        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required|digits:10|numeric',
            'subject' => 'required',
            'message' => 'required',
            'g-recaptcha-response' => 'required|recaptcha',
        ]);

        $input = $request->all();

        Contact::create($input);

        //  Send mail to admin

        /*
        \Mail::send('contactMail', array(

            'name' => $input['name'],

            'email' => $input['email'],

            'phone' => $input['phone'],

            'subject' => $input['subject'],

            'message' => $input['message'],

        ), function($message) use ($request){

            $message->from($request->email);

            $message->to('admin@admin.com', 'Admin')->subject($request->get('subject'));

        });
        */



        return redirect()->back()->with(['success' => 'Contact Form Submit Successfully']);

    }
}
