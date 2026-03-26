<form action="{{ route('admin.videos.upload') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="file" name="video_file">
    <button type="submit">Upload Video</button>
</form>
