@extends('layouts.app')

@section('content')
<div class="flex min-h-screen items-center justify-center bg-gray-100">
    <div class="bg-white rounded-xl shadow-lg flex flex-col md:flex-row-reverse overflow-hidden w-full max-w-6xl min-h-[600px] md:min-h-[700px]">

        <div class="md:w-1/2 bg-gradient-to-br from-purple-600 to-blue-600 flex items-center justify-center p-12">
            <div class="text-white text-center">
                <svg class="w-24 h-24 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                </svg>
                <h1 class="text-4xl font-bold mb-4">Admin Registration</h1>
                <p class="text-lg opacity-90">Create an administrator account to access the admin dashboard</p>
            </div>
        </div>

        <div class="md:w-1/2 p-12 flex flex-col justify-center">
            <h2 class="text-3xl font-bold text-gray-800 mb-4">Create Admin Account</h2>
            <p class="text-gray-600 mb-8">Fill in the details to register as an administrator</p>

            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6">
                    {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('register-admin.submit') }}">
                @csrf

                <div class="mb-6">
                    <input id="name" type="text" name="name" placeholder="Full Name" value="{{ old('name') }}" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring focus:ring-blue-200 focus:outline-none text-lg">
                </div>

                <div class="mb-6">
                    <input id="email" type="email" name="email" placeholder="Email" value="{{ old('email') }}" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring focus:ring-blue-200 focus:outline-none text-lg">
                </div>

                <div class="mb-6">
                    <input id="password" type="password" name="password" placeholder="Password" required minlength="6"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring focus:ring-blue-200 focus:outline-none text-lg">
                </div>

                <div class="mb-6">
                    <input id="password_confirmation" type="password" name="password_confirmation" placeholder="Confirm Password" required minlength="6"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring focus:ring-blue-200 focus:outline-none text-lg">
                </div>

                <button type="submit"
                        class="w-full bg-purple-600 hover:bg-purple-700 text-white font-semibold py-3 rounded-lg transition mb-4 text-lg">
                    Register as Admin
                </button>
            </form>

            <div class="text-center space-y-2">
                <p class="text-sm text-gray-600">
                    Already have an account?
                    <a href="{{ route('login') }}" class="text-blue-600 hover:underline font-medium">Login</a>
                </p>
                <p class="text-sm text-gray-600">
                    Regular user?
                    <a href="{{ route('register') }}" class="text-blue-600 hover:underline font-medium">Register here</a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection

