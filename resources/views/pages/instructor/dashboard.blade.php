@extends('layouts.app')

@section('front-content')

<div class="pagetitle">
    <h1>Instructor Dashboard</h1>
</div>

<section class="section dashboard">

    <div class="row">

        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <h5>My Courses</h5>
                    <h2>8</h2>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <h5>Total Students</h5>
                    <h2>320</h2>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <h5>Revenue</h5>
                    <h2>$2,450</h2>
                </div>
            </div>
        </div>

        <!-- Courses -->
        <div class="col-12 mt-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">My Courses</h5>
                    <ul>
                        <li>Advanced Laravel</li>
                        <li>PHP OOP Mastery</li>
                        <li>Database Design</li>
                    </ul>
                </div>
            </div>
        </div>

    </div>

</section>

@endsection
