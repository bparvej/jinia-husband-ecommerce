@extends('layouts.admin')

@section('content')
<div class="page-header">
    <h2 class="page-title">Email Templates</h2>
    <p class="text-muted" style="margin-top:4px;font-size:0.9rem;">Manage customer order confirmation email templates. One template is active at a time.</p>
</div>

@if (session('success'))
<div class="alert alert-success">
    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
    {{ session('success') }}
</div>
@endif

@if (session('error'))
<div class="alert alert-error">
    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
    {{ session('error') }}
</div>
@endif

<div class="email-templates-grid">
    @foreach($templates as $template)
    <div class="panel email-template-card" x-data="{ editing: false, previewing: false, previewHtml: '', previewSubject: '', saving: false }">
        <div class="panel-header" style="display:flex;justify-content:space-between;align-items:center;">
            <div>
                <h3 style="margin:0;font-size:1.1rem;">{{ $template->label }}</h3>
                <div style="margin-top:6px;display:flex;gap:8px;">
                    @if($template->is_default)
                    <span style="background:#dbeafe;color:#1d4ed8;font-size:0.7rem;padding:2px 8px;border-radius:12px;font-weight:600;">DEFAULT</span>
                    @endif
                    @if($template->is_active)
                    <span style="background:#d1fae5;color:#065f46;font-size:0.7rem;padding:2px 8px;border-radius:12px;font-weight:600;">ACTIVE</span>
                    @endif
                </div>
            </div>
            <div style="display:flex;gap:8px;">
                @if(!$template->is_active)
                <form action="{{ route('admin.settings.email-templates.activate', $template->id) }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn-admin btn-primary-admin" style="padding:5px 12px;font-size:0.8rem;">
                        Set Active
                    </button>
                </form>
                @endif
                <button type="button" class="btn-admin" style="padding:5px 12px;font-size:0.8rem;background:#f3f4f6;border:1px solid #d1d5db;" @click="previewing = !previewing; if(previewing){ fetch('{{ route('admin.settings.email-templates.preview', $template->id) }}').then(r=>r.json()).then(d=>{previewHtml=d.body_html;previewSubject=d.subject;}) }">
                    Preview
                </button>
                <button type="button" class="btn-admin" style="padding:5px 12px;font-size:0.8rem;background:#f3f4f6;border:1px solid #d1d5db;" @click="editing = !editing">
                    Edit
                </button>
            </div>
        </div>

        <!-- Preview Panel -->
        <div x-show="previewing" x-cloak style="padding:0 20px 20px;">
            <div style="background:#f9fafb;border:1px solid #e5e7eb;border-radius:8px;overflow:hidden;">
                <div style="padding:12px 16px;background:#fff;border-bottom:1px solid #e5e7eb;">
                    <p style="margin:0;font-size:0.8rem;color:#6b7280;">Subject: <strong x-text="previewSubject" style="color:#111827;"></strong></p>
                </div>
                <div style="padding:16px;max-height:600px;overflow-y:auto;background:#e5e7eb;">
                    <div x-html="previewHtml" style="background:#fff;border-radius:4px;"></div>
                </div>
            </div>
        </div>

        <!-- Edit Panel -->
        <div x-show="editing" x-cloak style="padding:0 20px 20px;">
            <form action="{{ route('admin.settings.email-templates.update', $template->id) }}" method="POST" @submit="saving = true">
                @csrf
                @method('PUT')
                <div style="margin-bottom:16px;">
                    <label style="display:block;font-weight:600;font-size:0.9rem;margin-bottom:6px;color:#374151;">Email Subject Line</label>
                    <p style="margin:0 0 8px;font-size:0.8rem;color:#6b7280;">Available variables: <code style="background:#f3f4f6;padding:1px 5px;border-radius:3px;">&#123;&#123; $order->order_number &#125;&#125;</code>, <code style="background:#f3f4f6;padding:1px 5px;border-radius:3px;">&#123;&#123; $user->name &#125;&#125;</code></p>
                    <input type="text" name="subject" value="{{ $template->subject }}" style="width:100%;padding:10px 14px;border:1px solid #d1d5db;border-radius:6px;font-size:0.95rem;font-family:monospace;" required>
                </div>
                <div style="margin-bottom:16px;">
                    <label style="display:block;font-weight:600;font-size:0.9rem;margin-bottom:6px;color:#374151;">HTML Body</label>
                    <p style="margin:0 0 8px;font-size:0.8rem;color:#6b7280;">Use Blade syntax: <code style="background:#f3f4f6;padding:1px 5px;border-radius:3px;">&#123;&#123; $order->order_number &#125;&#125;</code>, <code style="background:#f3f4f6;padding:1px 5px;border-radius:3px;">&#123;&#123; $order->total &#125;&#125;</code>, <code style="background:#f3f4f6;padding:1px 5px;border-radius:3px;">&#123;&#123; $user->name &#125;&#125;</code>, <code style="background:#f3f4f6;padding:1px 5px;border-radius:3px;">&#64;foreach(...) &#64;endforeach</code></p>
                    <textarea name="body_html" rows="20" style="width:100%;padding:14px;border:1px solid #d1d5db;border-radius:6px;font-size:0.85rem;font-family:monospace;line-height:1.5;resize:vertical;tab-size:2;" required>{{ $template->body_html }}</textarea>
                </div>
                <div style="display:flex;gap:10px;">
                    <button type="submit" class="btn-admin btn-primary-admin" style="padding:8px 20px;font-size:0.9rem;" :disabled="saving">
                        <span x-show="!saving">Save Template</span>
                        <span x-show="saving">Saving...</span>
                    </button>
                    <button type="button" class="btn-admin" style="padding:8px 20px;font-size:0.9rem;background:#f3f4f6;border:1px solid #d1d5db;color:#4b5563;" @click="editing = false">Cancel</button>
                </div>
            </form>
        </div>
    </div>
    @endforeach
</div>

<style>
.email-templates-grid {
    display: flex;
    flex-direction: column;
    gap: 20px;
    max-width: 900px;
}
.email-template-card {
    border: 1px solid #e5e7eb;
}
[x-cloak] { display: none !important; }
</style>
@endsection
