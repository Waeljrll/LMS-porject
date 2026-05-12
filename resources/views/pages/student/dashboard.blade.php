@extends('layouts.app')

@section('front-content')

<div class="pagetitle">
    <h1>Student Dashboard</h1>
</div>

<section class="section dashboard">

    <div class="row">

        <!-- Progress -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <h5>My Courses</h5>
                    <h2>5</h2>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <h5>Completed</h5>
                    <h2>2</h2>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <h5>In Progress</h5>
                    <h2>3</h2>
                </div>
            </div>
        </div>

        <!-- Courses List -->
        <div class="col-12 mt-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">My Courses</h5>

                    <ul>
                        <li>Laravel for Beginners - 60%</li>
                        <li>OOP PHP - 30%</li>
                        <li>Database - 80%</li>
                    </ul>

                </div>
            </div>
        </div>

    </div>

</section>

@endsection
