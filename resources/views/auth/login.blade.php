@extends('layouts.auth')

@section('contents')
    <section class="login-section py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-5 col-12 tt-login-img"
                    data-background="{{ staticAsset('frontend/default/assets/img/banner/login-banner.jpg') }}"></div>
                <div class="col-lg-5 col-12 bg-white d-flex p-0 tt-login-col shadow">
                    <form class="tt-login-form-wrap p-3 p-md-6 p-lg-6 py-7 w-100" action="{{ route('login') }}" method="POST">
                        @csrf
                        <div class="mb-7">
                            <a href="{{ route('home') }}">
                                <img src="{{ uploadedAsset(getSetting('navbar_logo')) }}" alt="logo">
                            </a>
                        </div>
                        <h2 class="mb-4 h3">{{ localize('Hey there!') }}
                            <br>{{ localize('Welcome back to Grostore.') }}
                        </h2>

                        <div class="row g-3">
                            <div class="col-sm-12">
                                <div class="input-field">
                                    <label class="fw-bold text-dark fs-sm mb-1">{{ localize('Email') }}</label>
                                    <input type="email" id="email" name="email"
                                        placeholder="{{ localize('Enter your email') }}" class="theme-input"
                                        value="{{ old('email') }}" required>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="input-field check-password">
                                    <label class="fw-bold text-dark fs-sm mb-1">{{ localize('Password') }}</label>
                                    <div class="check-password">
                                        <input type="password" name="password" id="password"
                                            placeholder="{{ localize('Password') }}" class="theme-input" required>
                                        <span class="eye eye-icon"><i class="fa-solid fa-eye"></i></span>
                                        <span class="eye eye-slash"><i class="fa-solid fa-eye-slash"></i></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex align-items-center justify-content-between mt-4">
                            <div class="checkbox d-inline-flex align-items-center gap-2">
                                <div class="theme-checkbox flex-shrink-0">
                                    <input type="checkbox" id="save-password">
                                    <span class="checkbox-field"><i class="fa-solid fa-check"></i></span>
                                </div>
                                <label for="save-password" class="fs-sm"> {{ localize('Remember me') }}</label>
                            </div>
                            <a href="{{ route('password.request') }}" class="fs-sm">{{ localize('Forgot Password') }}</a>
                        </div>

                        @if (env('DEMO_MODE') == 'On')
                            <div class="row mt-5">
                                <div class="col-12">
                                    <label class="fw-bold">Admin Access</label>
                                    <div
                                        class="d-flex flex-wrap align-items-center justify-content-between border-bottom pb-3">
                                        <small>admin@themetags.com</small>
                                        <small>123456</small>
                                        <button class="btn btn-sm btn-secondary py-0 px-2" type="button"
                                            onclick="copyAdmin()">Copy</button>
                                    </div>
                                </div>

                                <div class="col-12 mt-3">
                                    <label class="fw-bold">Customer Access</label>
                                    <div class="d-flex flex-wrap align-items-center justify-content-between">
                                        <small>customer@themetags.com</small>
                                        <small>123456</small>

                                        <button class="btn btn-sm btn-secondary py-0 px-2" type="button"
                                            onclick="copyCustomer()">Copy</button>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="row g-4 mt-4">
                            <div class="col-sm-6">
                                <button type="submit" class="btn btn-primary w-100">{{ localize('Sign In') }}</button>
                            </div>

                            <!--social login-->
                            @include('frontend.default.inc.social')
                            <!--social login-->

                        </div>
                        <p class="mb-0 fs-xs mt-4">{{ localize("Don't have an Account?") }} <a
                                href="{{ route('register') }}">{{ localize('Sign Up') }}</a></p>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection


@section('scripts')
    <script>
        "use strict";

        // copyAdmin
        function copyAdmin() {
            $('#email').val('admin@themetags.com');
            $('#password').val('123456');
        }

        // copyCustomer
        function copyCustomer() {
            $('#email').val('customer@themetags.com');
            $('#password').val('123456');
        }
    </script>
@endsection
