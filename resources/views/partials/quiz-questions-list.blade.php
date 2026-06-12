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
                                    <span class="badge bg-secondary rounded-pill">
                                        {{ $question->sort_order }}
                                    </span>
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
                                        <span class="badge bg-primary bg-opacity-10 text-primary border border-primary">
                                            MCQ
                                        </span>
                                    @elseif ($question->question_type === 'true_false')
                                        <span class="badge bg-info bg-opacity-10 text-info border border-info">
                                            صح/خطأ
                                        </span>
                                    @else
                                        <span class="badge bg-success bg-opacity-10 text-success border border-success">
                                            Essay
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-warning text-dark rounded-pill">
                                        {{ $question->points }}
                                    </span>
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
                            <td>
                                <span class="badge bg-primary rounded-pill">{{ $totalPoints }}</span>
                            </td>
                            <td colspan="2"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        @endif
    </div>
</div>
