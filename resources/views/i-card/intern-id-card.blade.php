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

        /* High-quality background image fix */
        .bg-image {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1; /* Keeps it behind everything */
        }

        .int-id{
            position: absolute;
            top: 45pt;
            width: 100%;
            text-align: center;
            font-size: 8pt;
            font-weight: bold;
            color: #1E73BE; 
            text-transform: uppercase;
        }
        
        .photo-container {
            position: absolute;
            top: 74pt; 
            left: 50%;
            width: 68pt;
            height: 68pt;
            margin-left: -34pt;
        }
        
        .photo {
            width: 100%;
            height: 100%;
            border-radius: 6px;
            object-fit: cover;
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
        
        .date{
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
        <img src="{{ $base64Image }}" class="bg-image">

        <div class="photo-container">
           <img src='{{ $intern->photo }}' class="photo">
        </div>

        <div class="name">{{ strtoupper($intern->application->name) }}</div>
        <div class="role">INTERN</div>
        <div class="int-id">{{ $intern->intern_code }}</div>
        <div class="date">
            [{{ \Carbon\Carbon::parse($intern->offer_letters->joining_date)->format('d M Y') }}
        -
        {{ $intern->offer_letters?->completion_date 
            ? \Carbon\Carbon::parse($intern->offer_letters->completion_date)->format('d M Y') 
            : '' }}]
        </div>
        <div class="contact">{{ $intern->application->phone }}</div>
        <div class="address">
            TechSrota, Alkapuri,<br>
            Vadodara, Gujarat - 390007
        </div>
    </div>
</body>
</html>