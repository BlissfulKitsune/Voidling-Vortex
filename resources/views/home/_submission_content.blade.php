<h1>
    {{ $submission->prompt_id ? 'Submission' : 'Claim' }} (#{{ $submission->id }})
    <span class="float-right badge badge-{{ $submission->status == 'Pending' ? 'secondary' : ($submission->status == 'Approved' ? 'success' : 'danger') }}">{{ $submission->status }}</span>
</h1>

<div class="mb-1">
    <div class="row">
        <div class="col-md-2 col-4"><h5>User</h5></div>
        <div class="col-md-10 col-8">{!! $submission->user->displayName !!}</div>
    </div>
    @if($submission->prompt_id)
        <div class="row">
            <div class="col-md-2 col-4"><h5>Prompt</h5></div>
            <div class="col-md-10 col-8">{!! $submission->prompt->displayName !!}</div>
        </div>
    @endif
    <div class="row">
        <div class="col-md-2 col-4"><h5>URL</h5></div>
        <div class="col-md-10 col-8"><a href="{{ $submission->url }}">{{ $submission->url }}</a></div>
    </div>
    <div class="row">
        <div class="col-md-2 col-4"><h5>Submitted</h5></div>
        <div class="col-md-10 col-8">{!! format_date($submission->created_at) !!} ({{ $submission->created_at->diffForHumans() }})</div>
    </div>
    @if($submission->status != 'Pending')
        <div class="row">
            <div class="col-md-2 col-4"><h5>Processed</h5></div>
            <div class="col-md-10 col-8">{!! format_date($submission->updated_at) !!} ({{ $submission->updated_at->diffForHumans() }}) by {!! $submission->staff->displayName !!}</div>
        </div>
    @endif
</div>
<h2>Comments</h2>
<div class="card mb-3"><div class="card-body">{!! nl2br(htmlentities($submission->comments)) !!}</div></div>
@if(Auth::check() && $submission->staff_comments && ($submission->user_id == Auth::user()->id || Auth::user()->hasPower('manage_submissions')))
    <h5 class="text-danger">Staff Comments</h5>
    <div class="card border-danger mb-3"><div class="card-body">{!! nl2br(htmlentities($submission->staff_comments)) !!}</div></div>
@endif

<h2>Rewards</h2>
<table class="table table-sm">
    <thead>
        <tr>
            <th width="70%">Reward</th>
            <th width="30%">Amount</th>
        </tr>
    </thead>
    <tbody>
        @foreach(parseAssetData($submission->data) as $type)
            @foreach($type as $asset)
                <tr>
                    <td>{!! $asset['asset']->displayName !!}</td>
                    <td>{{ $asset['quantity'] }}</td>
                </tr>
            @endforeach
        @endforeach
    </tbody>
</table>

<h2>Characters</h2>
@foreach($submission->characters as $character)
    <div class="submission-character-row mb-2">
        <div class="submission-character-thumbnail"><a href="{{ $character->character->url }}"><img src="{{ $character->character->image->thumbnailUrl }}" class="img-thumbnail" /></a></div>
        <div class="submission-character-info card ml-2">
            <div class="card-body">
                <div class="submission-character-info-content">
                    <h3 class="mb-2 submission-character-info-header"><a href="{{ $character->character->url }}">{{ $character->character->fullName }}</a></h3>
                    <div class="submission-character-info-body">
                    <table class="table table-sm mb-0">
                        <thead>
                            <tr>
                                <th width="70%">Reward</th>
                                <th width="30%">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach(parseAssetData($character->data) as $type)
                                @foreach($type as $asset)
                                    <tr>
                                        <td>{!! $asset['asset']->displayName !!}</td>
                                        <td>{{ $asset['quantity'] }}</td>
                                    </tr>
                                @endforeach
                            @endforeach
                        </tbody>
                    </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endforeach