<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<style>
    @page {
        margin: 80px 50px 100px 50px;
    }

    body {
        font-family: 'Times New Roman', Times, serif;
        font-size: 14px;
        line-height: 1.6;
        color: #000;
    }

    header {
        position: fixed;
        top: -55px;
        left: 0;
        right: 0;
        height: 50px;
        text-align: center;
        font-weight: bold;
        /* border-bottom: 0.2px solid #000;
        padding-bottom: 0.1px; */
    }

    .header-table {
        width: 100%;
        border: none;
    }

    .header-logo {
        height: 50px; /* Adjusted to match PDF proportion  */
        vertical-align: middle;
    }

    footer {
        position: fixed;
        bottom: -90px;
        margin-left: 50px;  /* Increase this value to make the section narrower */
        margin-right: 50px;
        height: 100px;
        text-align: left;
        font-size: 15px;
        padding-top: 10px;
    }

    main {
        margin-top: 20px;
    }

    .recipient-info {
        margin-bottom: 20px;
    }

    .date-section {
        text-align: left;
        margin-bottom: 15px;
    }

    .subject {
        text-align: center;
        font-weight: bold;
        text-decoration: underline;
        margin: 20px 0;
    }

    .details-list {
        list-style-type: none;
        padding-left: 0;
    }

    .details-list li {
        margin-bottom: 5px;
    }

    .requirements {
        margin-top: 15px;
    }

    .signature-section {
        margin-top: 40px;
    }

    .page-break {
        page-break-after: always;
    }
</style>
</head>

<body>

    <header>
        <table class="header-table">
            <tr>
                <td style="text-align: left; font-size: 15px; width: 30%; vertical-align: bottom">
                    Email: info@techstrota.com
                </td>
                <td style="width: 34%; text-align: center;">
                    <img src="{{ public_path('images/TsLogo.png') }}" class="header-logo" alt="">
                </td>
                <td style="text-align: right; font-size: 15px; width: 30%; vertical-align: bottom">
                    Tel: +91 81288 40055
                </td>
            </tr>
        </table>
    </header>

    <footer>
        <strong>Techstrota</strong><br>
        <u>www.techstrota.com</u><br>
        156, 1st Floor, K10 Atlantis, C tower, Near Genda Circle, Opp Honest Restaurant,<br>
        Vadodara, Gujarat - 390007 | CIN: GJ240114897
    </footer>

    <main>
        @if(isset($offers))
            @foreach($offers as $offer)
                
                <div class="date-section">
                    <strong>Date:</strong> {{ \Carbon\Carbon::parse($offer->created_at ?? '2025-11-29')->format('d/m/Y') }}
                </div>

                <div class="recipient-info">
                    To,<br>
                    <strong>{{ $offer->application->name ?? 'Dharmik R Gajjar' }}</strong><br>
                    {{ $offer->application->college ?? 'GSFC University' }},<br>
                    {{ $offer->application->city ?? 'Vadodara' }} - {{ $offer->application->pincode ?? '391750' }}
                </div>

                <div class="subject">
                    Subject: Internship Offer/Appointment Letter
                </div>

                <p>Dear {{ $offer->application->name ?? 'Dharmik R Gajjar' }},</p>

                <p>
                    We are pleased to inform you that you have been selected for a 
                    <strong>{{ $offer->duration_text ?? 'one-month' }} {{ $offer->internship_role ?? 'Web Development' }} Internship Program (Open-source Technology)</strong> at Techstrota.
                </p>

                <p>The details of your internship are as follows:</p>
                <ul class="details-list">
                    <li><strong>5) Internship Position:</strong> {{ $offer->position_title ?? 'BCA Intern' }}</li>
                    <li><strong>6) Duration:</strong> {{ \Carbon\Carbon::parse($offer->joining_date)->format('d/m/Y') }} to {{ \Carbon\Carbon::parse($offer->completion_date)->format('d/m/Y') }} (1 Month)</li>
                    <li><strong>7) Working Hours:</strong> 11:00 AM to 4:00 PM, Monday to Saturday</li>
                    <li><strong>8) Internship Type:</strong> On-site</li>
                </ul>

                <div class="requirements">
                    <p>During the internship period, you are expected to:</p>
                    <ul>
                        <li>Follow all company rules, regulations, and code of conduct.</li>
                        <li>Complete all assigned tasks and projects within deadlines.</li>
                        <li>Maintain confidentiality and professionalism at all times.</li>
                    </ul>
                </div>

                <p>
                    Upon successful completion of your internship, you will receive an 
                    <strong>Internship Completion Certificate</strong> from Techstrota acknowledging your contribution and experience gained during this period.
                </p>

                <p>We are excited to have you onboard and look forward to your positive participation and learning during your time with us.</p>

                <p>Please confirm your acceptance of this offer by replying to this letter or by signing and returning a copy to us.</p>

                <div class="signature-section">
                    <p>Best wishes for a productive internship experience!</p>
                    <br>
                    Sincerely,<br><br>
                    <strong>{{ $offer->sender_name ?? 'Badal Jamod' }}</strong><br>
                    CEO/CTO, Techstrota
                </div>

                @if(!$loop->last)
                    <div class="page-break"></div>
                @endif

            @endforeach
        @endif
    </main>

</body>
</html>