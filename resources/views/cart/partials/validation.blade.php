@if(!$errors->isEmpty())
		
		@if($errors->has('message'))
			<div class="alert alert-danger alert-dismissible" role="alert">
		
				<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				
				{{ $errors->first('message') }}

			</div>
		@endif

		@if($errors->has('code'))
			<div class="alert alert-danger alert-dismissible" role="alert">
		
				<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				
				{{ $errors->first('code') }}

			</div>
		@endif

	@endif