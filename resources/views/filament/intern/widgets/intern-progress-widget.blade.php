<x-filament-widgets::widget>
@php
    $data        = $this->getViewData();
    $progress    = $data['progress'];
    $elapsed     = $data['elapsed_days'];
    $left        = $data['days_left'];
    $total       = $data['total_days'];
    $startDate   = $data['start_date'];
    $endDate     = $data['end_date'];

    // Arc path for SVG donut
    $radius = 36;
    $circumference = 2 * M_PI * $radius;
    $dashOffset = $circumference - ($progress / 100) * $circumference;
@endphp

<style>
.ipw-card {
    position: relative;
    overflow: hidden;
    border-radius: 14px;
    padding: 20px;
    background: linear-gradient(145deg, #0d1117 0%, #161b27 50%, #0d1117 100%);
    border: 1px solid rgba(99,102,241,0.18);

    height: 100%; 
    min-height: 380px; 
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}
.ipw-card::before {
    content:'';
    position:absolute;
    top:-60px; right:-60px;
    width:180px; height:180px;
    border-radius:50%;
    background: radial-gradient(circle, rgba(99,102,241,0.14) 0%, transparent 65%);
    pointer-events:none;
}
.ipw-top {
    display: flex;
    align-items: center;
    gap: 14px;
    margin-bottom: 18px;
}
.ipw-donut {
    position: relative;
    width: 88px;
    height: 88px;
    flex-shrink: 0;
}
.ipw-donut svg {
    width: 88px;
    height: 88px;
    transform: rotate(-90deg);
}
.ipw-donut-label {
    position: absolute;
    inset: 0;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    line-height: 1;
}
.ipw-donut-pct {
    font-size: 20px;
    font-weight: 800;
    color: #c7d2fe;
    letter-spacing: -0.03em;
    font-variant-numeric: tabular-nums;
}
.ipw-donut-sub {
    font-size: 9px;
    font-weight: 600;
    color: #6366f1;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    margin-top: 2px;
}
.ipw-info {
    flex: 1;
}
.ipw-title-row {
    display: flex;
    align-items: center;
    gap: 7px;
    margin-bottom: 4px;
}
.ipw-icon-wrap {
    width: 24px; height: 24px;
    border-radius: 7px;
    display: flex; align-items: center; justify-content: center;
    background: rgba(99,102,241,0.15);
    border: 1px solid rgba(99,102,241,0.25);
    flex-shrink: 0;
}
.ipw-title {
    font-size: 13px;
    font-weight: 700;
    color: #e2e8f0;
    letter-spacing: -0.01em;
}
.ipw-subtitle {
    font-size: 10px;
    color: #475569;
    font-weight: 500;
    margin-bottom: 10px;
}
.ipw-bar-wrap {
    position: relative;
    height: 5px;
    border-radius: 99px;
    background: rgba(255,255,255,0.06);
    overflow: visible;
}
.ipw-bar-fill {
    height: 100%;
    border-radius: 99px;
    background: linear-gradient(90deg, #6366f1, #a78bfa);
    position: relative;
    transition: width 1s ease;
    min-width: {{ $progress > 0 ? '8px' : '0' }};
}
.ipw-bar-fill::after {
    content: '';
    position: absolute;
    right: -1px; top: 50%;
    transform: translateY(-50%);
    width: 9px; height: 9px;
    border-radius: 50%;
    background: #a78bfa;
    border: 2px solid #0d1117;
    display: {{ $progress > 0 ? 'block' : 'none' }};
}
.ipw-bar-pct {
    font-size: 9px;
    font-weight: 700;
    color: #818cf8;
    margin-top: 4px;
    text-align: right;
    font-variant-numeric: tabular-nums;
}
.ipw-stats {
    display: grid;
    grid-template-columns: 1fr 1fr 1fr;
    gap: 8px;
    margin-bottom: 14px;
}
.ipw-stat {
    border-radius: 10px;
    padding: 10px 10px 8px;
    background: rgba(255,255,255,0.03);
    border: 1px solid rgba(255,255,255,0.06);
}
.ipw-stat.highlight {
    background: rgba(99,102,241,0.10);
    border-color: rgba(99,102,241,0.22);
}
.ipw-stat-key {
    font-size: 9px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.1em;
    color: #334155;
    margin-bottom: 4px;
}
.ipw-stat.highlight .ipw-stat-key { color: #6366f1; }
.ipw-stat-val {
    font-size: 22px;
    font-weight: 800;
    color: #e2e8f0;
    letter-spacing: -0.03em;
    line-height: 1;
    font-variant-numeric: tabular-nums;
}
.ipw-stat.highlight .ipw-stat-val { color: #c7d2fe; }
.ipw-stat-unit {
    font-size: 9px;
    color: #1e293b;
    margin-top: 2px;
}
.ipw-stat.highlight .ipw-stat-unit { color: #4f46e5; }
.ipw-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding-top: 12px;
    border-top: 1px solid rgba(255,255,255,0.05);
}
.ipw-date-block .ipw-date-key {
    font-size: 9px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.1em;
    color: #1e293b;
    margin-bottom: 2px;
}
.ipw-date-block .ipw-date-val {
    font-size: 11px;
    font-weight: 600;
    color: #64748b;
}
.ipw-date-block.end .ipw-date-val { color: #818cf8; }
.ipw-timeline {
    flex: 1;
    display: flex;
    align-items: center;
    gap: 4px;
    margin: 0 10px;
}
.ipw-tl-line { flex:1; height:1px; background:rgba(99,102,241,0.18); }
.ipw-tl-dot { width:5px; height:5px; border-radius:50%; background:#6366f1; flex-shrink:0; }
</style>

<div class="ipw-card">

    {{-- Top: donut + title + bar --}}
    <div class="ipw-top">

        {{-- Donut --}}
        <div class="ipw-donut">
            <svg viewBox="0 0 88 88" fill="none" xmlns="http://www.w3.org/2000/svg">
                {{-- Track --}}
                <circle cx="44" cy="44" r="{{ $radius }}"
                        stroke="rgba(255,255,255,0.06)"
                        stroke-width="7"
                        fill="none" />
                {{-- Progress arc --}}
                <circle cx="44" cy="44" r="{{ $radius }}"
                        stroke="url(#donutGrad)"
                        stroke-width="7"
                        fill="none"
                        stroke-linecap="round"
                        stroke-dasharray="{{ $circumference }}"
                        stroke-dashoffset="{{ $dashOffset }}" />
                <defs>
                    <linearGradient id="donutGrad" x1="0" y1="0" x2="1" y2="0">
                        <stop offset="0%" stop-color="#6366f1"/>
                        <stop offset="100%" stop-color="#a78bfa"/>
                    </linearGradient>
                </defs>
            </svg>
            <div class="ipw-donut-label">
                <span class="ipw-donut-pct">{{ $progress }}<span style="font-size:11px;color:#818cf8;">%</span></span>
                <span class="ipw-donut-sub">done</span>
            </div>
        </div>

        {{-- Right of donut: title + bar --}}
        <div class="ipw-info">
            <div class="ipw-title-row">
                <div class="ipw-icon-wrap">
                    <x-heroicon-m-rocket-launch style="width:12px;height:12px;color:#818cf8;" />
                </div>
                <span class="ipw-title">Internship Progress</span>
            </div>
            <p class="ipw-subtitle">{{ $data['status_message'] }}</p>

            <div class="ipw-bar-wrap">
                <div class="ipw-bar-fill" style="width:{{ max($progress, 0) }}%;"></div>
            </div>
            <p class="ipw-bar-pct">{{ $elapsed }} of {{ $total }} days elapsed</p>
        </div>
    </div>

    {{-- Stat boxes --}}
    <div class="ipw-stats">
        <div class="ipw-stat">
            <p class="ipw-stat-key">Elapsed</p>
            <p class="ipw-stat-val">{{ $elapsed }}</p>
            <p class="ipw-stat-unit">days</p>
        </div>
        <div class="ipw-stat highlight">
            <p class="ipw-stat-key">Remaining</p>
            <p class="ipw-stat-val">{{ $left }}</p>
            <p class="ipw-stat-unit">days left</p>
        </div>
        <div class="ipw-stat">
            <p class="ipw-stat-key">Total</p>
            <p class="ipw-stat-val">{{ $total }}</p>
            <p class="ipw-stat-unit">days</p>
        </div>
    </div>

    {{-- Date footer --}}
    <div class="ipw-footer">
        <div class="ipw-date-block">
            <p class="ipw-date-key">Start</p>
            <p class="ipw-date-val">{{ $startDate }}</p>
        </div>
        <div class="ipw-timeline">
            <div class="ipw-tl-line"></div>
            <div class="ipw-tl-dot"></div>
            <div class="ipw-tl-line" style="background:rgba(99,102,241,0.08);"></div>
        </div>
        <div class="ipw-date-block end" style="text-align:right;">
            <p class="ipw-date-key">Target</p>
            <p class="ipw-date-val">{{ $endDate }}</p>
        </div>
    </div>

</div>
</x-filament-widgets::widget>