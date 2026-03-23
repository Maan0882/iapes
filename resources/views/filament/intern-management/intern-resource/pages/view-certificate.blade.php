<x-filament-panels::page>
    <div class="flex flex-col items-center mb-6 no-print">
        <x-filament::button 
            icon="heroicon-m-printer" 
            onclick="window.print()"
            color="info">
            Print / Save as PDF
        </x-filament::button>
    </div>

    <div id="certificate-container" class="certificate-preview-wrapper">
        <style>
            /* Screen-only wrapper to handle the large A4 size in the Filament UI */
            @media screen {
                .certificate-preview-wrapper {
                    background: #dde4ed;
                    padding: 2rem;
                    display: flex;
                    justify-content: center;
                    overflow-x: auto;
                }
                .cert {
                    box-shadow: 0 0 20px rgba(0,0,0,0.2);
                    zoom: 0.7; /* Scales it down to fit smaller screens */
                }
            }

            @media print {
                .no-print { display: none !important; }
                body { background: white !important; margin: 0; padding: 0; }
                .fi-main { padding: 0 !important; margin: 0 !important; }
                .fi-sidebar, .fi-topbar, .fi-header { display: none !important; }
                .cert { margin: 0 !important; border: none !important; }
                @page { size: a4 landscape; margin: 0; }
            }

            /* Your provided styles */
            .cert {
                position: relative;
                width: 297mm;
                height: 210mm;
                background: #ffffff;
                overflow: hidden;
                margin: 0 auto;
                color: black;
            }

            .b-outer {
                position: absolute;
                top: 1.1rem; left: 1.1rem; right: 1.1rem; bottom: 1.1rem;
                border: 2px solid #0e72b4;
                z-index: 3;
            }

            .b-inner {
                position: absolute;
                top: 1.55rem; left: 1.55rem; right: 1.55rem; bottom: 1.55rem;
                border: 1px solid #f4a243;
                z-index: 3;
            }

            .corner-svg {
                position: absolute;
                width: 110px; height: 110px;
                z-index: 5;
            }
            .corner-svg.tl { top: 0; left: 0; }
            .corner-svg.tr { top: 0; right: 0; }
            .corner-svg.bl { bottom: 0; left: 0; }
            .corner-svg.br { bottom: 0; right: 0; }

            .top-fill {
                position: absolute;
                top: 0; left: 0; right: 0;
                height: 3px;
                background: linear-gradient(90deg, #0e72b4 50%, #f4a243 100%);
                z-index: 10;
            }

            .coding-watermark-container {
                position: absolute;
                top: 0; left: 0; width: 100%; height: 100%;
                z-index: 1;
                overflow: hidden;
            }

            .logo-wm {
                position: absolute;
                width: 45px; height: 45px;
                opacity: 0.2;
            }

            .content {
                position: absolute;
                top: 3px; left: 0; width: 100%; height: 100%;
                padding: 2.5rem 5.5rem 4rem;
                z-index: 10;
                text-align: center;
            }

            .cert-main-title {
                font-size: 24px;
                font-weight: bold;
                letter-spacing: 5px;
                text-transform: uppercase;
                color: #0e72b4;
                margin-top: 2rem;
            }

            .recipient-name {
                font-size: 48px;
                font-weight: bold;
                color: #0e72b4;
                margin: 2.5rem 0 0;
            }

            .name-underline {
                margin: 0.5rem auto;
                width: 70%;
                height: 2px;
                background: linear-gradient(90deg, transparent 0%, #f4a243 25%, #f4a243 75%, transparent 100%);
            }

            .body-text {
                font-size: 18px;
                color: #374151;
                line-height: 1.6;
                margin: 2rem auto;
                max-width: 85%;
            }

            .footer-section {
                position: absolute;
                bottom: 4rem; left: 5.5rem; right: 5.5rem;
            }

            .sig-block { float: left; width: 180px; text-align: left; }
            .sig-line { height: 1px; background: #d1d5db; width: 100%; margin-top: 5px;}
            
            .stamp-block { display: inline-block; width: 200px; text-align: center; }
            .stamp {
                width: 80px; height: 80px;
                border-radius: 50%; border: 2px solid #0e72b4;
                margin: 0 auto; position: relative;
            }
            .stamp-inner {
                width: 66px; height: 66px;
                border-radius: 50%; border: 1px dashed #f4a243;
                position: absolute; top: 5px; left: 5px;
                font-size: 8px; color: #9ca3af; padding-top: 15px;
            }

            .qr-block { float: right; width: 150px; text-align: right; }
            .qr-box { 
                width: 60px; height: 60px; border: 1.5px solid #e5e7eb; 
                float: right; line-height: 60px; font-size: 8px; color: #d1d5db;
            }
            .clearfix::after { content: ""; clear: both; display: table; }
        </style>

        <div class="cert">
            <div class="top-fill"></div>
            <div class="b-outer"></div>
            <div class="b-inner"></div>

            <div class="coding-watermark-container">
                <img class="logo-wm" src="https://cdn.simpleicons.org/php/0e72b4" style="top: 15%; left: 10%;">
                <img class="logo-wm" src="https://cdn.simpleicons.org/laravel/0e72b4" style="top: 25%; left: 80%;">
                <img class="logo-wm" src="https://cdn.simpleicons.org/python/0e72b4" style="top: 70%; left: 15%;">
                <img class="logo-wm" src="https://cdn.simpleicons.org/nodedotjs/0e72b4" style="top: 65%; left: 85%;">
                <img class="logo-wm" src="https://cdn.simpleicons.org/mysql/0e72b4" style="top: 45%; left: 5%;">
                <img class="logo-wm" src="https://cdn.simpleicons.org/react/0e72b4" style="top: 10%; left: 45%;">
            </div>

            <div class="content">
                <div class="top-section">
                    <img src="{{ asset('images/TsLogo.png') }}" alt="TechStrota" style="height:45px;">
                </div>

                <div class="title-section">
                    <p class="cert-main-title">Certificate of Internship</p>
                    <p style="font-style: italic; color: #6b7280; margin-top: 0.5rem;">This is to certify that,</p>
                </div>

                <div class="name-section">
                    <h1 class="recipient-name">{{ $offer->application?->name ?? 'Intern Name' }}</h1>
                    <div class="name-underline"></div>
                </div>

                <div class="body-section">
                    <p class="body-text">
                        has successfully completed the <b>{{ $offer->internship_role ?? 'Web Development' }}</b> Internship at
                        <b>TechStrota</b> from 
                        <b>{!! !empty($offer->joining_date) ? \Carbon\Carbon::parse($offer->joining_date)->format('j\<\s\u\p\>S\<\/\s\u\p\> F Y') : '1<sup>st</sup> Dec 2025' !!}</b> to 
                        <b>{!! !empty($offer->completion_date) ? \Carbon\Carbon::parse($offer->completion_date)->format('j\<\s\u\p\>S\<\/\s\u\p\> F Y') : '31<sup>st</sup> Dec 2025' !!}</b>.<br>
                        During the internship period, the intern actively contributed to learning
                        and real-time development tasks and demonstrated excellent discipline,
                        creativity, and problem-solving skills.
                    </p>
                </div>

                <div class="footer-section clearfix">
                    <div class="sig-block">
                        <div style="height: 40px;">
                            </div>
                        <div class="sig-line"></div>
                        <p style="font-size: 11px; font-weight: bold; color: #0e72b4; margin-top: 5px;">FOUNDER / CEO</p>
                        <p style="font-size: 10px; color: #9ca3af;">TechStrota</p>
                    </div>

                    <div class="stamp-block">
                        <div class="stamp">
                            <div class="stamp-inner">Official<br>Stamp</div>
                        </div>
                        <p style="font-size: 11px; color: #9ca3af; margin-top: 10px;">WWW.TECHSTROTA.COM</p>
                    </div>

                    <div class="qr-block">
                        <div class="qr-box">QR CODE</div>
                        <div class="clearfix"></div>
                        <p style="font-size: 10px; color: #9ca3af; margin-top: 5px;">
                            TS25 / WD / {{ $record->intern_code ?? '17' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>