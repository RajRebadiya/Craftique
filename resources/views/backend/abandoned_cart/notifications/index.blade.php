@extends('backend.layouts.app')

@section('content')
    <div class="aiz-titlebar text-left mt-2 mb-3">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="h3">{{ translate('All Email Notifications') }}</h1>
            </div>
            <div class="col-md-6 text-md-right">
                <a href="{{ route('ac.email-notifications.create') }}" class="btn btn-circle btn-info">
                    <span>{{ translate('Add New Email Notifications') }}</span>
                </a>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0 h6">{{ translate('Email Notifications') }}</h5>
        </div>
        <div class="card-body">
            <table class="table aiz-table mb-0">
                <thead>
                    <tr>
                        <th data-breakpoints="lg" width="10%">#</th>
                        <th>{{ translate('Name') }}</th>
                        <th data-breakpoints="lg">{{ translate('Minutes After Trigger') }}</th>
                        <th data-breakpoints="lg">{{ translate('Active') }}</th>
                        <th data-breakpoints="lg">{{ translate('Subject') }}</th>
                        <th width="10%" class="text-right">{{ translate('Action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($emailNotifications as $key => $emailNotification)
                        <tr>
                            <td>{{ $key + 1 + ($emailNotifications->currentPage() - 1) * $emailNotifications->perPage() }}
                            </td>
                            <td>{{ $emailNotification->name }}</td>
                            <td>{{ $emailNotification->minutes_after_trigger }}</td>
                            <td>{{ $emailNotification->is_active ? 'Active' : 'Inactive' }}</td>
                            <td>{{ $emailNotification->subject }}</td>
                            <td class="text-right">
                                <div class="d-inline-block">
                                    <a class="btn btn-soft-primary btn-icon btn-circle btn-sm"
                                        href="{{ route('ac.email-notifications.edit', $emailNotification->id) }}"
                                        title="{{ translate('Edit') }}">
                                        <i class="las la-edit"></i>
                                    </a>
                                </div>
                                <div class="d-inline-block ml-2">
                                    <form action="{{ route('ac.email-notifications.destroy', $emailNotification->id) }}"
                                        method="POST" class="form-inline"
                                        onsubmit="return confirm('Are you sure you want to delete this item?');">
                                        @csrf
                                        @method('delete')
                                        <button type="submit" class="btn btn-soft-danger btn-icon btn-circle btn-sm"
                                            title="{{ translate('Delete') }}">
                                            <i class="las la-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="aiz-pagination">
                {{ $emailNotifications->appends(request()->input())->links() }}
            </div>
        </div>
    </div>
@endsection
