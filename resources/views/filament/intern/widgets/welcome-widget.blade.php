<x-filament-widgets::widget>
    <div class="relative overflow-hidden rounded-xl px-6 py-5 flex items-center justify-between"
         style="background:linear-gradient(135deg,#0f172a 0%,#1e1b4b 60%,#1e293b 100%); border:1px solid rgba(99,102,241,0.15);">

        {{-- Background decoration --}}
        <div class="absolute right-0 top-0 h-full w-64 pointer-events-none"
             style="background:radial-gradient(ellipse at right center, rgba(99,102,241,0.12) 0%, transparent 70%);"></div>

        {{-- Left content --}}
        <div class="relative">
            <div class="flex items-center gap-2 mb-2">
                <div class="h-3 w-0.5 rounded-full" style="background:#6366f1;"></div>
                <span class="text-[10px] font-bold uppercase tracking-[0.25em]" style="color:#475569;">Workspace Overview</span>
            </div>
            <h1 class="text-2xl font-black tracking-tight" style="color:#f1f5f9; letter-spacing:-0.02em;">
                {{ $this->getViewData()['greeting'] }},
                <span style="color:#818cf8;">{{ explode(' ', $this->getViewData()['name'])[0] }}</span>
            </h1>
            <p class="flex items-center gap-1.5 text-xs mt-1.5" style="color:#475569;">
                <x-heroicon-m-calendar-days class="w-3 h-3 shrink-0" style="color:#334155;" />
                {{ $this->getViewData()['date'] }}
            </p>
        </div>

        {{-- Right: live pill --}}
        <div class="relative hidden md:flex items-center gap-2 px-4 py-2 rounded-lg flex-shrink-0"
             style="background:rgba(255,255,255,0.04); border:1px solid rgba(255,255,255,0.08);">
            <span class="relative flex h-2 w-2">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full opacity-60" style="background:#34d399;"></span>
                <span class="relative inline-flex h-2 w-2 rounded-full" style="background:#10b981;"></span>
            </span>
            <span class="text-[10px] font-bold uppercase tracking-[0.18em]" style="color:#475569;">Live Services</span>
        </div>

    </div>
</x-filament-widgets::widget>