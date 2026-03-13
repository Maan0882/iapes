<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>IAPES Portal — Tech स्त्रोत</title>
        
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600|jetbrains-mono:400,500,700" rel="stylesheet" />
        
        <script src="https://cdn.tailwindcss.com"></script>

        <style>
            .portal-mono { font-family: 'JetBrains Mono', monospace; }
            
            /* Glassmorphism Logic */
            .glass-container {
                background: rgba(255, 255, 255, 0.45);
                backdrop-filter: blur(20px) saturate(180%);
                -webkit-backdrop-filter: blur(20px) saturate(180%);
                border: 1px solid rgba(255, 255, 255, 0.25);
                box-shadow: 0 12px 40px 0 rgba(0, 0, 0, 0.05);
            }

            .glass-btn {
                background: rgba(255, 255, 255, 0.5);
                backdrop-filter: blur(8px);
                border: 1px solid rgba(255, 255, 255, 0.3);
                transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            }

            /* Automatic Dark Mode Adjustments */
            @media (prefers-color-scheme: dark) {
                .glass-container {
                    background: rgba(18, 18, 17, 0.8);
                    border: 1px solid rgba(255, 255, 255, 0.08);
                    box-shadow: 0 12px 40px 0 rgba(0, 0, 0, 0.5);
                }
                .glass-btn {
                    background: rgba(255, 255, 255, 0.03);
                    border-color: rgba(255, 255, 255, 0.1);
                }
                .glass-btn:hover {
                    background: rgba(255, 255, 255, 0.08);
                    border-color: rgba(255, 255, 255, 0.25);
                }
            }
        </style>
    </head>
    <body class="bg-[#FDFDFC] dark:bg-[#080808] text-[#1b1b18] dark:text-[#EDEDEC] min-h-screen flex items-center justify-center p-4 md:p-8 relative overflow-hidden transition-colors duration-500">
        
        <div class="absolute top-[-15%] left-[-10%] w-[55%] h-[55%] bg-[#f7a93b]/20 dark:bg-[#f7a93b]/10 rounded-full blur-[140px]"></div>
        <div class="absolute bottom-[-15%] right-[-10%] w-[55%] h-[55%] bg-[#1d70b8]/20 dark:bg-[#1d70b8]/10 rounded-full blur-[140px]"></div>

        <main class="w-full sm:max-w-lg md:max-w-xl lg:max-w-3xl glass-container rounded-[40px] overflow-hidden relative z-10 p-1 lg:p-2 animate-in fade-in zoom-in duration-700">
            
            <div class="flex items-center gap-2 px-8 py-6 border-b border-black/5 dark:border-white/5">
                <div class="flex gap-2">
                    <div class="w-3 h-3 rounded-full bg-[#ff5f56]"></div>
                    <div class="w-3 h-3 rounded-full bg-[#ffbd2e]"></div>
                    <div class="w-3 h-3 rounded-full bg-[#27c93f]"></div>
                </div>
                <div class="ml-6 flex-1 bg-black/5 dark:bg-white/5 rounded-xl py-2 px-5 text-[10px] lg:text-[12px] portal-mono text-gray-400 dark:text-gray-500 truncate tracking-widest">
                    <a href="https://techstrota.com/">https://techstrota.com/</a>
                </div>
            </div>

            <div class="p-8 md:p-14 lg:p-20">
                <div class="flex flex-col items-center text-center mb-14 lg:mb-20">
                    <div class="p-5 bg-white dark:bg-white/5 rounded-[28px] shadow-sm mb-6 transition-all duration-300 hover:scale-110">
                        <img src="{{ asset('images/TsLogo.png') }}" alt="Tech स्त्रोत" class="h-14 md:h-20 lg:h-24 w-auto">
                    </div>
                    <h1 class="text-4xl md:text-5xl lg:text-6xl font-black tracking-tighter text-gray-900 dark:text-white uppercase italic">
                        IAPES <span class="text-[#f7a93b] dark:text-[#f7a93b]">PORTAL</span>
                    </h1>
                    <p class="text-[10px] md:text-xs font-bold text-gray-400 dark:text-gray-500 uppercase tracking-[0.4em] mt-4">
                        Powered by Tech स्त्रोत &bull; Enterprise
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 lg:gap-8">
                    <a href="{{ url('/admin') }}" class="glass-btn flex flex-col items-center p-8 md:p-10 rounded-[32px] no-underline group text-center hover:scale-105 transition-all">
                        <div class="w-16 h-16 bg-[#f7a93b]/15 rounded-2xl flex items-center justify-center text-[#f7a93b] mb-5 transition-all group-hover:bg-[#f7a93b] group-hover:text-white group-hover:shadow-[0_0_30px_rgba(247,169,59,0.3)]">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A10.003 10.003 0 0012 21a9.983 9.983 0 005.143-1.41l.054.09m-4.695-4.695a2 2 0 11-2.828-2.828 2 2 0 012.828 2.828zm-2.828-4.95a5 5 0 105.656 5.656"/></svg>
                        </div>
                        <div class="flex-1">
                            <div class="text-xl font-black dark:text-white uppercase tracking-tight">Admin</div>
                            <div class="text-[10px] text-gray-500 mt-2 font-bold uppercase tracking-widest">Manage Interview and Interns</div>
                        </div>
                    </a>

                    <a href="{{ url('/intern') }}" class="glass-btn flex flex-col items-center p-8 md:p-10 rounded-[32px] no-underline group text-center hover:scale-105 transition-all">
                        <div class="w-16 h-16 bg-[#1d70b8]/15 rounded-2xl flex items-center justify-center text-[#1d70b8] mb-5 transition-all group-hover:bg-[#1d70b8] group-hover:text-white group-hover:shadow-[0_0_30px_rgba(29,112,184,0.3)]">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        </div>
                        <div class="flex-1">
                            <div class="text-xl font-black dark:text-white uppercase tracking-tight">Intern</div>
                            <div class="text-[10px] text-gray-500 mt-2 font-bold uppercase tracking-widest">Workspace, Task & Evaluation</div>
                        </div>
                    </a>
                </div>

                <div class="mt-16 lg:mt-24 pt-10 border-t border-black/5 dark:border-white/5 flex flex-col md:flex-row justify-between items-center gap-6">
                    <div class="flex items-center gap-3">
                        <span class="relative flex h-3 w-3">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-3 w-3 bg-green-500"></span>
                        </span>
                        <span class="text-[11px] portal-mono text-gray-400 dark:text-gray-500 uppercase tracking-widest font-bold">Encrypted Node: Active</span>
                    </div>
                    <p class="text-[10px] portal-mono text-gray-400 dark:text-gray-500 uppercase tracking-[0.2em] font-medium">
                        &copy; 2026 Tech स्त्रोत &bull; Secure Enterprise Protocol
                    </p>
                </div>
            </div>
        </main>
    </body>
</html>