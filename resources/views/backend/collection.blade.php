@extends('backend.layouts.app')

@section('content')
<div class="aiz-titlebar text-left mt-2 mb-3">
    <h1 class="h3">{{ $page_title }}</h1>
</div>

<div class="row">
    @include('backend.showcase._sidebar')

    <div class="col-lg-9">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Showcase Collection Details</h5>
            </div>

            <div class="card-body">
                <form action="javascript:void(0);" method="POST">
                    @csrf

                    <div class="form-group">
                        <label>Collection Name</label>
                        <input type="text" class="form-control" name="title" placeholder="Enter collection name">
                    </div>

                    <div class="form-group">
                        <label>Cover Image</label>
                        <input type="text" class="form-control" name="cover_image" placeholder="Image path or upload later">
                    </div>

                    <div class="form-group">
                        <label>Short Intro</label>
                        <input type="text" class="form-control" name="intro" placeholder="Enter short intro">
                    </div>

                    <div class="form-group">
                        <label>Collection Description</label>
                        <textarea class="form-control" rows="8" name="description" placeholder="Write the collection content here..."></textarea>
                    </div>

                    <div class="form-group">
                        <label>Linked Products</label>
                        <input type="text" class="form-control" name="linked_products" placeholder="Product IDs or selection logic for next phase">
                    </div>

                    <div class="form-group">
                        <label>Subscription Type</label>
                        <select class="form-control" name="billing_period">
                            <option value="monthly">Monthly</option>
                            <option value="yearly">Yearly</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Status</label>
                        <select class="form-control" name="status">
                            <option value="draft">Draft</option>
                            <option value="published">Published</option>
                        </select>
                    </div>

                    <div class="text-right">
                        <button type="submit" class="btn btn-primary">Save Collection</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection