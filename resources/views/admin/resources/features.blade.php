@extends('layouts.admin')

@section('content')
<div class="page-header">
    <div class="page-header-left">
        <h1 class="page-title">CartLite — Feature List</h1>
        <p class="page-subtitle">Complete feature overview of the e-commerce platform</p>
    </div>
    <div class="page-header-actions">
        <a href="{{ route('admin.resources.brochure') }}" class="btn-admin btn-primary-admin">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
            Brochure
        </a>
        <a href="{{ route('admin.resources.guide') }}" class="btn-admin btn-primary-admin">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg>
            Admin Guide
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body feature-content">
        {!! $content !!}
    </div>
</div>

<style>
.feature-content h1 { font-size: 28px; font-weight: 700; color: #1a1a2e; margin-bottom: 15px; }
.feature-content h2 { font-size: 20px; font-weight: 600; color: #0f3460; margin: 30px 0 12px; padding-bottom: 8px; border-bottom: 2px solid #e94560; }
.feature-content h3 { font-size: 16px; font-weight: 600; color: #1a1a2e; margin: 20px 0 8px; }
.feature-content p { margin: 8px 0; line-height: 1.7; color: #333; }
.feature-content ul, .feature-content ol { margin: 8px 0 8px 20px; }
.feature-content li { margin: 4px 0; line-height: 1.6; color: #333; }
.feature-content li strong { color: #0f3460; }
.feature-content p strong { color: #0f3460; }
.feature-content table { width: 100%; border-collapse: collapse; margin: 15px 0; font-size: 14px; }
.feature-content th { background: #1a1a2e; color: #fff; padding: 10px 14px; text-align: left; font-weight: 600; }
.feature-content td { padding: 10px 14px; border-bottom: 1px solid #e0e0e0; color: #333; }
.feature-content tr:nth-child(even) td { background: #f8f9fa; }
.feature-content .code-block { background: #1a1a2e; color: #e0e0e0; padding: 16px; border-radius: 8px; font-size: 13px; overflow-x: auto; margin: 12px 0; }
.feature-content br { display: block; content: ""; margin: 10px 0; }
</style>
@endsection
