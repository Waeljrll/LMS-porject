<div>
    {{-- Success Message --}}
    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Error Message for Correct Answer --}}
    @error('form.options')
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i>{{ $message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @enderror

    {{-- Question Form --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">
                <i class="bi bi-{{ $isEditing ? 'pencil-square' : 'plus-circle' }} me-2"></i>
                {{ $isEditing ? 'تعديل السؤال #' . $form->questionModel->sort_order : 'إضافة سؤال جديد' }}
            </h5>
        </div>
        <div class="card-body">
            <form wire:submit.prevent="save">

                {{-- Question Type --}}
                <div class="mb-3">
                    <label class="form-label fw-bold">نوع السؤال</label>
                    <select wire:model.live="form.question_type" class="form-select w-auto">
                        <option value="mcq">اختيار من متعدد (MCQ)</option>
                        <option value="true_false">صح / خطأ (True/False)</option>
                    </select>
                </div>

                {{-- Question Text --}}
                <div class="mb-3">
                    <label class="form-label fw-bold">نص السؤال *</label>
                    <textarea wire:model="form.question_text"
                        class="form-control @error('form.question_text') is-invalid @enderror"
                        rows="3" placeholder="اكتب نص السؤال هنا..."></textarea>
                    @error('form.question_text')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Points --}}
                <div class="mb-3">
                    <label class="form-label fw-bold">النقاط *</label>
                    <input type="number" wire:model="form.points"
                        class="form-control w-auto @error('form.points') is-invalid @enderror"
                        min="1" max="10">
                    @error('form.points')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- MCQ Options --}}
                @if ($form->question_type === 'mcq')
                    <div class="mb-3">
                        <label class="form-label fw-bold">الخيارات (اختر الإجابة الصحيحة)</label>

                        @foreach ($form->options as $index => $option)
                            <div class="input-group mb-2" wire:key="option-{{ $index }}">
                                <div class="input-group-text">
                                    <input type="radio" name="correct_option"
                                        wire:click="setCorrectOption({{ $index }})"
                                        {{ $option['is_correct'] ? 'checked' : '' }}>
                                </div>
                                <input type="text" wire:model="form.options.{{ $index }}.option_text"
                                    class="form-control @error('form.options.'.$index.'.option_text') is-invalid @enderror"
                                    placeholder="نص الخيار {{ $index + 1 }}">

                                @if (count($form->options) > 2)
                                    <button type="button" wire:click="removeOption({{ $index }})"
                                        class="btn btn-outline-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                @endif
                            </div>
                            @error('form.options.'.$index.'.option_text')
                                <div class="text-danger small mb-2">{{ $message }}</div>
                            @enderror
                        @endforeach

                        @if (count($form->options) < 6)
                            <button type="button" wire:click="addOption" class="btn btn-sm btn-outline-success mt-2">
                                <i class="bi bi-plus-circle me-1"></i> إضافة خيار
                            </button>
                        @endif
                    </div>
                @endif

                {{-- True/False --}}
                @if ($form->question_type === 'true_false')
                    <div class="mb-3">
                        <label class="form-label fw-bold">الإجابة الصحيحة</label>
                        <div class="d-flex gap-3">
                            <div class="form-check">
                                <input type="radio" wire:model="form.options.0.is_correct" value="1"
                                    class="form-check-input" id="tf-true" checked
                                    wire:click="setCorrectOption(0)">
                                <label class="form-check-label fw-bold text-success" for="tf-true">
                                    <i class="bi bi-check-circle me-1"></i> صح (True)
                                </label>
                            </div>
                            <div class="form-check">
                                <input type="radio" wire:model="form.options.1.is_correct" value="1"
                                    class="form-check-input" id="tf-false"
                                    wire:click="setCorrectOption(1)">
                                <label class="form-check-label fw-bold text-danger" for="tf-false">
                                    <i class="bi bi-x-circle me-1"></i> خطأ (False)
                                </label>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Explanation --}}
                <div class="mb-3">
                    <label class="form-label fw-bold">الشرح (يظهر بعد الإجابة)</label>
                    <textarea wire:model="form.explanation" class="form-control" rows="2"
                        placeholder="شرح الإجابة الصحيحة (اختياري)..."></textarea>
                </div>

                {{-- Buttons --}}
                <div class="d-flex justify-content-between">
                    @if ($isEditing)
                        <button type="button" wire:click="cancelEdit" class="btn btn-secondary">
                            <i class="bi bi-x-circle me-1"></i> إلغاء
                        </button>
                    @else
                        <div></div>
                    @endif
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check-circle me-1"></i>
                        {{ $isEditing ? 'حفظ التعديلات' : 'إضافة السؤال' }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Questions List --}}
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold"><i class="bi bi-list-ol me-2"></i>قائمة الأسئلة</h5>
            <span class="badge bg-primary">إجمالي النقاط: {{ $questions->sum('points') }}</span>
        </div>
        <div class="card-body p-0">
            @if ($questions->isEmpty())
                <div class="text-center py-5">
                    <i class="bi bi-clipboard-plus text-muted" style="font-size: 3rem;"></i>
                    <h5 class="mt-3 text-muted">لا توجد أسئلة بعد</h5>
                    <p class="text-muted">استخدم النموذج أعلاه لإضافة أول سؤال</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>السؤال</th>
                                <th>النوع</th>
                                <th>النقاط</th>
                                <th>الخيارات</th>
                                <th>الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($questions as $question)
                                <tr wire:key="question-{{ $question->id }}">
                                    <td><span class="badge bg-secondary">{{ $question->sort_order }}</span></td>
                                    <td>
                                        <div class="fw-bold">{{ Str::limit($question->question_text, 50) }}</div>
                                        @if ($question->explanation)
                                            <small class="text-muted"><i class="bi bi-info-circle me-1"></i>يوجد شرح</small>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $question->question_type === 'mcq' ? 'primary' : 'info' }}">
                                            {{ $question->question_type === 'mcq' ? 'MCQ' : 'صح/خطأ' }}
                                        </span>
                                    </td>
                                    <td><span class="badge bg-warning text-dark">{{ $question->points }}</span></td>
                                    <td>
                                        @if ($question->isMcq())
                                            {{ $question->options->count() }} خيار
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <button wire:click="moveUp({{ $question->id }})"
                                                class="btn btn-outline-secondary" title="لأعلى"
                                                {{ $question->sort_order === 1 ? 'disabled' : '' }}>
                                                <i class="bi bi-arrow-up"></i>
                                            </button>
                                            <button wire:click="moveDown({{ $question->id }})"
                                                class="btn btn-outline-secondary" title="لأسفل"
                                                {{ $question->sort_order === $questions->count() ? 'disabled' : '' }}>
                                                <i class="bi bi-arrow-down"></i>
                                            </button>
                                            <button wire:click="editQuestion({{ $question->id }})"
                                                class="btn btn-outline-primary" title="تعديل">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <button wire:click="deleteQuestion({{ $question->id }})"
                                                class="btn btn-outline-danger" title="حذف"
                                                onclick="return confirm('هل أنت متأكد من حذف هذا السؤال؟')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
