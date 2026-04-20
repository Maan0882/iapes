<x-filament-panels::page>
    {{-- ── Styles ─────────────────────────────────────────────────────────── --}}
    <style>
        .report-section {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 1px 6px rgba(0,0,0,.08);
            margin-bottom: 2rem;
            overflow: hidden;
        }
        .report-section-header {
            padding: 1.1rem 1.5rem;
            display: flex;
            align-items: center;
            gap: .75rem;
            border-bottom: 1px solid #f1f5f9;
        }
        .report-section-header h2 {
            font-size: 1.05rem;
            font-weight: 700;
            margin: 0;
        }
        .report-section-body { padding: 1.25rem 1.5rem 1.5rem; }

        /* Stat cards */
        .stat-grid {
            display: grid;
            gap: 1rem;
        }
        .stat-grid-5 { grid-template-columns: repeat(5, 1fr); }
        .stat-grid-4 { grid-template-columns: repeat(4, 1fr); }
        .stat-grid-3 { grid-template-columns: repeat(3, 1fr); }

        .stat-card {
            background: #f8fafc;
            border-radius: 10px;
            padding: .9rem 1rem;
            text-align: center;
            border: 1px solid #e2e8f0;
        }
        .stat-card .stat-value {
            font-size: 1.8rem;
            font-weight: 800;
            line-height: 1;
        }
        .stat-card .stat-label {
            font-size: .75rem;
            color: #64748b;
            margin-top: .35rem;
            font-weight: 500;
        }

        /* color helpers */
        .text-blue   { color: #3b82f6; }
        .text-green  { color: #22c55e; }
        .text-yellow { color: #f59e0b; }
        .text-red    { color: #ef4444; }
        .text-purple { color: #a855f7; }
        .text-indigo { color: #6366f1; }
        .text-teal   { color: #14b8a6; }
        .text-orange { color: #f97316; }
        .text-gray   { color: #6b7280; }

        /* mini-bar chart */
        .bar-list { display: flex; flex-direction: column; gap: .5rem; }
        .bar-row { display: flex; align-items: center; gap: .75rem; font-size: .8rem; }
        .bar-label { width: 160px; text-align: right; color: #374151; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .bar-track { flex: 1; height: 10px; background: #f1f5f9; border-radius: 99px; overflow: hidden; }
        .bar-fill  { height: 100%; border-radius: 99px; }
        .bar-count { width: 36px; text-align: right; color: #64748b; font-weight: 600; }

        /* trend sparkline using flex */
        .trend-row { display: flex; align-items: flex-end; gap: 6px; height: 72px; }
        .trend-col { display: flex; flex-direction: column; align-items: center; gap: 3px; flex: 1; }
        .trend-bar { width: 100%; background: currentColor; border-radius: 4px 4px 0 0; min-height: 4px; }
        .trend-lbl { font-size: .62rem; color: #94a3b8; text-align: center; }

        /* grid 2-col */
        .two-col { display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; }
        .three-col { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1.25rem; }

        /* badge */
        .badge { display: inline-flex; align-items: center; padding: 2px 8px; border-radius: 99px; font-size: .72rem; font-weight: 600; }
        .badge-blue   { background:#dbeafe; color:#1d4ed8; }
        .badge-green  { background:#dcfce7; color:#15803d; }
        .badge-yellow { background:#fef9c3; color:#92400e; }
        .badge-red    { background:#fee2e2; color:#b91c1c; }
        .badge-purple { background:#f3e8ff; color:#7e22ce; }
        .badge-gray   { background:#f1f5f9; color:#475569; }

        .mini-table { width: 100%; border-collapse: collapse; font-size: .82rem; }
        .mini-table th { background: #f8fafc; padding: .5rem .75rem; text-align: left; color: #374151; font-weight: 600; border-bottom: 1px solid #e2e8f0; }
        .mini-table td { padding: .5rem .75rem; border-bottom: 1px solid #f1f5f9; color: #374151; }
        .mini-table tr:last-child td { border-bottom: none; }

        @media (max-width: 900px) {
            .stat-grid-5, .stat-grid-4 { grid-template-columns: repeat(2, 1fr); }
            .stat-grid-3 { grid-template-columns: repeat(2, 1fr); }
            .two-col, .three-col { grid-template-columns: 1fr; }
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
        <div class="report-section-header" style="background: linear-gradient(135deg,#1e40af,#3b82f6);">
            <svg class="w-5 h-5" style="color:#fff;width:20px;height:20px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <h2 style="color:#fff;">📋 Application & Interview Report</h2>
        </div>
        <div class="report-section-body">

            {{-- KPI row --}}
            <div class="stat-grid stat-grid-5" style="margin-bottom:1.25rem;">
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
                <div>
                    <p style="font-weight:700;font-size:.85rem;margin-bottom:.75rem;color:#374151;">Application Status Breakdown</p>
                    @php
                        $statuses = [
                            'applied'             => ['label'=>'Applied',              'color'=>'#3b82f6', 'count'=>$a['applied']],
                            'interview_scheduled' => ['label'=>'Interview Scheduled',  'color'=>'#8b5cf6', 'count'=>$a['interview_scheduled']],
                            'interviewed'         => ['label'=>'Interviewed',          'color'=>'#f59e0b', 'count'=>$a['interviewed']],
                            'shortlisted'         => ['label'=>'Shortlisted',          'color'=>'#22c55e', 'count'=>$a['shortlisted']],
                            'rejected'            => ['label'=>'Rejected',             'color'=>'#ef4444', 'count'=>$a['rejected']],
                        ];
                        $maxS = max(array_column($statuses,'count') ?: [1]);
                    @endphp
                    <div class="bar-list">
                        @foreach($statuses as $s)
                        <div class="bar-row">
                            <span class="bar-label">{{ $s['label'] }}</span>
                            <div class="bar-track">
                                <div class="bar-fill"style="background:{{ $s['color'] }};width:{{ $maxS > 0 ? ($s['count']/$maxS*100) : 0 }}%"></div>
                            </div>
                            <span class="bar-count">{{ $s['count'] }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- Interview stats --}}
                <div>
                    <p style="font-weight:700;font-size:.85rem;margin-bottom:.75rem;color:#374151;">Interview Attendance & Result</p>
                    <div class="stat-grid stat-grid-4" style="margin-bottom:1rem;">
                        <div class="stat-card">
                            <div class="stat-value text-green" style="font-size:1.4rem;">{{ $a['interview_present'] }}</div>
                            <div class="stat-label">Present</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-value text-red" style="font-size:1.4rem;">{{ $a['interview_absent'] }}</div>
                            <div class="stat-label">Absent</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-value text-green" style="font-size:1.4rem;">{{ $a['selected'] }}</div>
                            <div class="stat-label">Selected</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-value text-red" style="font-size:1.4rem;">{{ $a['not_selected'] }}</div>
                            <div class="stat-label">Not Selected</div>
                        </div>
                    </div>
                    <div class="stat-card" style="text-align:left;">
                        <span style="font-size:.8rem;color:#374151;">Average CGPA of Applicants: <strong class="text-indigo">{{ $a['avg_cgpa'] }}</strong></span>
                    </div>
                </div>
            </div>

            {{-- Domain breakdown --}}
            @if(count($a['domain_breakdown']))
            <div style="margin-top:1.25rem;">
                <p style="font-weight:700;font-size:.85rem;margin-bottom:.75rem;color:#374151;">Top Internship Domains</p>
                @php $maxD = max(array_column($a['domain_breakdown'],'total') ?: [1]); @endphp
                <div class="bar-list">
                    @foreach($a['domain_breakdown'] as $d)
                    <div class="bar-row">
                        <span class="bar-label">{{ $d['domain'] }}</span>
                        <div class="bar-track">
                            <div class="bar-fill"style="background:#6366f1;width:{{ $maxD > 0 ? ($d['total']/$maxD*100) : 0 }}%"></div>
                        </div>
                        <span class="bar-count">{{ $d['total'] }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Monthly trend --}}
            <div style="margin-top:1.25rem;">
                <p style="font-weight:700;font-size:.85rem;margin-bottom:.75rem;color:#374151;">Monthly Application Trend (Last 6 Months)</p>
                @php $maxM = max($a['monthly_trend']->pluck('count')->toArray() ?: [1]); @endphp
                <div class="trend-row">
                    @foreach($a['monthly_trend'] as $month)
                    @php $h = $maxM > 0 ? max(4, intval($month['count']/$maxM*60)) : 4; @endphp
                    <div class="trend-col" style="color:#3b82f6;">
                        <span style="font-size:.7rem;color:#374151;font-weight:600;">{{ $month['count'] }}</span>
                        <div class="trend-bar"style="height:{{ $h }}px;"></div>
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
        <div class="report-section-header" style="background: linear-gradient(135deg,#065f46,#10b981);">
            <h2 style="color:#fff;">👥 Intern Management Report</h2>
        </div>
        <div class="report-section-body">

            <div class="stat-grid stat-grid-4" style="margin-bottom:1.25rem;">
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
                <div>
                    <p style="font-weight:700;font-size:.85rem;margin-bottom:.75rem;color:#374151;">Interns per Batch</p>
                    @if(count($i['batch_breakdown']))
                    <table class="mini-table">
                        <thead><tr><th>Batch</th><th style="text-align:right;">Interns</th></tr></thead>
                        <tbody>
                            @foreach($i['batch_breakdown'] as $b)
                            <tr><td>{{ $b['name'] ?? 'N/A' }}</td><td style="text-align:right;"><span class="badge badge-green">{{ $b['count'] }}</span></td></tr>
                            @endforeach
                        </tbody>
                    </table>
                    @else
                    <p style="color:#94a3b8;font-size:.82rem;">No batch data available.</p>
                    @endif
                </div>

                {{-- Team breakdown --}}
                <div>
                    <p style="font-weight:700;font-size:.85rem;margin-bottom:.75rem;color:#374151;">Interns per Team</p>
                    @if(count($i['team_breakdown']))
                    <table class="mini-table">
                        <thead><tr><th>Team</th><th style="text-align:right;">Interns</th></tr></thead>
                        <tbody>
                            @foreach($i['team_breakdown'] as $tm)
                            <tr><td>{{ $tm['name'] ?? 'N/A' }}</td><td style="text-align:right;"><span class="badge badge-blue">{{ $tm['count'] }}</span></td></tr>
                            @endforeach
                        </tbody>
                    </table>
                    @else
                    <p style="color:#94a3b8;font-size:.82rem;">No team data available.</p>
                    @endif
                </div>
            </div>

            {{-- Monthly joining --}}
            <div style="margin-top:1.25rem;">
                <p style="font-weight:700;font-size:.85rem;margin-bottom:.75rem;color:#374151;">Monthly Joining Trend (Last 6 Months)</p>
                @php $maxJ = max($i['monthly_joining']->pluck('count')->toArray() ?: [1]); @endphp
                <div class="trend-row">
                    @foreach($i['monthly_joining'] as $month)
                    @php $h = $maxJ > 0 ? max(4, intval($month['count']/$maxJ*60)) : 4; @endphp
                    <div class="trend-col" style="color:#10b981;">
                        <span style="font-size:.7rem;color:#374151;font-weight:600;">{{ $month['count'] }}</span>
                        <div class="trend-bar"style="height:{{ $h }}px;"></div>
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
        <div class="report-section-header" style="background: linear-gradient(135deg,#78350f,#f59e0b);">
            <h2 style="color:#fff;">📝 Task Management Report</h2>
        </div>
        <div class="report-section-body">

            <div class="stat-grid stat-grid-5" style="margin-bottom:1.25rem;">
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
                <div class="stat-card">
                    <p style="font-weight:700;font-size:.82rem;margin-bottom:.75rem;color:#374151;">Priority Breakdown</p>
                    <div class="bar-list">
                        @php
                            $maxP = max($t['priority_high'], $t['priority_medium'], $t['priority_low'], 1);
                        @endphp
                        <div class="bar-row">
                            <span class="bar-label">High</span>
                            <div class="bar-track"><div class="bar-fill"style="background:#ef4444;width:{{ $maxP>0?($t['priority_high']/$maxP*100):0 }}%"></div></div>
                            <span class="bar-count">{{ $t['priority_high'] }}</span>
                        </div>
                        <div class="bar-row">
                            <span class="bar-label">Medium</span>
                            <div class="bar-track"><div class="bar-fill"style="background:#f59e0b;width:{{ $maxP>0?($t['priority_medium']/$maxP*100):0 }}%"></div></div>
                            <span class="bar-count">{{ $t['priority_medium'] }}</span>
                        </div>
                        <div class="bar-row">
                            <span class="bar-label">Low</span>
                            <div class="bar-track"><div class="bar-fill"style="background:#22c55e;width:{{ $maxP>0?($t['priority_low']/$maxP*100):0 }}%"></div></div>
                            <span class="bar-count">{{ $t['priority_low'] }}</span>
                        </div>
                    </div>
                </div>

                {{-- Submission rate --}}
                <div class="stat-card" style="display:flex;flex-direction:column;justify-content:center;align-items:center;gap:.5rem;">
                    <p style="font-weight:700;font-size:.82rem;color:#374151;">Submission Rate</p>
                    <div class="stat-value text-indigo" style="font-size:2.5rem;">{{ $t['submission_rate'] }}%</div>
                    <p style="font-size:.75rem;color:#64748b;">{{ $t['total_submissions'] }} / {{ $t['total_assignments'] }} assignments submitted</p>
                    <p style="font-size:.75rem;color:#64748b;">Avg Marks: <strong>{{ $t['avg_marks'] }}</strong></p>
                </div>

                {{-- Evaluation --}}
                <div class="stat-card">
                    <p style="font-weight:700;font-size:.82rem;margin-bottom:.75rem;color:#374151;">Evaluation Status</p>
                    <div class="bar-list">
                        @php $maxEv = max($t['evaluated'], $t['pending_evaluation'], 1); @endphp
                        <div class="bar-row">
                            <span class="bar-label">Evaluated</span>
                            <div class="bar-track"><div class="bar-fill"style="background:#22c55e;width:{{ $maxEv>0?($t['evaluated']/$maxEv*100):0 }}%"></div></div>
                            <span class="bar-count">{{ $t['evaluated'] }}</span>
                        </div>
                        <div class="bar-row">
                            <span class="bar-label">Pending</span>
                            <div class="bar-track"><div class="bar-fill"style="background:#f59e0b;width:{{ $maxEv>0?($t['pending_evaluation']/$maxEv*100):0 }}%"></div></div>
                            <span class="bar-count">{{ $t['pending_evaluation'] }}</span>
                        </div>
                    </div>

                    @if(count($t['grade_distribution']))
                    <p style="font-weight:600;font-size:.78rem;margin-top:.75rem;margin-bottom:.4rem;color:#374151;">Grade Distribution</p>
                    <div style="display:flex;flex-wrap:wrap;gap:.4rem;">
                        @foreach($t['grade_distribution'] as $grade => $cnt)
                        <span class="badge badge-purple">{{ $grade }}: {{ $cnt }}</span>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>

            {{-- Monthly tasks --}}
            <div style="margin-top:1.25rem;">
                <p style="font-weight:700;font-size:.85rem;margin-bottom:.75rem;color:#374151;">Monthly Task Creation (Last 6 Months)</p>
                @php $maxT = max($t['monthly_tasks']->pluck('count')->toArray() ?: [1]); @endphp
                <div class="trend-row">
                    @foreach($t['monthly_tasks'] as $month)
                    @php $h = $maxT > 0 ? max(4, intval($month['count']/$maxT*60)) : 4; @endphp
                    <div class="trend-col" style="color:#f59e0b;">
                        <span style="font-size:.7rem;color:#374151;font-weight:600;">{{ $month['count'] }}</span>
                        <div class="trend-bar"style="height:{{ $h }}px;"></div>
                        <span class="trend-lbl">{{ $month['month'] }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- Footer --}}
    <p style="text-align:center;font-size:.75rem;color:#94a3b8;margin-top:.5rem;">
        Report generated on {{ $generatedAt }} &nbsp;|&nbsp; IAPES – Internship Administration & Performance Evaluation System
    </p>

</x-filament-panels::page>
