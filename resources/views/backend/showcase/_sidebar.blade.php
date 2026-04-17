<div class="col-lg-3">
    <div class="bg-white rounded shadow-sm mb-3">
        <div class="list-group list-group-flush">

            <div class="px-3 py-2 text-muted fw-700 border-bottom">
                Showcase Story
            </div>
            <a href="{{ route('showcase.history') }}"
               class="list-group-item list-group-item-action border-0 {{ $section == 'history' && !request()->routeIs('showcase.history.list') ? 'active' : '' }}">
                Add / Edit Story
            </a>
            <a href="{{ route('showcase.history.list') }}"
               class="list-group-item list-group-item-action border-0 {{ request()->routeIs('showcase.history.list') ? 'active' : '' }}">
                Story List
            </a>

            <div class="px-3 py-2 text-muted fw-700 border-bottom border-top">
                Showcase Collection
            </div>
            <a href="{{ route('showcase.collection') }}"
               class="list-group-item list-group-item-action border-0 {{ $section == 'collection' && !request()->routeIs('showcase.collection.list') ? 'active' : '' }}">
                Add / Edit Collection
            </a>
            <a href="{{ route('showcase.collection.list') }}"
               class="list-group-item list-group-item-action border-0 {{ request()->routeIs('showcase.collection.list') ? 'active' : '' }}">
                Collection List
            </a>

            <div class="px-3 py-2 text-muted fw-700 border-bottom border-top">
                Showcase Storefront
            </div>
            <a href="{{ route('showcase.vitrin') }}"
               class="list-group-item list-group-item-action border-0 {{ $section == 'vitrin' && !request()->routeIs('showcase.vitrin.list') ? 'active' : '' }}">
                Add / Edit Storefront
            </a>
            <a href="{{ route('showcase.vitrin.list') }}"
               class="list-group-item list-group-item-action border-0 {{ request()->routeIs('showcase.vitrin.list') ? 'active' : '' }}">
                Storefront List
            </a>

            <div class="px-3 py-2 text-muted fw-700 border-bottom border-top">
                Showcase Launch
            </div>
            <a href="{{ route('showcase.launch') }}"
               class="list-group-item list-group-item-action border-0 {{ $section == 'launch' && !request()->routeIs('showcase.launch.list') ? 'active' : '' }}">
                Add / Edit Launch
            </a>
            <a href="{{ route('showcase.launch.list') }}"
               class="list-group-item list-group-item-action border-0 {{ request()->routeIs('showcase.launch.list') ? 'active' : '' }}">
                Launch List
            </a>

        </div>
    </div>
</div>
