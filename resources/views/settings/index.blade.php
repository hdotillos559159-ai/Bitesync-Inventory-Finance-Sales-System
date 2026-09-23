@extends('layouts.app')

@section('title', 'System Settings - BiteSync')
@section('page-title', 'System settings')
@section('tagline', 'Manage your BiteSync workspace and account preferences.')

@section('content')
  <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data" class="settings-grid">
    @csrf
    @method('PUT')

    <section class="panel settings-card">
      <div class="settings-heading">
        @if ($user->profile_photo_path)
          <img class="settings-avatar" src="{{ asset('storage/' . $user->profile_photo_path) }}" alt="Profile photo">
        @else
          <div class="settings-avatar">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
        @endif
        <div>
          <h2>Profile information</h2>
          <p>Update the details shown in your BiteSync account.</p>
        </div>
      </div>
      <div class="settings-fields">
        <div class="form-group">
          <label for="name">Name</label>
          <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required>
          @error('name') <div class="form-error">{{ $message }}</div> @enderror
        </div>
        <div class="form-group">
          <label for="email">Email</label>
          <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required>
          @error('email') <div class="form-error">{{ $message }}</div> @enderror
        </div>
      </div>
      <div class="form-group profile-upload">
        <label for="profile_photo">Profile photo</label>
        <input id="profile_photo" name="profile_photo" type="file" accept="image/*">
        @error('profile_photo') <div class="form-error">{{ $message }}</div> @enderror
      </div>
    </section>

    <section class="panel settings-card">
      <div class="settings-heading">
        <div class="settings-icon">✦</div>
        <div>
          <h2>Appearance</h2>
          <p>Choose how BiteSync looks on this account.</p>
        </div>
      </div>
      <div class="form-group">
        <label for="theme">Theme</label>
        <select id="theme" name="theme" required>
          <option value="light" {{ old('theme', $user->theme ?? 'light') === 'light' ? 'selected' : '' }}>Light mode</option>
          <option value="dark" {{ old('theme', $user->theme ?? 'light') === 'dark' ? 'selected' : '' }}>Dark mode</option>
        </select>
        @error('theme') <div class="form-error">{{ $message }}</div> @enderror
      </div>
    </section>

    <section class="panel settings-card">
      <div class="settings-heading">
        <div class="settings-icon">⌁</div>
        <div>
          <h2>Change password</h2>
          <p>Leave these fields blank to keep your current password.</p>
        </div>
      </div>
      <div class="settings-fields">
        <div class="form-group">
          <label for="password">New password</label>
          <input id="password" name="password" type="password" autocomplete="new-password">
          @error('password') <div class="form-error">{{ $message }}</div> @enderror
        </div>
        <div class="form-group">
          <label for="password_confirmation">Confirm password</label>
          <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password">
        </div>
      </div>
    </section>

    <div class="settings-actions settings-submit"><button class="btn" type="submit">Save changes</button></div>
  </form>
@endsection
