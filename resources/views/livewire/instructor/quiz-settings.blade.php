<div>

    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2" role="alert">
            <i class="bi bi-check-circle-fill"></i>
            {{ session('success') }}
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-header bg-dark text-white py-3">
            <h5 class="mb-0 d-flex align-items-center gap-2">
                <i class="bi bi-gear"></i> إعدادات الاختبار
            </h5>
        </div>
        <div class="card-body">
            <div class="row g-3">

                <div class="col-md-6">
                    <label class="form-label fw-semibold">
                        الوقت المحدد
                        <span class="text-muted fw-normal small">(بالدقائق)</span>
                    </label>
                    <input type="number"
                           wire:model.defer="time_limit_minutes"
                           class="form-control @error('time_limit_minutes') is-invalid @enderror"
                           placeholder="بدون حد زمني"
                           min="0">
                    <div class="form-text">اتركه فارغاً إذا لا يوجد وقت محدد</div>
                    @error('time_limit_minutes')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">
                        درجة النجاح (%)
                    </label>
                    <input type="number"
                           wire:model.defer="passing_score_percentage"
                           class="form-control @error('passing_score_percentage') is-invalid @enderror"
                           min="0" max="100">
                    @error('passing_score_percentage')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">
                        عدد المحاولات المسموحة
                    </label>
                    <input type="number"
                           wire:model.defer="max_attempts"
                           class="form-control @error('max_attempts') is-invalid @enderror"
                           min="0">
                    <div class="form-text">0 = محاولات غير محدودة</div>
                    @error('max_attempts')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">
                        إظهار الإجابات الصحيحة
                    </label>
                    <select wire:model.defer="show_correct_answers"
                            class="form-select @error('show_correct_answers') is-invalid @enderror">
                        <option value="after_completion">بعد الانتهاء من الاختبار</option>
                        <option value="immediately">فور الإجابة على كل سؤال</option>
                        <option value="never">لا تظهر أبداً</option>
                    </select>
                    @error('show_correct_answers')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

            </div>

            <div class="d-flex gap-4 mt-3 pt-3 border-top flex-wrap">
                <div class="form-check form-switch">
                    <input type="checkbox"
                           wire:model.defer="shuffle_questions"
                           class="form-check-input"
                           id="shuffle_{{ $this->getId() }}"
                           role="switch">
                    <label class="form-check-label" for="shuffle_{{ $this->getId() }}">
                        ترتيب الأسئلة عشوائي
                    </label>
                </div>
                <div class="form-check form-switch">
                    <input type="checkbox"
                           wire:model.defer="is_published"
                           class="form-check-input"
                           id="published_{{ $this->getId() }}"
                           role="switch">
                    <label class="form-check-label" for="published_{{ $this->getId() }}">
                        نشر الاختبار للطلاب
                    </label>
                </div>
            </div>

            <div class="mt-4">
                <button wire:click="saveSettings"
                        wire:loading.attr="disabled"
                        class="btn btn-primary px-4">
                    <span wire:loading.remove wire:target="saveSettings">
                        <i class="bi bi-floppy me-1"></i> حفظ الإعدادات
                    </span>
                    <span wire:loading wire:target="saveSettings">
                        <span class="spinner-border spinner-border-sm me-1"></span>
                        جاري الحفظ...
                    </span>
                </button>
            </div>
        </div>
    </div>

</div>
