@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto py-12 px-4">
        <h2 class="text-lg font-medium text-black">Profile Information</h2>
        <p class="mt-1 text-sm text-black">
            Update your account's profile information and email address.
        </p>

        @include('profile.partials.update-profile-information-form')
    </div>
@endsection
