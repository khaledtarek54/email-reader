@foreach ($mails as $mail)
<a href="{{ route('mailview', ['id' => $mail->id]) }}" style="text-decoration: none; color: inherit;">
    <li class="email-item">
        <div class="email-left">
            <div class="avatar" style="background-color: #fbbc05;">{{ strtoupper(substr($mail->from, 0, 1)) }}</div>
            <div class="email-info">
                <div class="sender">{{ $mail->from }}</div>
                <div class="subject">{{ $mail->subject . " " . "....." }}</div>
                <div class="snippet">{{ strip_tags($mail->html_body) . " " . "....." }}</div>
            </div>
        </div>
        <div class="email-right">
            <div class="email-time">
                {{ \Carbon\Carbon::parse($mail->mail_datetime)->isToday() ? 
                        \Carbon\Carbon::parse($mail->mail_datetime)->format('g:i A') : 
                        \Carbon\Carbon::parse($mail->mail_datetime)->format('M d, Y') 
                }}
            </div>
        </div>
    </li>
</a>
@endforeach
