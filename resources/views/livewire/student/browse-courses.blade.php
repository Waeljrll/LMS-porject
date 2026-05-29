<div>
    {{-- Search & Sort Bar --}}
    <div class="row mb-4">
        <div class="col-md-8">
            <div class="input-group">
                <span class="input-group-text bg-white border-end-0">
                    <i class="bi bi-search"></i>
                </span>
                <input type="text" wire:model.debounce.300ms="search" class="form-control border-start-0"
                    placeholder="Search by course title or instructor name...">
                <button class="btn btn-primary" type="button" wire:click="$refresh">
                    Search
                </button>
            </div>
        </div>
        <div class="col-md-4 text-end">
            <div class="d-flex align-items-center justify-content-end">
                <label class="me-2 text-muted">Sort By:</label>
                <select wire:model.live="sortBy" class="form-select w-auto">
                    <option value="newest">Newest</option>
                    <option value="oldest">Oldest</option>
                    <option value="price_low">Price: Low to High</option>
                    <option value="price_high">Price: High to Low</option>
                    <option value="popular">Most Popular</option>
                </select>
            </div>
        </div>
    </div>

    {{-- Main Row: Sidebar + Courses --}}
    <div class="row">
        {{-- Sidebar Filters --}}
        <div class="col-lg-3 col-md-4 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    {{-- Filters Header --}}
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="mb-0 fw-bold"><i class="bi bi-funnel-fill me-2"></i>Filters</h6>
                        <button wire:click="clearAll" class="btn btn-link btn-sm text-decoration-none p-0">
                            Clear All
                        </button>
                    </div>

                    <hr class="my-3">

                    {{-- Categories --}}
                    <div class="mb-4">
                        <h6 class="text-uppercase text-muted small fw-bold mb-3">Categories</h6>
                        @foreach ($allCategories as $category)
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" wire:model.live="categories"
                                    value="{{ $category->id }}" id="cat{{ $category->id }}"
                                    {{ $category->courses_count == 0 ? 'disabled' : '' }}>
                                <label class="form-check-label d-flex justify-content-between w-100"
                                    for="cat{{ $category->id }}">
                                    <span>{{ $category->name }}</span>
                                    <span class="text-muted small">{{ $category->courses_count }}</span>
                                </label>
                            </div>
                        @endforeach
                    </div>

                    {{-- Difficulty Level --}}
                    <div class="mb-4">
                        <h6 class="text-uppercase text-muted small fw-bold mb-3">Difficulty Level</h6>
                        @foreach (['beginner' => 'Beginner', 'intermediate' => 'Intermediate', 'advanced' => 'Advanced'] as $value => $label)
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" wire:model.live="difficulty"
                                    value="{{ $value }}" id="diff{{ $value }}">
                                <label class="form-check-label" for="diff{{ $value }}">
                                    {{ $label }}
                                </label>
                            </div>
                        @endforeach
                    </div>

                    {{-- Price --}}
                    <div class="mb-3">
                        <h6 class="text-uppercase text-muted small fw-bold mb-3">Price</h6>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" wire:model.live="freeOnly" value="1"
                                id="freeOnly">
                            <label class="form-check-label" for="freeOnly">
                                Free Courses Only
                            </label>
                        </div>
                        <div class="mt-3">
                            <label class="form-label small">Max Price: <span
                                    class="text-primary fw-bold">${{ $priceMax }}</span></label>
                            <input type="range" wire:model.live="priceMax" class="form-range" min="0"
                                max="500" step="10">
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="col-lg-9 col-md-8">
            @if ($courses->count() > 0)
                <div class="row row-cols-1 row-cols-md-2 row-cols-xl-3 g-4">
                    @foreach ($courses as $course)
                        <div class="col">
                            <div class="card h-100 border-0 shadow-sm course-card">
                                {{-- Thumbnail with Price Badge --}}
                                <div class="position-relative">
                                    <img src="{{ $course->thumbnail ? asset('storage/' . $course->thumbnail) : asset('images/default-course.jpg') }}"
                                        class="card-img-top" alt="{{ $course->title }}"
                                        style="height: 200px; object-fit: cover;">

                                    <div class="position-absolute top-0 end-0 m-2">
                                        <span class="badge bg-dark px-3 py-2">
                                            @if ($course->price == 0)
                                                Free
                                            @else
                                                ${{ number_format($course->price, 2) }}
                                            @endif
                                        </span>
                                    </div>
                                </div>

                                {{-- Card Body --}}
                                <div class="card-body d-flex flex-column">
                                    {{-- Badges --}}
                                    <div class="mb-2">
                                        <span class="badge bg-primary bg-opacity-10 text-primary">
                                            {{ $course->category->name }}
                                        </span>
                                        <span class="badge bg-secondary bg-opacity-10 text-secondary">
                                            {{ ucfirst($course->difficulty_level) }}
                                        </span>
                                    </div>

                                    {{-- Title --}}
                                    <h5 class="card-title fw-bold text-primary mb-2" style="font-size: 1.1rem;">
                                        {{ $course->title }}
                                    </h5>

                                    {{-- Short Description - FIXED HEIGHT --}}
                                    <p class="card-text text-muted small mb-3 description-text">
                                        {{ $course->short_description ?? Str::limit($course->description, 100) }}
                                    </p>

                                    {{-- Instructor --}}
                                    <p class="card-text small mb-3">
                                        By <span class="fw-semibold">{{ $course->instructor->name }}</span>
                                    </p>

                                    {{-- Meta Info --}}
                                    <div class="d-flex justify-content-between text-muted small mb-3">
                                        <span><i class="bi bi-clock me-1"></i> {{ $course->duration_hours ?? 0 }}
                                            hrs</span>
                                        <span><i class="bi bi-people me-1"></i> {{ $course->enrollments_count ?? 0 }}
                                            Students</span>
                                    </div>

                                    {{-- Spacer --}}
                                    <div class="mt-auto"></div>

                                    {{-- View Details Button - ALWAYS AT BOTTOM --}}
                                    <a href="{{ route('student.courses.show', $course) }}"
                                        class="btn btn-outline-primary w-100">
                                        View Details <i class="bi bi-arrow-right ms-1"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Pagination --}}
                <div class="mt-4">
                    {{ $courses->links() }}
                </div>
            @else
                {{-- Empty State --}}
                <div class="text-center py-5">
                    <div class="mb-3">
                        <i class="bi bi-search" style="font-size: 3rem; color: #dee2e6;"></i>
                    </div>
                    <h5 class="text-muted">No courses found</h5>
                    <p class="text-muted small">Try adjusting your filters or search query.</p>
                    <button wire:click="clearAll" class="btn btn-primary btn-sm">
                        Clear All Filters
                    </button>
                </div>
            @endif
        </div>
    </div>
</div>
