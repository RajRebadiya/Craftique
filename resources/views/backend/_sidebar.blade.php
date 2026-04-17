<div class="col-lg-3">
    <div class="bg-white rounded shadow-sm mb-3">
        <div class="list-group list-group-flush">
            <a href="{{ route('showcase.history') }}"
               class="list-group-item list-group-item-action border-0 {{ $section == 'history' ? 'active' : '' }}">
                Showcase Story
            </a>

            <a href="{{ route('showcase.collection') }}"
               class="list-group-item list-group-item-action border-0 {{ $section == 'collection' ? 'active' : '' }}">
                Showcase Collection
            </a>

            <a href="{{ route('showcase.oru') }}"
               class="list-group-item list-group-item-action border-0 {{ $section == 'oru' ? 'active' : '' }}">
                Showcase Oru
            </a>
        </div>
    </div>
</div>
