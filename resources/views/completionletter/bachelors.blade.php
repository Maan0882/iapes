<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        @page {
            /* Standard page margins */
            margin: 0.4in 0.5in 0.6in 0.5in;
        }

        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            font-size: 14px;
            line-height: 1.6;
            color: #000000; 
            margin: 0;
            padding: 0;
            width: 100%;
        }

        /* --- Sophisticated Watermark --- */
        .watermark {
            position: fixed;
            top: 55%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 70%;
            z-index: -1000;
            opacity: 0.05;
        }

        header {
            width: 100%;
            margin-bottom: 20px;
            border-bottom: 3px solid #1a2a6c; 
            padding-bottom: 12px;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
        }

        /* --- Increased Main Content Margins --- */
        main {
            padding: 0 40px; /* Increased horizontal margin for main tag only */
            margin-top: 10px;
        }

        .subject {
            margin: 10px 0 25px 0;
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            letter-spacing: 1px;
            color: #000000;
            text-transform: uppercase;
        }

        .meta-section {
            margin-bottom: 20px;
            font-size: 13px;
            color: #000000;
            border-bottom: 1px solid #eee;
            padding-bottom: 8px;
        }

        .content-p {
            margin-bottom: 18px;
            text-align: justify;
        }

        /* --- Premium Competency Box --- */
        .tech-section {
            background: #f8f9fa; 
            border-left: 5px solid #f39200; 
            border-top: 1px solid #e1e1e1;
            border-right: 1px solid #e1e1e1;
            border-bottom: 1px solid #e1e1e1;
            border-radius: 0 8px 8px 0;
            padding: 18px 25px;
            margin: 25px 0;
        }

        .tech-title {
            color: #000000;
            font-weight: bold;
            margin-bottom: 10px;
            font-size: 15px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .tech-list {
            margin: 0;
            padding: 0;
            list-style: none;
            font-size: 13.5px;
        }

        .tech-list li {
            margin-bottom: 6px;
        }

        /* --- Refined Signature Area --- */
        .signature-container {
            margin-top: 50px;
            width: 100%;
        }

        .sig-block {
            text-align: center;
            width: 250px;
        }

        .sig-line {
            border-top: 2px solid #1a2a6c;
            margin-bottom: 8px;
        }

        /* --- Footer --- */
        footer {
            position: fixed;
            bottom: -20px;
            left: 0;
            right: 0;
            width: 100%;
            font-size: 10.5px;
            color: #000000;
            border-top: 1px solid #dee2e6;
            padding-top: 12px;
        }

        .system-remark {
            font-style: italic;
            font-size: 9px;
            color: #c0392b; 
            text-align: right;
        }

        strong {
            color: #000000;
        }
    </style>
</head>

<body>
    <div class="watermark">
        <img src="{{ public_path('images/TsLogo.png') }}" style="width: 100%;">
    </div>

    @if(isset($offers))
        @foreach($offers as $offer)

            {{-- Resolve relationships once for clean usage throughout --}}
            @php
                $application  = $offer->application;                    // OfferLetter → application()
                $intern       = $offer->intern;                          // OfferLetter → intern()

                // Name & Degree — from Application (via OfferLetter)
                $internName   = $application?->name   ?? 'N/A';
                $degree       = strtoupper($application?->degree ?? 'N/A');

                // Intern Code — from Intern
                $internCode   = $intern?->intern_code ?? 'N/A';

                // Duration & Duration Unit — from Application (via OfferLetter)
                $duration     = $application?->duration      ?? 'N/A';
                $durationUnit = $application?->duration_unit ?? '';

                // Working Hours & Role — from OfferLetter
                $workingHours = $offer->working_hours    ?? 'N/A';
                $role         = $offer->internship_role  ?? 'N/A';

                // Dates — from OfferLetter
                $joiningDate    = $offer->joining_date
                    ? \Carbon\Carbon::parse($offer->joining_date)->format('d/m/Y')
                    : 'N/A';
                $completionDate = $offer->completion_date
                    ? \Carbon\Carbon::parse($offer->completion_date)->format('d/m/Y')
                    : 'N/A';

                // Project — from OfferLetter
                $projectName        = $offer->project_name        ?? 'N/A';
                $projectDescription = $offer->project_description ?? 'N/A';

                // Parse skills from project_description
                // Expected format: "Label: skill1, skill2 | Label2: skill3, skill4"
                $skillCategories = [];
                if ($offer->project_description) {
                    $segments = explode('|', $offer->project_description);
                    foreach ($segments as $segment) {
                        $segment = trim($segment);
                        if (str_contains($segment, ':')) {
                            [$label, $skills] = explode(':', $segment, 2);
                            $skillCategories[trim($label)] = trim($skills);
                        }
                    }
                }
            @endphp

            <header>
                <table class="header-table">
                    <tr>
                        <td style="text-align: left; width: 33%; font-size: 12px; font-weight: bold;">
                            Email: <a href="/cdn-cgi/l/email-protection" class="__cf_email__" data-cfemail="e38a8d858ca39786808b9097918c9782cd808c8e">[email&#160;protected]</a>
                        </td>
                        <td style="text-align: center; width: 34%;">
                            <img src="{{ public_path('images/TsLogo.png') }}" style="height: 60px;">
                        </td>
                        <td style="text-align: right; width: 33%; font-size: 12px; font-weight: bold;">
                            Tel: +91 90334 76660
                        </td>
                    </tr>
                </table>
            </header>

            <main>
                <div class="subject">
                    Internship Completion Certificate
                </div>

                <div class="meta-section">
                    <table style="width: 100%;">
                        <tr>
                            {{-- completion_date → OfferLetter --}}
                            <td style="text-align: left;"><strong>Issued on:</strong> {{ $completionDate }}</td>
                            {{-- intern_code → Intern --}}
                            <td style="text-align: right;"><strong>Certificate ID No:</strong> {{ $internCode }}</td>
                        </tr>
                    </table>
                </div>

                <div class="content-p">
                    {{-- name, degree → Application | duration, duration_unit → Application | working_hours, internship_role, joining_date, completion_date → OfferLetter --}}
                    This is to certify that <strong>{{ $internName }}</strong>, a student of 
                    <strong>{{ $degree }}</strong>, has successfully completed the 
                    <strong>{{ $duration }} {{ $durationUnit }} ({{ $workingHours }} Hours)</strong> intensive internship with an overall performance <strong>Grade A</strong>. 
                    The internship was carried out for the course titled <strong>"{{ $role }}"</strong>, conducted by <strong>Techstrota</strong> 
                    and facilitated by <strong>Shree P.M. Patel College of Computer Science & Technology, Anand</strong>. 
                    The internship duration was from <strong>{{ $joiningDate }} to {{ $completionDate }}</strong> at Techstrota.
                </div>

                <div class="content-p">
                    {{-- project_name, project_description → OfferLetter --}}
                    <strong>Project Work:</strong> As part of the internship, the student worked on a 
                    <strong>{{ $projectName }}</strong>, which involved developing the 
                    <strong>{{ $projectDescription }}</strong>. This real-world assignment helped the student understand 
                    client requirements, gain exposure to industry-level workflows, and apply practical front-end development skills effectively. 
                    Also built an <strong>Admin Backend for Blogs and Contacts</strong> with content management features.
                </div>

                <div class="tech-section">
                    <div class="tech-title">Technical Competencies Acquired</div>
                    <ul class="tech-list">
                        {{-- Parsed from OfferLetter project_description --}}
                        {{-- Format: "Label: skill1, skill2 | Label2: skill3" --}}
                        @forelse($skillCategories as $label => $skills)
                            <li><strong>{{ $label }}:</strong> {{ $skills }}</li>
                        @empty
                            <li>No technical competencies recorded.</li>
                        @endforelse
                    </ul>
                </div>

                <div class="content-p">
                    During the internship, the student worked under the guidance of <strong>Ms. Vidhi Patel</strong>. 
                    We acknowledge their dedication and wish them the very best in their future professional endeavors.
                </div>

                <div class="signature-container">
                    <table style="width: 100%;">
                        <tr>
                            <td style="width: 50%;">
                                <div class="sig-block">
                                    <div style="height: 60px;"></div> <div class="sig-line"></div>
                                    <strong>Badal Jamod</strong><br>
                                    <span style="font-size: 11px;">CEO/CTO, Techstrota</span>
                                </div>
                            </td>
                            <!-- <td style="width: 50%; text-align: right;">
                                <div class="sig-block" style="display: inline-block;">
                                    <div style="height: 60px;"></div>
                                    <div class="sig-line"></div>
                                    <strong>Candidate Signature</strong>
                                </div>
                            </td> -->
                        </tr>
                    </table>
                </div>
            </main>

            @if(!$loop->last)
                <div style="page-break-after: always;"></div>
            @endif
        @endforeach
    @endif

    <footer>
        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                <td style="width: 75%; text-align: left;">
                    <strong>Techstrota</strong> | <span style="color: #000000;">www.techstrota.com</span><br>
                    156, 1st Floor, K10 Atlantis, C tower, Near Genda Circle, Opp Honest Restaurant,<br>
                    Vadodara, Gujarat-390007 | CIN: GJ240114897
                </td>
                <td style="width: 25%; vertical-align: bottom;">
                    <div class="system-remark">This is