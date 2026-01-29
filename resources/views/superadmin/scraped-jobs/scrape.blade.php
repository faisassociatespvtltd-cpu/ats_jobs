@extends('layouts.app')

@section('title', 'Scrape Jobs from Source')

@section('content')
    <div class="form-header">
        <h1 class="form-title">Scrape Jobs from External Sources</h1>
    </div>

    <div class="form-container">
        <form method="POST" action="{{ route('superadmin.scraped-jobs.execute') }}">
            @csrf

            <div class="form-group">
                <label for="source">Select Source Platform</label>
                <select name="source" id="source" class="form-control" required>
                    <option value="">-- Select Platform --</option>
                    <option value="whatsapp">WhatsApp Business</option>
                    <option value="linkedin">LinkedIn Jobs</option>
                    <option value="facebook">Facebook Jobs</option>
                    <option value="instagram">Instagram Business</option>
                    <option value="other">Other</option>
                </select>
            </div>

            <div class="form-group">
                <label for="url">Source URL</label>
                <input type="url" name="url" id="url" class="form-control" placeholder="https://example.com/jobs" required>
                <small class="text-muted">Enter the URL of the job listing or company page</small>
            </div>

            <div class="alert alert-info"
                style="background-color: #e3f2fd; border: 1px solid #90caf9; padding: 12px; border-radius: 4px; margin-top: 16px;">
                <strong>Note:</strong> This feature requires API integration with the selected platform.
                The system will log the scraping attempt and notify you when API credentials are configured.
            </div>

            <div class="form-actions" style="margin-top: 24px;">
                <button type="submit" class="btn btn-primary">Start Scraping</button>
                <a href="{{ route('superadmin.scraped-jobs') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>

        <div class="form-container" style="margin-top: 24px;">
            <h3 style="font-size: 16px; margin-bottom: 16px;">Scraping Guidelines</h3>
            <ul style="line-height: 1.8;">
                <li><strong>WhatsApp Business:</strong> Only public business information is accessible</li>
                <li><strong>LinkedIn:</strong> Requires LinkedIn API access token</li>
                <li><strong>Facebook:</strong> Requires Facebook Graph API credentials</li>
                <li><strong>Instagram:</strong> Requires Instagram Business API access</li>
                <li><strong>Compliance:</strong> All scraping respects robots.txt and platform policies</li>
            </ul>
        </div>
    </div>
@endsection