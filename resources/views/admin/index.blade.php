@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-6">
            <div class="justify-content-center">Користувачі:</div>
            <div class="justify-content-center">
                @foreach ($users as $u)
                    <form 
                        action="{{ url('admin/change-user/'.$u->id) }}" 
                        method="POST"
                        style="margin-right: 20px;">
                        {{ csrf_field() }}
                        <div class="form-part">
                            Ім'я: {{ $u->name }}
                        </div>
                        <div class="form-part">
                            Email: {{ $u->email }}
                        </div>
                        <div class="form-part">
                            Інститут/факультет: {{ $u->employee->department->faculty->name ?? '-' }}
                        </div>
                        <div class="form-part">
                            Відділ/кафедра: 
                            <select name="department">
                                @if(empty($u->employee->department))
                                    <option selected value="none">Не визначено</option>
                                @endif
                                @foreach ($departments as $d)
                                    <option @if( ! empty($u->employee->department) && $u->employee->department->id === $d->id) selected @endif value="{{ $d->id }}">{{ $d->name }}({{ $d->faculty->name }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-part">
                            Посада: 
                            <select name="position">
                                @if(empty($u->employee->position))
                                    <option selected value="none">Не визначено</option>
                                @endif
                                @foreach ($positions as $p)
                                    <option @if( ! empty($u->employee->position) && $u->employee->position->id === $p->id) selected @endif value="{{ $p->id }}">{{ $p->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <button type="submit" class="btn btn-success">Змінити</button>
                        </div>
                    </form>
                @endforeach
            </div>
            <br/>
            {{ $users->links() }}
        </div>
        <div class="col-md-6">
            <div class="row justify-content-center">Доступні вибори:</div>
            <div class= justify-content-center">
                @foreach ($votings as $v)
                    <div style="margin-bottom: 5px" class="row justify-content-center">
                        <div style="margin-right: 5px; margin-top: 3px">
                            <a href="{{ url('admin/voting/'.$v->id) }}">{{ $v->name }}</a>
                        </div>
                        @if ( ! $v->is_published)
                            <a class="btn btn-danger" href="{{ url('admin/voting/'.$v->id.'/delete') }}">Видалити</a>
                        @endif
                    </div>
                @endforeach
            </div>
            <div class="row justify-content-center">
                <!--<div class="col-md-4">-->
                    <a class="btn btn-success" href="{{ url('admin/create-voting-form') }}">Створити вибори</a>
                <!--</div>-->
                <!--<div class="col-md-4 ml-auto">
                    <a href="{{ url('admin/clients') }}">Клієнти</a>
                </div>-->
            </div>
        </div>
    </div>
</div>
@endsection