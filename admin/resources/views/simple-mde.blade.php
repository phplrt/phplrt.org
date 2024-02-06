<x-moonshine::form.textarea
    :attributes="$element->attributes()->merge([
        'name' => $element->name()
    ])"
    ::id="'simple-mde-{!! \spl_object_id($element) !!}'">{!! $element->value() ?? '' !!}</x-moonshine::form.textarea>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        new SimpleMDE({
            element: document.getElementById("simple-mde-{!! \spl_object_id($element) !!}"),
            placeholder: "Type here...",
            toolbar: false,
        });
    });
</script>

