@extends('layouts.app')

@section('content')
<div class="flex min-h-screen items-center justify-center bg-gray-100">
    <div class="bg-white rounded-xl shadow-lg flex flex-col md:flex-row-reverse overflow-hidden w-full max-w-6xl min-h-[600px] md:min-h-[700px]">

        <div class="md:w-1/2">
            <img src="{{ asset('images/register.jpg') }}" alt="Welcome"
                 class="w-full h-full object-cover">
        </div>

        <div class="md:w-1/2 p-12 flex flex-col justify-center">
            <h2 class="text-3xl font-bold text-gray-800 mb-4">Create an Account</h2>
            <p class="text-gray-600 mb-8">Please fill in the details to register</p>

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="mb-6">
                    <input id="name" type="text" name="name" placeholder="Full Name" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring focus:ring-blue-200 focus:outline-none text-lg">
                </div>

                <div class="mb-6">
                    <input id="email" type="email" name="email" placeholder="Email" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring focus:ring-blue-200 focus:outline-none text-lg">
                </div>

                <div class="mb-6">
                    <input id="password" type="password" name="password" placeholder="Password" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring focus:ring-blue-200 focus:outline-none text-lg">
                </div>

                <div class="mb-6">
                    <input id="password_confirmation" type="password" name="password_confirmation" placeholder="Confirm Password" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring focus:ring-blue-200 focus:outline-none text-lg">
                </div>

                <button type="submit"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-lg transition mb-4 text-lg">
                    Register
                </button>
            </form>

            <p class="text-center text-sm text-gray-600 mt-8">
                Already have an account?
                <a href="{{ route('login') }}" class="text-blue-600 hover:underline font-medium">Login</a>
            </p>
        </div>
    </div>
</div>
@endsection
