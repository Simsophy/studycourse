<form action="{{ route('videos.upload') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="file" name="video_file" required>
    <button type="submit">Upload Video</button>
</form>
