<div>
    @if (session()->has('message'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('message') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="card-title mb-0">Course Wizard - Step {{ $currentStep }} of 2</h5>
                <span class="badge bg-primary">Progress: {{ $currentStep == 1 ? '50%' : '100%' }}</span>
            </div>

            <form wire:submit.prevent="saveCourse">
                @if ($currentStep == 1)
                    <div id="step1">
                        <h5 class="mb-4 text-primary">Step 1: Basic Information</h5>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Course Title</label>
                            <input type="text" wire:model="title"
                                class="form-control @error('title') is-invalid @enderror">
                            @error('title')
                                <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Short Description (for Card)</label>
                            <input type="text" wire:model="short_description"
                                class="form-control @error('short_description') is-invalid @enderror">
                            @error('short_description')
                                <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Full Description</label>
                            <textarea wire:model="description" class="form-control @error('description') is-invalid @enderror" rows="5"></textarea>
                            @error('description')
                                <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Category</label>
                                <select wire:model="category_id"
                                    class="form-select @error('category_id') is-invalid @enderror">
                                    <option value="">Select Category...</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Language</label>
                                <input type="text" wire:model="language"
                                    class="form-control @error('language') is-invalid @enderror">
                                @error('language')
                                    <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Instructor</label>

                            @if (Auth::user()->isAdmin())
                                <select wire:model="instructor_id"
                                    class="form-select @error('instructor_id') is-invalid @enderror">
                                    <option value="">Select Instructor...</option>
                                    @foreach ($instructors as $instructor)
                                        <option value="{{ $instructor->id }}">{{ $instructor->name }}</option>
                                    @endforeach
                                </select>
                            @else
                                <input type="text" class="form-control" value="{{ Auth::user()->name }}" disabled>
                                <input type="hidden" wire:model="instructor_id">
                            @endif

                            @error('instructor_id')
                                <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold d-block">Difficulty Level</label>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" wire:model="difficulty_level"
                                    value="beginner" id="lvl1">
                                <label class="form-check-label" for="lvl1">Beginner</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" wire:model="difficulty_level"
                                    value="intermediate" id="lvl2">
                                <label class="form-check-label" for="lvl2">Intermediate</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" wire:model="difficulty_level"
                                    value="advanced" id="lvl3">
                                <label class="form-check-label" for="lvl3">Advanced</label>
                            </div>
                            @error('difficulty_level')
                                <div class="text-danger small d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Course Thumbnail</label>

                            @if ($existingThumbnail && !$thumbnail)
                                <div class="mb-2">
                                    <img src="{{ asset('storage/' . $existingThumbnail) }}" width="100"
                                        class="rounded border">
                                    <p class="text-muted small mb-0">Current Image</p>
                                </div>
                            @endif

                            <input type="file" wire:model="thumbnail"
                                class="form-control @error('thumbnail') is-invalid @enderror">
                            <small class="text-muted">Max 2MB - Leave empty to keep current image</small>

                            @error('thumbnail')
                                <span class="text-danger small d-block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Status</label>
                            <select wire:model="status" class="form-select w-25 @error('status') is-invalid @enderror">
                                <option value="draft">Draft</option>
                                <option value="published">Publish</option>
                            </select>
                            @error('status')
                                <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="text-end mt-4">
                            <button type="button" wire:click="nextStep" class="btn btn-primary px-4">Next</button>
                        </div>
                    </div>
                @endif

                @if ($currentStep == 2)
                    <div id="step2">
                        <h5 class="mb-4 text-primary">Step 2: Content Details</h5>

                        <div class="mb-4">
                            <label class="form-label fw-bold">What will students learn? (min 3 points)</label>
                            @foreach ($objectives as $index => $objective)
                                <div class="input-group mb-2" wire:key="obj-{{ $index }}">
                                    <input type="text" wire:model="objectives.{{ $index }}"
                                        class="form-control @error('objectives.' . $index) is-invalid @enderror"
                                        placeholder="Learning objective...">

                                    @if (count($objectives) > 3)
                                        <button type="button" wire:click="removeObjective({{ $index }})"
                                            class="btn btn-outline-danger"><i class="bi bi-trash"></i></button>
                                    @endif
                                </div>
                                @error('objectives.' . $index)
                                    <div class="text-danger small mb-2">{{ $message }}</div>
                                @enderror
                            @endforeach

                            <button type="button" wire:click="addObjective"
                                class="btn btn-sm btn-outline-success mt-1">+ Add Objective</button>

                            @error('objectives')
                                <div class="text-danger small mt-2">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Prerequisites</label>
                            <textarea wire:model="requirements" class="form-control @error('requirements') is-invalid @enderror" rows="3"></textarea>
                            @error('requirements')
                                <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Who is this course for?</label>
                            <textarea wire:model="who_is_it_for" class="form-control @error('who_is_it_for') is-invalid @enderror"
                                rows="3"></textarea>
                            @error('who_is_it_for')
                                <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="row mb-3">
                            <label class="form-label fw-bold">Course Duration</label>
                            <div class="col">
                                <input type="number" wire:model="duration_hours"
                                    class="form-control @error('duration_hours') is-invalid @enderror"
                                    placeholder="Hours">
                                @error('duration_hours')
                                    <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col">
                                <input type="number" wire:model="duration_minutes"
                                    class="form-control @error('duration_minutes') is-invalid @enderror"
                                    placeholder="Minutes">
                                @error('duration_minutes')
                                    <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Price ($) - Leave 0 for free courses</label>
                            <input type="number" wire:model="price"
                                class="form-control w-25 @error('price') is-invalid @enderror">
                            @error('price')
                                <span class="text-danger small d-block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between mt-5 pt-3 border-top">
                            <button type="button" wire:click="previousStep"
                                class="btn btn-secondary px-4">Back</button>
                            <button type="submit" class="btn btn-success px-5">Save Course</button>
                        </div>
                    </div>
                @endif
            </form>
        </div>
    </div>
</div>
