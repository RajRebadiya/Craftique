@if(!empty($previousPost) || !empty($nextPost))
    <div class="card border-0 shadow-sm mt-4">
        <div class="card-body p-3 p-lg-4">
            <div class="d-flex justify-content-between align-items-center flex-wrap" style="gap:12px;">
                <div>
                    @if(!empty($previousPost))
                        <a href="{{ route('frontend.showcase.post', ['id' => $previousPost->id, 'slug' => \Illuminate\Support\Str::slug($previousPost->title_gr ?: ($previousPost->title_en ?: $previousPost->title ?: $previousPost->id))]) }}"
                           class="btn btn-soft-secondary">
                            <i class="las la-arrow-left mr-1"></i>
                            {{ translate('Previous') }}
                        </a>
                    @endif
                </div>

                <div class="text-center text-muted fw-600">
                    {{ translate('Navigate Showcase Posts') }}
                </div>

                <div class="text-right">
                    @if(!empty($nextPost))
                        <a href="{{ route('frontend.showcase.post', ['id' => $nextPost->id, 'slug' => \Illuminate\Support\Str::slug($nextPost->title_gr ?: ($nextPost->title_en ?: $nextPost->title ?: $nextPost->id))]) }}"
                           class="btn btn-soft-secondary">
                            {{ translate('Next') }}
                            <i class="las la-arrow-right ml-1"></i>
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endif