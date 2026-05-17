@extends('layouts.app')

@section('front-content')
    <div class="pagetitle">
        <h1>Profile</h1>
        <nav>
            <ol class="breadcrumb">
                @php
                    $homeRoute = match (auth()->user()->role) {
                        'admin' => route('admin.dashboard'),
                        'instructor' => route('instructor.dashboard'),
                        default => route('student.dashboard'),
                    };
                @endphp
                <li class="breadcrumb-item"><a href="{{ $homeRoute }}">Home</a></li>
                <li class="breadcrumb-item">Users</li>
                <li class="breadcrumb-item active">Profile Edit</li>
            </ol>
        </nav>
    </div>

    <section class="section profile">
        <div class="row">
            <div class="col-xl-8">

                <!-- Profile Update Card -->
                <div class="card">
                    <div class="card-body pt-3">
                        <h5 class="card-title">Update Profile Information</h5>

                        <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                            @csrf
                            @method('patch')

                            <div class="row mb-3 align-items-center">
                                <label class="col-md-4 col-lg-3 col-form-label">Profile Image</label>
                                <div class="col-md-8 col-lg-9">
                                    <div class="d-flex align-items-center gap-3">
                                        <img src="{{ $user->imageUrl() }}" alt="Profile"
                                            class="rounded-circle img-thumbnail"
                                            style="width: 120px; height: 120px; object-fit: cover;">

                                        <div class="flex-grow-1">
                                            <input type="file" name="profile_picture" class="form-control">
                                            @error('profile_picture')
                                                <div class="text-danger small mt-1">{{ $message }}</div>
                                            @enderror
                                            <small class="text-muted">JPG or PNG, max 2MB</small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="name" class="col-md-4 col-lg-3 col-form-label">Full Name</label>
                                <div class="col-md-8 col-lg-9">
                                    <input name="name" type="text" class="form-control" id="name"
                                        value="{{ old('name', $user->name) }}" required>
                                    @error('name')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="email" class="col-md-4 col-lg-3 col-form-label">Email</label>
                                <div class="col-md-8 col-lg-9">
                                    <input name="email" type="email" class="form-control" id="email"
                                        value="{{ old('email', $user->email) }}" required>
                                    @error('email')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="phone" class="col-md-4 col-lg-3 col-form-label">Phone</label>
                                <div class="col-md-8 col-lg-9">
                                    <input name="phone" type="text" class="form-control" id="phone"
                                        value="{{ old('phone', $user->phone) }}">
                                    @error('phone')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="about" class="col-md-4 col-lg-3 col-form-label">About (Bio)</label>
                                <div class="col-md-8 col-lg-9">
                                    <textarea name="bio" class="form-control" id="about" style="height: 100px" maxlength="500">{{ old('bio', $user->bio) }}</textarea>
                                    <small class="text-muted">Maximum 500 characters</small>
                                    @error('bio')
                                        <div class="text-danger small">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="text-center mt-4">
                                <button type="submit" class="btn btn-primary px-4">
                                    <i class="bi bi-check-circle me-1"></i>Save Changes
                                </button>
                            </div>
                        </form>

                    </div>
                </div>

                <!-- Change Password Card -->
                <div class="card mt-4">
                    <div class="card-body pt-3">
                        <h5 class="card-title">
                            <i class="bi bi-shield-lock text-warning me-2"></i>Update Password
                        </h5>
                        <p class="text-muted small">Ensure your account is using a long, random password to stay secure.</p>

                        <form method="post" action="{{ route('password.update') }}" class="mt-4">
                            @csrf
                            @method('put')

                            <div class="row mb-3">
                                <label for="update_password_current_password" class="col-md-4 col-lg-3 col-form-label">Current Password</label>
                                <div class="col-md-8 col-lg-9">
                                    <input id="update_password_current_password" name="current_password" type="password" class="form-control" autocomplete="current-password" required>
                                    @error('current_password', 'updatePassword')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="update_password_password" class="col-md-4 col-lg-3 col-form-label">New Password</label>
                                <div class="col-md-8 col-lg-9">
                                    <input id="update_password_password" name="password" type="password" class="form-control" autocomplete="new-password" required>
                                    @error('password', 'updatePassword')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="update_password_password_confirmation" class="col-md-4 col-lg-3 col-form-label">Confirm Password</label>
                                <div class="col-md-8 col-lg-9">
                                    <input id="update_password_password_confirmation" name="password_confirmation" type="password" class="form-control" autocomplete="new-password" required>
                                    @error('password_confirmation', 'updatePassword')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-8 col-lg-9 offset-md-4 offset-lg-3">
                                    <button type="submit" class="btn btn-warning">
                                        <i class="bi bi-key me-1"></i>Update Password
                                    </button>

                                    @if (session('status') === 'password-updated')
                                        <span class="ms-3 text-success">
                                            <i class="bi bi-check-circle"></i> Password updated successfully!
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Delete Account Card -->
                <div class="card mt-4 border-danger">
                    <div class="card-body pt-3">
                        <h5 class="card-title text-danger">
                            <i class="bi bi-exclamation-triangle me-2"></i>Delete Account
                        </h5>
                        <p class="text-muted small">Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.</p>

                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
                            <i class="bi bi-trash me-1"></i>Delete Account
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- Delete Account Modal -->
    <div class="modal fade" id="deleteAccountModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="post" action="{{ route('profile.destroy') }}">
                    @csrf
                    @method('delete')
                    <div class="modal-header">
                        <h5 class="modal-title text-danger">Are you sure?</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p>Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.</p>
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" placeholder="Enter your password" required>
                            @error('password', 'userDeletion')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">Delete Account</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
