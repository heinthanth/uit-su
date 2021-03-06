@extends('layouts.main')

@section('main')
<div id="create-news-form">
    <div class="container">
        <form action="/news/update" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="id" value="{{ $news->id }}">
            <h1>Post News</h1>
            <div class="form-group">
                <label for="title">News Title</label>
                <input type="text" name="title" id="title" class="form-control" placeholder="News Title" value="{{ $news->title }}">
            </div>
            <div class="form-group">
                <label for="description">Description</label>
                <textarea name="description" id="description" rows="3" class="form-control" placeholder="News Description" value="{{ $news->description }}"></textarea>
            </div>
            <div class="form-group">
                <label for="content">News Body</label>
                <textarea name="content" id="content" rows="5" class="form-control" placeholder="News Content">{{ $news->content }}</textarea>
            </div>
            <div class="form-group">
                <label for="tag">News Tags</label>
                <input type="text" name="tag" id="tag" class="form-control" placeholder="News Tags (Seperate with Comma)" value="{{ $news->tag }}">
            </div>
            <div class="form-group">
                <label for="committee">Committee</label>
                <select name="committee" id="committee" class="form-control">
                    <option value="">--  --</option>
                    @if(App\Role::where('id', Auth::user()->role)->first()->standalone)
                        @foreach (App\Committee::all() as $committee)
                            @if($committee->id == $news->committee)
                            <option value="{{ $committee->id }}" selected>{{ $committee->name }}</option>
                            @else
                            <option value="{{ $committee->id }}">{{ $committee->name }}</option>
                            @endif
                        @endforeach
                    @else
                        @isset(Auth::user()->committee)
                        <option value="{{ Auth::user()->committee }}">{{ App\Committee::where('id', Auth::user()->committee)->first()->name }}</option>
                        @endif
                    @endif
                </select>
            </div>
            <div class="form-group">
                <label for="club">Club</label>
                <select name="club" id="club" class="form-control">
                    <option value="">--  --</option>
                    @if(App\Role::where('id', Auth::user()->role)->first()->standalone)
                        @foreach (App\Club::all() as $club)
                            @if($club->id == $news->club)
                            <option value="{{ $club->id }}" selected>{{ $club->name }}</option>
                            @else
                            <option value="{{ $club->id }}">{{ $club->name }}</option>
                            @endif
                        @endforeach
                    @else
                        @isset(Auth::user()->club)
                        <option value="{{ Auth::user()->club }}">{{ App\Club::where('id', Auth::user()->club)->first()->name }}</option>
                        @endif
                    @endif
                </select>
            </div>
            <div class="form-group">
                <label for="cover_image">News Cover Image</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="cover_image">Upload</span>
                    </div>
                    <div class="custom-file">
                        <input type="file" name="cover_image" class="custom-file-input" id="inputGroupFile01"
                        aria-describedby="cover_image">
                        <label class="custom-file-label" for="inputGroupFile01">Choose file</label>
                    </div>
                </div>
            </div>
            <button type="submit" class="mt-4 btn-block custom-btn">Upload</button>
        </form>
    </div>
</div>
@endsection

@section('add-js')
<script src="https://cdn.tiny.cloud/1/7vbl7oks5lj5prw6x5zpxh9q4fpa0xcqh5gzz4hja1d626o0/tinymce/5/tinymce.min.js"></script>
<script>
    tinymce.init({
        selector: '#content',
        plugins: "table image link imagetools autoresize",
        jsplusInclude: {
            framework: "b4" // or "b4", "f5", "f6", "f6x"
        },
        menu: {
            format: { 
                title: "Format", 
                items: "forecolor backcolor" 
            }
        },
        toolbar: "undo redo forecolor backcolor link image",
        image_dimensions: false,
        image_class_list: [
            {title: 'Responsive', value: 'img-fluid'}
        ],
        relative_urls : false,
        file_picker_types: 'image media',
        image_uploadtab: true,
        images_upload_base_path: '/storage/news/images',
        images_upload_url: '/news/upload',
        images_upload_credentials: true,
        automatic_uploads: true,
        images_upload_handler: function (blobInfo, success, failure) {
            var xhr, formData;

            xhr = new XMLHttpRequest();
            xhr.withCredentials = false;
            xhr.open('POST', '/news/upload');
            var token = '{{ csrf_token() }}';
            xhr.setRequestHeader("X-CSRF-Token", token);
            xhr.onload = function() {
                var json;

                if (xhr.status != 200) {
                    failure('HTTP Error: ' + xhr.status);
                    return;
                }

                json = JSON.parse(xhr.responseText);

                if (!json || typeof json.location != 'string') {
                    failure('Invalid JSON: ' + xhr.responseText);
                    return;
                }

                success(json.location);
            };

            formData = new FormData();
            formData.append('media', blobInfo.blob(), blobInfo.filename());

            xhr.send(formData);
        }
    });
</script>
@endsection