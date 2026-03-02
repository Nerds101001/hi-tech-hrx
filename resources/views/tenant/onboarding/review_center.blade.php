@extends('layouts/layoutMaster')

@section('title', 'Onboarding Review Center')

@section('vendor-style')
    <!-- Tailwind CSS with Forms and Typography plugins -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography,aspect-ratio"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&amp;display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <style type="text/tailwindcss">
        :root {
            --primary-teal: #006D77;
            --deep-teal: #004d54;
            --sidebar-bg: #00353a;
            --bg-light: #F8FAFC;
        }
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        .status-badge {
            @apply px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider;
        }
        /* Modal Styles */
        .onboarding-modal-backdrop { @apply fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-[60]; }
        .onboarding-modal-content { @apply fixed top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 bg-white rounded-2xl shadow-2xl z-[70] w-full max-w-lg p-0 overflow-hidden; }
        
        /* Ensure tailwind doesn't clash too much with bootstrap */
        .tailwind-scope {
            font-family: 'Inter', sans-serif;
        }
    </style>
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        primary: "#006D77",
                        "deep-teal": "#004d54",
                        "sidebar-bg": "#00353a",
                    }
                },
            },
        };
    </script>
@endsection

@section('page-style')
<style>
    /* Custom Scrollbar */
    .onboarding-scrollbar::-webkit-scrollbar { width: 6px; }
    .onboarding-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .onboarding-scrollbar::-webkit-scrollbar-thumb { background: #CBD5E1; border-radius: 10px; }
    .onboarding-scrollbar::-webkit-scrollbar-thumb:hover { background: #94A3B8; }
    
    /* Layout Overrides for layoutMaster */
    .content-wrapper { background-color: #F8FAFC !important; }
</style>
@endsection

@section('content')
<div class="tailwind-scope text-slate-800">
    {{-- Main Header inside section --}}
    <div class="flex flex-col md:flex-row md:items-end justify-between mb-10 gap-4 mt-4 px-4">
        <div class="animate__animated animate__fadeIn">
            <h1 class="text-3xl font-black text-slate-900 tracking-tight">Onboarding Review Center</h1>
            <p class="text-slate-500 text-sm mt-1 font-medium">Coordinate, audit, and finalize candidate integration workflows.</p>
        </div>
        <div class="flex items-center gap-4">
            <button onclick="openOnboardingModal()" class="bg-primary hover:bg-deep-teal text-white px-5 py-2.5 rounded-xl text-sm font-bold flex items-center gap-2 transition-all shadow-lg shadow-primary/20 active:scale-95">
                <span class="material-symbols-outlined text-lg">person_add</span>
                New Onboarding
            </button>
            <div class="bg-amber-50 border border-amber-200 px-5 py-2.5 rounded-2xl flex items-center gap-3 shadow-sm">
                <div class="w-8 h-8 bg-amber-500 text-white rounded-lg flex items-center justify-center">
                    <span class="material-symbols-outlined text-lg">pending_actions</span>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-amber-600 uppercase tracking-widest leading-none mb-1">Attention Required</p>
                    <p class="text-sm font-black text-amber-900 leading-none">{{ $pendingCount }} Pending Reviews</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-4 gap-10 items-start px-4">
        {{-- Left Pane: Pending List --}}
        <div class="xl:col-span-3 space-y-8">
            <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/60 border border-slate-200 overflow-hidden">
                <div class="p-8 border-b border-slate-100 flex flex-col md:flex-row justify-between items-center bg-slate-50/30 gap-6">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 bg-primary/10 rounded-xl flex items-center justify-center">
                            <span class="material-symbols-outlined text-primary">analytics</span>
                        </div>
                        <div>
                            <h3 class="font-black text-slate-900 uppercase tracking-widest text-xs">Submission Pipeline</h3>
                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">Live Candidate Data</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 w-full md:w-auto">
                        <div class="relative flex-1 md:flex-none">
                            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-lg">search</span>
                            <input class="pl-12 pr-6 py-3 text-sm rounded-2xl border-slate-200 bg-white w-full md:w-72 focus:ring-primary/20 focus:border-primary transition-all placeholder:text-slate-400 text-slate-900 font-medium" placeholder="Filter by candidate or ID..." type="text"/>
                        </div>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left table-fixed">
                        <thead>
                            <tr class="bg-slate-50/50">
                                <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] w-1/3">Candidate Detail</th>
                                <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Submitted</th>
                                <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Department</th>
                                <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Status</th>
                                <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($onboardingUsers->where('status', \App\Enums\UserAccountStatus::ONBOARDING_SUBMITTED) as $oUser)
                                <tr class="group hover:bg-slate-50/80 transition-all cursor-pointer {{ $selectedUser && $selectedUser->id == $oUser->id ? 'bg-primary/[0.03] ring-1 ring-inset ring-primary/10' : '' }}" onclick="window.location.href='{{ route('onboarding.reviewCenter', ['user_id' => $oUser->id]) }}'">
                                    <td class="px-8 py-6">
                                        <div class="flex items-center gap-4">
                                            <div class="relative">
                                                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-slate-100 to-slate-200 flex items-center justify-center text-slate-900 font-black text-base shadow-inner border border-white">
                                                    {{ $oUser->getInitials() }}
                                                </div>
                                                <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-amber-500 border-2 border-white rounded-full"></div>
                                            </div>
                                            <div class="min-w-0">
                                                <p class="text-sm font-black text-slate-900 truncate">{{ $oUser->getFullName() }}</p>
                                                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">{{ $oUser->roles->first()->name ?? 'N/A' }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-8 py-6">
                                        <div class="flex flex-col">
                                            <span class="text-sm text-slate-600 font-bold">{{ $oUser->onboarding_completed_at ? \Carbon\Carbon::parse($oUser->onboarding_completed_at)->format('d M, Y') : 'N/A' }}</span>
                                            <span class="text-[10px] text-slate-400 font-medium uppercase tracking-tighter">{{ $oUser->onboarding_completed_at ? \Carbon\Carbon::parse($oUser->onboarding_completed_at)->format('H:i A') : '' }}</span>
                                        </div>
                                    </td>
                                    <td class="px-8 py-6">
                                        <div class="inline-flex items-center gap-2 px-3 py-1 bg-slate-100 rounded-lg text-slate-600 text-[10px] font-black uppercase tracking-wider">
                                            <span class="w-1.5 h-1.5 bg-slate-400 rounded-full"></span>
                                            {{ $oUser->team->name ?? 'Unmanaged' }}
                                        </div>
                                    </td>
                                    <td class="px-8 py-6">
                                        <span class="px-3 py-1.5 rounded-xl text-[9px] font-black uppercase tracking-widest bg-amber-100 text-amber-700 ring-1 ring-inset ring-amber-500/20">Awaiting HR Audit</span>
                                    </td>
                                    <td class="px-8 py-6 text-right">
                                        <div class="flex items-center justify-end gap-3 opacity-0 group-hover:opacity-100 transition-opacity">
                                            <a href="{{ route('onboarding.reviewCenter', ['user_id' => $oUser->id]) }}" class="px-4 py-2 text-[10px] font-black uppercase tracking-[0.2em] text-primary bg-primary/5 hover:bg-primary hover:text-white rounded-xl transition-all border border-primary/20">Audit File</a>
                                            <form method="POST" action="{{ route('onboarding.approve', $oUser->id) }}">
                                                @csrf
                                                <button type="submit" class="w-9 h-9 flex items-center justify-center text-green-600 hover:bg-green-50 rounded-xl transition-colors border border-green-200" title="Instant Approval">
                                                    <span class="material-symbols-outlined text-xl">verified</span>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-8 py-20 text-center">
                                        <div class="flex flex-col items-center">
                                            <div class="w-20 h-20 bg-slate-100 rounded-full flex items-center justify-center mb-4">
                                                <span class="material-symbols-outlined text-4xl text-slate-300">work_outline</span>
                                            </div>
                                            <p class="text-slate-500 font-bold uppercase tracking-widest text-xs">All caught up! No pending reviews found.</p>
                                            <p class="text-slate-400 text-[10px] mt-1">Review recently approved candidates in the side panel.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Detail View Pane --}}
            @if($selectedUser)
            <div class="bg-white rounded-3xl shadow-2xl shadow-slate-200/40 border border-slate-200 overflow-hidden animate__animated animate__fadeInUp">
                <div class="px-8 py-6 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 bg-primary text-white rounded-xl flex items-center justify-center shadow-lg shadow-primary/20">
                            <span class="material-symbols-outlined text-lg">fact_check</span>
                        </div>
                        <div>
                            <h3 class="font-black text-slate-900 uppercase tracking-widest text-xs">Audit File: {{ $selectedUser->getFullName() }}</h3>
                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">Reference ID: #EMP-{{ $selectedUser->id }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-4">
                        <span class="text-[10px] text-slate-400 font-black uppercase tracking-widest">Global Status:</span>
                        <span class="bg-primary/10 text-primary text-[9px] font-black px-3 py-1 rounded-full uppercase tracking-widest">Active Review</span>
                    </div>
                </div>
                <div class="p-10">
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
                        {{-- Docs --}}
                        <div class="space-y-6">
                            <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                                <h4 class="text-[11px] font-black text-slate-900 uppercase tracking-widest flex items-center gap-2">
                                    <span class="material-symbols-outlined text-slate-400 text-lg">folder_open</span>
                                    Documentation
                                </h4>
                                <span class="bg-slate-100 text-slate-500 text-[9px] px-2 py-0.5 rounded-md font-bold uppercase tracking-wider">Vault</span>
                            </div>
                            <div class="space-y-3 onboarding-scrollbar max-h-[400px] overflow-y-auto">
                                @php
                                    $onboardingFolder = \AppConstants::BaseFolderOnboardingDocuments . $selectedUser->id;
                                    $files = \Illuminate\Support\Facades\Storage::disk('public')->files($onboardingFolder);
                                @endphp
                                @foreach($files as $file)
                                <div class="group flex items-center justify-between p-4 bg-slate-50 hover:bg-white rounded-2xl border border-slate-100 hover:border-primary/30 transition-all hover:shadow-lg hover:shadow-slate-200/50">
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center text-primary group-hover:scale-110 transition-transform">
                                            <span class="material-symbols-outlined">attachment</span>
                                        </div>
                                        <div class="min-w-0">
                                            <p class="text-xs font-bold text-slate-900 truncate">{{ basename($file) }}</p>
                                            <p class="text-[9px] text-slate-400 uppercase font-black tracking-widest">Digital Asset</p>
                                        </div>
                                    </div>
                                    <a href="{{ asset('storage/'.$file) }}" target="_blank" class="w-9 h-9 bg-white flex items-center justify-center rounded-xl text-primary border border-slate-200 hover:border-primary hover:bg-primary hover:text-white transition-all shadow-sm">
                                        <span class="material-symbols-outlined text-lg">open_in_new</span>
                                    </a>
                                </div>
                                @endforeach
                                @if(count($files) == 0)
                                    <div class="p-8 text-center bg-slate-50 rounded-2xl border border-dashed border-slate-200">
                                        <p class="text-[10px] text-slate-400 font-black uppercase tracking-[0.1em]">No supporting files uploaded</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                        {{-- Info --}}
                        <div class="space-y-6">
                            <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                                <h4 class="text-[11px] font-black text-slate-900 uppercase tracking-widest flex items-center gap-2">
                                    <span class="material-symbols-outlined text-slate-400 text-lg">contact_page</span>
                                    Core Intelligence
                                </h4>
                            </div>
                            <div class="space-y-6">
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="p-4 bg-slate-50/50 rounded-xl">
                                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Legal Name</p>
                                        <p class="text-xs font-bold text-slate-900">{{ $selectedUser->getFullName() }}</p>
                                    </div>
                                    <div class="p-4 bg-slate-50/50 rounded-xl">
                                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Corporate ID</p>
                                        <p class="text-xs font-bold text-slate-900 truncate">HITECH-{{ $selectedUser->id }}</p>
                                    </div>
                                </div>
                                <div class="p-4 bg-slate-50/50 rounded-xl">
                                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Residential Coordinates</p>
                                    <p class="text-xs font-bold text-slate-900 leading-relaxed">{{ $selectedUser->address ?? 'No physical address reported' }}</p>
                                </div>
                                <div class="p-4 bg-slate-50/50 rounded-xl">
                                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Communication Channel</p>
                                    <div class="flex items-center gap-2">
                                        <span class="material-symbols-outlined text-xs text-primary">mail</span>
                                        <p class="text-xs font-bold text-slate-900 truncate">{{ $selectedUser->email }}</p>
                                    </div>
                                    <div class="flex items-center gap-2 mt-1">
                                        <span class="material-symbols-outlined text-xs text-primary">phone_iphone</span>
                                        <p class="text-xs font-bold text-slate-900">{{ $selectedUser->phone }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- Control --}}
                        <div class="space-y-6">
                            <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                                <h4 class="text-[11px] font-black text-slate-900 uppercase tracking-widest flex items-center gap-2">
                                    <span class="material-symbols-outlined text-slate-400 text-lg">lock_open</span>
                                    Executive Control
                                </h4>
                            </div>
                            <div class="flex flex-col gap-4">
                                <form method="POST" action="{{ route('onboarding.approve', $selectedUser->id) }}">
                                    @csrf
                                    <button type="submit" class="w-full bg-primary text-white py-4 rounded-2xl text-xs font-black uppercase tracking-widest shadow-xl shadow-primary/20 hover:bg-deep-teal transition-all active:scale-95 flex items-center justify-center gap-3">
                                        <span class="material-symbols-outlined text-xl">new_releases</span>
                                        Grant Dashboard Access
                                    </button>
                                </form>
                                
                                <button onclick="document.getElementById('resubmit-box').classList.toggle('hidden')" class="w-full bg-white border-2 border-slate-100 text-slate-500 py-4 rounded-2xl text-xs font-black uppercase tracking-widest hover:border-red-500 hover:text-red-500 transition-all flex items-center justify-center gap-3">
                                    <span class="material-symbols-outlined text-xl">published_with_changes</span>
                                    Challenge Submission
                                </button>
                                
                                <div id="resubmit-box" class="hidden mt-2 p-6 bg-red-50 rounded-2xl border border-red-100 shadow-inner">
                                    <form method="POST" action="{{ route('onboarding.resubmit', $selectedUser->id) }}">
                                        @csrf
                                        <p class="text-[10px] font-black text-red-600 uppercase tracking-widest mb-3 italic">Identify Required Corrections:</p>
                                        <textarea name="notes" placeholder="Detailed audit notes for candidate..." class="w-full px-4 py-3 text-sm border-red-200 rounded-xl mb-4 focus:ring-red-500 focus:border-red-500 bg-white placeholder:text-red-300 font-medium" rows="3" required></textarea>
                                        <button type="submit" class="w-full bg-red-600 text-white py-3 rounded-xl text-[10px] font-black uppercase tracking-[0.2em] shadow-lg shadow-red-200 hover:bg-red-700 transition-all">Relay Instruction</button>
                                    </form>
                                </div>
                            </div>
                            <div class="p-6 bg-slate-50 rounded-2xl border border-slate-100 border-dashed">
                                <div class="flex items-start gap-4">
                                    <span class="material-symbols-outlined text-primary text-lg mt-0.5">info</span>
                                    <p class="text-[10px] text-slate-500 font-bold leading-relaxed uppercase tracking-widest group">
                                        Approving this candidate will transition their system status to <span class="text-primary underline">ACTIVE</span> and provision all core platform modules.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @else
                <div class="bg-white/40 backdrop-blur-sm rounded-[3rem] p-24 text-center border-2 border-dashed border-slate-200/60 group hover:border-primary/20 transition-all flex flex-col items-center justify-center">
                    <div class="relative mb-8">
                        <div class="w-32 h-32 bg-white rounded-[2rem] shadow-2xl shadow-slate-200 flex items-center justify-center text-slate-200 group-hover:text-primary/20 transition-colors">
                            <span class="material-symbols-outlined text-7xl font-light">person_search</span>
                        </div>
                        <div class="absolute -top-4 -right-4 w-12 h-12 bg-primary/5 rounded-full animate-ping"></div>
                    </div>
                    <h3 class="text-xl font-black text-slate-900 uppercase tracking-widest mb-3">Audit Inspector Idle</h3>
                    <p class="text-slate-500 max-w-xs text-sm font-medium leading-relaxed">Please select a pending candidate from the submission pipeline above to initialize deep audit proceedings.</p>
                </div>
            @endif
        </div>

        {{-- Right Pane: Recently Approved --}}
        <div class="xl:col-span-1 h-full">
            <div class="bg-white rounded-[2.5rem] shadow-2xl shadow-slate-200/60 border border-slate-200 overflow-hidden sticky top-8">
                <div class="p-8 border-b border-slate-100 bg-slate-50/50">
                    <h3 class="font-black text-slate-900 uppercase tracking-[0.2em] text-[10px] flex items-center gap-3">
                        <div class="w-2 h-6 bg-green-500 rounded-full"></div>
                        Recent Success
                    </h3>
                </div>
                <div class="divide-y divide-slate-50 max-h-[500px] overflow-y-auto onboarding-scrollbar">
                    @forelse($recentlyApproved as $approved)
                    <div class="p-6 flex items-center gap-5 group hover:bg-green-50/10 transition-all border-l-4 border-transparent hover:border-green-500">
                        <div class="relative">
                            <div class="w-12 h-12 rounded-[1.25rem] bg-gradient-to-tr from-slate-50 to-white flex items-center justify-center border border-slate-100 shadow-sm group-hover:scale-105 transition-transform overflow-hidden font-black text-slate-400">
                                @if($approved->profile_picture)
                                    <img src="{{ asset('storage/'.$approved->profile_picture) }}" class="w-full h-full object-cover">
                                @else
                                    {{ $approved->getInitials() }}
                                @endif
                            </div>
                            <div class="absolute -bottom-1 -right-1 w-5 h-5 bg-green-500 border-4 border-white rounded-full flex items-center justify-center">
                                <span class="material-symbols-outlined text-[10px] text-white font-black">done</span>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-black text-slate-900 truncate tracking-tight">{{ $approved->getFullName() }}</p>
                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-0.5 truncate">{{ $approved->team->name ?? 'Core System' }} • <span class="text-green-600/60 font-black">{{ \Carbon\Carbon::parse($approved->onboarding_completed_at)->diffForHumans(null, true) }}</span></p>
                        </div>
                    </div>
                    @empty
                    <div class="p-16 text-center">
                        <span class="material-symbols-outlined text-slate-100 text-6xl mb-4 block">history</span>
                        <p class="text-[10px] text-slate-300 font-bold uppercase tracking-[0.25em]">Registry Empty</p>
                    </div>
                    @endforelse
                </div>
                <div class="p-8 border-t border-slate-50">
                    <a href="{{ route('employees.index') }}" class="w-full flex items-center justify-center gap-3 bg-slate-50 hover:bg-slate-100 text-slate-500 py-4 rounded-2xl text-[10px] font-black uppercase tracking-[0.25em] transition-all border border-slate-100">
                        Global Registry
                        <span class="material-symbols-outlined text-xs">arrow_forward_ios</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Invitation Modal (Re-using standard design) --}}
