@extends('layouts.app')

@section('front-content')

<div class="pagetitle">
    <h1>Admin Dashboard</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">Home</li>
            <li class="breadcrumb-item active">Dashboard</li>
        </ol>
    </nav>
</div>

<section class="section dashboard">
    <div class="row">

        <!-- Stats -->
        <div class="col-lg-3 col-md-6">
            <div class="card info-card">
                <div class="card-body">
                    <h5 class="card-title">Students</h5>
                    <h3>1,240</h3>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card info-card">
                <div class="card-body">
                    <h5 class="card-title">Courses</h5>
                    <h3>48</h3>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card info-card">
                <div class="card-body">
                    <h5 class="card-title">Instructors</h5>
                    <h3>12</h3>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card info-card">
                <div class="card-body">
                    <h5 class="card-title">Enrollments</h5>
                    <h3>3,560</h3>
                </div>
            </div>
        </div>

        <!-- Recent Courses -->
        <div class="col-12 mt-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Recent Courses</h5>
                    <ul>
                        <li>Laravel Basics</li>
                        <li>Advanced PHP</li>
                        <li>Database Design</li>
                    </ul>
                </div>
            </div>
        </div>

    </div>
</section>

@endsection
