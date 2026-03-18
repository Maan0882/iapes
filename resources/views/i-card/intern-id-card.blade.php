<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        @page { margin: 0; }
        body { margin: 0; padding: 0; font-family: 'Helvetica', sans-serif; }
        
        .card-container {
            position: relative;
            width: 153pt; 
            height: 243pt;
            overflow: hidden;
        }

        /* =========================================
           LAYER 1: BOTTOM (Intern Photo) 
           ========================================= */
        .photo-container {
            position: absolute;
            top: 70pt; 
            left: 48.5%; 
            width: 60pt; 
            height: 60pt;
            margin-left: -30pt; 
            z-index: 1; /* Lowest Layer */
            overflow: hidden; 
            border-radius: 4px; 
            /* Note: Removed flexbox as PDF engines do not support it */
        }

        .photo {
            width: 100%;
            height: 100%;
            /* object-fit: cover; works in modern browsers, but if it fails in PDF, 
               ensure your $internImageBase64 is cropped to a 1:1 ratio before encoding */
        }

        /* =========================================
           LAYER 2: MIDDLE (Background Frame) 
           ========================================= */
        .bg-image {
            position: absolute;
            top: 0;
            left: 0;
            width: 153pt;
            height: 243pt;
            z-index: 2; /* Middle Layer - Covers the edges of the photo */
        }

        /* =========================================
           LAYER 3: TOP (All Text Elements) 
           ========================================= */
        /* Grouping z-index for all text to ensure it stays above the background */
        .int-id, .name, .role, .date, .contact, .address {
            z-index: 3; /* Top Layer */
        }

        .int-id {
            position: absolute;
            top: 45pt;
            width: 100%;
            text-align: center;
            font-size: 8pt;
            font-weight: bold;
            color: #1E73BE; 
            text-transform: uppercase;
        }
        
        .name {
            position: absolute;
            top: 140pt;
            width: 100%;
            text-align: center;
            font-size: 11pt;
            font-weight: bold;
            color: #1a2b4c;
        }

        .role {
            position: absolute;
            top: 158pt;
            width: 100%;
            text-align: center;
            font-size: 8pt;
            font-weight: bold;
            color: #f39200; 
            text-transform: uppercase;
        }
        
        .date {
            position: absolute;
            top: 170pt;
            width: 100%;
            text-align: center;
            font-size: 8pt;
            font-weight: bold;
            color: #1E73BE; 
        }

        .contact {
            position: absolute;
            bottom: 42pt; 
            left: 36pt;
            font-size: 7.5pt;
            font-weight: bold;
            color: #1a2b4c;
        }

        .address {
            position: absolute;
            bottom: 12pt; 
            left: 38pt;
            font-size: 5.5pt;
            color: #333;
            width: 100pt;
            line-height: 1.1;
        }
    </style>
</head>
<body>
    <div class="card-container">
        
        <div class="photo-container">
           @if($internImageBase64)
                <img src="{{ $internImageBase64 }}" class="photo">
           @endif
        </div>

        <img src="{{ $base64Image }}" class="bg-image">

        <div class="name">{{ strtoupper($intern->application->name) }}</div>
        <div class="role">INTERN</div>
        <div class="int-id">{{ $intern->intern_code }}</div>
        <div class="date">
            [{{ \Carbon\Carbon::parse($intern->offer_letters->joining_date)->format('d M Y') }}
        -
        {{ $intern->offer_letters?->completion_date 
            ? \Carbon\Carbon::parse($intern->offer_letters->completion_date)->format('d M Y') 
            : 'PRESENT' }}]
        </div>
        <div class="contact">{{ $intern->application->phone }}</div>
        <div class="address">
            TechSrota, Alkapuri,<br>
            Vadodara, Gujarat - 390007
        </div>
        
    </div>
</body>
</html>