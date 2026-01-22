<?php
$type = $type ?? 'text';
$name = $name ?? '';
$placeholder = $placeholder ?? '';
$require = $require ?? false;
?>
<input
    class="p-3 border-1 border-gray-300 rounded-md w-full"
    type="<?= $type ?>"
    name="<?= $name ?>"
    placeholder="<?= $placeholder ?>"
    require="<?= $require ?>">