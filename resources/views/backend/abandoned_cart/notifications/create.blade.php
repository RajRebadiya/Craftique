@extends('backend.layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-10 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{ translate('Email Notification Information') }}</h5>
                </div>

                <form class="form-horizontal" action="{{ route('ac.email-notifications.store') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-sm-3 col-from-label" for="name">{{ translate('Name') }}</label>
                            <div class="col-sm-9">
                                <input type="text" value="{{ old('name') }}" placeholder="{{ translate('Name') }}"
                                    id="name" name="name" class="form-control">
                            </div>
                            @error('name')
                                <div class="col-sm-9 offset-sm-3">
                                    <span class="text-danger">{{ $errors->first('name') }}</span>
                                </div>
                            @enderror
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-from-label"
                                for="email">{{ translate('Minutes After Trigger') }}</label>
                            <div class="col-sm-9">
                                <input type="number" placeholder="{{ translate('Minutes After Trigger') }}"
                                    value="{{ old('minutes_after_trigger') }}" id="minutes_after_trigger"
                                    name="minutes_after_trigger" class="form-control">
                            </div>
                            @error('minutes_after_trigger')
                                <div class="col-sm-9 offset-sm-3">
                                    <span class="text-danger">{{ $errors->first('minutes_after_trigger') }}</span>
                                </div>
                            @enderror
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-from-label" for="name">{{ translate('Active?') }}</label>
                            <div class="col-sm-9">
                                <select name="is_active" required class="form-control aiz-selectpicker">
                                    <option @selected(old('is_active') == 1) value="1">{{ translate('Active') }}</option>
                                    <option @selected(old('is_active') == 0) value="0">{{ translate('Inactive') }}
                                    </option>
                                </select>
                            </div>
                            @error('is_active')
                                <div class="col-sm-9 offset-sm-3">
                                    <span class="text-danger">{{ $errors->first('is_active') }}</span>
                                </div>
                            @enderror
                        </div>
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">{{ translate('Subject') }}</label>
                            <div class="col-md-9">
                                <textarea name="subject" rows="2" class="form-control">{{ old('subject') }}</textarea>
                            </div>
                            @error('subject')
                                <div class="col-sm-9 offset-sm-3">
                                    <span class="text-danger">{{ $errors->first('subject') }}</span>
                                </div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label class="fs-13">{{ translate('Body') }}</label>
                            <textarea class="aiz-text-editor" name="body">{{ old('body') }}</textarea>
                        </div>
                        @error('body')
                            <div class="col-sm-9 offset-sm-3">
                                <span class="text-danger">{{ $errors->first('body') }}</span>
                            </div>
                        @enderror
                        <div class="form-group mb-0 text-right">
                            <button type="submit" class="btn btn-sm btn-primary">{{ translate('Save') }}</button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
@endsection
