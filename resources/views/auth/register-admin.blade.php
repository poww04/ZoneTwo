@extends('layouts.app')

@section('content')
<div class="flex min-h-screen items-center justify-center bg-gray-100">
    <div class="bg-white rounded-xl shadow-lg flex flex-col md:flex-row-reverse overflow-hidden w-full max-w-6xl min-h-[600px] md:min-h-[700px]">

        <div class="md:w-1/2">
            <img src="{{ asset('images/register-ad.jpg') }}" alt="Admin Registration"
                 class="w-full h-full object-cover">
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
                        class="w-full bg-black hover:bg-red-600 text-white font-semibold py-3 rounded-lg transition mb-4 text-lg">
                    Register as Admin
                </button>
            </form>

            <div class="text-center space-y-2">
                <p class="text-sm text-gray-600">
                    Already have an account?
                    <a href="{{ route('login') }}" class="text-blue-600 hover:underline font-medium">Login</a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection

