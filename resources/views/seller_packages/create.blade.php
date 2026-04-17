@extends('backend.layouts.app')

@section('content')

    <div class="col-lg-10 mx-auto">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 h6">{{translate('Create New Seller Package')}}</h5>
            </div>

            <form class="form-horizontal" action="{{ route('seller_packages.store') }}" method="POST" enctype="multipart/form-data">
            	@csrf
                <div class="card-body">
                    <div class="form-group row">
                        <label class="col-sm-2 col-from-label" for="name">{{translate('Package Name')}}</label>
                        <div class="col-sm-10">
                            <input type="text" placeholder="{{translate('Name')}}" id="name" name="name" class="form-control" required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-2 col-from-label" for="amount">{{translate('Amount')}}</label>
                        <div class="col-sm-10">
                            <input type="number" min="0" step="0.01" placeholder="{{translate('Amount')}}" id="amount" name="amount" class="form-control" required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-2 col-from-label" for="showcase_post_limit">{{translate('Showcase Post Limit')}}</label>
                        <div class="col-sm-10">
                            <input type="number" min="0" step="1" placeholder="{{translate('Leave empty for unlimited')}}" id="showcase_post_limit" name="showcase_post_limit" class="form-control">
                            <small class="text-muted d-block mt-1">
                                {{ translate('This is the total Showcase limit for Stories + Collections + Storefronts combined. Leave empty for unlimited.') }}
                            </small>
                        </div>
                    </div>
					
					<div class="form-group row">
    <label class="col-sm-2 col-from-label">{{ translate('Allowed Showcase Types') }}</label>
    <div class="col-sm-10">
        <div class="border rounded p-3">
            <div class="custom-control custom-checkbox mb-2">
                <input type="hidden" name="allow_showcase_history" value="0">
                <input type="checkbox" class="custom-control-input" id="allow_showcase_history" name="allow_showcase_history" value="1" checked>
                <label class="custom-control-label" for="allow_showcase_history">
                    {{ translate('Allow Story') }}
                </label>
            </div>

            <div class="custom-control custom-checkbox mb-2">
                <input type="hidden" name="allow_showcase_collection" value="0">
                <input type="checkbox" class="custom-control-input" id="allow_showcase_collection" name="allow_showcase_collection" value="1" checked>
                <label class="custom-control-label" for="allow_showcase_collection">
                    {{ translate('Allow Collection') }}
                </label>
            </div>

            <div class="custom-control custom-checkbox mb-0">
                <input type="hidden" name="allow_showcase_vitrin" value="0">
                <input type="checkbox" class="custom-control-input" id="allow_showcase_vitrin" name="allow_showcase_vitrin" value="1" checked>
                <label class="custom-control-label" for="allow_showcase_vitrin">
                    {{ translate('Allow Storefront') }}
                </label>
            </div>
        </div>

        <small class="text-muted d-block mt-2">
            {{ translate('This controls which Showcase publication types are available inside the seller publication center.') }}
        </small>
    </div>
</div>
	
                    <div class="form-group row">
                        <label class="col-sm-2 col-from-label" for="duration">{{translate('Duration')}}</label>
                        <div class="col-sm-10">
                            <input type="number" min="0" step="1" placeholder="{{translate('Validity in number of days')}}" id="duration" name="duration" class="form-control" required>
                            <small class="text-muted d-block mt-1" id="duration_helper">
                                {{ translate('Enter the package validity in days.') }}
                            </small>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-2 col-form-label" for="signinSrEmail">{{translate('Package Logo')}}</label>
                        <div class="col-md-10">
                            <div class="input-group" data-toggle="aizuploader" data-type="image" data-multiple="false">
                                <div class="input-group-prepend">
                                    <div class="input-group-text bg-soft-secondary font-weight-medium">{{ translate('Browse')}}</div>
                                </div>
                                <div class="form-control file-amount">{{ translate('Choose File') }}</div>
                                <input type="hidden" name="logo" class="selected-files">
                            </div>
                            <div class="file-preview box sm">
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-0 text-right">
                        <button type="submit" class="btn btn-sm btn-primary">{{translate('Save')}}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection

@section('script')
@endsection
