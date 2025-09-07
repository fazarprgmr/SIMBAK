@extends('layouts.app')
@section('title', 'Edit Profil')

@section('content')
    <div class="container">
        <h2 class="mb-4">Edit Profil</h2>

        {{-- Notifikasi Profil --}}
        @if (session('status') === 'profile-updated')
            <div class="sufee-alert alert with-close alert-success alert-dismissible fade show">
                <span class="badge badge-pill badge-success">Success</span>
                Anda Berhasil Mengubah Profil.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        {{-- Form Update Profil --}}
        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="mb-5">
            @csrf
            @method('PATCH')

            <div class="mb-3">
                <label class="form-label">Nama</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}">
                @error('name')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}">
                @error('email')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Foto Profil</label>
                <input type="file" name="profile_photo" class="form-control">
                @if ($user->profile_photo)
                    <div class="mt-2">
                        <img src="{{ asset('storage/' . $user->profile_photo) }}" alt="Foto Profil" width="100"
                            class="rounded">
                    </div>
                @endif
                @error('profile_photo')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror
            </div>

            <button class="btn btn-primary">Simpan Profil</button>
        </form>

        <hr class="my-5">

        {{-- Form Update Password --}}
        <section>
            <header>
                <h2 class="text-lg font-medium text-gray-900">
                    {{ __('Update Password') }}
                </h2>
                <p class="mt-1 text-sm text-gray-600">
                    {{ __('Ensure your account is using a long, random password to stay secure.') }}
                </p>
            </header>

            @if (session('status') === 'password-updated')
                <div class="sufee-alert alert with-close alert-success alert-dismissible fade show">
                    <span class="badge badge-pill badge-success">Success</span>
                    Anda Berhasil Mengubah Password.
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
                @csrf
                @method('put')

                <div class="mb-3">
                    <label for="update_password_current_password" class="form-label">{{ __('Current Password') }}</label>
                    <input id="update_password_current_password" name="current_password" type="password"
                        class="form-control" autocomplete="current-password">
                    @error('current_password')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="update_password_password" class="form-label">{{ __('New Password') }}</label>
                    <input id="update_password_password" name="password" type="password" class="form-control"
                        autocomplete="new-password">
                    @error('password')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="update_password_password_confirmation"
                        class="form-label">{{ __('Confirm Password') }}</label>
                    <input id="update_password_password_confirmation" name="password_confirmation" type="password"
                        class="form-control" autocomplete="new-password">
                    @error('password_confirmation')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>

                <button class="btn btn-primary">Simpan Password</button>
            </form>
        </section>
    </div>
@endsection
