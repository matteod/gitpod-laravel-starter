@component('mail::message')
# Introduction

Lista di errori sui piani editoriali

@component('mail::table')
| Titolo       | Errori         |
| ------------- |:-------------:|
@foreach($data as $item)
| {{$item['title']}}      | {{$item['errors_count']}}      |
@endforeach
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent