@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-4">
            <div>Ім'я: {{ $profile->name }}</div>
            <div>Email: {{ $profile->email }}</div>
            <div>Інститут/факультет: {{ $profile->employee->department->faculty->name ?? '-' }}</div>
            <div>Відділ/кафедра: {{ $profile->employee->department->name ?? '-' }}</div>
            <div>Посада: {{ $profile->employee->position->name ?? '-' }}</div>
        </div>
        <div class="col-md-8">
            <div class="row justify-content-center"
                    style="margin-top: 20px">
                Доступні вибори:
            </div>
            @foreach ($votings as $v)
                <div class="row justify-content-center">
                    <a href="{{ url('voting/'.$v->id) }}">{{ $v->name }}</a>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
