@extends('layouts.app')

@section('front-content')
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-1">
                        <li class="breadcrumb-item"><a href="{{ route('instructor.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Course Analytics</li>
                    </ol>
                </nav>
                <h1 class="h3 mb-0 text-gray-800">تحليلات كورس: {{ $course->title }}</h1>
            </div>
            <a href="{{ route('instructor.dashboard') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> العودة للوحة التحكم
            </a>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-md-3">
                <div class="card border-0 shadow-sm border-start border-primary border-4 h-100 py-2">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">إجمالي الطلاب
                                    المسجلين</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalEnrollments }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-users fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card border-0 shadow-sm border-start border-success border-4 h-100 py-2">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">طلاب نشطين (آخر 7
                                    أيام)</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $activeStudentsCount }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-user-clock fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card border-0 shadow-sm border-start border-info border-4 h-100 py-2">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">معدل إكمال الكورس</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $completionRate }}%</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-award fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card border-0 shadow-sm border-start border-warning border-4 h-100 py-2">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">متوسط تقدم الطلاب
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $averageProgress }}%</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-chart-line fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <h5 class="card-title mb-0 text-dark font-weight-bold">قائمة الطلاب المشتركين ومستويات تقدمهم</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">الطالب</th>
                                <th>تاريخ الاشتراك</th>
                                <th>نسبة التقدم</th>
                                <th>آخر ظهور / تفاعل</th>
                                <th>الحالة</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($enrollments as $enrollment)
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center">
                                            <img src="{{ $enrollment->student->imageUrl() }}" alt="avatar"
                                                class="rounded-circle me-3"
                                                style="width: 40px; height: 40px; object-fit: cover;">

                                            <div>
                                                <h6 class="mb-0 fw-bold text-gray-800">{{ $enrollment->student->name }}</h6>
                                                <span class="text-muted small">{{ $enrollment->student->email }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $enrollment->enrolled_at ? \Carbon\Carbon::parse($enrollment->enrolled_at)->format('Y-m-d') : $enrollment->created_at->format('Y-m-d') }}
                                    </td>
                                    <td style="width: 25%;">
                                        <div class="d-flex align-items-center">
                                            <div class="progress flex-grow-1 me-2" style="height: 6px;">
                                                <div class="progress-bar bg-success" role="progressbar"
                                                    style="width: {{ $enrollment->progress_percentage }}%;"
                                                    aria-valuenow="{{ $enrollment->progress_percentage }}"
                                                    aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                            <span class="small fw-bold">{{ $enrollment->progress_percentage }}%</span>
                                        </div>
                                    </td>
                                    <td>{{ $enrollment->updated_at->diffForHumans() }}</td>
                                    <td>
                                        @if ($enrollment->status === 'completed')
                                            <span
                                                class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1 rounded">مكتمل
                                                ✓</span>
                                        @else
                                            <span
                                                class="badge bg-warning-subtle text-warning border border-warning-subtle px-2 py-1 rounded">قيد
                                                الدراسة</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">
                                        <i class="fas fa-folder-open d-block fa-2x mb-2 text-gray-300"></i>
                                        لا يوجد طلاب مسجلين في هذا الكورس حتى الآن.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if ($enrollments->hasPages())
                <div class="card-footer bg-white py-3">
                    {{ $enrollments->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
