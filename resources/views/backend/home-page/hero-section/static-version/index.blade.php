@extends('backend.layout')

{{-- this style will be applied when the direction of language is right-to-left --}}
@includeIf('backend.partials.rtl-style')
<style>
	.new-hero-imgs {
    display: flex;
    flex-wrap: wrap;
    justify-content: flex-start; /* 👈 Start from left */
    gap: 20px; /* equal spacing between images */
}

.preview-item {
    position: relative;
    width: 200px;
    height: 130px;
    overflow: hidden;
    background-color: #f9f9f9;
    border: 1px solid #ddd;
    border-radius: 8px;
    transition: transform 0.2s ease-in-out;
}

.preview-item:hover {
    transform: scale(1.03);
}

.preview-item img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.remove-image {
    position: absolute;
    top: 5px;
    right: 5px;
    border-radius: 50%;
    padding: 0 6px;
    line-height: 1;
    font-weight: bold;
}


	</style>
@section('content')
<div class="page-header">
	<h4 class="page-title">{{ __('Banner Section') }}</h4>
	<ul class="breadcrumbs">
		<li class="nav-home">
			<a href="{{ route('admin.dashboard') }}">
				<i class="flaticon-home"></i>
			</a>
		</li>
		<li class="separator">
			<i class="flaticon-right-arrow"></i>
		</li>
		<li class="nav-item">
			<a href="#">{{ __('Home Page') }}</a>
		</li>
		<li class="separator">
			<i class="flaticon-right-arrow"></i>
		</li>
		<li class="nav-item">
			<a href="#">{{ __('Banner Section') }}</a>
		</li>
	</ul>
</div>

<div class="row">
	<div class="col-md-6">
		<div class="card">
			<div class="card-header">
				<div class="row">
					<div class="col">
						<div class="card-title">{{ __('Section Image') }}</div>
					</div>
				</div>
			</div>
			
			<div class="card-body">
				<div class="row">
					<div class="col-lg-12">
						<form id="heroImgForm"
							action="{{ route('admin.home_page.hero_section.static_version.update_image') }}"
							method="POST" enctype="multipart/form-data">
							@csrf
							<div class="form-group">
								<label class="fw-bold mb-2">{{ __('Image') . '*' }}</label>

								<!-- ✅ Image preview section -->
								<div class="new-hero-imgs d-flex flex-wrap justify-content-start gap-3" id="image-preview">
									@if (empty($heroImg) || count($heroImg) == 0)
										<img src="{{ asset('assets/img/noimage.jpg') }}" alt="No image" class="uploaded-img">
									@else
										@foreach ($heroImg as $img)
											<div class="preview-item position-relative border rounded shadow-sm p-2 bg-white">
												<img src="{{ asset('assets/img/hero/static/' . $img) }}"
													 alt="image"
													 class="uploaded-img img-fluid rounded"
													 style="width:200px; height:130px; object-fit:cover;">
												<button type="button"
														class="btn btn-sm btn-danger remove-image position-absolute top-0 end-0 translate-middle"
														data-name="{{ $img }}">×</button>
											</div>
										@endforeach
									@endif
								</div>

								<div class="mt-3">
									<label class="btn btn-primary btn-sm upload-btn">
										{{ __('Choose Image') }}
										<input type="file" class="img-input" name="images[]" multiple hidden>
									</label>
								</div>

								@error('image')
									<p class="mt-2 mb-0 text-danger">{{ $message }}</p>
								@enderror

<p class="text-warning mb-0">Image Size : 1900 x 350</p>

							</div>
						</form>
					</div>
				</div>
			</div> 
			
			<div class="card-footer">
				<div class="row">
					<div class="col-12 text-center">
						<button type="submit" form="heroImgForm" class="btn btn-success">
							{{ __('Update') }}
						</button>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<div class="col-md-6">
		<div class="card">
			<div class="card-header">
				<div class="row">
					<div class="col-lg-9">
						<div class="card-title">{{ __('Hero Section Information') }}</div>
					</div>
					
					<div class="col-lg-3">
						@includeIf('backend.partials.languages')
					</div>
				</div>
			</div>
			
			<div class="card-body">
				<div class="row">
					<div class="col-lg-12">
						<form id="staticForm"
						action="{{ route('admin.home_page.hero_section.static_version.update_information', ['language' => request()->input('language')]) }}"
						method="post">
							@csrf
							<div class="form-group">
								<label for="">{{ __('Title') }}</label>
								<input type="text" class="form-control" name="title" placeholder="Enter Title"
								value="@if (!empty($heroInfo)) {{ $heroInfo->title }} @endif">
							</div>
							
							<div class="form-group">
								<label for="">{{ __('Text') }}</label>
								<textarea class="form-control" name="text" rows="5" placeholder="Enter Text">
									@if (!empty($heroInfo))
									{{ $heroInfo->text }}
									@endif
								</textarea>
							</div> 
						</form>
					</div>
				</div>
			</div>
			
			<div class="card-footer">
				<div class="row">
					<div class="col-12 text-center">
						<button type="submit" form="staticForm" class="btn btn-success">
							{{ __('Update') }}
						</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

@section('script')
<script> 
	document.addEventListener('click', function(e) {
		if (e.target.classList.contains('remove-image')) {
			const btn = e.target;
			const imageName = btn.dataset.name;

			if (confirm('Are you sure you want to remove this image?')) {
				fetch("{{ route('admin.home_page.hero_section.static_version.remove_image') }}", {
					method: "POST",
					headers: {
						"X-CSRF-TOKEN": "{{ csrf_token() }}",
						"Content-Type": "application/json"
					},
					body: JSON.stringify({ image_name: imageName })
				})
				.then(res => res.json())
				.then(data => {
					if (data.success) {
						btn.closest('.preview-item').remove();
					}
				})
				.catch(err => console.error(err));
			}
		}
	});

	let selectedFiles = [];

// When user selects files
document.querySelector(".img-input").addEventListener("change", function (e) {
    const previewContainer = document.getElementById("image-preview");
    let files = Array.from(e.target.files);

    // Add only new files (prevent duplicates)
    files.forEach(file => {
        if (!selectedFiles.some(f => f.name === file.name && f.size === file.size)) {
            selectedFiles.push(file);

            let reader = new FileReader();
            reader.onload = function (event) {
                let div = document.createElement("div");
                div.classList.add("preview-item", "position-relative", "border", "rounded", "p-2", "bg-white");
                div.innerHTML = `
                    <img src="${event.target.result}" class="uploaded-img" style="width:200px; height:130px; object-fit:cover;">
                    <button type="button" class="btn btn-sm btn-danger remove-image position-absolute top-0 end-0 translate-middle" data-name="${file.name}" data-size="${file.size}">×</button>
                `;
                previewContainer.appendChild(div);
            };
            reader.readAsDataURL(file);
        }
    });

    // Allow reselecting the same file again
    e.target.value = "";
});

	
	// Remove file when X is clicked
	/* document.getElementById("image-preview").addEventListener("click", function (e) {
		if (e.target.classList.contains("remove-image")) {
			let index = e.target.getAttribute("data-index");
			selectedFiles.splice(index, 1);
			e.target.parentElement.remove();
		}
	}); */
	
	// Before form submit, assign all files properly
	document.querySelector("#heroImgForm").addEventListener("submit", function (e) {
		let input = document.querySelector(".img-input");
		let dt = new DataTransfer();
		selectedFiles.forEach(file => dt.items.add(file));
		input.files = dt.files;
	});
</script>

@endsection
