<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<style>
    @page {
        margin: 100px 60px 120px 60px; /* Precise margins matching Techstrota standard */
    }

    body {
        font-family: 'Times New Roman', Times, serif; /* Standard formal font  */
        font-size: 15px; /* Slightly larger for better readability */
        line-height: 1.5;
        color: #000;
        text-align: justify;
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
        margin-left: 50px;  /* Increase this value to make the section narrower */
        margin-right: 50px; /* Keep these balanced for a centered look */
        margin-top: 10px;
    }
    .address-section {
        margin-top: 100px;
        line-height: 1.3;
    }

    .subject {
        margin: 25px 0;
        text-decoration: none;
        text-align: center;
    }

    .date-section {
        margin-top: 80px;
        text-align: right;
    }

    ul {
        margin-left: 20px;
        padding-left: 0;
    }

    li {
        margin-bottom: 8px;
    }

    .signature-container {
        margin-top: 300px;
        width: 100%;
    }

    .company-stamp {
        width: 110px; /* Matches the Techstrota Vadodara seal  */
        float: left;
        margin-right: 20px;
    }

    .page-break {
        page-break-after: always;
    }
</style>
</head>

<body>

    <header>
        <table style="width: 100%; border: none;">
            <tr>
                <td style="text-align: left; font-size: 15px; width: 30%; vertical-align: bottom">
                    <strong>Email:</strong> info@techstrota.com 
                </td>
                <td style="text-align: center; width: 40%;">
                    <img src="{{ public_path('images/TsLogo.png') }}" class="header-logo" alt="Bachelor's Degree">
                </td>
                <td style="text-align: right; font-size: 15px; width: 30%; vertical-align: bottom">
                    <strong>Tel:</strong> +91 81288 40055
                </td>
            </tr>
        </table>
    </header>

    <main>
        @if(isset($offers))
            @foreach($offers as $offer)
                
                <div class="address-section">
                    To, <br>
                    <strong>{{ strtoupper($offer->application->name) }}</strong>, <br>
                    {{ $offer->application->college }} <br>
                    {{ $offer->application->university }} <br>
                    {{ $offer->application->address }}
                </div>

                <div class="date-section">
                    <strong>Date:</strong> {{ \Carbon\Carbon::parse($offer->created_at ?? now())->format('d/m/Y') }} 
                </div>

                <div class="subject">
                    <strong>Subject: Intern Offer / Appointment Letter</strong> 
                </div>
                <br>
                <p>Dear <strong>{{ strtoupper($offer->application->name) }}</strong>,</p>

                <p>
                    We are pleased to offer you an internship position at Techstrota for the role of 
                    <strong>{{ $offer->internship_role }}</strong>.  
                    This internship presents an excellent opportunity for you to gain valuable experience and enhance your skills in software development by working with a talented and dynamic team. 
                </p>
                
                <p>As an intern, you will be responsible for: </p>
                <ul>
                    <li>Developing, testing, and debugging software applications and features using various technologies and tools. </li>
                    <li>Collaborating with other developers, designers, and project managers to deliver high-quality products and services. </li>
                    <li>Following best practices and standards for coding, documentation, and quality assurance. </li>
                    <li>Learning new skills and technologies and applying them to your projects. </li>
                    <li>Contributing to the improvement and innovation of the software development process and culture. </li>
                </ul>

                <div class="page-break"></div>

                <p style="margin-top: 80px">
                    The internship will commence on <strong>{{ \Carbon\Carbon::parse($offer->joining_date)->format('d F, Y') }}</strong> 
                    and will conclude on <strong>{{ \Carbon\Carbon::parse($offer->completion_date)->format('d F, Y') }}</strong>.  
                    You will be expected to work {{ $offer->working_hours ?? '42' }} hours per week, from Monday to Saturday, between 10:30 AM to 5:30 PM. 
                </p>
                
                <p>
                    Upon successful completion of the internship, you will receive a
                    <strong>Certificate of Completion</strong> and a <strong>Letter of Recommendation</strong>. 
                </p>

                <p>You will also be eligible for certain benefits, including access to the company's facilities, events, and training programs. </p>
                
                <p>
                    To accept this offer, please sign and return this letter before the joining date. 
                    If you have any questions or concerns, please feel free to contact us at any time. 
                </p>

                <p>We are excited to have you join our team and look forward to working with you. </p>

                <div class="signature-container">
                    <div style="float: left; width: 60%;">
                        For, <strong>TECHSTROTA</strong><br>
                        Yours Sincerely, <br><br><br>
                        <strong>Jamod Badal</strong><br>
                        CEO 
                    </div>
                    <div style="float: right; width: 30%; text-align: center; margin-top: 80px;">
                        <div style="border-top: 1px solid #000; padding-top: 5px;">
                            Intern Signature
                        </div>
                    </div>
                    <div style="clear: both;"></div>
                </div>

            @endforeach
        @endif
    </main>

    <footer>
        <strong>Techstrota</strong> <br>
        <u>www.techstrota.com</u> <br>
        <strong>156, 1st Floor, K10 Atlantis, C Tower, Near Genda Circle, Opp Honest Restaurant,<br>
        Vadodara, Gujarat - 390007 Tel: +91 81288 40055, CIN: GJ240114897</strong> 
    </footer>

</body>
</html>