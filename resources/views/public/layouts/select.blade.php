@foreach($options as $option => $val)
    <option value="{{ $val }}"{{ (isset($selected) && $val == $selected) || (count($options) == 2 && $val != '') ? ' selected' : '' }}>{{ $option }}</option>
@endforeach