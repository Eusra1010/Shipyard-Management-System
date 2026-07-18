@php
    $iconMap = [
        'Clear'       => ['fas fa-sun',          '#f59e0b'],
        'Clouds'      => ['fas fa-cloud',         '#94a3b8'],
        'Rain'        => ['fas fa-cloud-rain',    '#3b82f6'],
        'Drizzle'     => ['fas fa-cloud-drizzle', '#60a5fa'],
        'Thunderstorm'=> ['fas fa-bolt',          '#a855f7'],
        'Snow'        => ['fas fa-snowflake',     '#93c5fd'],
        'Mist'        => ['fas fa-smog',          '#94a3b8'],
        'Haze'        => ['fas fa-smog',          '#94a3b8'],
        'Fog'         => ['fas fa-smog',          '#94a3b8'],
    ];
    $icon = $iconMap[$weather['main']] ?? ['fas fa-cloud', '#94a3b8'];
@endphp

<div class="wx-row">
    <i class="{{ $icon[0] }}" style="font-size:30px;color:{{ $icon[1] }};flex-shrink:0;"></i>
    <div>
        <div style="display:flex;align-items:baseline;gap:2px;">
            <span class="wx-temp">{{ $weather['temp'] }}</span>
            <span class="wx-deg">°C</span>
        </div>
        <div class="wx-cond">{{ $weather['condition'] }}</div>
    </div>
</div>

<div class="wx-meta">
    <div class="wx-meta-item">
        <i class="fas fa-tint" style="color:#3b82f6;width:13px;"></i>
        <span>Humidity {{ $weather['humidity'] }}%</span>
    </div>
    <div class="wx-meta-item">
        <i class="fas fa-wind" style="color:#64748b;width:13px;"></i>
        <span>Wind {{ $weather['wind_speed'] }} m/s</span>
    </div>
    <div class="wx-meta-item">
        <i class="fas fa-thermometer-half" style="color:#f59e0b;width:13px;"></i>
        <span>Feels like {{ $weather['feels_like'] }}°C</span>
    </div>
</div>

@if($forecastRisk['outdoor_risk'])
<div style="margin-top:10px;padding:7px 10px;background:#fffbeb;border:1px solid #fcd34d;border-radius:7px;
            font-size:11px;font-weight:600;color:#92400e;display:flex;align-items:center;gap:6px;">
    <i class="fas fa-exclamation-triangle" style="color:#f59e0b;"></i>
    {{ $forecastRisk['risk_reason'] }} from {{ $forecastRisk['earliest_risk'] }}
</div>
@endif

<button class="wx-refresh" onclick="refreshWeather()">
    <i class="fas fa-sync-alt" id="wxSpinner"></i> Refresh &middot; {{ $weather['fetched_at'] }}
</button>
