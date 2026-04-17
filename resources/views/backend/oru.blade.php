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
                <h5 class="mb-0">Showcase Oru Details</h5>
            </div>

            <div class="card-body">
                <form action="javascript:void(0);" method="POST">
                    @csrf

                    <div class="form-group">
                        <label>Section Title</label>
                        <input type="text" class="form-control" name="title" placeholder="Enter title">
                    </div>

                    <div class="form-group">
                        <label>Main Visual</label>
                        <input type="text" class="form-control" name="main_visual" placeholder="Image or video source">
                    </div>

                    <div class="form-group">
                        <label>Text Content</label>
                        <textarea class="form-control" rows="8" name="description" placeholder="Write the content here..."></textarea>
                    </div>

                    <div class="form-group">
                        <label>Linked Products</label>
                        <input type="text" class="form-control" name="linked_products" placeholder="Product IDs or selection logic for next phase">
                    </div>

                    <div class="form-group">
                        <label>Status</label>
                        <select class="form-control" name="status">
                            <option value="draft">Draft</option>
                            <option value="published">Published</option>
                        </select>
                    </div>

                    <div class="text-right">
                        <button type="submit" class="btn btn-primary">Save Oru</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection