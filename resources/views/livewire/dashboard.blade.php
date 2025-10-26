<div class="">
    @php
    $cards = [
    [
    'title' => 'Users',
    'icon' => 'users',
    'color' => 'primary',
    'text' => 'Manage system users and their roles.',
    'links' => [
    ['route' => 'users.index', 'label' => 'Manage Users']
    ]
    ],
    [
    'title' => 'Products',
    'icon' => 'box',
    'color' => 'success',
    'text' => 'View and manage registered products.',
    'links' => [
    ['route' => 'products.index', 'label' => 'View Products']
    ]
    ],
    [
    'title' => 'Reports',
    'icon' => 'chart-line',
    'color' => 'dark',
    'text' => 'Feedback and system usage reports.',
    'links' => [
    ['route' => 'reports.index', 'label' => 'View Reports']
    ]
    ],
    [
    'title' => 'Activity Logs',
    'icon' => 'history',
    'color' => 'warning',
    'text' => 'Track system-wide user activities.',
    'links' => [
    ['route' => 'activity-logs.index', 'label' => 'View Logs']
    ]
    ],
    [
    'title' => 'Sent Logs',
    'icon' => 'paper-plane',
    'color' => 'info',
    'text' => 'View logs of all sent email/SMS messages.',
    'links' => [
    ['route' => 'sent-logs.index', 'label' => 'Sent Logs']
    ]
    ],
    [
    'title' => 'Feedback Management',
    'icon' => 'comments',
    'color' => 'info',
    'text' => 'View and manage all client feedback.',
    'links' => [
    ['route' => 'feedbacks.index', 'label' => 'Manage Feedback']
    ]
    ],
    [
    'title' => 'Pending Feedback',
    'icon' => 'hourglass-half',
    'color' => 'warning',
    'text' => 'Feedback awaiting response or review.',
    'count' => $statusCounts['pending'] ?? 0,
    'links' => [
    ['route' => ['feedbacks.index', 'status' => 'pending'], 'label' => 'Pending']
    ]
    ],
    [
    'title' => 'Resolved Feedback',
    'icon' => 'check-circle',
    'color' => 'success',
    'text' => 'Feedback that has been addressed.',
    'count' => $statusCounts['resolved'] ?? 0,
    'links' => [
    ['route' => ['feedbacks.index', 'status' => 'resolved'], 'label' => 'Resolved']
    ]
    ],
    [
    'title' => 'Dismissed Feedback',
    'icon' => 'times-circle',
    'color' => 'danger',
    'text' => 'Feedback marked as invalid or closed.',
    'count' => $statusCounts['dismissed'] ?? 0,
    'links' => [
    ['route' => ['feedbacks.index', 'status' => 'dismissed'], 'label' => 'Dismissed']
    ]
    ],
    [
    'title' => 'Feedback Categories',
    'icon' => 'tags',
    'color' => 'secondary',
    'text' => 'Create and manage feedback categories.',
    'links' => [
    ['route' => 'feedback-categories.index', 'label' => 'Categories']
    ]
    ],
    [
    'title' => 'Reports',
    'icon' => 'chart-bar',
    'color' => 'dark',
    'text' => 'View analytics and generate reports.',
    'links' => [
    ['route' => 'reports.index', 'label' => 'View Reports']
    ]
    ]
    ];
    @endphp

    <div class="row g-3 mb-3">
        @foreach($cards as $card)
        @php
        $hasLink = isset($card['links'][0]['route']);
        $href = $hasLink
        ? (is_array($card['links'][0]['route'])
        ? route($card['links'][0]['route'][0], array_slice($card['links'][0]['route'], 1))
        : route($card['links'][0]['route']))
        : 'javascript:void(0)';
        @endphp

        <div class="col-12 col-sm-3 col-md-3 text-center">
            <a href="{{ $href }}"
                class="d-block p-3 rounded bg-light border border-{{ $card['color'] }} text-decoration-none position-relative"
                data-bs-toggle="tooltip" data-bs-placement="top" title="{{ $card['text'] }}">

                <i class="fa fa-{{ $card['icon'] }} fa-2x text-{{ $card['color'] }}"></i>

                @if(isset($card['count']))
                <span class="badge bg-{{ $card['color'] }} position-absolute top-0 end-0 mt-1 me-1">
                    {{ $card['count'] }}
                </span>
                @endif

                <div class="small mt-2 text-dark fw-semibold">{{ $card['title'] }}</div>
            </a>
        </div>
        @endforeach

        <div class="col-12 col-sm-3 col-md-3 text-center">
            <a href="javascript:void(0)"
                class="d-block p-3 rounded bg-light border border-success text-decoration-none position-relative"
                data-bs-toggle="modal" data-bs-target="#feedbackModal" title="{{ __('Send Feedback Request') }}">
                <i class="fa fa-paper-plane fa-2x text-success"></i>
                <div class="small mt-2 text-dark fw-semibold">{{ __('Request Feedback') }}</div>
            </a>
        </div>

    </div>
   
    {{-- wire:poll.30s="refreshChart" --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-light fw-bold">Feedback Overview</div>
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="mb-0">Feedback Summary</h6>
                <select id="feedbackFilter" class="form-select w-auto">
                    <option value="all">All Time</option>
                    <option value="week">This Week</option>
                    <option value="month">This Month</option>
                </select>
            </div>

            <canvas id="feedbackChart" height="120"></canvas>
        </div>

        <script>
            document.addEventListener('livewire:init', () => {
                    let feedbackChart;
                    const ctx = document.getElementById('feedbackChart').getContext('2d');
                    const chartData = @json($chartData);
        
                    function renderChart(data) {
                        if (feedbackChart) feedbackChart.destroy();
        
                        feedbackChart = new Chart(ctx, {
                            type: 'bar',
                            data: {
                                labels: data.labels,
                                datasets: [{
                                    label: 'Feedback Count',
                                    data: data.all,
                                    backgroundColor: [
                                        'rgba(255, 193, 7, 0.7)',
                                        'rgba(25, 135, 84, 0.7)',
                                        'rgba(220, 53, 69, 0.7)',
                                    ],
                                    borderColor: [
                                        'rgba(255, 193, 7, 1)',
                                        'rgba(25, 135, 84, 1)',
                                        'rgba(220, 53, 69, 1)',
                                    ],
                                    borderWidth: 1
                                }]
                            },
                            options: {
                                responsive: true,
                                plugins: {
                                    legend: { display: false },
                                    tooltip: {
                                        callbacks: {
                                            label: ctx => `${ctx.dataset.label}: ${ctx.parsed.y}`
                                        }
                                    }
                                },
                                scales: {
                                    y: {
                                        beginAtZero: true,
                                        ticks: { stepSize: 1 },
                                        title: {
                                            display: true,
                                            text: 'Number of Feedbacks'
                                        }
                                    }
                                }
                            }
                        });
                    }
        
                    renderChart(chartData);
        
                    // Filter Change
                    document.getElementById('feedbackFilter').addEventListener('change', (e) => {
                        const selected = e.target.value;
                        feedbackChart.data.datasets[0].data = chartData[selected];
                        feedbackChart.update();
                    });
        
                    // Refresh Chart When Livewire Dispatches Event
                    Livewire.on('chart-updated', ({ chartData }) => {
                        renderChart(chartData);
                    });
                });
        </script>
    </div>

    <div class="card mt-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Recent Feedback Requests</h5>
            <a href="{{ route('feedbacks.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
        </div>
        <div class="card-body p-0">
            @if ($recentFeedbacks->isEmpty())
            <p class="text-center p-3 text-muted">No recent feedback requests.</p>
            @else
            <ul class="list-group list-group-flush">
                @foreach ($recentFeedbacks as $feedback)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <div>
                        <strong>{{ $feedback->user ? $feedback->user->name : $feedback->guestUser->name }}</strong>
                        <br>
                        <small class="text-muted">
                            {{ $feedback->log->channel ?? 'N/A' }} |
                            {{ $feedback->status }}
                        </small>
                    </div>
                    <span class="badge bg-secondary">{{ $feedback->created_at->diffForHumans() }}</span>
                </li>
                @endforeach
            </ul>
            @endif
        </div>
    </div>
    {{-- wire:poll.60s="refreshProducts" --}}
    <div class="card shadow-sm border-0 mt-3">
        <div class="card-header bg-light fw-bold d-flex justify-content-between align-items-center">
            <span>Top Rated Products</span>
            <span class="text-muted small">(Updated every minute)</span>
        </div>

        <div class="card-body">
            @if($topProducts->isEmpty())
            <p class="text-muted mb-0 text-center">No product ratings yet.</p>
            @else
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Product</th>
                            <th>Average Rating</th>
                            <th>Total Reviews</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($topProducts as $item)
                        <tr>
                            <td class="fw-semibold">
                                <img src="{{ asset($item->product->image ?? NO_IMAGE) }}" class="rounded me-2"
                                    width="40" height="40" alt="Product">
                                {{ $item->product->name }}
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <span class="me-2">{{ number_format($item->avg_rating, 1) }}</span>
                                    <div class="text-warning">
                                        @for ($i = 1; $i <= 5; $i++) @if ($i <=round($item->avg_rating))
                                            <i class="bi bi-star-fill"></i>
                                            @else
                                            <i class="bi bi-star"></i>
                                            @endif
                                            @endfor
                                    </div>
                                </div>
                            </td>
                            <td>{{ $item->total_reviews }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>
    </div>


    {{-- <script>
        const ctx = document.getElementById('feedbackChart').getContext('2d');

        const chartData = {
            all: [20, 15, 10, 5],
            week: [5, 3, 2, 1],
            month: [10, 7, 5, 2]
        };

        let feedbackChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Pending', 'Reviewed', 'Resolved', 'Rejected'],
                datasets: [{
                    label: 'Feedback Count',
                    data: chartData.all,
                    backgroundColor: [
                        'rgba(255, 193, 7, 0.7)',
                        'rgba(13, 202, 240, 0.7)',
                        'rgba(25, 135, 84, 0.7)',
                        'rgba(220, 53, 69, 0.7)'
                    ],
                    borderColor: [
                        'rgba(255, 193, 7, 1)',
                        'rgba(13, 202, 240, 1)',
                        'rgba(25, 135, 84, 1)',
                        'rgba(220, 53, 69, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: ctx => `${ctx.dataset.label}: ${ctx.parsed.y}`
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 1 },
                        title: {
                            display: true,
                            text: 'Number of Feedbacks'
                        }
                    }
                }
            }
        });

        // Update chart based on filter
        document.getElementById('feedbackFilter').addEventListener('change', function () {
            const selected = this.value;
            feedbackChart.data.datasets[0].data = chartData[selected];
            feedbackChart.update();
        });

        document.addEventListener('DOMContentLoaded', function () {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.forEach(function (tooltipTriggerEl) {
                new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script> --}}

</div>