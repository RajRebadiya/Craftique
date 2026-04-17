@if(!empty($collectionItems) && $collectionItems->count())
    <section class="mb-5">
        <div class="container">
            <div class="d-flex align-items-center justify-content-between flex-wrap mb-3" style="gap:12px;">
                <div>
                    <h3 class="h4 fw-700 mb-1">{{ $sectionTitle ?? translate('Collection Items') }}</h3>
                    <p class="text-muted mb-0">
                        {{ $sectionSubtitle ?? translate('Each card can contain its own cover, text and linked product.') }}
                    </p>
                </div>
            </div>

            <div class="row">
                @foreach($collectionItems as $collectionItem)
                    @php
                        $product = $collectionItem->product ?? null;

                        $coverUrl = null;

                        if (!empty($collectionItem->cover_image)) {
                            if (is_numeric($collectionItem->cover_image)) {
                                $coverUrl = uploaded_asset($collectionItem->cover_image);
                            } elseif (filter_var($collectionItem->cover_image, FILTER_VALIDATE_URL)) {
                                $coverUrl = $collectionItem->cover_image;
                            } else {
                                $coverUrl = asset($collectionItem->cover_image);
                            }
                        } elseif (!empty($product) && !empty($product->thumbnail_img)) {
                            if (is_numeric($product->thumbnail_img)) {
                                $coverUrl = uploaded_asset($product->thumbnail_img);
                            } elseif (filter_var($product->thumbnail_img, FILTER_VALIDATE_URL)) {
                                $coverUrl = $product->thumbnail_img;
                            } else {
                                $coverUrl = asset($product->thumbnail_img);
                            }
                        }

                        $productUrl = !empty($product) && !empty($product->slug)
                            ? url('/product/' . $product->slug)
                            : null;

                        $finalPrice = null;
                        if (!empty($product)) {
                            $finalPrice = $product->unit_price;
                            if (!empty($product->discount) && !empty($product->discount_type)) {
                                if ($product->discount_type === 'percent') {
                                    $finalPrice = $product->unit_price - (($product->unit_price * $product->discount) / 100);
                                } elseif ($product->discount_type === 'amount') {
                                    $finalPrice = $product->unit_price - $product->discount;
                                }
                            }
                            $finalPrice = max(0, $finalPrice);
                        }
                    @endphp

                    <div class="col-md-6 col-xl-4 mb-4">
                        <div class="card h-100 border-0 shadow-sm overflow-hidden">
                            @if($coverUrl)
                                @if($productUrl)
                                    <a href="{{ $productUrl }}" class="d-block">
                                        <img src="{{ $coverUrl }}" alt="{{ $collectionItem->title ?: translate('Collection item') }}" class="img-fit w-100" style="height:260px;">
                                    </a>
                                @else
                                    <img src="{{ $coverUrl }}" alt="{{ $collectionItem->title ?: translate('Collection item') }}" class="img-fit w-100" style="height:260px;">
                                @endif
                            @else
                                <div class="d-flex align-items-center justify-content-center bg-light text-muted text-center" style="height:260px;">
                                    <div class="fs-13">{{ translate('No image') }}</div>
                                </div>
                            @endif

                            <div class="card-body p-3 p-lg-4">
                                @if(!empty($collectionItem->title))
                                    <h4 class="h5 fw-700 mb-2">{{ $collectionItem->title }}</h4>
                                @endif

                                @if(!empty($collectionItem->description))
                                    <div class="text-secondary mb-3">
                                        {!! nl2br(e($collectionItem->description)) !!}
                                    </div>
                                @endif

                                @if(!empty($product))
                                    <div class="border-top pt-3">
                                        <div class="fw-600 mb-1">
                                            @if($productUrl)
                                                <a href="{{ $productUrl }}" class="text-reset">
                                                    {{ $product->name }}
                                                </a>
                                            @else
                                                {{ $product->name }}
                                            @endif
                                        </div>

                                        @if(!is_null($finalPrice))
                                            <div class="fs-15 fw-700 text-primary mb-2">
                                                {{ single_price($finalPrice) }}
                                            </div>

                                            @if((float) $finalPrice !== (float) $product->unit_price)
                                                <div class="fs-13 text-muted mb-2">
                                                    <del>{{ single_price($product->unit_price) }}</del>
                                                </div>
                                            @endif
                                        @endif

                                        @if($productUrl)
                                            <a href="{{ $productUrl }}" class="btn btn-soft-primary btn-sm">
                                                {{ translate('Open Product') }}
                                            </a>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif