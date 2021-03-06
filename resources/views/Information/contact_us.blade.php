@extends('Layouts.main')
@section('meta')
{{--
<div class="col-md-6"> {!! htmlFormSnippet() !!} </div>
--}}
@endsection
@section('body')
    <div class="container">

        <div class="row mt-5 mb-5">

            <div class="col-8 offset-2 mt-5">

                <div class="card">

                    <div class="card-header bg-prim">

                        <h3 class="text-white">Contact us</h3>

                    </div>

                    <div class="card-body">



                        @if(Session::has('success'))

                        <div class="alert alert-success">

                            {{ Session::get('success') }}

                            @php

                                Session::forget('success');

                            @endphp

                        </div>

                        @endif



                        <form method="POST" action="{{ route('store.contact.form') }}">

                            @csrf

                            <div class="row">

                                <div class="col-md-6">

                                    <div class="form-group">

                                        <strong>Name:</strong>

                                        <input type="text" name="name" class="form-control" placeholder="Name" value="{{ old('name') }}">

                                        @if ($errors->has('name'))

                                            <span class="text-danger">{{ $errors->first('name') }}</span>

                                        @endif

                                    </div>

                                </div>

                                <div class="col-md-6">

                                    <div class="form-group">

                                        <strong>Email:</strong>

                                        <input type="text" name="email" class="form-control" placeholder="Email" value="{{ old('email') }}">

                                        @if ($errors->has('email'))

                                            <span class="text-danger">{{ $errors->first('email') }}</span>

                                        @endif

                                    </div>

                                </div>

                            </div>

                            <div class="row">

                                <div class="col-md-6">

                                    <div class="form-group">

                                        <strong>Phone:</strong>

                                        <input type="text" name="phone" class="form-control" placeholder="05xxxxxxxx" value="{{ old('phone') }}">

                                        @if ($errors->has('phone'))

                                            <span class="text-danger">{{ $errors->first('phone') }}</span>

                                        @endif

                                    </div>

                                </div>

                                <div class="col-md-6">

                                    <div class="form-group">

                                        <strong>Subject:</strong>

                                        <input type="text" name="subject" class="form-control" placeholder="Subject" value="{{ old('subject') }}">

                                        @if ($errors->has('subject'))

                                            <span class="text-danger">{{ $errors->first('subject') }}</span>

                                        @endif

                                    </div>

                                </div>

                            </div>

                            <div class="row">

                                <div class="col-md-12">

                                    <div class="form-group">

                                        <strong>Message:</strong>

                                        <textarea name="message" rows="3" class="form-control">{{ old('message') }}</textarea>

                                        @if ($errors->has('message'))

                                            <span class="text-danger">{{ $errors->first('message') }}</span>

                                        @endif

                                    </div>

                                </div>

                            </div>

                            {!! ReCaptcha::htmlScriptTagJsApi() !!}
                            <div class="col-md-6"> {!! htmlFormSnippet() !!} </div>
                            @if ($errors->has('g-recaptcha-response'))
                            <span class="text-danger">
                            <strong>{{ $errors->first('g-recaptcha-response') }}</strong>
                            </span>
                            @endif
                            <div class="form-group text-center">

                                <button class="btn btn-success btn-submit">Send</button>

                            </div>

                        </form>

                    </div>

                </div>

            </div>

        </div>

    </div>

@endsection
