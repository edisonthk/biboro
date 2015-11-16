@if(!is_null($user))
ユーザ: {{ $user->name }}({{$user->id}})
From:  {{ $user->email }}
@else
ユーザ: ログインしていません
@endif

{{$feedback}}
