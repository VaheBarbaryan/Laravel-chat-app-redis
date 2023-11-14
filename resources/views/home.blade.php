@extends('layouts.app')

@section('content')
    <div class="container">
        <chat :user_id="{{ \Illuminate\Support\Facades\Auth::user()->id }}"></chat>
    </div>
@endsection


@push('scripts')
    <script src="{{ asset('/js/app.min.js') }}"></script>
{{--    <script src="https://cdn.socket.io/socket.io-3.0.1.min.js"></script>--}}
{{--    <script>--}}
{{--        const socket = io('ws://localhost:8005', { transports : ['websocket'] });--}}

{{--        socket.on("connect",() => {--}}
{{--            socket.emit('user_connected',{id: "{{ \Illuminate\Support\Facades\Auth::user()->id }}"});--}}
{{--        });--}}

{{--        socket.on('updateUserStatus', (data) => {--}}
{{--            console.log("data", data);--}}
{{--            alert(JSON.stringify(data));--}}
{{--        });--}}
{{--    </script>--}}
@endpush