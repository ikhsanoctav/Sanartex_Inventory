@php
    $isLogin = request()->routeIs('filament.admin.auth.login');
@endphp
<div style="border-top: 1px solid #e2e8f0; background: {{ $isLogin ? '#ffffff' : 'transparent' }}; padding: 1rem {{ $isLogin ? '2rem' : '0' }}; display: flex; justify-content: space-between; align-items: center; font-size: 0.875rem; color: #64748b; {{ $isLogin ? 'position: fixed; bottom: 0; left: 0; width: 100%; z-index: 10;' : 'width: 100%; margin-top: 2rem;' }} box-sizing: border-box;">
    <div>
        <strong style="color: #334155;">Sanartex</strong> &copy; {{ date('Y') }} Sanartex Inventori. All rights reserved.
    </div>
    <div style="display: flex; gap: 1.5rem;">
        <a href="#" style="color: #64748b; text-decoration: none;">Privacy Policy</a>
        <a href="#" style="color: #64748b; text-decoration: none;">Terms of Service</a>
        <a href="#" style="color: #64748b; text-decoration: none;">Contact Support</a>
    </div>
</div>