<div id="onboardingInviteModal" class="hidden">
    <div class="onboarding-modal-backdrop" onclick="closeOnboardingModal()"></div>
    <div class="onboarding-modal-content animate__animated animate__zoomIn animate__faster">
        <div class="p-8 pb-4 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-primary/10 rounded-2xl flex items-center justify-center text-primary">
                    <span class="material-symbols-outlined text-2xl">person_add_alt_1</span>
                </div>
                <div>
                    <h2 class="text-xl font-black text-slate-900 tracking-tight">Candidate Deployment</h2>
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-0.5">Initialize Onboarding Invitation</p>
                </div>
            </div>
            <button onclick="closeOnboardingModal()" class="w-10 h-10 flex items-center justify-center text-slate-400 hover:bg-slate-100 rounded-xl transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <div class="p-8 pt-6">
            <form id="onboardingInviteForm" action="{{ route('employees.initiateOnboarding') }}" method="POST" class="space-y-6">
                @csrf
                <div class="grid grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Given Name</label>
                        <input type="text" name="firstName" class="w-full px-4 py-3 bg-slate-50 border-slate-200 rounded-2xl focus:ring-primary/20 focus:border-primary font-bold text-sm" placeholder="John" required>
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Family Name</label>
                        <input type="text" name="lastName" class="w-full px-4 py-3 bg-slate-50 border-slate-200 rounded-2xl focus:ring-primary/20 focus:border-primary font-bold text-sm" placeholder="Doe" required>
                    </div>
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Verified Digital ID (Email)</label>
                    <input type="email" name="email" class="w-full px-4 py-3 bg-slate-50 border-slate-200 rounded-2xl focus:ring-primary/20 focus:border-primary font-bold text-sm" placeholder="candidate@provider.com" required>
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Telecommunications Handle</label>
                    <input type="text" name="phone" class="w-full px-4 py-3 bg-slate-50 border-slate-200 rounded-2xl focus:ring-primary/20 focus:border-primary font-bold text-sm" placeholder="Mobile Number" required maxlength="10">
                </div>
                <div class="grid grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Operational Role</label>
                        <select name="role" class="w-full px-4 py-3 bg-slate-50 border-slate-200 rounded-2xl focus:ring-primary/20 focus:border-primary font-bold text-sm" required>
                            <option value="">Select Protocol</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->name }}">{{ $role->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Assigned Sector</label>
                        <select name="teamId" class="w-full px-4 py-3 bg-slate-50 border-slate-200 rounded-2xl focus:ring-primary/20 focus:border-primary font-bold text-sm" required>
                            <option value="">Select Division</option>
                            @foreach($teams as $team)
                                <option value="{{ $team->id }}">{{ $team->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="pt-6 border-t border-slate-100 flex gap-4">
                    <button type="button" onclick="closeOnboardingModal()" class="flex-1 py-4 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] hover:text-slate-600 transition-colors">Abort Procedure</button>
                    <button type="submit" class="flex-[2] bg-primary text-white py-4 rounded-2xl text-[10px] font-black uppercase tracking-[0.2em] shadow-xl shadow-primary/20 hover:bg-deep-teal transition-all">Relay Invitation</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('page-script')
<script>
    function openOnboardingModal() {
        document.getElementById('onboardingInviteModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
    function closeOnboardingModal() {
        document.getElementById('onboardingInviteModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }
</script>
@endsection
