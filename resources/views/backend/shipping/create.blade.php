@extends('backend.layouts.master')

@section('main-content')

<div class="card">
    <h5 class="card-header">Добавить способ доставки</h5>
    <div class="card-body">
      <form method="post" action="{{route('shipping.store')}}">
        @csrf
        <div class="form-group">
          <label for="inputTitle" class="col-form-label">Тип <span class="text-danger">*</span></label>
        <input id="inputTitle" type="text" name="type" placeholder="Введите тип доставки"  value="{{old('type')}}" class="form-control">
        @error('type')
        <span class="text-danger">{{$message}}</span>
        @enderror
        </div>

        <div class="form-group">
          <label for="price" class="col-form-label">Стоимость <span class="text-danger">*</span></label>
        <input id="price" type="number" name="price" placeholder="Введите стоимость"  value="{{old('price')}}" class="form-control">
        @error('price')
        <span class="text-danger">{{$message}}</span>
        @enderror
        </div>

        <div class="form-group">
          <label for="status" class="col-form-label">Статус <span class="text-danger">*</span></label>
          <select name="status" class="form-control">
              <option value="active">Активный</option>
              <option value="inactive">Не активный</option>
          </select>
          @error('status')
          <span class="text-danger">{{$message}}</span>
          @enderror
        </div>
        <div class="form-group mb-3">
          <button type="reset" class="btn btn-warning">Сброс</button>
           <button class="btn btn-success" type="submit">Готово</button>
        </div>
      </form>
    </div>
</div>

@endsection

@push('styles')
<link rel="stylesheet" href="{{asset('backend/summernote/summernote.min.css')}}">
@endpush
@push('scripts')
<script src="/vendor/laravel-filemanager/js/stand-alone-button.js"></script>
<script src="{{asset('backend/summernote/summernote.min.js')}}"></script>
<script>
    $('#lfm').filemanager('image');

    $(document).ready(function() {
    $('#description').summernote({
      placeholder: "Write short description.....",
        tabsize: 2,
        height: 150
    });
    });
</script>
@endpush
