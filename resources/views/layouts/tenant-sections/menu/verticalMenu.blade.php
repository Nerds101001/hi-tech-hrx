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
        @if($settings->company_logo ?? false)
          <img src="{{ asset('storage/' . $settings->company_logo) }}" alt="Logo">
        @else
          <img src="{{asset('assets/img/logo.png')}}" alt="Logo">
        @endif
      </div>
    </a>
  </div>

  {{-- 2. SCROLLABLE MENU LIST --}}
  <div class="hitech-menu-container">
    <ul class="hitech-menu-list menu-inner">

      @foreach ($menuData[3]->menu as $menu)
        @php
          // 1. Addon Check
          if(isset($menu->addon) && !$addonService->isAddonEnabled($menu->addon)) continue;

          // 2. Standard Addon Check
          if(isset($menu->standardAddon) && !in_array($menu->standardAddon, $settings->available_modules ?? [])) continue;

          // 3. Role Check
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
                  if (str_contains($currentRouteName, $slug) && strpos($currentRouteName, $slug) === 0) {
                    $activeClass = 'active open';
                  }
                }
              } else {
                if (str_contains($currentRouteName, ($menu->slug ?? '')) && strpos($currentRouteName, ($menu->slug ?? '')) === 0) {
                  $activeClass = 'active open';
                }
              }
            }
          @endphp

          <li class="hitech-menu-item {{$activeClass}}">
            <a href="{{ $isLocked ? 'javascript:void(0);' : (isset($menu->url) ? url($menu->url) : 'javascript:void(0);') }}"
               class="hitech-menu-link {{ isset($menu->submenu) ? 'has-submenu' : '' }} {{ $isLocked ? 'hitech-menu-locked' : '' }}"
               @if (isset($menu->target) && !empty($menu->target)) target="_blank" @endif>
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
                       if(isset($submenu->standardAddon) && !in_array($submenu->standardAddon, $settings->available_modules ?? [])) continue;
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
          <span class="hitech-user-role">{{ auth()->user() ? (auth()->user()->getRoleNames()->first() ?? 'Employee') : 'Employee' }}</span>
        </div>
        <div class="hitech-profile-actions">
           <i class="bx bx-cog"></i>
        </div>
      </div>
  </div>

</aside>

<script>
document.addEventListener('DOMContentLoaded', function() {
  
  // 1. Handle Submenu Accordion Toggles
  const menuLinks = document.querySelectorAll('.hitech-menu-link.has-submenu');
  
  menuLinks.forEach(link => {
    link.addEventListener('click', function(e) {
      e.preventDefault();
      
      const parentItem = this.parentElement;
      const isOpen = parentItem.classList.contains('open');
      
      // Close other open menus at the same level (accordion behavior)
      const siblings = parentItem.parentElement.querySelectorAll('.hitech-menu-item.open');
      siblings.forEach(sibling => {
        if (sibling !== parentItem) {
          sibling.classList.remove('open');
        }
      });
      
      if (isOpen) {
        parentItem.classList.remove('open');
      } else {
        parentItem.classList.add('open');
      }
    });
  });

  // 2. Handle Mobile Sidebar Toggle
  // We attach click listeners to the hamburger icons on the navbar
  const layoutMenuToggles = document.querySelectorAll('.layout-menu-toggle');
  const htmlEl = document.documentElement;
  
  layoutMenuToggles.forEach(toggle => {
    toggle.addEventListener('click', function(e) {
      e.preventDefault();
      e.stopPropagation();
      
      // Sneat uses 'layout-menu-expanded' on the <html> tag to show the sidebar on mobile
      if (htmlEl.classList.contains('layout-menu-expanded')) {
        htmlEl.classList.remove('layout-menu-expanded');
      } else {
        htmlEl.classList.add('layout-menu-expanded');
      }
    });
  });

  // 3. Handle clicking the overlay to close the sidebar on mobile
  const layoutOverlay = document.querySelector('.layout-overlay');
  if (layoutOverlay) {
    layoutOverlay.addEventListener('click', function(e) {
      if (htmlEl.classList.contains('layout-menu-expanded')) {
        htmlEl.classList.remove('layout-menu-expanded');
      }
    });
  }

});
</script>
