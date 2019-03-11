@extends('layouts.app')

@section('content')
<div class="container">
    <a class="btn btn-danger" href="{{ url('/admin') }}">Назад</a>
    <div class="row justify-content-center">
        <div class="col-md-12">
            Назва: {{ $voting->name }}
        </div>
        <div class="col-md-12">
            Опис: {{ $voting->description }}
        </div>
        <div class="col-md-12">
            Початок: {{ $voting->start_time }}
        </div>
        <div class="col-md-12">
            Кінець: {{ $voting->end_time }}
        </div>
        <div class="col-md-12">
            Інститут/факультет: {{ $voting->faculty->name }}
        </div>
        <div class="col-md-12">
            Відділ/кафедра: {{ $voting->department->name }}({{ $voting->department->faculty->name }})
        </div>
        <div class="col-md-12">
            Посада: {{ $voting->position->name }}
        </div>
        @if ( ! $voting->is_published)
            <div class="col-md-12">
                <a class="btn btn-success" href="{{ url('admin/voting/'.$voting->id.'/publish') }}">Опублікувати</a>
            </div>
        @endif
        <div class="col-md-12">
            Кандидиати:
        </div>
        <div class="col-md-12">
            @foreach ($voting->candidates as $c)
                <div class="col-md-8">
                    Ім'я: {{ $c->name }} <br/>
                    Опис: {{ $c->description }} <br/>
                    @if ( ! $voting->is_published)
                        <a class="btn btn-danger" href="{{ url('admin/candidate/'.$c->id.'/delete') }}">Видалити</a>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection