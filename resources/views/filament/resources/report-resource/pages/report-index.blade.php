<x-filament-panels::page>
    {{-- ── Styles ─────────────────────────────────────────────────────────── --}}
    <style>
        :root {
            --bg-section: rgba(255, 255, 255, 0.85);
            --bg-card: rgba(248, 250, 252, 0.7);
            --border-color: rgba(226, 232, 240, 0.6);
            --text-main: #1e293b;
            --text-muted: #64748b;
            --track-bg: #f1f5f9;
            --th-bg: rgba(248, 250, 252, 0.8);
            --shadow-sm: 0 4px 15px rgba(0,0,0,0.03);
            --shadow-hover: 0 10px 25px rgba(0,0,0,0.08);
            --backdrop-blur: blur(12px);
        }

        .dark {
            --bg-section: rgba(30, 41, 59, 0.6);
            --bg-card: rgba(15, 23, 42, 0.6);
            --border-color: rgba(51, 65, 85, 0.5);
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
            --track-bg: rgba(15, 23, 42, 0.8);
            --th-bg: rgba(15, 23, 42, 0.8);
            --shadow-sm: 0 4px 15px rgba(0,0,0,0.2);
            --shadow-hover: 0 10px 25px rgba(0,0,0,0.4);
        }

        .report-section {
            background: var(--bg-section);
            backdrop-filter: var(--backdrop-blur);
            -webkit-backdrop-filter: var(--backdrop-blur);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            box-shadow: var(--shadow-sm);
            margin-bottom: 2.5rem;
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .report-section:hover {
            box-shadow: var(--shadow-hover);
            transform: translateY(-2px);
        }

        .report-section-header {
            padding: 1.25rem 1.75rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            border-bottom: 1px solid var(--border-color);
            position: relative;
            overflow: hidden;
        }
        
        .report-section-header::before {
            content: '';
            position: absolute;
            top: 0; left: -100%; right: 0; bottom: 0;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.15), transparent);
            animation: shimmer 6s infinite;
        }
        
        @keyframes shimmer {
            0% { left: -100%; }
            50% { left: 100%; }
            100% { left: 100%; }
        }

        .report-section-header h2 {
            font-size: 1.15rem;
            font-weight: 700;
            margin: 0;
            color: #fff;
            letter-spacing: 0.5px;
            text-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }

        .report-section-body { 
            padding: 1.5rem 1.75rem 1.75rem; 
        }

        /* Stat cards */
        .stat-grid {
            display: grid;
            gap: 1.25rem;
        }
        .stat-grid-5 { grid-template-columns: repeat(5, 1fr); }
        .stat-grid-4 { grid-template-columns: repeat(4, 1fr); }
        .stat-grid-3 { grid-template-columns: repeat(3, 1fr); }

        .stat-card {
            background: var(--bg-card);
            border-radius: 12px;
            padding: 1.25rem 1rem;
            text-align: center;
            border: 1px solid var(--border-color);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }
        
        .stat-card:hover {
            transform: translateY(-4px) scale(1.02);
            box-shadow: 0 8px 20px rgba(0,0,0,0.06);
            border-color: rgba(99, 102, 241, 0.4);
        }
        
        .dark .stat-card:hover {
            box-shadow: 0 8px 20px rgba(0,0,0,0.3);
        }

        .stat-card .stat-value {
            font-size: 2.2rem;
            font-weight: 900;
            line-height: 1;
            background-clip: text;
            -webkit-background-clip: text;
            color: transparent;
        }
        
        .stat-card .stat-label {
            font-size: 0.8rem;
            color: var(--text-muted);
            margin-top: 0.6rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* color helpers with gradients for text */
        .text-blue   { background-image: linear-gradient(135deg, #60a5fa, #2563eb); }
        .text-green  { background-image: linear-gradient(135deg, #4ade80, #16a34a); }
        .text-yellow { background-image: linear-gradient(135deg, #fbbf24, #d97706); }
        .text-red    { background-image: linear-gradient(135deg, #f87171, #dc2626); }
        .text-purple { background-image: linear-gradient(135deg, #c084fc, #9333ea); }
        .text-indigo { background-image: linear-gradient(135deg, #818cf8, #4f46e5); }
        .text-teal   { background-image: linear-gradient(135deg, #2dd4bf, #0d9488); }
        .text-orange { background-image: linear-gradient(135deg, #fb923c, #ea580c); }
        .text-gray   { background-image: linear-gradient(135deg, #9ca3af, #4b5563); }

        /* mini-bar chart */
        .bar-list { display: flex; flex-direction: column; gap: 0.85rem; }
        .bar-row { display: flex; align-items: center; gap: 1rem; font-size: 0.85rem; }
        .bar-label { width: 160px; text-align: right; color: var(--text-main); font-weight: 600; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .bar-track { flex: 1; height: 12px; background: var(--track-bg); border-radius: 99px; overflow: hidden; position: relative; box-shadow: inset 0 1px 3px rgba(0,0,0,0.05); }
        .bar-fill  { 
            height: 100%; 
            border-radius: 99px; 
            transition: width 1s cubic-bezier(0.4, 0, 0.2, 1);
            background-size: 200% 200%;
            animation: gradient-move 3s ease infinite;
        }
        .bar-count { width: 45px; text-align: right; color: var(--text-muted); font-weight: 800; font-size: 0.9rem; }

        @keyframes gradient-move {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        /* trend sparkline */
        .trend-row { display: flex; align-items: flex-end; gap: 8px; height: 90px; margin-top: 1.5rem; }
        .trend-col { display: flex; flex-direction: column; align-items: center; gap: 6px; flex: 1; transition: transform 0.2s ease; cursor: pointer; }
        .trend-col:hover { transform: translateY(-4px); }
        .trend-bar { 
            width: 100%; 
            border-radius: 6px 6px 0 0; 
            min-height: 4px; 
            transition: height 1s ease, filter 0.2s ease; 
        }
        .trend-col:hover .trend-bar { filter: brightness(1.2); box-shadow: 0 -2px 10px rgba(0,0,0,0.1); }
        .trend-lbl { font-size: 0.7rem; color: var(--text-muted); text-align: center; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; }
        .trend-val { font-size: 0.8rem; color: var(--text-main); font-weight: 800; opacity: 0; transition: opacity 0.2s ease; transform: translateY(5px); }
        .trend-col:hover .trend-val { opacity: 1; transform: translateY(0); }

        /* grid 2-col */
        .two-col { display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; }
        .three-col { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 2rem; }

        /* badge */
        .badge { display: inline-flex; align-items: center; padding: 4px 12px; border-radius: 99px; font-size: 0.75rem; font-weight: 800; letter-spacing: 0.5px; }
        .badge-blue   { background:rgba(59, 130, 246, 0.15); color:#3b82f6; border: 1px solid rgba(59, 130, 246, 0.2); }
        .badge-green  { background:rgba(34, 197, 94, 0.15); color:#22c55e; border: 1px solid rgba(34, 197, 94, 0.2); }
        .badge-yellow { background:rgba(245, 158, 11, 0.15); color:#f59e0b; border: 1px solid rgba(245, 158, 11, 0.2); }
        .badge-red    { background:rgba(239, 68, 68, 0.15); color:#ef4444; border: 1px solid rgba(239, 68, 68, 0.2); }
        .badge-purple { background:rgba(168, 85, 247, 0.15); color:#a855f7; border: 1px solid rgba(168, 85, 247, 0.2); }
        .badge-gray   { background:rgba(100, 116, 139, 0.15); color:var(--text-muted); border: 1px solid rgba(100, 116, 139, 0.2); }

        .mini-table { width: 100%; border-collapse: collapse; font-size: 0.9rem; }
        .mini-table th { background: var(--th-bg); padding: 0.85rem 1rem; text-align: left; color: var(--text-main); font-weight: 800; border-bottom: 2px solid var(--border-color); text-transform: uppercase; font-size: 0.75rem; letter-spacing: 0.5px; }
        .mini-table td { padding: 0.85rem 1rem; border-bottom: 1px solid var(--border-color); color: var(--text-main); font-weight: 600; transition: background 0.2s ease; }
        .mini-table tr:hover td { background: var(--bg-card); }
        .mini-table tr:last-child td { border-bottom: none; }
        
        .section-subtitle {
            font-weight: 800;
            font-size: 1rem;
            margin-bottom: 1.25rem;
            color: var(--text-main);
            display: flex;
            align-items: center;
            gap: 0.6rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .section-subtitle::before {
            content: '';
            display: block;
            width: 5px;
            height: 16px;
            background: currentColor;
            border-radius: 4px;
        }

        .subtitle-blue { color: #3b82f6; }
        .subtitle-green { color: #10b981; }
        .subtitle-orange { color: #f59e0b; }

        @media (max-width: 1024px) {
            .stat-grid-5, .stat-grid-4 { grid-template-columns: repeat(2, 1fr); }
            .stat-grid-3 { grid-template-columns: repeat(2, 1fr); }
            .two-col, .three-col { grid-template-columns: 1fr; }
        }
        @media (max-width: 640px) {
            .stat-grid-5, .stat-grid-4, .stat-grid-3 { grid-template-columns: 1fr; }
        }
    </style>

    @php
        $a = $applicationStats;
        $i = $internStats;
        $t = $taskStats;

        // bar chart helper
        $maxBar = fn($arr) => max(array_column($arr, 'total') ?: [1]);
    @endphp

    {{-- ════════════════════════════════════════════════════════════════════
         SECTION 1 – APPLICATION / INTERVIEW
    ═══════════════════════════════════════════════════════════════════════ --}}
    <div class="report-section">
        <div class="report-section-header" style="background: linear-gradient(135deg, #1e3a8a, #3b82f6);">
            <svg class="w-6 h-6" style="color:#fff;width:24px;height:24px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <h2>APPLICATION & INTERVIEW REPORT</h2>
        </div>
        <div class="report-section-body">

            {{-- KPI row --}}
            <div class="stat-grid stat-grid-5" style="margin-bottom:2rem;">
                <div class="stat-card">
                    <div class="stat-value text-blue">{{ $a['total'] }}</div>
                    <div class="stat-label">Total Applications</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value text-yellow">{{ $a['shortlisted'] }}</div>
                    <div class="stat-label">Shortlisted</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value text-green">{{ $a['selected'] }}</div>
                    <div class="stat-label">Selected</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value text-red">{{ $a['rejected'] }}</div>
                    <div class="stat-label">Rejected</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value text-indigo">{{ $a['conversion_rate'] }}%</div>
                    <div class="stat-label">Conversion Rate</div>
                </div>
            </div>

            <div class="two-col">
                {{-- Status breakdown --}}
                <div class="stat-card" style="text-align: left;">
                    <div class="section-subtitle subtitle-blue">Status Breakdown</div>
                    @php
                        $statuses = [
                            'applied'             => ['label'=>'Applied',              'gradient'=>'linear-gradient(90deg, #60a5fa, #2563eb)', 'count'=>$a['applied']],
                            'interview_scheduled' => ['label'=>'Interview Scheduled',  'gradient'=>'linear-gradient(90deg, #a78bfa, #7c3aed)', 'count'=>$a['interview_scheduled']],
                            'interviewed'         => ['label'=>'Interviewed',          'gradient'=>'linear-gradient(90deg, #fbbf24, #d97706)', 'count'=>$a['interviewed']],
                            'shortlisted'         => ['label'=>'Shortlisted',          'gradient'=>'linear-gradient(90deg, #4ade80, #16a34a)', 'count'=>$a['shortlisted']],
                            'rejected'            => ['label'=>'Rejected',             'gradient'=>'linear-gradient(90deg, #f87171, #dc2626)', 'count'=>$a['rejected']],
                        ];
                        $maxS = max(array_column($statuses,'count') ?: [1]);
                    @endphp
                    <div class="bar-list">
                        @foreach($statuses as $s)
                        <div class="bar-row">
                            <span class="bar-label">{{ $s['label'] }}</span>
                            <div class="bar-track">
                                <div class="bar-fill" style="background: {{ $s['gradient'] }}; width: {{ $maxS > 0 ? ($s['count']/$maxS*100) : 0 }}%"></div>
                            </div>
                            <span class="bar-count">{{ $s['count'] }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- Interview stats --}}
                <div class="stat-card" style="text-align: left;">
                    <div class="section-subtitle subtitle-blue">Interview Attendance & Result</div>
                    <div class="stat-grid stat-grid-4" style="margin-bottom:1.5rem; gap: 0.75rem;">
                        <div class="stat-card" style="padding: 1rem 0.5rem; background: var(--track-bg); border: none; box-shadow: none;">
                            <div class="stat-value text-green" style="font-size:1.6rem;">{{ $a['interview_present'] }}</div>
                            <div class="stat-label" style="font-size: 0.65rem;">Present</div>
                        </div>
                        <div class="stat-card" style="padding: 1rem 0.5rem; background: var(--track-bg); border: none; box-shadow: none;">
                            <div class="stat-value text-red" style="font-size:1.6rem;">{{ $a['interview_absent'] }}</div>
                            <div class="stat-label" style="font-size: 0.65rem;">Absent</div>
                        </div>
                        <div class="stat-card" style="padding: 1rem 0.5rem; background: var(--track-bg); border: none; box-shadow: none;">
                            <div class="stat-value text-green" style="font-size:1.6rem;">{{ $a['selected'] }}</div>
                            <div class="stat-label" style="font-size: 0.65rem;">Selected</div>
                        </div>
                        <div class="stat-card" style="padding: 1rem 0.5rem; background: var(--track-bg); border: none; box-shadow: none;">
                            <div class="stat-value text-red" style="font-size:1.6rem;">{{ $a['not_selected'] }}</div>
                            <div class="stat-label" style="font-size: 0.65rem;">Not Selected</div>
                        </div>
                    </div>
                    <div style="background: rgba(99, 102, 241, 0.1); border-left: 4px solid #6366f1; padding: 1rem; border-radius: 6px; display: flex; justify-content: space-between; align-items: center;">
                        <span style="font-size:0.9rem;color:var(--text-main);font-weight:700;">Average CGPA of Applicants</span>
                        <strong class="text-indigo" style="font-size:1.4rem;">{{ $a['avg_cgpa'] }}</strong>
                    </div>
                </div>
            </div>

            {{-- Domain breakdown --}}
            @if(count($a['domain_breakdown']))
            <div class="stat-card" style="margin-top:2rem; text-align: left;">
                <div class="section-subtitle subtitle-blue">Top Internship Domains</div>
                @php $maxD = max(array_column($a['domain_breakdown'],'total') ?: [1]); @endphp
                <div class="bar-list">
                    @foreach($a['domain_breakdown'] as $d)
                    <div class="bar-row">
                        <span class="bar-label">{{ $d['domain'] }}</span>
                        <div class="bar-track">
                            <div class="bar-fill" style="background: linear-gradient(90deg, #818cf8, #4f46e5); width: {{ $maxD > 0 ? ($d['total']/$maxD*100) : 0 }}%"></div>
                        </div>
                        <span class="bar-count">{{ $d['total'] }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Monthly trend --}}
            <div class="stat-card" style="margin-top:2rem; text-align: left;">
                <div class="section-subtitle subtitle-blue">Monthly Application Trend (Last 6 Months)</div>
                @php $maxM = max($a['monthly_trend']->pluck('count')->toArray() ?: [1]); @endphp
                <div class="trend-row">
                    @foreach($a['monthly_trend'] as $month)
                    @php $h = $maxM > 0 ? max(4, intval($month['count']/$maxM*60)) : 4; @endphp
                    <div class="trend-col">
                        <span class="trend-val">{{ $month['count'] }}</span>
                        <div class="trend-bar" style="height: {{ $h }}px; background: linear-gradient(0deg, #2563eb, #60a5fa);"></div>
                        <span class="trend-lbl">{{ $month['month'] }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- ════════════════════════════════════════════════════════════════════
         SECTION 2 – INTERN
    ═══════════════════════════════════════════════════════════════════════ --}}
    <div class="report-section">
        <div class="report-section-header" style="background: linear-gradient(135deg, #064e3b, #10b981);">
            <svg class="w-6 h-6" style="color:#fff;width:24px;height:24px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
            <h2>INTERN MANAGEMENT REPORT</h2>
        </div>
        <div class="report-section-body">

            <div class="stat-grid stat-grid-4" style="margin-bottom:2rem;">
                <div class="stat-card">
                    <div class="stat-value text-teal">{{ $i['total'] }}</div>
                    <div class="stat-label">Total Interns</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value text-green">{{ $i['active'] }}</div>
                    <div class="stat-label">Active</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value text-gray">{{ $i['inactive'] }}</div>
                    <div class="stat-label">Inactive</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value text-blue">{{ $i['with_project'] }}</div>
                    <div class="stat-label">With Project</div>
                </div>
            </div>

            <div class="two-col">
                {{-- Batch breakdown --}}
                <div class="stat-card" style="text-align: left; padding: 0; overflow: hidden;">
                    <div style="padding: 1.5rem 1.5rem 0.5rem;">
                        <div class="section-subtitle subtitle-green">Interns per Batch</div>
                    </div>
                    @if(count($i['batch_breakdown']))
                    <table class="mini-table">
                        <thead><tr><th style="padding-left: 1.5rem;">Batch</th><th style="text-align:right; padding-right: 1.5rem;">Interns</th></tr></thead>
                        <tbody>
                            @foreach($i['batch_breakdown'] as $b)
                            <tr><td style="padding-left: 1.5rem;">{{ $b['name'] ?? 'N/A' }}</td><td style="text-align:right; padding-right: 1.5rem;"><span class="badge badge-green">{{ $b['count'] }}</span></td></tr>
                            @endforeach
                        </tbody>
                    </table>
                    @else
                    <p style="color:var(--text-muted);font-size:0.9rem; padding: 0 1.5rem 1.5rem;">No batch data available.</p>
                    @endif
                </div>

                {{-- Team breakdown --}}
                <div class="stat-card" style="text-align: left; padding: 0; overflow: hidden;">
                    <div style="padding: 1.5rem 1.5rem 0.5rem;">
                        <div class="section-subtitle subtitle-green">Interns per Team</div>
                    </div>
                    @if(count($i['team_breakdown']))
                    <table class="mini-table">
                        <thead><tr><th style="padding-left: 1.5rem;">Team</th><th style="text-align:right; padding-right: 1.5rem;">Interns</th></tr></thead>
                        <tbody>
                            @foreach($i['team_breakdown'] as $tm)
                            <tr><td style="padding-left: 1.5rem;">{{ $tm['name'] ?? 'N/A' }}</td><td style="text-align:right; padding-right: 1.5rem;"><span class="badge badge-blue">{{ $tm['count'] }}</span></td></tr>
                            @endforeach
                        </tbody>
                    </table>
                    @else
                    <p style="color:var(--text-muted);font-size:0.9rem; padding: 0 1.5rem 1.5rem;">No team data available.</p>
                    @endif
                </div>
            </div>

            {{-- Monthly joining --}}
            <div class="stat-card" style="margin-top:2rem; text-align: left;">
                <div class="section-subtitle subtitle-green">Monthly Joining Trend (Last 6 Months)</div>
                @php $maxJ = max($i['monthly_joining']->pluck('count')->toArray() ?: [1]); @endphp
                <div class="trend-row">
                    @foreach($i['monthly_joining'] as $month)
                    @php $h = $maxJ > 0 ? max(4, intval($month['count']/$maxJ*60)) : 4; @endphp
                    <div class="trend-col">
                        <span class="trend-val">{{ $month['count'] }}</span>
                        <div class="trend-bar" style="height: {{ $h }}px; background: linear-gradient(0deg, #059669, #34d399);"></div>
                        <span class="trend-lbl">{{ $month['month'] }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- ════════════════════════════════════════════════════════════════════
         SECTION 3 – TASK
    ═══════════════════════════════════════════════════════════════════════ --}}
    <div class="report-section">
        <div class="report-section-header" style="background: linear-gradient(135deg, #78350f, #f59e0b);">
            <svg class="w-6 h-6" style="color:#fff;width:24px;height:24px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
            </svg>
            <h2>TASK MANAGEMENT REPORT</h2>
        </div>
        <div class="report-section-body">

            <div class="stat-grid stat-grid-5" style="margin-bottom:2rem;">
                <div class="stat-card">
                    <div class="stat-value text-orange">{{ $t['total'] }}</div>
                    <div class="stat-label">Total Tasks</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value text-blue">{{ $t['total_assignments'] }}</div>
                    <div class="stat-label">Assignments</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value text-green">{{ $t['total_submissions'] }}</div>
                    <div class="stat-label">Submissions</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value text-red">{{ $t['overdue'] }}</div>
                    <div class="stat-label">Overdue</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value text-yellow">{{ $t['due_soon'] }}</div>
                    <div class="stat-label">Due in 7 Days</div>
                </div>
            </div>

            <div class="three-col">
                {{-- Priority --}}
                <div class="stat-card" style="text-align: left;">
                    <div class="section-subtitle subtitle-orange">Priority Breakdown</div>
                    <div class="bar-list">
                        @php
                            $maxP = max($t['priority_high'], $t['priority_medium'], $t['priority_low'], 1);
                        @endphp
                        <div class="bar-row">
                            <span class="bar-label">High</span>
                            <div class="bar-track"><div class="bar-fill" style="background: linear-gradient(90deg, #f87171, #dc2626); width: {{ $maxP>0?($t['priority_high']/$maxP*100):0 }}%"></div></div>
                            <span class="bar-count">{{ $t['priority_high'] }}</span>
                        </div>
                        <div class="bar-row">
                            <span class="bar-label">Medium</span>
                            <div class="bar-track"><div class="bar-fill" style="background: linear-gradient(90deg, #fbbf24, #d97706); width: {{ $maxP>0?($t['priority_medium']/$maxP*100):0 }}%"></div></div>
                            <span class="bar-count">{{ $t['priority_medium'] }}</span>
                        </div>
                        <div class="bar-row">
                            <span class="bar-label">Low</span>
                            <div class="bar-track"><div class="bar-fill" style="background: linear-gradient(90deg, #4ade80, #16a34a); width: {{ $maxP>0?($t['priority_low']/$maxP*100):0 }}%"></div></div>
                            <span class="bar-count">{{ $t['priority_low'] }}</span>
                        </div>
                    </div>
                </div>

                {{-- Submission rate --}}
                <div class="stat-card" style="display:flex;flex-direction:column;justify-content:center;align-items:center;gap:0.75rem;">
                    <div class="section-subtitle subtitle-orange" style="margin-bottom:0;">Submission Rate</div>
                    <div class="stat-value text-indigo" style="font-size:3.5rem;">{{ $t['submission_rate'] }}%</div>
                    <div style="text-align: center;">
                        <p style="font-size:0.85rem;color:var(--text-muted); font-weight: 600;">{{ $t['total_submissions'] }} / {{ $t['total_assignments'] }} assignments submitted</p>
                        <div style="margin-top: 10px; background: var(--track-bg); padding: 6px 12px; border-radius: 99px; display: inline-block;">
                            <span style="font-size:0.8rem;color:var(--text-main); font-weight:700;">Avg Marks: <span class="text-indigo" style="font-size: 1.1rem;">{{ $t['avg_marks'] }}</span></span>
                        </div>
                    </div>
                </div>

                {{-- Evaluation --}}
                <div class="stat-card" style="text-align: left;">
                    <div class="section-subtitle subtitle-orange">Evaluation Status</div>
                    <div class="bar-list">
                        @php $maxEv = max($t['evaluated'], $t['pending_evaluation'], 1); @endphp
                        <div class="bar-row">
                            <span class="bar-label">Evaluated</span>
                            <div class="bar-track"><div class="bar-fill" style="background: linear-gradient(90deg, #4ade80, #16a34a); width: {{ $maxEv>0?($t['evaluated']/$maxEv*100):0 }}%"></div></div>
                            <span class="bar-count">{{ $t['evaluated'] }}</span>
                        </div>
                        <div class="bar-row">
                            <span class="bar-label">Pending</span>
                            <div class="bar-track"><div class="bar-fill" style="background: linear-gradient(90deg, #fbbf24, #d97706); width: {{ $maxEv>0?($t['pending_evaluation']/$maxEv*100):0 }}%"></div></div>
                            <span class="bar-count">{{ $t['pending_evaluation'] }}</span>
                        </div>
                    </div>

                    @if(count($t['grade_distribution']))
                    <div style="margin-top: 1.5rem;">
                        <p style="font-weight:700;font-size:0.85rem;margin-bottom:0.75rem;color:var(--text-main); text-transform: uppercase; letter-spacing: 0.5px;">Grade Distribution</p>
                        <div style="display:flex;flex-wrap:wrap;gap:0.5rem;">
                            @foreach($t['grade_distribution'] as $grade => $cnt)
                            <span class="badge badge-purple">{{ $grade }}: {{ $cnt }}</span>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Monthly tasks --}}
            <div class="stat-card" style="margin-top:2rem; text-align: left;">
                <div class="section-subtitle subtitle-orange">Monthly Task Creation (Last 6 Months)</div>
                @php $maxT = max($t['monthly_tasks']->pluck('count')->toArray() ?: [1]); @endphp
                <div class="trend-row">
                    @foreach($t['monthly_tasks'] as $month)
                    @php $h = $maxT > 0 ? max(4, intval($month['count']/$maxT*60)) : 4; @endphp
                    <div class="trend-col">
                        <span class="trend-val">{{ $month['count'] }}</span>
                        <div class="trend-bar" style="height: {{ $h }}px; background: linear-gradient(0deg, #d97706, #fbbf24);"></div>
                        <span class="trend-lbl">{{ $month['month'] }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- Footer --}}
    <p style="text-align:center;font-size:0.85rem;color:var(--text-muted);font-weight:600;margin-top:1rem;margin-bottom:2rem;">
        Report generated on <span style="color:var(--text-main);">{{ $generatedAt }}</span> &nbsp;|&nbsp; IAPES – Internship Administration & Performance Evaluation System
    </p>

</x-filament-panels::page>
