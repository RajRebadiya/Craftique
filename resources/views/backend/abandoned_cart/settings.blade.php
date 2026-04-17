@extends('backend.layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 h6">{{ translate('Abandoned Cart Settings') }}</h5>
                </div>
                <div class="card-body">
                    <form class="form-horizontal" action="{{ route('ac.settings.update') }}" method="POST">
                        @csrf
                        <div class="form-group row">
                            <input type="hidden" name="types[]" value="AC_ENABLE_TRACKING">
                            <label class="col-md-3 col-form-label">
                                {{ translate('Enable tracking') }}
                            </label>
                            <div class="col-md-9">
                                <select class="form-control aiz-selectpicker mb-2 mb-md-0" name="AC_ENABLE_TRACKING">
                                    <option value="true" @if (config('abandoned-cart.enable_tracking') == true) selected @endif>
                                        {{ translate('True') }}</option>
                                    <option value="false" @if (config('abandoned-cart.enable_tracking') == false) selected @endif>
                                        {{ translate('False') }}</option>
                                </select>
                            </div>
                        </div>
                        <div id="smtp">
                            <div class="form-group row">
                                <input type="hidden" name="types[]" value="AC_NOTIFY_ADMIN_ON_RECOVERY">
                                <label class="col-md-3 col-form-label">{{ translate('Notify admin on recovery') }}</label>
                                <div class="col-md-9">
                                    <select class="form-control aiz-selectpicker mb-2 mb-md-0"
                                        name="AC_NOTIFY_ADMIN_ON_RECOVERY">
                                        <option value="true" @if (config('abandoned-cart.notify_admin_on_recovery') == true) selected @endif>
                                            {{ translate('True') }}</option>
                                        <option value="false" @if (config('abandoned-cart.notify_admin_on_recovery') == false) selected @endif>
                                            {{ translate('False') }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <input type="hidden" name="types[]" value="AC_SEND_RECOVERY_REPORT">
                                <label class="col-md-3 col-form-label">{{ translate('Send recovery report') }}</label>
                                <div class="col-md-9">
                                    <select class="form-control aiz-selectpicker mb-2 mb-md-0"
                                        name="AC_SEND_RECOVERY_REPORT">
                                        <option value="true" @if (config('abandoned-cart.send_recovery_report') == true) selected @endif>
                                            {{ translate('True') }}</option>
                                        <option value="false" @if (config('abandoned-cart.send_recovery_report') == false) selected @endif>
                                            {{ translate('False') }}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <input type="hidden" name="types[]" value="AC_CUT_OF_TIME_IN_MINUTES">
                                <div class="col-md-3">
                                    <label class="col-form-label">{{ translate('Cut of time in minutes') }}</label>
                                </div>
                                <div class="col-md-9">
                                    <input type="number" class="form-control" name="AC_CUT_OF_TIME_IN_MINUTES"
                                        value="{{ config('abandoned-cart.cut_of_time_in_minutes') }}"
                                        placeholder="{{ translate('CUT OF TIME IN MINUTES') }}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <input type="hidden" name="types[]" value="AC_EMAIL_FROM_NAME">
                                <div class="col-md-3">
                                    <label class="col-form-label">{{ translate('Email from name') }}</label>
                                </div>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" name="AC_EMAIL_FROM_NAME"
                                        value="{{ config('abandoned-cart.email_from_name') }}"
                                        placeholder="{{ translate('EMAIL FROM NAME') }}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <input type="hidden" name="types[]" value="AC_EMAIL_FROM_ADDRESS">
                                <div class="col-md-3">
                                    <label class="col-form-label">{{ translate('Email from address') }}</label>
                                </div>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" name="AC_EMAIL_FROM_ADDRESS"
                                        value="{{ config('abandoned-cart.email_from_address') }}"
                                        placeholder="{{ translate('EMAIL FROM ADDRESS') }}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <input type="hidden" name="types[]" value="AC_EMAIL_REPLY_TO_ADDRESS">
                                <div class="col-md-3">
                                    <label class="col-form-label">{{ translate('Email reply to address') }}</label>
                                </div>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" name="AC_EMAIL_REPLY_TO_ADDRESS"
                                        value="{{ config('abandoned-cart.email_reply_to_address') }}"
                                        placeholder="{{ translate('AC EMAIL REPLY TO ADDRESS') }}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <input type="hidden" name="types[]" value="AC_RECOVERY_REPORT_TO_EMAIL">
                                <div class="col-md-3">
                                    <label class="col-form-label">{{ translate('Recovery report to email') }}</label>
                                </div>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" name="AC_RECOVERY_REPORT_TO_EMAIL"
                                        value="{{ config('abandoned-cart.recovery_report_to_email') }}"
                                        placeholder="{{ translate('AC RECOVERY REPORT TO EMAIL') }}">
                                </div>
                            </div>
                        </div>
                        <div class="form-group mb-0 text-right">
                            <button type="submit" class="btn btn-primary">{{ translate('Save Configuration') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
