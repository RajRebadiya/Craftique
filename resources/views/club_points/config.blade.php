@extends('backend.layouts.app')
@section('content')

    <div class="col-lg-12">
        <div class="row">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{translate('Convert Point To Wallet')}}</h5>
                    </div>
                    <div class="card-body mb-2">
                        <form class="form-horizontal" action="{{ route('point_convert_rate_store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="type" value="club_point_convert_rate">
                            <div class="form-group row">
                                <div class="col-lg-4">
                                    <label class="col-from-label">{{translate('Set Point For ')}}
                                        {{ single_price(1) }}</label>
                                </div>
                                <div class="col-lg-5">
                                    <input type="number" min="0" step="0.01" class="form-control" name="value"
                                        value="{{ get_setting('club_point_convert_rate') }}" placeholder="100" required>
                                </div>
                                <div class="col-lg-3">
                                    <label class="col-from-label">{{translate('Points')}}</label>
                                </div>
                            </div>
                            <div class="form-group mb-3 text-right">
                                <button type="submit" class="btn btn-sm btn-primary">{{translate('Save')}}</button>
                            </div>
                        </form>
                        <br>
                        <i
                            class="fs-12"><b>{{ translate('Note: You need to activate wallet option first before using club point addon.') }}</b></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0 h6">{{translate('Set Point For Product Review')}}</h5>
                    </div>
                    <div class="card-body">
                        <form class="form-horizontal" action="{{ route('point_for_review_store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="type" value="set_point_for_product_review">
                            <div class="form-group row">
                                <div class="col-lg-4">
                                    <label class="col-from-label">{{translate('Set Point For Product Review')}}</label>
                                </div>
                                <div class="col-lg-5">
                                    <input type="number" min="0" step="0.01" class="form-control" name="value"
                                        value="{{ get_setting('set_point_for_product_review') }}" placeholder="100"
                                        required>
                                </div>
                                <div class="col-lg-3">
                                    <label class="col-from-label">{{translate('Points')}}</label>
                                </div>
                            </div>
                            <div class="form-group mb-3 text-right">
                                <button type="submit" class="btn btn-sm btn-primary">{{translate('Save')}}</button>
                            </div>
                        </form>
                        <div class="form-group row">
                            <div class="col-lg-7">
                                <label class="col-from-label">
                                    {{translate('Allow points for seller product reviews?')}}
                                </label>
                            </div>
                            <div class="col-md-2">
                                <label class="aiz-switch aiz-switch-success mb-0">
                                    <input type="checkbox"
                                        id="set_club_point_for_sellers_product_review"
                                        @if(get_setting('set_club_point_for_sellers_product_review') == '1') checked @endif>
                                    <span></span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('modal')
    <!-- confirm trigger Modal -->
    <div id="confirm-trigger-modal" class="modal fade">
        <div class="modal-dialog modal-md modal-dialog-centered" style="max-width: 540px;">
            <div class="modal-content p-2rem">
                <div class="modal-body text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="72" height="64" viewBox="0 0 72 64">
                        <g id="Octicons" transform="translate(-0.14 -1.02)">
                          <g id="alert" transform="translate(0.14 1.02)">
                            <path id="Shape" d="M40.159,3.309a4.623,4.623,0,0,0-7.981,0L.759,58.153a4.54,4.54,0,0,0,0,4.578A4.718,4.718,0,0,0,4.75,65.02H67.587a4.476,4.476,0,0,0,3.945-2.289,4.773,4.773,0,0,0,.046-4.578Zm.6,52.555H31.582V46.708h9.173Zm0-13.734H31.582V23.818h9.173Z" transform="translate(-0.14 -1.02)" fill="#ffc700" fill-rule="evenodd"/>
                          </g>
                        </g>
                    </svg>
                    <p class="mt-2 mb-2 fs-16 fw-700" id="confirm_text"></p>
                    <p class="fs-13" id="confirm_detail_text"></p>
                    <a href="javascript:void(0)" id="trigger_btn" class="btn btn-warning rounded-2 mt-2 fs-13 fw-700 w-250px"></a>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript">

        $('#trigger_btn').on('click', function() {
            const actionType = $(this).attr('data-action-type');
            if (actionType === 'set_club_point_for_sellers_product_review') {
                updateSettings();
            }
            $(this).attr('data-clicked', 1);
            $('#confirm-trigger-modal').modal('hide');
        });

        function updateSettings() {
            var value = $('#trigger_btn').attr('data-value');
            var type = $('#trigger_btn').attr('data-type');

            $.post('{{ route('business_settings.point_convert_rate_store') }}', {
                _token: '{{ csrf_token() }}',
                type: type,
                value: value
            }, function(data) {
                if (data == 1) {
                    AIZ.plugins.notify('success', '{{ translate('Settings updated successfully') }}');
                } else {
                    AIZ.plugins.notify('danger', '{{ translate('Something went wrong') }}');
                }
            }).fail(function() {
                AIZ.plugins.notify('danger', '{{ translate('Network error') }}');
            });
        }

        $('#set_club_point_for_sellers_product_review').on('change', function() {
            if('{{ env('DEMO_MODE') }}' == 'On') {
                AIZ.plugins.notify('info', '{{ translate('Data can not change in demo mode.') }}');
                $(this).prop('checked', !$(this).is(':checked'));
                return;
            }
            const isChecked = $(this).is(':checked');
            const confirmText = isChecked 
                ? "{{ translate('Are you sure you want to set this club points for the seller’s products?') }}"
                : "{{ translate('Are you sure you want to disable club points for the seller’s products?') }}";
            const detailText = isChecked 
                ? "{{ translate('Customers will earn club points by reviewing seller’s products.') }}"
                : "{{ translate('Customers will no longer earn club points by reviewing seller’s products.') }}";
            const btnText = isChecked 
                ? "{{ translate('Allow') }}"
                : "{{ translate('Disable') }}";
            $('#confirm_text').text(confirmText);
            $('#confirm_detail_text').text(detailText);
            $('#trigger_btn')
                .text(btnText)
                .attr('data-action-type', 'set_club_point_for_sellers_product_review')
                .attr('data-type', 'set_club_point_for_sellers_product_review')
                .attr('data-value', isChecked ? 1 : 0);
            $('#confirm-trigger-modal')
                .data('action-type', 'set_club_point_for_sellers_product_review')
                .modal('show');
        });  
        
        
        $('#confirm-trigger-modal').on('hidden.bs.modal', function () {
            const actionType = $(this).data('action-type');
            if ($('#trigger_btn').attr('data-clicked') == 1) {
                $('#trigger_btn').attr('data-clicked', '');
                $(this).removeData('action-type');
            } else {
                if (actionType === 'set_club_point_for_sellers_product_review') {
                    const current = $('#set_club_point_for_sellers_product_review').is(':checked');
                    $('#set_club_point_for_sellers_product_review').prop('checked', !current);
                } 
                else if (actionType === 'set_club_point_for_sellers_product_review') {
                    var id = $('#trigger_btn').attr('data-value');
                    if (id) {
                        var set_club_point_for_sellers_product_review = $('#trigger_btn').attr('data-set_club_point_for_sellers_product_review') == 1 ? false : true;
                        $('#trigger_alert_' + id).prop('checked', set_club_point_for_sellers_product_review);
                    }
                }
                $(this).removeData('action-type');
                $('#trigger_btn').removeAttr('data-action-type data-type data-value data-set_club_point_for_sellers_product_review');
            }
        });

    </script>
@endsection