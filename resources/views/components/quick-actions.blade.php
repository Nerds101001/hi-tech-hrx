@props([
    'actions' => []
])

<div class="hitech-card">
    <div class="hitech-card-header">
        <h5 class="title mb-0">Quick Actions</h5>
        <span class="badge bg-label-primary rounded-pill">{{ count($actions) }} Available</span>
    </div>
    <div class="card-body p-sm-5 p-4">
        <div class="row g-3">
            @foreach($actions as $action)
                <div class="col-sm-6 col-md-4 col-lg-3">
                    <a href="{{ $action['url'] }}" class="quick-action-card text-decoration-none">
                        <div class="action-icon">
                            <i class="bx {{ $action['icon'] ?? 'bx-plus' }}"></i>
                        </div>
                        <div class="action-title">{{ $action['title'] }}</div>
                        <div class="action-subtitle">{{ $action['subtitle'] ?? '' }}</div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</div>
