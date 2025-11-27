@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100 p-6">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-3xl font-bold text-gray-800">Admin Settings</h1>
                <a href="{{ route('admin.dashboard') }}" 
                   class="text-blue-600 hover:text-blue-800 font-semibold">
                     Back to Dashboard
                </a>
            </div>

            <div class="space-y-6">
                <div class="border-b border-gray-200 pb-4">
                    <h2 class="text-xl font-semibold text-gray-700 mb-2">Account Information</h2>
                    <div class="space-y-2">
                        <p><span class="font-medium">Name:</span> {{ Auth::user()->name }}</p>
                        <p><span class="font-medium">Email:</span> {{ Auth::user()->email }}</p>
                        <p><span class="font-medium">Role:</span> {{ Auth::user()->role ?? 'N/A' }}</p>
                        <p><span class="font-medium">Admin Status:</span> 
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                Active Admin
                            </span>
                        </p>
                    </div>
                </div>

                <div class="border-b border-gray-200 pb-4">
                    <h2 class="text-xl font-semibold text-gray-700 mb-2">System Information</h2>
                    <p class="text-gray-600">This is the admin settings page. You can add more settings here as needed.</p>
                </div>

                <div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                                class="bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded-lg transition">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

