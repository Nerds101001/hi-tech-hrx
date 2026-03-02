@php
  use App\Services\AddonService\IAddonService;
  use Illuminate\Support\Facades\Route;
  $configData = Helper::appClasses();
  $addonService = app(IAddonService::class);
  $isLocked = auth()->check() && auth()->user()->status === \App\Enums\UserAccountStatus::ONBOARDING_SUBMITTED;
@endphp

<aside id="layout-menu" class="hitech-sidebar">
  
  {{-- 1. LOGO HUB --}}
  <div class="hitech-logo-area">
    <a href="{{url('/')}}" class="hitech-logo-link">
      <div class="hitech-logo-container">
         <img src="{{asset('assets/img/logo.png')}}" alt="Logo">
      </div>
      {{-- <span class="hitech-brand-text">{{config('variables.templateName')}}</span> --}}
    </a>
  </div>

  {{-- 2. SCROLLABLE MENU LIST --}}
  <div class="hitech-menu-container">
    <ul class="hitech-menu-list">
      @php
        // Determine which menu to show: SuperAdmin uses index 0, Tenant uses index 3
        $menuIndex = 3; 
        if (Auth::check() && Auth::user()->hasRole('super_admin')) {
            $menuIndex = 0;
        }
        $targetMenu = $menuData[$menuIndex]->menu ?? [];
      @endphp

      @foreach ($targetMenu as $menu)
        @php
          // 1. Addon Check
          if(isset($menu->addon) && !$addonService->isAddonEnabled($menu->addon)) continue;
          
          // 2. Role Check
          if(isset($menu->roles)) {
              if (!auth()->user()->hasAnyRole($menu->roles)) continue;
          }
        @endphp

        @if (!isset($menu->menuHeader))
          @php
            $activeClass = '';
            $currentRouteName = Route::currentRouteName();
            if ($currentRouteName === ($menu->slug ?? '')) {
              $activeClass = 'active';
            } elseif (isset($menu->submenu)) {
              if (gettype($menu->slug) === 'array') {
                foreach($menu->slug as $slug) {
                  if (str_contains($currentRouteName,$slug) && strpos($currentRouteName,$slug) === 0) {
                    $activeClass = 'active open';
                  }
                }
              } else {
                if (str_contains($currentRouteName,($menu->slug ?? '')) && strpos($currentRouteName,($menu->slug ?? '')) === 0) {
                  $activeClass = 'active open';
                }
              }
            }
          @endphp
          <li class="hitech-menu-item {{$activeClass}}">
            <a href="{{ $isLocked ? 'javascript:void(0);' : (isset($menu->url) ? url($menu->url) : 'javascript:void(0);') }}"
               class="hitech-menu-link {{ isset($menu->submenu) ? 'has-submenu' : '' }} {{ $isLocked ? 'hitech-menu-locked' : '' }}">
              @isset($menu->icon)
                <i class="hitech-menu-icon {{ $menu->icon }}"></i>
              @endisset
              <span class="hitech-menu-text">{{ isset($menu->name) ? __($menu->name) : '' }}</span>
              @if($isLocked)
                 <i class="bx bx-lock-alt ms-auto menu-lock-icon"></i>
              @endif
            </a>
            @isset($menu->submenu)
               <ul class="hitech-submenu">
                  @foreach($menu->submenu as $submenu)
                    @php
                       // Submenu Role/Addon check
                       if(isset($submenu->addon) && !$addonService->isAddonEnabled($submenu->addon)) continue;
                       if(isset($submenu->roles) && !auth()->user()->hasAnyRole($submenu->roles)) continue;
                    @endphp
                    <li class="hitech-submenu-item {{ Route::currentRouteName() === $submenu->slug ? 'active' : '' }}">
                       <a href="{{ url($submenu->url) }}" class="hitech-submenu-link">
                          {{ __($submenu->name) }}
                       </a>
                    </li>
                  @endforeach
               </ul>
            @endisset
          </li>
        @endif
      @endforeach

    </ul>
  </div>

  {{-- 3. FLOATING PROFILE CARD --}}
  <div class="hitech-profile-card">
      <div class="hitech-profile-info">
        <div class="hitech-avatar">
          <img src="{{ auth()->user() ? auth()->user()->profile_photo_url : asset('assets/img/avatars/1.png') }}" alt="Profile">
        </div>
        <div class="hitech-user-meta">
          <span class="hitech-user-name">{{ auth()->user() ? auth()->user()->name : 'User' }}</span>
          <span class="hitech-user-role">{{ auth()->user() ? (auth()->user()->getRoleNames()->first() ?? 'Admin') : 'Admin' }}</span>
        </div>
        <div class="hitech-profile-actions">
           <i class="bx bx-cog"></i>
        </div>
      </div>
  </div>

</aside>
