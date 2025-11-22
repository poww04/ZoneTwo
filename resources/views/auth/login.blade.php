@extends('layouts.app')

@section('content')
<div class="flex min-h-screen items-center justify-center bg-gray-100">
    <div class="bg-white rounded-xl shadow-lg flex flex-col md:flex-row overflow-hidden w-full max-w-6xl min-h-[600px] md:min-h-[700px]">

        <div class="md:w-1/2">
            <img src="{{ asset('images/login.jpg') }}" alt="Welcome"
                 class="w-full h-full object-cover">
        </div>

        <div class="md:w-1/2 p-12 flex flex-col justify-center">
            <h2 class="text-3xl font-bold text-gray-800 mb-4">Login</h2>
            <p class="text-gray-600 mb-8">Please login to continue</p>

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="mb-6">
                    <input type="email" name="email" placeholder="Email"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring focus:ring-blue-200 focus:outline-none text-lg">
                </div>

                <div class="mb-6">
                    <input type="password" name="password" placeholder="Password"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring focus:ring-blue-200 focus:outline-none text-lg">
                </div>

                <button type="submit"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-lg transition mb-4 text-lg">
                    LOGIN
                </button>
            </form>

            <p class="text-center text-sm text-gray-600 mt-8">
                Don’t have an account?
                <a href="{{ route('register') }}" class="text-blue-600 hover:underline font-medium">Register</a>
            </p>
        </div>
    </div>
</div>
@endsection
