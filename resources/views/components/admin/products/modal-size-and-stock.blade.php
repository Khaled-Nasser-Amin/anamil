@props(['index'])
<div wire:ignore.self class="modal fade" id="sizeAndStock{{ $index }}" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm bg-primary">
    <div class="modal-content">
        <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">@lang('text.Size')</h5>
        <button onclick="$('#sizeAndStock{{ $index }}').modal('toggle')" type="button" class="close"  >
            <span aria-hidden="true">&times;</span>
        </button>
        </div>
            <div class="modal-body">
                <div class="col-sm-12">
                    <label for="size">{{__('text.Size')}}</label><br>
                    <input type="size" class="form-control"  wire:model='size' id="size" ><br>
                    <x-general.input-error for="size" />
                </div>
                <div class="col-sm-12">
                    <label for="stock">{{__('text.Stock')}}</label><br>
                    <input type="stock" class="form-control"  wire:model='stock' id="stock" ><br>
                    <x-general.input-error for="stock" />
                </div>
            </div>
        <div class="modal-footer row justify-content-center">
        <button wire:click.prevent="addSize({{ $index }})" type="button" class="btn btn-primary">@lang('text.Save')</button>
        </div>
    </div>
    </div>
</div>
