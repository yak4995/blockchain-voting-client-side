@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col">
            Ім'я: {{ $profile->name }}
        </div>
        <div class="col">
            Email: {{ $profile->email }}
        </div>
        <div class="col">
            Інститут/факультет: {{ $profile->employee->department->faculty->name ?? '-' }}
        </div>
        <div class="col">
            Відділ/кафедра: {{ $profile->employee->department->name ?? '-' }}
        </div>
        <div class="col">
            Посада: {{ $profile->employee->position->name ?? '-' }}
        </div>
    </div>
    <div class="row justify-content-center"
            style="margin-top: 20px">
        Доступні вибори:
    </div>
    @foreach ($votings as $v)
        <div class="row justify-content-center">
            {{ $v->name }}
        </div>
    @endforeach
</div>
@endsection
