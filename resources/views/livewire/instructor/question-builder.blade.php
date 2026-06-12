<div class="row g-4">

    {{-- ==================== LEFT: Question Form ==================== --}}
    <div class="col-lg-6">
        {{-- Flash Messages --}}
        @if (session()->has('success'))
            <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2 mb-3" role="alert">
                <i class="bi bi-check-circle-fill"></i>
                {{ session('success') }}
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @error('form.options')
            <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center gap-2 mb-3" role="alert">
                <i class="bi bi-exclamation-triangle-fill"></i>
                {{ $message }}
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
            </div>
        @enderror

        @if ($hasAttempts)
            <div class="alert alert-warning alert-dismissible fade show d-flex align-items-center gap-2 mb-3 shadow-sm" role="alert">
                <i class="bi bi-lock-fill fs-5"></i>
                <div>
                    <strong>عذراً، هذا الاختبار مغلق تماماً!</strong> نظراً لوجود محاولات حل قائمة أو سابقة من الطلاب، تم
                    تجميد التعديلات لحماية نزاهة البيانات والدرجات التاريخية.
                </div>
            </div>
        @endif

        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white py-3">
                <h5 class="mb-0 d-flex align-items-center gap-2">
                    <i class="bi bi-{{ $isEditing ? 'pencil-square' : 'plus-circle' }}"></i>
                    @if ($isEditing)
                        تعديل السؤال #{{ $form->questionModel?->sort_order }}
                    @else
                        إضافة سؤال جديد
                    @endif
                </h5>
            </div>

            <div class="card-body">
                <form wire:submit.prevent="save">

                    {{-- Question Type --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">نوع السؤال</label>
                        <select wire:model.live="form.question_type" class="form-select w-auto">
                            <option value="mcq">اختيار من متعدد (MCQ)</option>
                            <option value="true_false">صح / خطأ (True/False)</option>
                            <option value="essay">سؤال مقالي (Essay)</option>
                        </select>
                    </div>

                    {{-- Question Text --}}
                    <div class="mb-3">
                        <label class="form-label fw-bold">نص السؤال <span class="text-danger">*</span></label>
                        <textarea wire:model.live.debounce.500ms="form.question_text"
                            class="form-control @error('form.question_text') is-invalid @enderror" rows="4"
                            placeholder="اكتب نص السؤال هنا..."></textarea>
                        @error('form.question_text')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Image Upload --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            صورة للسؤال <span class="text-muted fw-normal">(اختياري)</span>
                        </label>
                        @if ($form->existing_image_path && !$form->image)
                            <div class="mb-2 position-relative d-inline-block">
                                <img src="{{ Storage::url($form->existing_image_path) }}" class="img-thumbnail" style="max-height: 140px;">
                                <button type="button" wire:click="$set('form.existing_image_path', null)"
                                    class="btn btn-sm btn-danger position-absolute top-0 end-0 m-1 p-1 lh-1" title="حذف الصورة">
                                    <i class="bi bi-x"></i>
                                </button>
                            </div>
                        @endif
                        @if ($form->image)
                            <div class="mb-2 position-relative d-inline-block">
                                <img src="{{ $form->image->temporaryUrl() }}" class="img-thumbnail" style="max-height: 140px;">
                                <button type="button" wire:click="$set('form.image', null)"
                                    class="btn btn-sm btn-danger position-absolute top-0 end-0 m-1 p-1 lh-1">
                                    <i class="bi bi-x"></i>
                                </button>
                            </div>
                        @else
                            <div>
                                <input type="file" wire:model="form.image" accept="image/*"
                                    class="form-control @error('form.image') is-invalid @enderror" style="max-width: 340px;">
                                @error('form.image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">PNG, JPG حتى 2MB</div>
                            </div>
                        @endif
                    </div>

                    @if ($form->question_type === 'essay')
                        <div class="alert alert-info d-flex align-items-center gap-2 mb-4" role="alert">
                            <i class="bi bi-info-circle-fill fs-5"></i>
                            <div>
                                <strong>تنبيه للأسئلة المقالية:</strong>
                                هذا النوع من الأسئلة لا يحتوي على خيارات محددة.
                                سيتم إظهار صندوق نصي فارغ للطالب أثناء الاختبار،
                                وسيقوم المحاضر بتصحيح الإجابة ورصد الدرجة يدوياً لاحقاً.
                            </div>
                        </div>
                    @else
                        <div class="card bg-light border-0 mb-4">
                            <div class="card-body">
                                <h6 class="fw-bold mb-3">إعدادات الخيارات والإجابة الصحيحة</h6>

                                @if ($form->question_type === 'mcq')
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">
                                            الخيارات <span class="text-danger">*</span>
                                            <span class="text-muted fw-normal small ms-1">(انقر على الدائرة لتحديد الإجابة الصحيحة)</span>
                                        </label>
                                        @foreach ($form->options as $index => $option)
                                            <div class="input-group mb-2" wire:key="mcq-opt-{{ $index }}"
                                                style="{{ $option['is_correct'] ? 'border-radius:0.375rem; outline: 2px solid #198754;' : '' }}">
                                                <span class="input-group-text bg-{{ $option['is_correct'] ? 'success text-white' : 'white' }}">
                                                    <input type="radio" name="correct_option_{{ $this->getId() }}"
                                                        wire:click="setCorrectOption({{ $index }})"
                                                        {{ $option['is_correct'] ? 'checked' : '' }}
                                                        class="form-check-input mt-0">
                                                </span>
                                                <input type="text" wire:model="form.options.{{ $index }}.option_text"
                                                    class="form-control @error('form.options.' . $index . '.option_text') is-invalid @enderror"
                                                    placeholder="نص الخيار {{ $index + 1 }}">
                                                @if (count($form->options) > 2)
                                                    <button type="button" wire:click="removeOption({{ $index }})"
                                                        class="btn btn-outline-danger" title="حذف هذا الخيار">
                                                        <i class="bi bi-trash3"></i>
                                                    </button>
                                                @endif
                                            </div>
                                            @error('form.options.' . $index . '.option_text')
                                                <div class="text-danger small mb-1">{{ $message }}</div>
                                            @enderror
                                        @endforeach
                                        @if (count($form->options) < 6)
                                            <button type="button" wire:click="addOption"
                                                class="btn btn-sm btn-outline-success mt-1">
                                                <i class="bi bi-plus-circle me-1"></i> إضافة خيار
                                            </button>
                                        @endif
                                        <div class="form-text">الحد الأدنى خيارين، الحد الأقصى 6 خيارات</div>
                                    </div>
                                @endif

                                @if ($form->question_type === 'true_false')
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">الإجابة الصحيحة <span class="text-danger">*</span></label>
                                        <div class="d-flex gap-3">
                                            <div class="card flex-fill text-center py-3 border-2 {{ $form->options[0]['is_correct'] ? 'border-success bg-success bg-opacity-10' : 'border-light' }}"
                                                wire:click="setCorrectOption(0)" style="cursor:pointer">
                                                <i class="bi bi-check-circle-fill text-success fs-3 mb-1"></i>
                                                <div class="fw-semibold text-success">صح (True)</div>
                                            </div>
                                            <div class="card flex-fill text-center py-3 border-2 {{ $form->options[1]['is_correct'] ? 'border-danger bg-danger bg-opacity-10' : 'border-light' }}"
                                                wire:click="setCorrectOption(1)" style="cursor:pointer">
                                                <i class="bi bi-x-circle-fill text-danger fs-3 mb-1"></i>
                                                <div class="fw-semibold text-danger">خطأ (False)</div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                    {{-- Explanation --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            شرح الإجابة <span class="text-muted fw-normal small ms-1">(يظهر للطالب بعد الإجابة — اختياري)</span>
                        </label>
                        <textarea wire:model="form.explanation" class="form-control" rows="2"
                            placeholder="اشرح لماذا هذه الإجابة صحيحة..."></textarea>
                    </div>

                    {{-- Points --}}
                    <div class="mb-4">
                        <label class="form-label fw-semibold">النقاط <span class="text-danger">*</span></label>
                        <div class="d-flex align-items-center gap-2">
                            <input type="number" wire:model="form.points"
                                class="form-control @error('form.points') is-invalid @enderror" style="width: 90px;" min="1" max="10">
                            <span class="text-muted small">من 1 إلى 10</span>
                            @error('form.points')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                        @if ($isEditing)
                            <button type="button" wire:click="cancelEdit" class="btn btn-outline-secondary">
                                <i class="bi bi-x-circle me-1"></i> إلغاء
                            </button>
                        @else
                            <div></div>
                        @endif
                        <button type="submit" wire:loading.attr="disabled" wire:target="save"
                            class="btn btn-success d-flex align-items-center gap-2">
                            <div wire:loading wire:target="save" class="spinner-border spinner-border-sm" role="status"></div>
                            <i wire:loading.remove wire:target="save" class="bi bi-{{ $isEditing ? 'check-circle' : 'plus-circle' }} me-1"></i>
                            <span>{{ $isEditing ? 'حفظ التعديلات' : 'إضافة السؤال' }}</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ==================== RIGHT: Questions List ==================== --}}
    <div class="col-lg-6">
        <div class="card shadow-sm border-0" id="questions-list-card">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom">
                <h5 class="mb-0 fw-bold d-flex align-items-center gap-2">
                    <i class="bi bi-list-ol text-primary"></i>
                    قائمة الأسئلة
                </h5>
                <div class="d-flex align-items-center gap-2">
                    @php
                        $totalPoints = $quiz->questions->sum('points');
                        $questionCount = $quiz->questions->count();
                    @endphp
                    <span class="badge bg-secondary rounded-pill">{{ $questionCount }} سؤال</span>
                    <span class="badge bg-primary rounded-pill">
                        <i class="bi bi-star me-1"></i>{{ $totalPoints }} نقطة
                    </span>
                </div>
            </div>

            <div class="card-body p-0">
                @if ($quiz->questions->isEmpty())
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-clipboard-plus" style="font-size: 3rem; opacity: .4;"></i>
                        <h6 class="mt-3">لا توجد أسئلة بعد</h6>
                        <p class="small mb-0">استخدم النموذج على اليسار لإضافة أول سؤال</p>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-3" style="width:48px">#</th>
                                    <th>السؤال</th>
                                    <th style="width:90px">النوع</th>
                                    <th style="width:70px">النقاط</th>
                                    <th style="width:80px">الخيارات</th>
                                    <th style="width:130px">الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($quiz->questions->sortBy('sort_order') as $question)
                                    <tr wire:key="q-row-{{ $question->id }}">
                                        <td class="ps-3">
                                            <span class="badge bg-secondary rounded-pill">{{ $question->sort_order }}</span>
                                        </td>
                                        <td>
                                            <div class="fw-semibold small" title="{{ strip_tags($question->question_text) }}">
                                                {{ Str::limit(strip_tags($question->question_text), 55) }}
                                            </div>
                                            @if ($question->explanation)
                                                <span class="badge bg-light text-muted border mt-1" style="font-size:10px">
                                                    <i class="bi bi-info-circle me-1"></i>يوجد شرح
                                                </span>
                                            @endif
                                            @if ($question->image_path)
                                                <span class="badge bg-light text-muted border mt-1" style="font-size:10px">
                                                    <i class="bi bi-image me-1"></i>يوجد صورة
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($question->question_type === 'mcq')
                                                <span class="badge bg-primary bg-opacity-10 text-primary border border-primary">MCQ</span>
                                            @elseif ($question->question_type === 'true_false')
                                                <span class="badge bg-info bg-opacity-10 text-info border border-info">صح/خطأ</span>
                                            @else
                                                <span class="badge bg-success bg-opacity-10 text-success border border-success">Essay</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-warning text-dark rounded-pill">{{ $question->points }}</span>
                                        </td>
                                        <td class="text-muted small">
                                            @if ($question->question_type === 'essay')
                                                <span class="text-muted">—</span>
                                            @elseif ($question->question_type === 'mcq')
                                                {{ $question->options->count() }} خيارات
                                            @else
                                                2 خيارات
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <button wire:click="moveUp({{ $question->id }})"
                                                    class="btn btn-outline-secondary" title="تحريك لأعلى"
                                                    {{ $question->sort_order === 1 ? 'disabled' : '' }}>
                                                    <i class="bi bi-arrow-up"></i>
                                                </button>
                                                <button wire:click="moveDown({{ $question->id }})"
                                                    class="btn btn-outline-secondary" title="تحريك لأسفل"
                                                    {{ $question->sort_order === $quiz->questions->count() ? 'disabled' : '' }}>
                                                    <i class="bi bi-arrow-down"></i>
                                                </button>
                                                <button wire:click="editQuestion({{ $question->id }})"
                                                    class="btn btn-outline-primary" title="تعديل">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                                <button wire:click="deleteQuestion({{ $question->id }})"
                                                    class="btn btn-outline-danger" title="حذف"
                                                    onclick="return confirm('هل أنت متأكد من حذف هذا السؤال؟')">
                                                    <i class="bi bi-trash3"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <td colspan="3" class="ps-3 text-muted small fw-semibold">الإجمالي</td>
                                    <td><span class="badge bg-primary rounded-pill">{{ $totalPoints }}</span></td>
                                    <td colspan="2"></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>

</div>
