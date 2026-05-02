<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>IAPES Report</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 9.5px;
            color: #1e293b;
            background: #ffffff;
            line-height: 1.6;
        }

        /* ─────────────────────────────────────────
           COLORS & VARIABLES (Hardcoded for dompdf)
           Primary (Petrol Blue): #0c4a6e
           Secondary (Sky Cyan): #0ea5e9
           Accent (Amber): #f59e0b
           Success (Green): #10b981
           Danger (Red): #ef4444
        ───────────────────────────────────────── */

        /* ─────────────────────────────────────────
           HEADER
        ───────────────────────────────────────── */
        .header {
            background: #0c4a6e;
            color: #fff;
            padding: 24px 32px 20px;
            border-bottom: 4px solid #0ea5e9;
        }
        .header-top {
            display: table;
            width: 100%;
        }
        .header-top-left {
            display: table-cell;
            vertical-align: top;
        }
        .header-top-right {
            display: table-cell;
            text-align: right;
            vertical-align: top;
        }
        .header-org {
            font-size: 18px;
            font-weight: 800;
            letter-spacing: 1px;
            color: #ffffff;
            text-transform: uppercase;
        }
        .header-sub {
            font-size: 9px;
            color: #bae6fd;
            margin-top: 4px;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }
        .header-badge {
            background: #0ea5e9;
            color: #ffffff;
            font-size: 9px;
            font-weight: 800;
            padding: 5px 14px;
            border-radius: 4px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: inline-block;
        }
        .header-divider {
            border: none;
            border-top: 1px solid #0284c7;
            margin: 16px 0 12px;
        }
        .header-meta {
            font-size: 8px;
            color: #e0f2fe;
            display: table;
            width: 100%;
        }
        .header-meta td {
            padding-right: 30px;
        }
        .header-meta strong { color: #ffffff; text-transform: uppercase; letter-spacing: 0.5px; }

        /* ─────────────────────────────────────────
           SECTION
        ───────────────────────────────────────── */
        .section {
            margin: 24px 32px;
            page-break-inside: avoid;
        }
        .section-title {
            font-size: 11px;
            font-weight: 800;
            color: #0c4a6e;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding-bottom: 6px;
            border-bottom: 2px solid #e2e8f0;
            margin-bottom: 16px;
        }
        .section-title .dot {
            width: 8px;
            height: 8px;
            display: inline-block;
            margin-right: 8px;
            vertical-align: middle;
            background: #0ea5e9;
        }

        /* ─────────────────────────────────────────
           KPI CARDS
        ───────────────────────────────────────── */
        .kpi-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 10px 0;
            margin-bottom: 20px;
            margin-left: -5px;
            margin-right: -5px;
        }
        .kpi-table td {
            background: #f8fafc;
            border: 1px solid #cbd5e1;
            border-top: 3px solid #0ea5e9;
            padding: 12px 10px;
            text-align: center;
            vertical-align: middle;
            width: 16.66%;
        }
        .kpi-value {
            font-size: 20px;
            font-weight: 900;
            line-height: 1;
            display: block;
            color: #0c4a6e;
        }
        .kpi-label {
            font-size: 7px;
            color: #64748b;
            margin-top: 6px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: block;
        }

        /* ─────────────────────────────────────────
           LAYOUT TABLES
        ───────────────────────────────────────── */
        .two-col-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 20px 0;
            margin-bottom: 16px;
            margin-left: -10px;
            margin-right: -10px;
        }
        .two-col-table > tr > td { vertical-align: top; width: 50%; }

        .three-col-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 16px 0;
            margin-bottom: 16px;
            margin-left: -8px;
            margin-right: -8px;
        }
        .three-col-table > tr > td { vertical-align: top; width: 33.3%; }

        /* ─────────────────────────────────────────
           SUBSECTION LABEL
        ───────────────────────────────────────── */
        .sub-label {
            font-size: 8px;
            font-weight: 800;
            color: #334155;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 10px;
            padding-bottom: 4px;
            border-bottom: 1px dashed #cbd5e1;
        }

        /* ─────────────────────────────────────────
           BAR ROWS (Table Based for Safety)
        ───────────────────────────────────────── */
        .bar-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
        }
        .bar-table td {
            padding: 4px 0;
            vertical-align: middle;
        }
        .bar-name-cell {
            width: 40%;
            font-size: 8px;
            color: #475569;
            font-weight: 600;
        }
        .bar-track-cell {
            width: 50%;
            padding: 0 10px;
        }
        .bar-num-cell {
            width: 10%;
            text-align: right;
            font-size: 8px;
            font-weight: 800;
            color: #0c4a6e;
        }
        .bar-track {
            width: 100%;
            height: 8px;
            background: #e2e8f0;
            overflow: hidden;
        }
        .bar-fill { height: 100%; }

        /* ─────────────────────────────────────────
           TREND BARS
        ───────────────────────────────────────── */
        .trend-wrap { margin-top: 6px; background: #f8fafc; padding: 10px; border: 1px solid #e2e8f0; }
        .trend-table {
            width: 100%;
            border-collapse: collapse;
        }
        .trend-table td { text-align: center; padding: 0 4px; }
        .trend-num-cell {
            font-size: 8px;
            font-weight: 800;
            color: #0c4a6e;
            padding-bottom: 4px;
            vertical-align: bottom;
        }
        .trend-bar-cell { vertical-align: bottom; height: 50px; }
        .trend-bar-inner {
            margin: 0 auto;
            width: 70%;
            min-height: 4px;
        }
        .trend-lbl-cell {
            font-size: 7px;
            color: #64748b;
            padding-top: 6px;
            font-weight: 700;
            text-transform: uppercase;
            vertical-align: top;
        }

        /* ─────────────────────────────────────────
           DATA TABLE
        ───────────────────────────────────────── */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 8.5px;
            border: 1px solid #cbd5e1;
        }
        .data-table th {
            background: #f1f5f9;
            padding: 8px 10px;
            text-align: left;
            font-weight: 800;
            color: #334155;
            border-bottom: 1px solid #cbd5e1;
            font-size: 7.5px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .data-table td {
            padding: 7px 10px;
            border-bottom: 1px solid #e2e8f0;
            color: #475569;
            font-weight: 600;
        }
        .data-table tr:last-child td { border-bottom: none; }
        .data-table tr:nth-child(even) td { background: #f8fafc; }

        /* ─────────────────────────────────────────
           INLINE STAT BOXES
        ───────────────────────────────────────── */
        .stat-row-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 8px 0;
            margin-bottom: 12px;
            margin-left: -4px;
            margin-right: -4px;
        }
        .stat-box {
            background: #ffffff;
            border: 1px solid #cbd5e1;
            padding: 10px 8px;
            text-align: center;
        }
        .stat-box-val { font-size: 18px; font-weight: 900; line-height: 1; display: block; }
        .stat-box-lbl { font-size: 7px; color: #64748b; font-weight: 700; text-transform: uppercase; margin-top: 5px; display: block; letter-spacing: 0.5px; }

        /* ─────────────────────────────────────────
           COLORS
        ───────────────────────────────────────── */
        .c-primary { color: #0c4a6e; }
        .c-cyan   { color: #0ea5e9; }
        .c-green  { color: #10b981; }
        .c-amber  { color: #f59e0b; }
        .c-red    { color: #ef4444; }
        .c-slate  { color: #475569; }

        .bg-primary { background: #0c4a6e; }
        .bg-cyan   { background: #0ea5e9; }
        .bg-green  { background: #10b981; }
        .bg-amber  { background: #f59e0b; }
        .bg-red    { background: #ef4444; }

        /* ─────────────────────────────────────────
           MISC
        ───────────────────────────────────────── */
        .divider {
            border: none;
            border-top: 1px solid #e2e8f0;
            margin: 16px 0;
        }
        .avg-box {
            margin-top: 12px;
            padding: 10px;
            background: #f0f9ff;
            border: 1px solid #bae6fd;
            border-left: 4px solid #0ea5e9;
        }
        .avg-box-lbl { font-size: 8px; color: #0369a1; text-transform: uppercase; font-weight: 800; display: block; letter-spacing: 0.5px; }
        .avg-box-val { font-size: 18px; font-weight: 900; color: #0c4a6e; margin-top: 4px; display: block; }

        /* ─────────────────────────────────────────
           FOOTER
        ───────────────────────────────────────── */
        .footer {
            margin: 30px 32px 20px;
            padding-top: 12px;
            border-top: 1px solid #cbd5e1;
            font-size: 8px;
            color: #64748b;
            font-weight: 600;
        }
        .footer-table { width: 100%; }
        .footer-table td { vertical-align: top; }
        .footer-table .right { text-align: right; }

        .page-break { page-break-after: always; }
    </style>
</head>
<body>

{{-- ══════════════════════════
     HEADER
══════════════════════════ --}}
<div class="header">
    <div class="header-top">
        <div class="header-top-left">
            <div class="header-org">IAPES Analytical Report</div>
            <div class="header-sub">Internship Administration &amp; Performance Evaluation System</div>
        </div>
        <div class="header-top-right">
            <div class="header-badge">
                @if($type === 'full') Comprehensive Report
                @elseif($type === 'application') Application Report
                @elseif($type === 'intern') Intern Report
                @elseif($type === 'task') Task Report
                @endif
            </div>
        </div>
    </div>
    <hr class="header-divider">
    <table class="header-meta">
        <tr>
            <td><strong>Generated On:</strong> {{ $generatedAt }}</td>
            <td><strong>Classification:</strong> Internal Use Only</td>
            <td><strong>System:</strong> IAPES Analytics Engine</td>
        </tr>
    </table>
</div>


{{-- ══════════════════════════════════════════════
     SECTION 1 — APPLICATION & INTERVIEW
══════════════════════════════════════════════ --}}
@if(isset($applicationStats))
@php $a = $applicationStats; @endphp

<div class="section">
    <div class="section-title">
        <span class="dot"></span>Application &amp; Interview Analytics
    </div>

    <table class="kpi-table">
        <tr>
            <td style="border-top-color: #0c4a6e;"><span class="kpi-value c-primary">{{ $a['total'] }}</span><span class="kpi-label">Total Applications</span></td>
            <td style="border-top-color: #f59e0b;"><span class="kpi-value c-amber">{{ $a['shortlisted'] }}</span><span class="kpi-label">Shortlisted</span></td>
            <td style="border-top-color: #10b981;"><span class="kpi-value c-green">{{ $a['selected'] }}</span><span class="kpi-label">Selected</span></td>
            <td style="border-top-color: #ef4444;"><span class="kpi-value c-red">{{ $a['rejected'] }}</span><span class="kpi-label">Rejected</span></td>
            <td style="border-top-color: #0ea5e9;"><span class="kpi-value c-cyan">{{ $a['conversion_rate'] }}%</span><span class="kpi-label">Conversion Rate</span></td>
            <td style="border-top-color: #64748b;"><span class="kpi-value c-slate">{{ $a['avg_cgpa'] }}</span><span class="kpi-label">Avg CGPA</span></td>
        </tr>
    </table>

    <table class="two-col-table">
        <tr>
            <td>
                <div class="sub-label">Application Status Breakdown</div>
                @php
                    $statuses = [
                        ['label' => 'Applied',             'color' => '#0c4a6e', 'count' => $a['applied']],
                        ['label' => 'Interview Scheduled', 'color' => '#0ea5e9', 'count' => $a['interview_scheduled']],
                        ['label' => 'Interviewed',         'color' => '#f59e0b', 'count' => $a['interviewed']],
                        ['label' => 'Shortlisted',         'color' => '#10b981', 'count' => $a['shortlisted']],
                        ['label' => 'Rejected',            'color' => '#ef4444', 'count' => $a['rejected']],
                    ];
                    $maxS = max(array_column($statuses, 'count') ?: [1]);
                @endphp
                
                <table class="bar-table">
                    @foreach($statuses as $s)
                    <tr>
                        <td class="bar-name-cell">{{ $s['label'] }}</td>
                        <td class="bar-track-cell">
                            <div class="bar-track">
                                <div class="bar-fill"style="background:{{ $s['color'] }};width:{{ $maxS > 0 ? ($s['count']/$maxS*100) : 0 }}%"></div>
                            </div>
                        </td>
                        <td class="bar-num-cell">{{ $s['count'] }}</td>
                    </tr>
                    @endforeach
                </table>
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
                <div class="sub-label" style="margin-top:16px;">Top Applied Durations</div>
                @php $maxDur = max(array_column($a['duration_breakdown'], 'total') ?: [1]); @endphp
                <table class="bar-table">
                    @foreach(array_slice($a['duration_breakdown'], 0, 4) as $d)
                    <tr>
                        <td class="bar-name-cell">{{ $d['duration'] }} {{ ucfirst($d['duration_unit']) }}</td>
                        <td class="bar-track-cell">
                            <div class="bar-track">
                                <div class="bar-fill bg-cyan"style="width:{{ $maxDur > 0 ? ($d['total']/$maxDur*100) : 0 }}%"></div>
                            </div>
                        </td>
                        <td class="bar-num-cell">{{ $d['total'] }}</td>
                    </tr>
                    @endforeach
                </table>
                @endif
            </td>
        </tr>
    </table>

    @if(!empty($a['domain_breakdown']))
    <hr class="divider">
    <div class="sub-label">Top Internship Domains</div>
    @php $maxD = max(array_column($a['domain_breakdown'], 'total') ?: [1]); @endphp
    <table class="bar-table">
        @foreach(array_slice($a['domain_breakdown'], 0, 6) as $d)
        <tr>
            <td class="bar-name-cell" style="width: 25%;">{{ $d['domain'] }}</td>
            <td class="bar-track-cell" style="width: 65%;">
                <div class="bar-track">
                    <div class="bar-fill bg-primary"style="width:{{ $maxD > 0 ? ($d['total']/$maxD*100) : 0 }}%"></div>
                </div>
            </td>
            <td class="bar-num-cell" style="width: 10%;">{{ $d['total'] }}</td>
        </tr>
        @endforeach
    </table>
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
                @php $h = $maxM > 0 ? max(4, intval($m['count']/$maxM*50)) : 4; @endphp
                <td class="trend-bar-cell"><div class="trend-bar-inner bg-cyan"style="height:{{ $h }}px;"></div></td>
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
        <span class="dot" style="background:#10b981;"></span>Intern Management Analytics
    </div>

    @php $activeRate = $i['total'] > 0 ? round($i['active'] / $i['total'] * 100) : 0; @endphp
    <table class="kpi-table">
        <tr>
            <td style="border-top-color: #0c4a6e;"><span class="kpi-value c-primary">{{ $i['total'] }}</span><span class="kpi-label">Total Interns</span></td>
            <td style="border-top-color: #10b981;"><span class="kpi-value c-green">{{ $i['active'] }}</span><span class="kpi-label">Active</span></td>
            <td style="border-top-color: #64748b;"><span class="kpi-value c-slate">{{ $i['inactive'] }}</span><span class="kpi-label">Inactive</span></td>
            <td style="border-top-color: #0ea5e9;"><span class="kpi-value c-cyan">{{ $i['with_project'] }}</span><span class="kpi-label">With Project</span></td>
            <td style="border-top-color: #f59e0b;"><span class="kpi-value c-amber">{{ $i['without_project'] }}</span><span class="kpi-label">Without Project</span></td>
            <td style="border-top-color: #10b981;"><span class="kpi-value c-green">{{ $activeRate }}%</span><span class="kpi-label">Active Rate</span></td>
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
                            <td style="text-align:right;font-weight:800;" class="c-primary">{{ $b['count'] }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <p style="font-size:8px;color:#94a3b8;margin-top:6px;font-weight:600;">No batch data available.</p>
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
                            <td style="text-align:right;font-weight:800;" class="c-cyan">{{ $tm['count'] }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <p style="font-size:8px;color:#94a3b8;margin-top:6px;font-weight:600;">No team data available.</p>
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
                @php $h = $maxJ > 0 ? max(4, intval($m['count']/$maxJ*50)) : 4; @endphp
                <td class="trend-bar-cell"><div class="trend-bar-inner bg-green"style="height:{{ $h }}px;"></div></td>
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
        <span class="dot" style="background:#f59e0b;"></span>Task Management Analytics
    </div>

    <table class="kpi-table">
        <tr>
            <td style="border-top-color: #0c4a6e; width: 16.66%;"><span class="kpi-value c-primary">{{ $t['total'] }}</span><span class="kpi-label">Total Tasks</span></td>
            <td style="border-top-color: #0ea5e9; width: 16.66%;"><span class="kpi-value c-cyan">{{ $t['total_assignments'] }}</span><span class="kpi-label">Assignments</span></td>
            <td style="border-top-color: #10b981; width: 16.66%;"><span class="kpi-value c-green">{{ $t['total_submissions'] }}</span><span class="kpi-label">Submissions</span></td>
            <td style="border-top-color: #0c4a6e; width: 16.66%;"><span class="kpi-value c-primary">{{ $t['submission_rate'] }}%</span><span class="kpi-label">Submission Rate</span></td>
            <td style="border-top-color: #ef4444; width: 16.66%;"><span class="kpi-value c-red">{{ $t['overdue'] }}</span><span class="kpi-label">Overdue</span></td>
            <td style="border-top-color: #f59e0b; width: 16.66%;"><span class="kpi-value c-amber">{{ $t['due_soon'] }}</span><span class="kpi-label">Due < 7 Days</span></td>
        </tr>
    </table>

    <table class="three-col-table">
        <tr>
            <td>
                <div class="sub-label">Priority Breakdown</div>
                @php $maxP = max($t['priority_high'], $t['priority_medium'], $t['priority_low'], 1); @endphp
                <table class="bar-table">
                    <tr>
                        <td class="bar-name-cell">High</td>
                        <td class="bar-track-cell"><div class="bar-track"><div class="bar-fill bg-red"style="width:{{ $maxP > 0 ? ($t['priority_high']/$maxP*100) : 0 }}%"></div></div></td>
                        <td class="bar-num-cell">{{ $t['priority_high'] }}</td>
                    </tr>
                    <tr>
                        <td class="bar-name-cell">Medium</td>
                        <td class="bar-track-cell"><div class="bar-track"><div class="bar-fill bg-amber"style="width:{{ $maxP > 0 ? ($t['priority_medium']/$maxP*100) : 0 }}%"></div></div></td>
                        <td class="bar-num-cell">{{ $t['priority_medium'] }}</td>
                    </tr>
                    <tr>
                        <td class="bar-name-cell">Low</td>
                        <td class="bar-track-cell"><div class="bar-track"><div class="bar-fill bg-green"style="width:{{ $maxP > 0 ? ($t['priority_low']/$maxP*100) : 0 }}%"></div></div></td>
                        <td class="bar-num-cell">{{ $t['priority_low'] }}</td>
                    </tr>
                </table>
            </td>

            <td>
                <div class="sub-label">Evaluation Status</div>
                @php $maxEv = max($t['evaluated'], $t['pending_evaluation'], 1); @endphp
                <table class="bar-table">
                    <tr>
                        <td class="bar-name-cell">Evaluated</td>
                        <td class="bar-track-cell"><div class="bar-track"><div class="bar-fill bg-green"style="width:{{ $maxEv > 0 ? ($t['evaluated']/$maxEv*100) : 0 }}%"></div></div></td>
                        <td class="bar-num-cell">{{ $t['evaluated'] }}</td>
                    </tr>
                    <tr>
                        <td class="bar-name-cell">Pending</td>
                        <td class="bar-track-cell"><div class="bar-track"><div class="bar-fill bg-amber"style="width:{{ $maxEv > 0 ? ($t['pending_evaluation']/$maxEv*100) : 0 }}%"></div></div></td>
                        <td class="bar-num-cell">{{ $t['pending_evaluation'] }}</td>
                    </tr>
                </table>
                <div class="avg-box">
                    <span class="avg-box-lbl">Average Marks</span>
                    <span class="avg-box-val">{{ $t['avg_marks'] }}</span>
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
                            <td style="font-weight:800;" class="c-primary">{{ $grade }}</td>
                            <td style="text-align:right;font-weight:800;">{{ $cnt }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <p style="font-size:8px;color:#94a3b8;margin-top:6px;font-weight:600;">No grade data available yet.</p>
                @endif
            </td>
        </tr>
    </table>

    @if(!empty($t['submission_status']))
    <hr class="divider">
    <div class="sub-label">Submission Status Breakdown</div>
    @php $maxSub = max(array_values($t['submission_status']) ?: [1]); @endphp
    <table class="bar-table">
        @foreach($t['submission_status'] as $status => $cnt)
        @php
            $sColors = ['submitted' => '#10b981', 'pending' => '#f59e0b', 'late' => '#ef4444', 'reviewed' => '#0ea5e9'];
            $sColor  = $sColors[$status] ?? '#0c4a6e';
        @endphp
        <tr>
            <td class="bar-name-cell" style="width:25%;">{{ ucfirst(str_replace('_', ' ', $status)) }}</td>
            <td class="bar-track-cell" style="width:65%;">
                <div class="bar-track">
                    <div class="bar-fill"style="background:{{ $sColor }};width:{{ $maxSub > 0 ? ($cnt/$maxSub*100) : 0 }}%"></div>
                </div>
            </td>
            <td class="bar-num-cell" style="width:10%;">{{ $cnt }}</td>
        </tr>
        @endforeach
    </table>
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
                @php $h = $maxT > 0 ? max(4, intval($m['count']/$maxT*50)) : 4; @endphp
                <td class="trend-bar-cell"><div class="trend-bar-inner bg-amber"style="height:{{ $h }}px;"></div></td>
                @endforeach
            </tr>
            <tr>@foreach($tTrend as $m)<td class="trend-lbl-cell">{{ $m['month'] }}</td>@endforeach</tr>
        </table>
    </div>
</div>
@endif


{{-- ── FOOTER ── --}}
<div class="footer">
    <table class="footer-table">
        <tr>
            <td>IAPES &mdash; Internship Administration &amp; Performance Evaluation System</td>
            <td class="right">Generated on: {{ $generatedAt }}</td>
        </tr>
    </table>
</div>

</body>
</html>