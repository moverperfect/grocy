@extends('layout.default')

@if($mode == 'edit')
@section('title', $__t('Edit shopping list item'))
@else
@section('title', $__t('Create shopping list item'))
@endif

@section('viewJsName', 'shoppinglistitemform')

@section('content')
<script>
	Grocy.QuantityUnits = {!! json_encode($quantityUnits) !!};
	Grocy.QuantityUnitConversionsResolved = {!! json_encode($quantityUnitConversionsResolved) !!};
</script>

<div class="row">
	<div class="col">
		<h2 class="title">@yield('title')</h2>
	</div>
</div>

<hr class="my-2">

<div class="row">
	<div class="col-xs-12 col-md-6 col-xl-4 pb-3">
		<script>
			Grocy.EditMode = '{{ $mode }}';
		</script>

		@if($mode == 'edit')
		<script>
			Grocy.EditObjectId = {{ $listItem->id }};
		</script>
		@endif

		<form id="shoppinglist-form"
			novalidate>

			@if(GROCY_FEATURE_FLAG_SHOPPINGLIST_MULTIPLE_LISTS)
			<div class="form-group">
				<label for="shopping_list_id">{{ $__t('Shopping list') }}</label>
				<select class="custom-control custom-select"
					id="shopping_list_id"
					name="shopping_list_id">
					@foreach($shoppingLists as $shoppingList)
					<option @if($mode=='edit'
						&&
						$shoppingList->id == $listItem->shopping_list_id) selected="selected" @endif value="{{ $shoppingList->id }}">{{ $shoppingList->name }}</option>
					@endforeach
				</select>
			</div>
			@else
			<input type="hidden"
				id="shopping_list_id"
				name="shopping_list_id"
				value="1">
			@endif

			<div>
				@php if($mode == 'edit') { $productId = $listItem->product_id; } else { $productId = ''; } @endphp
				@include('components.productpicker', array(
				'products' => $products,
				'nextInputSelector' => '#amount',
				'isRequired' => false,
				'prefillById' => $productId
				))
			</div>

			@php if($mode == 'edit') { $value = $listItem->amount; } else { $value = 1; } @endphp
			@php if($mode == 'edit') { $initialQuId = $listItem->qu_id; } else { $initialQuId = ''; } @endphp
			@include('components.productamountpicker', array(
			'value' => $value,
			'initialQuId' => $initialQuId,
			'min' => '0.' . str_repeat('0', $userSettings['stock_decimal_places_amounts'] - 1) . '1',
			'isRequired' => false
			))

			<div class="form-group">
				<label for="note">{{ $__t('Note') }}</label>
				<textarea class="form-control"
					rows="2"
					id="note"
					name="note">@if($mode == 'edit'){{ $listItem->note }}@endif</textarea>
			</div>

			@include('components.userfieldsform', array(
			'userfields' => $userfields,
			'entity' => 'shopping_list'
			))

			<button id="save-shoppinglist-button"
				class="btn btn-success">{{ $__t('Save') }}</button>

		</form>
	</div>
</div>
@stop
