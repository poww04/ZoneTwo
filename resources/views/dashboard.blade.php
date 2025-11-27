@extends('layouts.app')

@section('content')
<div class="p-6 flex flex-col items-start space-y-6">
    <h1 class="text-3xl font-bold">Welcome to ZoneTwo!</h1>
    <p class="text-gray-600 mt-2">Welcome back, {{ Auth::user()->name }}!</p>
    <p class="text-gray-600">You are now logged in.</p>

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit"
                class="bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded-lg transition">
            Logout
        </button>
    </form>
</div>
@endsection
