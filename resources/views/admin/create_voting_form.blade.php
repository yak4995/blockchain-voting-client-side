@extends('layouts.app')

@section('content')
<div class="container">
    <a class="btn btn-danger" href="{{ url('/admin') }}">Назад</a>
    <div class="row justify-content-center">Нові вибори:</div>
    <div class="row justify-content-center">
        @if (count($errors) > 0)
            <div class="alert alert-danger">
                <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
                </ul>
            </div>
        @endif
        <form action="{{ url('admin/create-voting') }}" method="POST">
            {{ csrf_field() }}
            <div class="form-group">
                <label for="name">Назва:</label>
                <input type="text" class="form-control" name="name" id="name" />
            </div>
            <div class="form-group">
                <label for="description">Опис:</label>
                <textarea rows="3" class="form-control" name="description" id="description"></textarea>
            </div>
            <div class="form-group">
                <label for="start_time">Початок:</label>
                <input type="datetime-local" class="form-text text-muted" name="start_time" id="start_time" />
            </div>
            <div class="form-group">
                <label for="end_time">Кінець:</label>
                <input type="datetime-local" class="form-text text-muted" name="end_time" id="end_time" />
            </div>
            <div class="form-group">
                <label for="">Інститут/факультет:</label>
                <select id="faculty" name="faculty" class="form-control">
                    <option selected value="none">Не визначено</option>
                    @foreach ($faculties as $f)
                        <option value="{{ $f->id }}">{{ $f->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="">Відділ/кафедра:</label>
                <select id="department" name="department" class="form-control">
                    <option selected value="none">Не визначено</option>
                    @foreach ($departments as $d)
                        <option value="{{ $d->id }}">{{ $d->name }}({{ $d->faculty->name }})</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="">Посада:</label>
                <select id="position" name="position" class="form-control">
                    <option selected value="none">Не визначено</option>
                    @foreach ($positions as $p)
                        <option value="{{ $p->id }}">{{ $p->name }}</option>
                    @endforeach
                </select>
            </div>
            <div style="text-align: center" class="col-md-12">
                <button type="submit" class="btn btn-success">Створити</button>
            </div>
        </form>
    </div>
</div>
@endsection