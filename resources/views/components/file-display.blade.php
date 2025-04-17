<!-- resources/views/components/file-display.blade.php -->
@if (pathinfo($getState(), PATHINFO_EXTENSION) === 'pdf')
    <iframe src="{{ asset('storage/' . $getState()) }}" width="100%" height="500px"></iframe>
@elseif (in_array(pathinfo($getState(), PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png', 'gif']))
    <img src="{{ asset('storage/' . $getState()) }}" alt="Evidence" style="max-width: 100%; height: auto;">
@elseif (in_array(pathinfo($getState(), PATHINFO_EXTENSION), ['mp4', 'avi', 'mov']))
    <video controls style="max-width: 100%; height: auto;">
        <source src="{{ asset('storage/' . $getState()) }}" type="video/{{ pathinfo($getState(), PATHINFO_EXTENSION) }}">
        Your browser does not support the video tag.
    </video>
@else
    <p>File type not supported for preview.</p>
@endif