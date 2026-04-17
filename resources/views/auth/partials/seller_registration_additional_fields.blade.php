<div class="fs-15 fw-600 py-2 mt-3">{{ translate('Business Details') }}</div>

<div class="form-group">
    <label for="business_role" class="fs-12 fw-700 text-soft-dark">{{ translate('What are you?') }}</label>
    <select name="business_role" class="form-control rounded-0{{ $errors->has('business_role') ? ' is-invalid' : '' }}" required>
        <option value="">{{ translate('Select one') }}</option>
        <option value="shop_creator" @selected(old('business_role') == 'shop_creator')>{{ translate('Shop / Creator') }}</option>
        <option value="artist_brand" @selected(old('business_role') == 'artist_brand')>{{ translate('Artist / Brand') }}</option>
        <option value="company_other" @selected(old('business_role') == 'company_other')>{{ translate('Company / Other') }}</option>
    </select>
    @if ($errors->has('business_role'))
        <span class="invalid-feedback" role="alert">
            <strong>{{ $errors->first('business_role') }}</strong>
        </span>
    @endif
</div>

<div class="form-group">
    <label for="business_category" class="fs-12 fw-700 text-soft-dark">{{ translate('Category') }}</label>
    <input type="text" class="form-control rounded-0{{ $errors->has('business_category') ? ' is-invalid' : '' }}" value="{{ old('business_category') }}" placeholder="{{ translate('Category') }}" name="business_category" required>
    @if ($errors->has('business_category'))
        <span class="invalid-feedback" role="alert">
            <strong>{{ $errors->first('business_category') }}</strong>
        </span>
    @endif
</div>

<div class="form-group">
    <label for="product_quantity_range" class="fs-12 fw-700 text-soft-dark">{{ translate('How many products do you have available?') }}</label>
    <select id="product_quantity_range" name="product_quantity_range" class="form-control rounded-0{{ $errors->has('product_quantity_range') ? ' is-invalid' : '' }}" required>
        <option value="">{{ translate('Select quantity range') }}</option>
        <option value="5-50" @selected(old('product_quantity_range') == '5-50')>5 - 50</option>
        <option value="50-250" @selected(old('product_quantity_range') == '50-250')>50 - 250</option>
        <option value="250-500" @selected(old('product_quantity_range') == '250-500')>250 - 500</option>
        <option value="500-1500" @selected(old('product_quantity_range') == '500-1500')>500 - 1500</option>
        <option value="more" @selected(old('product_quantity_range') == 'more')>{{ translate('More') }}</option>
    </select>
    @if ($errors->has('product_quantity_range'))
        <span class="invalid-feedback" role="alert">
            <strong>{{ $errors->first('product_quantity_range') }}</strong>
        </span>
    @endif
</div>

<div class="form-group" id="product_quantity_exact_group" style="{{ old('product_quantity_range') === 'more' ? '' : 'display:none;' }}">
    <label for="product_quantity_exact" class="fs-12 fw-700 text-soft-dark">{{ translate('If more, write the exact number') }}</label>
    <input type="text" class="form-control rounded-0{{ $errors->has('product_quantity_exact') ? ' is-invalid' : '' }}" value="{{ old('product_quantity_exact') }}" placeholder="{{ translate('Exact number, if needed') }}" name="product_quantity_exact">
    @if ($errors->has('product_quantity_exact'))
        <span class="invalid-feedback" role="alert">
            <strong>{{ $errors->first('product_quantity_exact') }}</strong>
        </span>
    @endif
</div>

<script>
    (function () {
        const quantityRange = document.getElementById('product_quantity_range');
        const exactGroup = document.getElementById('product_quantity_exact_group');
        if (!quantityRange || !exactGroup) return;

        const toggleExactField = () => {
            exactGroup.style.display = quantityRange.value === 'more' ? '' : 'none';
        };

        quantityRange.addEventListener('change', toggleExactField);
        toggleExactField();
    })();
</script>

<div class="form-group">
    <label for="eshop_domain" class="fs-12 fw-700 text-soft-dark">{{ translate('Do you have an e-shop? If yes, write the domain') }}</label>
    <input type="text" class="form-control rounded-0{{ $errors->has('eshop_domain') ? ' is-invalid' : '' }}" value="{{ old('eshop_domain') }}" placeholder="{{ translate('example.com') }}" name="eshop_domain">
    @if ($errors->has('eshop_domain'))
        <span class="invalid-feedback" role="alert">
            <strong>{{ $errors->first('eshop_domain') }}</strong>
        </span>
    @endif
</div>

<div class="form-group">
    <label for="brand_count" class="fs-12 fw-700 text-soft-dark">{{ translate('How many brands do you have?') }}</label>
    <input type="text" class="form-control rounded-0{{ $errors->has('brand_count') ? ' is-invalid' : '' }}" value="{{ old('brand_count') }}" placeholder="{{ translate('Brand count') }}" name="brand_count">
    @if ($errors->has('brand_count'))
        <span class="invalid-feedback" role="alert">
            <strong>{{ $errors->first('brand_count') }}</strong>
        </span>
    @endif
</div>

<div class="form-group mt-4">
    <div class="custom-control custom-checkbox">
        <input type="checkbox" class="custom-control-input{{ $errors->has('accept_seller_terms') ? ' is-invalid' : '' }}" id="accept_seller_terms" name="accept_seller_terms" value="1" required @checked(old('accept_seller_terms') == 1)>
        <label class="custom-control-label fs-12 fw-500 text-soft-dark" for="accept_seller_terms">
            {{ translate('I accept the') }}
            <a href="{{ route('home') }}/seller-legal-notice" target="_blank" class="fw-700 text-primary">{{ translate('Legal Notice') }}</a>,
            <a href="{{ route('home') }}/seller-withdrawal-policy" target="_blank" class="fw-700 text-primary">{{ translate('Right of Withdrawal') }}</a>,
            <a href="{{ route('home') }}/seller-terms-and-conditions" target="_blank" class="fw-700 text-primary">{{ translate('Terms & Conditions') }}</a>,
            <a href="{{ route('home') }}/seller-policy" target="_blank" class="fw-700 text-primary">{{ translate('Seller Privacy Policy') }}</a>
        </label>
    </div>
    @if ($errors->has('accept_seller_terms'))
        <span class="invalid-feedback d-block" role="alert">
            <strong>{{ $errors->first('accept_seller_terms') }}</strong>
        </span>
    @endif
</div>
