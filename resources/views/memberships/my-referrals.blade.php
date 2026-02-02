@extends('layouts.app')

@section('title', 'Refer & Earn')

@section('content')
<div class="form-container">
    <div class="form-header">
        <h1 class="form-title">Invite Friends & Referrals</h1>
        <p class="text-muted">Invite your colleagues and friends to join ATS Job Portal and help them find their next big opportunity.</p>
    </div>

    <div class="row mb-4">
        <div class="col-12">
            <div class="card p-4">
                <h3 style="font-size: 18px; margin-bottom: 15px;">Your Referral Stats</h3>
                <div class="row text-center">
                    <div class="col-6">
                        <div style="font-size: 24px; font-weight: 600;">{{ $membership->referral_count }}</div>
                        <div class="small text-muted">Friends Invited & Joined</div>
                    </div>
                    <div class="col-6">
                        <div style="font-size: 24px; font-weight: 600;">{{ ucfirst($membership->membership_type) }}</div>
                        <div class="small text-muted">Current Plan</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card p-4 h-100 mb-4" style="background: #f8f9fa; border: 1px dashed #0078D4;">
                <h3 style="font-size: 18px; margin-bottom: 15px;">Your Referral Code</h3>
                <div class="d-flex align-items-center gap-3">
                    <div style="font-size: 16px; font-weight: 600; color: #0078D4; padding: 10px 20px; background: white; border-radius: 8px; border: 1px solid #edebe9; word-break: break-all;">
                        {{ url('/welcome?ref=' . $membership->referral_code) }}
                    </div>
                    <button id="copyBtn" class="btn btn-primary" onclick="copyToClipboard('{{ url('/welcome?ref=' . $membership->referral_code) }}')">
                        <i class="fas fa-copy"></i> <span id="copyBtnText">Copy</span>
                    </button>
                </div>
                <p class="mt-3 small text-muted">Share this code with your friends. They can enter it during signup.</p>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card p-4 h-100 mb-4">
                <h3 style="font-size: 18px; margin-bottom: 15px;">Invite via Email</h3>
                <form action="{{ route('memberships.invite') }}" method="POST">
                    @csrf
                    <div class="form-group mb-3">
                        <label>Friend's Email Address</label>
                        <input type="email" name="email" class="form-control" placeholder="enter.email@example.com" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Send Invitation</button>
                </form>
                <p class="mt-3 small text-muted">We'll send them a professional invitation with your unique link.</p>
            </div>
        </div>
    </div>

    <div class="mt-5">
        <h3 class="form-title" style="font-size: 20px;">Your Successful Referrals</h3>
        <div class="table-container mt-3">
            <table class="table">
                <thead>
                    <tr>
                        <th>Joined User</th>
                        <th>User Type</th>
                        <th>Joined Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($referrals as $ref)
                        <tr>
                            <td>
                                <strong>{{ $ref->user->name ?? 'User' }}</strong><br>
                                <small class="text-muted">{{ $ref->user->email ?? '' }}</small>
                            </td>
                            <td><span class="badge bg-secondary">{{ ucfirst($ref->user->user_type ?? 'N/A') }}</span></td>
                            <td>{{ $ref->created_at->format('M d, Y') }}</td>
                            <td><span class="badge bg-success">Active</span></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted">You haven't referred anyone yet. Start sharing your code!</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="pagination-container">
            {{ $referrals->links() }}
        </div>
    </div>
</div>

<script>
function copyToClipboard(text) {
    const btn = document.getElementById('copyBtn');
    const btnText = document.getElementById('copyBtnText');
    
    navigator.clipboard.writeText(text).then(function() {
        // Disable button and change text
        btn.disabled = true;
        btn.style.opacity = '0.6';
        btnText.textContent = 'Copied!';
        
        // Re-enable after 1 second
        setTimeout(() => {
            btn.disabled = false;
            btn.style.opacity = '1';
            btnText.textContent = 'Copy';
        }, 1000);
    }, function(err) {
        console.error('Could not copy text: ', err);
        btnText.textContent = 'Failed';
        setTimeout(() => {
            btnText.textContent = 'Copy';
        }, 1000);
    });
}
</script>
@endsection
