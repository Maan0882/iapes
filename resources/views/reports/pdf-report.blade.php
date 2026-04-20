<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>IAPES Report</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: DejaVu Sans, Arial, sans-serif;
            font-size: 9.5px;
            color: #1a1a2e;
            background: #ffffff;
            line-height: 1.6;
        }

        /* ─────────────────────────────────────────
           HEADER
        ───────────────────────────────────────── */
        .header {
            background: #1a1a2e;
            color: #fff;
            padding: 24px 32px 20px;
        }
        .header-top {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }
        .header-org {
            font-size: 15px;
            font-weight: 700;
            letter-spacing: 0.3px;
            color: #ffffff;
        }
        .header-sub {
            font-size: 9px;
            color: #94a3b8;
            margin-top: 3px;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }
        .header-badge {
            background: #f7a93b;
            color: #1a1a2e;
            font-size: 8px;
            font-weight: 700;
            padding: 4px 12px;
            border-radius: 3px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .header-divider {
            border: none;
            border-top: 1px solid #2d2d4e;
            margin: 14px 0 12px;
        }
        .header-meta {
            display: flex;
            gap: 28px;
            font-size: 8px;
            color: #64748b;
        }
        .header-meta strong { color: #cbd5e1; }

        /* ─────────────────────────────────────────
           SECTION
        ───────────────────────────────────────── */
        .section {
            margin: 20px 28px;
            page-break-inside: avoid;
        }
        .section-title {
            font-size: 10px;
            font-weight: 700;
            color: #1a1a2e;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            padding-bottom: 7px;
            border-bottom: 2px solid #1a1a2e;
            margin-bottom: 14px;
        }
        .section-title .dot {
            width: 9px;
            height: 9px;
            border-radius: 2px;
            display: inline-block;
            margin-right: 6px;
            vertical-align: middle;
        }
        .dot-blue   { background: #3b82f6; }
        .dot-green  { background: #10b981; }
        .dot-yellow { background: #f59e0b; }

        /* ─────────────────────────────────────────
           KPI CARDS — table-based for dompdf
        ───────────────────────────────────────── */
        .kpi-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 8px 0;
            margin-bottom: 16px;
        }
        .kpi-table td {
            background: #f8fafc;
            border: 1px solid #e9eef5;
            border-radius: 6px;
            padding: 10px 8px;
            text-align: center;
            vertical-align: middle;
        }
        .kpi-value {
            font-size: 22px;
            font-weight: 900;
            line-height: 1;
            display: block;
        }
        .kpi-label {
            font-size: 7px;
            color: #64748b;
            margin-top: 4px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            display: block;
        }

        /* ─────────────────────────────────────────
           TWO / THREE COLUMN — table-based
        ───────────────────────────────────────── */
        .two-col-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 14px 0;
            margin-bottom: 14px;
        }
        .two-col-table > tr > td { vertical-align: top; width: 50%; }

        .three-col-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 10px 0;
            margin-bottom: 14px;
        }
        .three-col-table > tr > td { vertical-align: top; width: 33.3%; }

        /* ─────────────────────────────────────────
           SUBSECTION LABEL
        ───────────────────────────────────────── */
        .sub-label {
            font-size: 7.5px;
            font-weight: 700;
            color: #374151;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
            padding-bottom: 4px;
            border-bottom: 1px solid #e9eef5;
        }

        /* ─────────────────────────────────────────
           BAR ROWS
        ───────────────────────────────────────── */
        .bar-item { margin-bottom: 7px; }
        .bar-meta {
            display: flex;
            justify-content: space-between;
            margin-bottom: 3px;
        }
        .bar-name { font-size: 8px; color: #374151; }
        .bar-num  { font-size: 8px; font-weight: 700; color: #1a1a2e; }
        .bar-track {
            width: 100%;
            height: 7px;
            background: #f1f5f9;
            border-radius: 99px;
            overflow: hidden;
        }
        .bar-fill { height: 100%; border-radius: 99px; }

        /* ─────────────────────────────────────────
           TREND BARS — table-based (dompdf safe)
        ───────────────────────────────────────── */
        .trend-wrap { margin-top: 4px; }
        .trend-table {
            width: 100%;
            border-collapse: collapse;
        }
        .trend-table td { text-align: center; padding: 0 3px; }
        .trend-num-cell {
            font-size: 7px;
            font-weight: 700;
            color: #374151;
            padding-bottom: 2px;
            vertical-align: bottom;
        }
        .trend-bar-cell { vertical-align: bottom; }
        .trend-bar-inner {
            margin: 0 auto;
            width: 65%;
            border-radius: 3px 3px 0 0;
            min-height: 4px;
        }
        .trend-lbl-cell {
            font-size: 6.5px;
            color: #94a3b8;
            padding-top: 4px;
            border-top: 1px solid #e9eef5;
            vertical-align: top;
        }

        /* ─────────────────────────────────────────
           DATA TABLE
        ───────────────────────────────────────── */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 8.5px;
        }
        .data-table th {
            background: #f1f5f9;
            padding: 5px 9px;
            text-align: left;
            font-weight: 700;
            color: #374151;
            border-bottom: 2px solid #e2e8f0;
            font-size: 7.5px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        .data-table td {
            padding: 5px 9px;
            border-bottom: 1px solid #f1f5f9;
            color: #374151;
        }
        .data-table tr:last-child td { border-bottom: none; }
        .data-table tr:nth-child(even) td { background: #fafbfc; }

        /* ─────────────────────────────────────────
           INLINE STAT BOXES
        ───────────────────────────────────────── */
        .stat-row-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 8px 0;
            margin-bottom: 10px;
        }
        .stat-box {
            background: #f8fafc;
            border: 1px solid #e9eef5;
            border-radius: 5px;
            padding: 8px;
            text-align: center;
        }
        .stat-box-val { font-size: 17px; font-weight: 900; line-height: 1; display: block; }
        .stat-box-lbl { font-size: 7px; color: #64748b; font-weight: 600; text-transform: uppercase; margin-top: 3px; display: block; }

        /* ─────────────────────────────────────────
           COLORS
        ───────────────────────────────────────── */
        .c-blue   { color: #2563eb; }
        .c-green  { color: #16a34a; }
        .c-amber  { color: #d97706; }
        .c-red    { color: #dc2626; }
        .c-purple { color: #7c3aed; }
        .c-indigo { color: #4f46e5; }
        .c-teal   { color: #0d9488; }
        .c-orange { color: #ea580c; }
        .c-slate  { color: #475569; }

        /* ─────────────────────────────────────────
           MISC
        ───────────────────────────────────────── */
        .divider {
            border: none;
            border-top: 1px solid #e9eef5;
            margin: 12px 0;
        }
        .avg-box {
            margin-top: 10px;
            padding: 7px 9px;
            background: #f8fafc;
            border: 1px solid #e9eef5;
            border-radius: 5px;
        }
        .avg-box-lbl { font-size: 7.5px; color: #64748b; text-transform: uppercase; font-weight: 700; display: block; }
        .avg-box-val { font-size: 16px; font-weight: 900; }

        /* ─────────────────────────────────────────
           FOOTER
        ───────────────────────────────────────── */
        .footer {
            margin: 24px 28px 16px;
            padding-top: 10px;
            border-top: 1px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            font-size: 7.5px;
            color: #94a3b8;
        }

        .page-break { page-break-after: always; }
    </style>
</head>
<body>

{{-- ══════════════════════════
     HEADER
══════════════════════════ --}}
<div class="header">
    <div class="header-top">
        <div>
            <div class="header-org">IAPES</div>
            <div class="header-sub">Internship Administration &amp; Performance Evaluation System</div>
        </div>
        <div class="header-badge">
            @if($type === 'full') Full Report
            @elseif($type === 'application') Application Report
            @elseif($type === 'intern') Intern Report
            @elseif($type === 'task') Task Report
            @endif
        </div>
    </div>
    <hr class="header-divider">
    <div class="header-meta">
        <div><strong>Generated</strong>&nbsp;&nbsp;{{ $generatedAt }}</div>
        <div><strong>Classification</strong>&nbsp;&nbsp;Confidential — Internal Use Only</div>
        <div><strong>System</strong>&nbsp;&nbsp;IAPES Admin Panel</div>
    </div>
</div>


{{-- ══════════════════════════════════════════════
     SECTION 1 — APPLICATION & INTERVIEW
══════════════════════════════════════════════ --}}
@if(isset($applicationStats))
@php $a = $applicationStats; @endphp

<div class="section">
    <div class="section-title">
        <span class="dot dot-blue"></span>Application &amp; Interview
    </div>

    <table class="kpi-table">
        <tr>
            <td><span class="kpi-value c-blue">{{ $a['total'] }}</span><span class="kpi-label">Total Applications</span></td>
            <td><span class="kpi-value c-amber">{{ $a['shortlisted'] }}</span><span class="kpi-label">Shortlisted</span></td>
            <td><span class="kpi-value c-green">{{ $a['selected'] }}</span><span class="kpi-label">Selected</span></td>
            <td><span class="kpi-value c-red">{{ $a['rejected'] }}</span><span class="kpi-label">Rejected</span></td>
            <td><span class="kpi-value c-indigo">{{ $a['conversion_rate'] }}%</span><span class="kpi-label">Conversion Rate</span></td>
            <td><span class="kpi-value c-slate">{{ $a['avg_cgpa'] }}</span><span class="kpi-label">Avg CGPA</span></td>
        </tr>
    </table>

    <table class="two-col-table">
        <tr>
            <td>
                <div class="sub-label">Application Status Breakdown</div>
                @php
                    $statuses = [
                        ['label' => 'Applied',             'color' => '#3b82f6', 'count' => $a['applied']],
                        ['label' => 'Interview Scheduled', 'color' => '#8b5cf6', 'count' => $a['interview_scheduled']],
                        ['label' => 'Interviewed',         'color' => '#f59e0b', 'count' => $a['interviewed']],
                        ['label' => 'Shortlisted',         'color' => '#10b981', 'count' => $a['shortlisted']],
                        ['label' => 'Rejected',            'color' => '#ef4444', 'count' => $a['rejected']],
                    ];
                    $maxS = max(array_column($statuses, 'count') ?: [1]);
                @endphp
                @foreach($statuses as $s)
                <div class="bar-item">
                    <div class="bar-meta">
                        <span class="bar-name">{{ $s['label'] }}</span>
                        <span class="bar-num">{{ $s['count'] }}</span>
                    </div>
                    <div class="bar-track">
                        <div class="bar-fill"style="background:{{ $s['color'] }};width:{{ $maxS > 0 ? ($s['count']/$maxS*100) : 0 }}%"></div>
                    </div>
                </div>
                @endforeach
            </td>

            <td>
                <div class="sub-label">Interview Attendance &amp; Result</div>
                <table class="stat-row-table">
                    <tr>
                        <td><div class="stat-box"><span class="stat-box-val c-green">{{ $a['interview_present'] }}</span><span class="stat-box-lbl">Present</span></div></td>
                        <td><div class="stat-box"><span class="stat-box-val c-red">{{ $a['interview_absent'] }}</span><span class="stat-box-lbl">Absent</span></div></td>
                        <td><div class="stat-box"><span class="stat-box-val c-green">{{ $a['selected'] }}</span><span class="stat-box-lbl">Selected</span></div></td>
                        <td><div class="stat-box"><span class="stat-box-val c-red">{{ $a['not_selected'] }}</span><span class="stat-box-lbl">Not Selected</span></div></td>
                    </tr>
                </table>

                @if(!empty($a['duration_breakdown']))
                <div class="sub-label" style="margin-top:12px;">Top Applied Durations</div>
                @php $maxDur = max(array_column($a['duration_breakdown'], 'total') ?: [1]); @endphp
                @foreach(array_slice($a['duration_breakdown'], 0, 4) as $d)
                <div class="bar-item">
                    <div class="bar-meta">
                        <span class="bar-name">{{ $d['duration'] }} {{ ucfirst($d['duration_unit']) }}</span>
                        <span class="bar-num">{{ $d['total'] }}</span>
                    </div>
                    <div class="bar-track">
                        <div class="bar-fill"style="background:#6366f1;width:{{ $maxDur > 0 ? ($d['total']/$maxDur*100) : 0 }}%"></div>
                    </div>
                </div>
                @endforeach
                @endif
            </td>
        </tr>
    </table>

    @if(!empty($a['domain_breakdown']))
    <hr class="divider">
    <div class="sub-label">Top Internship Domains</div>
    @php $maxD = max(array_column($a['domain_breakdown'], 'total') ?: [1]); @endphp
    @foreach($a['domain_breakdown'] as $d)
    <div class="bar-item">
        <div class="bar-meta">
            <span class="bar-name">{{ $d['domain'] }}</span>
            <span class="bar-num">{{ $d['total'] }}</span>
        </div>
        <div class="bar-track">
            <div class="bar-fill"style="background:#6366f1;width:{{ $maxD > 0 ? ($d['total']/$maxD*100) : 0 }}%"></div>
        </div>
    </div>
    @endforeach
    @endif

    <hr class="divider">
    <div class="sub-label">Monthly Application Trend — Last 6 Months</div>
    @php
        $mTrend = $a['monthly_trend'];
        $maxM   = max($mTrend->pluck('count')->toArray() ?: [1]);
    @endphp
    <div class="trend-wrap">
        <table class="trend-table">
            <tr>@foreach($mTrend as $m)<td class="trend-num-cell">{{ $m['count'] }}</td>@endforeach</tr>
            <tr>
                @foreach($mTrend as $m)
                @php $h = $maxM > 0 ? max(4, intval($m['count']/$maxM*44)) : 4; @endphp
                <td class="trend-bar-cell"><div class="trend-bar-inner"style="height:{{ $h }}px;background:#3b82f6;"></div></td>
                @endforeach
            </tr>
            <tr>@foreach($mTrend as $m)<td class="trend-lbl-cell">{{ $m['month'] }}</td>@endforeach</tr>
        </table>
    </div>
</div>
@endif


{{-- ══════════════════════════════════════════════
     SECTION 2 — INTERN MANAGEMENT
══════════════════════════════════════════════ --}}
@if(isset($internStats))
@php $i = $internStats; @endphp

@if($type === 'full')<div class="page-break"></div>@endif

<div class="section">
    <div class="section-title">
        <span class="dot dot-green"></span>Intern Management
    </div>

    @php $activeRate = $i['total'] > 0 ? round($i['active'] / $i['total'] * 100) : 0; @endphp
    <table class="kpi-table">
        <tr>
            <td><span class="kpi-value c-teal">{{ $i['total'] }}</span><span class="kpi-label">Total Interns</span></td>
            <td><span class="kpi-value c-green">{{ $i['active'] }}</span><span class="kpi-label">Active</span></td>
            <td><span class="kpi-value c-slate">{{ $i['inactive'] }}</span><span class="kpi-label">Inactive</span></td>
            <td><span class="kpi-value c-blue">{{ $i['with_project'] }}</span><span class="kpi-label">With Project</span></td>
            <td><span class="kpi-value c-amber">{{ $i['without_project'] }}</span><span class="kpi-label">Without Project</span></td>
            <td><span class="kpi-value c-green">{{ $activeRate }}%</span><span class="kpi-label">Active Rate</span></td>
        </tr>
    </table>

    <table class="two-col-table">
        <tr>
            <td>
                <div class="sub-label">Interns per Batch</div>
                @if(!empty($i['batch_breakdown']))
                <table class="data-table">
                    <thead><tr><th>Batch Name</th><th style="text-align:right;">Interns</th></tr></thead>
                    <tbody>
                        @foreach($i['batch_breakdown'] as $b)
                        <tr>
                            <td>{{ $b['name'] ?? 'N/A' }}</td>
                            <td style="text-align:right;font-weight:700;" class="c-green">{{ $b['count'] }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <p style="font-size:8px;color:#94a3b8;margin-top:4px;">No batch data available.</p>
                @endif
            </td>
            <td>
                <div class="sub-label">Interns per Team</div>
                @if(!empty($i['team_breakdown']))
                <table class="data-table">
                    <thead><tr><th>Team Name</th><th style="text-align:right;">Interns</th></tr></thead>
                    <tbody>
                        @foreach($i['team_breakdown'] as $tm)
                        <tr>
                            <td>{{ $tm['name'] ?? 'N/A' }}</td>
                            <td style="text-align:right;font-weight:700;" class="c-blue">{{ $tm['count'] }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <p style="font-size:8px;color:#94a3b8;margin-top:4px;">No team data available.</p>
                @endif
            </td>
        </tr>
    </table>

    <hr class="divider">
    <div class="sub-label">Monthly Joining Trend — Last 6 Months</div>
    @php
        $jTrend = $i['monthly_joining'];
        $maxJ   = max($jTrend->pluck('count')->toArray() ?: [1]);
    @endphp
    <div class="trend-wrap">
        <table class="trend-table">
            <tr>@foreach($jTrend as $m)<td class="trend-num-cell">{{ $m['count'] }}</td>@endforeach</tr>
            <tr>
                @foreach($jTrend as $m)
                @php $h = $maxJ > 0 ? max(4, intval($m['count']/$maxJ*44)) : 4; @endphp
                <td class="trend-bar-cell"><div class="trend-bar-inner"style="height:{{ $h }}px;background:#10b981;"></div></td>
                @endforeach
            </tr>
            <tr>@foreach($jTrend as $m)<td class="trend-lbl-cell">{{ $m['month'] }}</td>@endforeach</tr>
        </table>
    </div>
</div>
@endif


{{-- ══════════════════════════════════════════════
     SECTION 3 — TASK MANAGEMENT
══════════════════════════════════════════════ --}}
@if(isset($taskStats))
@php $t = $taskStats; @endphp

@if($type === 'full')<div class="page-break"></div>@endif

<div class="section">
    <div class="section-title">
        <span class="dot dot-yellow"></span>Task Management
    </div>

    <table class="kpi-table">
        <tr>
            <td><span class="kpi-value c-orange">{{ $t['total'] }}</span><span class="kpi-label">Total Tasks</span></td>
            <td><span class="kpi-value c-blue">{{ $t['total_assignments'] }}</span><span class="kpi-label">Assignments</span></td>
            <td><span class="kpi-value c-green">{{ $t['total_submissions'] }}</span><span class="kpi-label">Submissions</span></td>
            <td><span class="kpi-value c-indigo">{{ $t['submission_rate'] }}%</span><span class="kpi-label">Submission Rate</span></td>
            <td><span class="kpi-value c-red">{{ $t['overdue'] }}</span><span class="kpi-label">Overdue</span></td>
            <td><span class="kpi-value c-amber">{{ $t['due_soon'] }}</span><span class="kpi-label">Due in 7 Days</span></td>
        </tr>
    </table>

    <table class="three-col-table">
        <tr>
            <td>
                <div class="sub-label">Priority Breakdown</div>
                @php $maxP = max($t['priority_high'], $t['priority_medium'], $t['priority_low'], 1); @endphp
                <div class="bar-item">
                    <div class="bar-meta"><span class="bar-name">High</span><span class="bar-num c-red">{{ $t['priority_high'] }}</span></div>
                    <div class="bar-track"><div class="bar-fill"style="background:#ef4444;width:{{ $maxP > 0 ? ($t['priority_high']/$maxP*100) : 0 }}%"></div></div>
                </div>
                <div class="bar-item">
                    <div class="bar-meta"><span class="bar-name">Medium</span><span class="bar-num c-amber">{{ $t['priority_medium'] }}</span></div>
                    <div class="bar-track"><div class="bar-fill"style="background:#f59e0b;width:{{ $maxP > 0 ? ($t['priority_medium']/$maxP*100) : 0 }}%"></div></div>
                </div>
                <div class="bar-item">
                    <div class="bar-meta"><span class="bar-name">Low</span><span class="bar-num c-green">{{ $t['priority_low'] }}</span></div>
                    <div class="bar-track"><div class="bar-fill"style="background:#22c55e;width:{{ $maxP > 0 ? ($t['priority_low']/$maxP*100) : 0 }}%"></div></div>
                </div>
            </td>

            <td>
                <div class="sub-label">Evaluation Status</div>
                @php $maxEv = max($t['evaluated'], $t['pending_evaluation'], 1); @endphp
                <div class="bar-item">
                    <div class="bar-meta"><span class="bar-name">Evaluated</span><span class="bar-num c-green">{{ $t['evaluated'] }}</span></div>
                    <div class="bar-track"><div class="bar-fill"style="background:#22c55e;width:{{ $maxEv > 0 ? ($t['evaluated']/$maxEv*100) : 0 }}%"></div></div>
                </div>
                <div class="bar-item">
                    <div class="bar-meta"><span class="bar-name">Pending</span><span class="bar-num c-amber">{{ $t['pending_evaluation'] }}</span></div>
                    <div class="bar-track"><div class="bar-fill"style="background:#f59e0b;width:{{ $maxEv > 0 ? ($t['pending_evaluation']/$maxEv*100) : 0 }}%"></div></div>
                </div>
                <div class="avg-box">
                    <span class="avg-box-lbl">Average Marks</span>
                    <span class="avg-box-val c-indigo">{{ $t['avg_marks'] }}</span>
                </div>
            </td>

            <td>
                <div class="sub-label">Grade Distribution</div>
                @if(!empty($t['grade_distribution']))
                <table class="data-table">
                    <thead><tr><th>Grade</th><th style="text-align:right;">Count</th></tr></thead>
                    <tbody>
                        @foreach($t['grade_distribution'] as $grade => $cnt)
                        <tr>
                            <td style="font-weight:700;">{{ $grade }}</td>
                            <td style="text-align:right;font-weight:700;" class="c-purple">{{ $cnt }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <p style="font-size:8px;color:#94a3b8;margin-top:4px;">No grade data available yet.</p>
                @endif
            </td>
        </tr>
    </table>

    @if(!empty($t['submission_status']))
    <hr class="divider">
    <div class="sub-label">Submission Status Breakdown</div>
    @php $maxSub = max(array_values($t['submission_status']) ?: [1]); @endphp
    @foreach($t['submission_status'] as $status => $cnt)
    @php
        $sColors = ['submitted' => '#22c55e', 'pending' => '#f59e0b', 'late' => '#ef4444', 'reviewed' => '#3b82f6'];
        $sColor  = $sColors[$status] ?? '#6366f1';
    @endphp
    <div class="bar-item">
        <div class="bar-meta">
            <span class="bar-name">{{ ucfirst(str_replace('_', ' ', $status)) }}</span>
            <span class="bar-num">{{ $cnt }}</span>
        </div>
        <div class="bar-track">
            <div class="bar-fill"style="background:{{ $sColor }};width:{{ $maxSub > 0 ? ($cnt/$maxSub*100) : 0 }}%"></div>
        </div>
    </div>
    @endforeach
    @endif

    <hr class="divider">
    <div class="sub-label">Monthly Task Creation — Last 6 Months</div>
    @php
        $tTrend = $t['monthly_tasks'];
        $maxT   = max($tTrend->pluck('count')->toArray() ?: [1]);
    @endphp
    <div class="trend-wrap">
        <table class="trend-table">
            <tr>@foreach($tTrend as $m)<td class="trend-num-cell">{{ $m['count'] }}</td>@endforeach</tr>
            <tr>
                @foreach($tTrend as $m)
                @php $h = $maxT > 0 ? max(4, intval($m['count']/$maxT*44)) : 4; @endphp
                <td class="trend-bar-cell"><div class="trend-bar-inner"style="height:{{ $h }}px;background:#f59e0b;"></div></td>
                @endforeach
            </tr>
            <tr>@foreach($tTrend as $m)<td class="trend-lbl-cell">{{ $m['month'] }}</td>@endforeach</tr>
        </table>
    </div>
</div>
@endif


{{-- ── FOOTER ── --}}
<div class="footer">
    <span>IAPES &mdash; Internship Administration &amp; Performance Evaluation System</span>
    <span>Generated: {{ $generatedAt }}</span>
</div>

</body>
</html>