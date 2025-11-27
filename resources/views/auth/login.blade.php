<?php $page = 'login-cover'; ?>
<x-layouts.app>
    <!-- Start Content -->
        <div class="container-fuild position-relative z-1">
            <div class="w-100 overflow-hidden position-relative flex-wrap d-block vh-100 bg-white">
                <!-- start row-->
                <div class="row">
                    <div class="col-lg-6 p-0">
                        <div class="login-backgrounds login-covers bg-primary d-lg-flex align-items-center justify-content-center d-none flex-wrap p-4 position-relative h-100 z-0">
                            <div class="authentication-card w-100">
                                <div class="authen-overlay-item w-100">
                                    <div class="authen-head text-center">
                                        <h1 class="text-white fs-32 fw-bold mb-2">{{ __('Seamless healthcare access') }} <br> {{ __('with smart, modern clinic') }}</h1>
                                        <p class="text-light fw-normal text-light">{{ __('Experience efficient, secure, and user-friendly healthcare management designed for modern clinics and growing practices.') }}</p>
                                    </div>
                                    <div class="mt-4 mx-auto authen-overlay-img">
                                        <img src="{{URL::asset('build/img/auth/cover-imgs-1.png')}}" alt="Img">
                                    </div>
                                </div>
                            </div>
                            <img src="{{URL::asset('build/img/auth/cover-imgs-2.png')}}" alt="cover-imgs-2" class="img-fluid cover-img">
                        </div>
                    </div> <!-- end row-->
    
                    <div class="col-lg-6 col-md-12 col-sm-12">
                        <div class="row justify-content-center align-items-center overflow-auto flex-wrap vh-100">
                            <div class="col-md-8 mx-auto">
                                <form method="POST" action="{{ route('login') }}" novalidate class="d-flex justify-content-center align-items-center">
                                    @csrf
                                    <div class="d-flex flex-column justify-content-lg-center p-4 p-lg-0 pb-0 flex-fill">
                                        <div class=" mx-auto mb-4 text-center">
                                            <img src="{{URL::asset('build/img/logo.svg')}}" class="img-fluid" alt="Logo">
                                        </div>
                                        <div class="card border-1 p-lg-3 shadow-md rounded-3 m-0">
                                            <div class="card-body">
                                                <div class="text-center mb-3">
                                                    <h5 class="mb-1 fs-20 fw-bold">{{ __('Sign In') }}</h5>
                                                    <p class="mb-0">{{ __('Please enter below details to access the dashboard') }}</p>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">{{ __('Email Address') }}</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text border-end-0 bg-white">
                                                            <i class="ti ti-mail fs-14 text-dark"></i>
                                                        </span>
                                                        <input type="text"
                                                               value="{{ old('email') }}"
                                                               class="form-control border-start-0 ps-0 @error('email') is-invalid @enderror"
                                                               name="email"
                                                               id="email"
                                                               placeholder="{{ __('Enter Email Address') }}"
                                                               required
                                                               autofocus
                                                               aria-describedby="email-error">
                                                    </div>
                                                    @error('email')
                                                        <div class="invalid-feedback d-block" id="email-error" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </div>
                                                    @enderror
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">{{ __('Password') }}</label>
                                                    <div class="position-relative">
                                                        <div class="pass-group input-group position-relative border rounded">
                                                            <span class="input-group-text bg-white border-0">
                                                                <i class="ti ti-lock text-dark fs-14"></i>
                                                            </span>
                                                            <input type="password"
                                                                   class="pass-input form-control ps-0 border-0 @error('password') is-invalid @enderror"
                                                                   name="password"
                                                                   id="password"
                                                                   placeholder="****************"
                                                                   required
                                                                   aria-describedby="password-error">
                                                            <span class="input-group-text bg-white border-0 toggle-password">
                                                                <i class="ti toggle-password ti-eye-off text-dark fs-14"></i>
                                                            </span>
                                                        </div>
                                                    </div>
                                                    @error('password')
                                                        <div class="invalid-feedback d-block" id="password-error" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </div>
                                                    @enderror
                                                </div>
                                                <div class="d-flex align-items-center justify-content-between mb-3">
                                                    <div class="d-flex align-items-center">
                                                        <div class="form-check form-check-md mb-0">
                                                            <input class="form-check-input" id="remember_me" type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                                                            <label for="remember_me" class="form-check-label mt-0 text-dark">{{ __('Remember Me') }}</label>
                                                        </div>
                                                    </div>
                                                    <div class="text-end">
                                                        <a href="{{ route('password.request') }}" class="text-danger">{{ __('Forgot Password?') }}</a>
                                                    </div>
                                                </div>
                                                <div class="mb-2">
                                                    <button type="submit" class="btn bg-primary text-white w-100">{{ __('Login') }}</button>
                                                </div>
                                                <div class="login-or position-relative mb-3">
                                                    <span class="span-or">{{ __('OR') }}</span>
                                                </div>
                                                <div class="text-center">
                                                    <h6 class="fw-normal fs-14 text-dark mb-0">{{ __("Don't have an account yet?") }}
                                                        <a href="{{ route('register') }}" class="hover-a"> {{ __('Register') }}</a>
                                                    </h6>
                                                </div>
                                            </div><!-- end card body -->
                                        </div><!-- end card -->
                                    </div>
                                </form>
                                <p class="fs-14 text-dark text-center mt-4">Copyright &copy; {{ date('Y') }} - {{ config('app.name') }}</p>
                            </div> <!-- end row-->
                        </div>
    
                    </div>
                </div>
                <!-- end row-->
    
            </div>
        </div>
        <!-- End Content -->
    
        @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Toggle password visibility
                const togglePassword = document.querySelector('.toggle-password');
                const passwordInput = document.getElementById('password');
    
                if (togglePassword && passwordInput) {
                    togglePassword.addEventListener('click', function() {
                        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                        passwordInput.setAttribute('type', type);
    
                        // Toggle eye icon
                        const eyeIcon = this.querySelector('i');
                        if (eyeIcon) {
                            eyeIcon.classList.toggle('ti-eye-off');
                            eyeIcon.classList.toggle('ti-eye');
                        }
                    });
                }
            });
        </script>
        @endpush
</x-layouts.app>